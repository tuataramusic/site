<?php
/**
 * @todo ��������� ���� ������� showScreen($oid=null)
 */


if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
 * ������� ����������
 *
 */

class BaseController extends Controller 
{
	public $user;
	public $cname;
	public $viewpath;
	
	/**
	 * ����������� ����������, ���-�� ����� ������������� ������,
	 * ������ ��� ��������������� �������� ������ �� ����� ���� ��������
	 * 
	 */	
	public $result;
	
	public function __construct()
	{
		parent::Controller();
//		$this->output->enable_profiler(true);
		
		$this->user			= Check::user();
		$this->cname		= $this->uri->rsegment(1) ? $this->uri->rsegment(1) : 'main';
		$this->viewpath		= '/'.$this->cname.'/';
		$this->load->helper('humanForm');
		

		
		/**
		 * �������� ������ �� �����, ���� ��� ��� ����
		 */
		if (Stack::size('result')){

			$this->result = Stack::shift('result');
			
		}else{
			$this->result		= new stdClass();
			$this->result->e	= 0; // ��� ������
			$this->result->m	= ''; // ���������
			$this->result->d	= ''; // ������������ ������			
		}

		
		View::$main_view	= '/'.$this->cname.'/index';
		
		View::$data	= array(
							'user'		=> $this->user,
							'pageinfo'	=> array(
												'cname'		=> $this->uri->rsegment(1),
												'mname'		=> $this->uri->rsegment(2),
												'params'	=> $this->uri->uri_to_assoc(),
							),
							
							/**
							 * example: /admin/
							 * �� ����� ����, �� � ��� ������ ������ �������,
							 * ��� ����� ������������� �� ������ � ������� ����������� ������������
							 * � �� �� �������� ��������� ����������
							 * (�������� �������� ����������, �� � ��� ���� ��� ������� ���� ���������,
							 * ������� ������ ����� ���������)
							 * ������, �� �������� ��� ��� �������� ������ ����� ��������������
							 * 
							 */
							'viewpath'	=> $this->viewpath,
							
							// example: http://omni.kio.samaraauto.ru/kio.php/admin/
							'selfurl'	=> BASEURL.$this->cname.'/',
							
							// postback
							'result'	=> $this->result,
		);
		
		//���������� ��� ������ ��� �������
		if ($this->user && $this->user->user_group	== 'client'){
			$this->loadClientData();
		}
	}
	

	/**
	 * ������ �� ������� � � ����� �������
	 * 
	 */
	private function loadClientData(){

		// ���������� �������
		$this->load->model('ClientModel', '__Clients');
		$this->__client			= $this->__Clients->getById($this->user->user_id);
		View::$data['client']	= $this->__client;
		
		// ���������� ���������
		$this->load->model('ManagerModel', '__Managers');
		$this->__partners		= $this->__Managers->getClientManagersById($this->user->user_id);
		if (is_array($this->__partners))
			$this->__partners		= Func::reIndexArrayOfObjects($this->__partners, $this->__Managers->getPK());
		View::$data['partners']	= $this->__partners;
	}
	
	
	
	private function updateDeclarationItem($declaration_id)
	{
		if (!is_numeric($declaration_id) ||
			!isset($_POST['declaration_amount'.$declaration_id]) ||
			!isset($_POST['declaration_cost'.$declaration_id])) return;

		// ������� ����� � ����������
		$declaration = $this->Declarations->getById($declaration_id);

		if (!$declaration)
		{
			throw new Exception('���������� ��������� ����������. ��������� ������ �� �������.');
		}

		// �������� ������ �� ����������
		if ($_POST['declaration_item'.$declaration_id] == '')
		{
			$deleted = $this->Declarations->delete($declaration_id);
				
			if (!$deleted)
			{
				throw new Exception('���������� ��������� ����������. ����������� ��� ���.');
			}
			
			return;
		}
			
		// ��������� ����������������� �����
		Check::reset_empties();
		$declaration->declaration_item 		= Check::txt('declaration_item'.$declaration_id, 8096, 1);
		$declaration->declaration_amount 	= Check::int('declaration_amount'.$declaration_id);
		$declaration->declaration_cost 		= Check::float('declaration_cost'.$declaration_id);
		$empties							= Check::get_empties();
		
		if ($empties)
		{
			throw new Exception('��������� ���� ���������� �� ���������. ���������� ��� ���.');
		}
				
		// ��������� ������� ������
		$new_declaration = $this->Declarations->saveDeclaration($declaration);

		if ($new_declaration === FALSE)
		{
			throw new Exception('���������� ��������� ����������. ����������� ��� ���.');
		}
	}
	
	private function insertDeclarationItem($declaration_id)
	{
		// ��������� ������ ����������� ������
		if (!is_numeric($declaration_id) ||
			!isset($_POST['new_item'.$declaration_id]) ||
			$_POST['new_item'.$declaration_id] == '') return;

		// ��������� ����������������� �����
		$declaration = new stdClass();
		$declaration->declaration_item 		= Check::txt('new_item'.$declaration_id, 8096, 1);
		$declaration->declaration_amount 	= Check::int('new_amount'.$declaration_id);
		$declaration->declaration_cost 		= Check::float('new_cost'.$declaration_id);
		$declaration->declaration_package	= $this->uri->segment(3);
		$empties							= Check::get_empties();

		if ($empties)
		{
			throw new Exception('��������� ���� ���������� �� ���������. ���������� ��� ���.');
		}

		// ���������� ������� ������
		$declaration->declaration_id = '';
		$new_declaration = $this->Declarations->saveDeclaration($declaration);
				
		if (!$new_declaration)
		{
			throw new Exception('���������� ��������� ����������. ����������� ��� ���.');
		}
	}

	protected function showPackages($packageStatus, $pageName, $showDeliveryList=FALSE)
	{
		try
		{
			$this->load->model('PackageModel', 'Packages');

			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
				// ��������� �������
				$view['filter'] = $this->initFilter($packageStatus);
				
				$this->load->model('ManagerModel', 'Managers');
				$view['managers'] = $this->Managers->getManagersData();
				
				// ���������� �������
				$view['packages'] = $this->Packages->getPackages($view['filter'], $packageStatus, null, null);
			}
			else if ($this->user->user_group == 'manager')
			{
				// ���������� �������
				$view['packages']	= $this->Packages->getPackages(null, $packageStatus, null, $this->user->user_id);
				if(is_array($view['packages']))
					$view['packFotos']	= $this->Packages->getPackagesFoto($view['packages']);
			}
			else if ($this->user->user_group == 'client')
			{
				// ����������� �������� ��������
				$this->load->model('DeliveryModel', 'Deliveries');
					
				if ($showDeliveryList)
				{
					$view['deliveries'] = $this->Deliveries->getList();
					
					if (!$view['deliveries'])
					{
						$this->result->m = '���������� ���������� �������. ������� �������� �� ��������.';
						Stack::push('result', $this->result);
					}
				}

				// ���������� �������
				$view['packages']	= $this->Packages->getPackages(null, $packageStatus, $this->user->user_id, null);
				if(is_array($view['packages']))
					$view['packFotos']	= $this->Packages->getPackagesFoto($view['packages']);
				$view['packages']	= $this->Packages->getAvailableDeliveries($view['packages'], $this->Deliveries);
			}
			
			if (!$view['packages'])
			{
				$this->result->m = '������� �� �������.';
				Stack::push('result', $this->result);
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ������ ������
		View::showChild($this->viewpath."pages/$pageName", $view);
	}
	
	protected function showOrders($orderStatus, $pageName)
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('������ ��������.');
			}
			
		    $this->load->model('OrderModel', 'Orders');
		    
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
				// ��������� �������
				$view['filter'] = $this->initFilter($orderStatus);
				
				$this->load->model('ManagerModel', 'Managers');
				$view['managers'] = $this->Managers->getManagersData();
				
				// ���������� ������
				$view['orders'] = $this->Orders->getOrders($view['filter'], $orderStatus, null, null);
			}
			else if ($this->user->user_group == 'manager')
			{
				// ���������� ������
				$view['orders'] = $this->Orders->getOrders(null, $orderStatus, null, $this->user->user_id);
			}
			else if ($this->user->user_group == 'client')
			{
				$this->load->model('CountryModel', 'CountryModel');
				$this->load->model('OdetailModel', 'OdetailModel');
				
				$Orders		= $this->Orders->getOrders(null, $orderStatus, $this->user->user_id, null);
				$Odetails	= $this->OdetailModel->getFilteredDetails(array('odetail_client' => $this->user->user_id, 'odetail_order' => 0));
				$Countries	= $this->CountryModel->getClientAvailableCountries($this->user->user_id);				
						
				$view = array (
					'orders'	=> $Orders,
					'Odetails'	=> $Odetails,
					'Countries'	=> $Countries,
				);
			}
			
			if (!$view['orders'])
			{
				$this->result->m = '������ �� �������.';
				Stack::push('result', $this->result);
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ������ ������
		View::showChild($this->viewpath."/pages/$pageName", $view);
	}
	
	protected function showOrderDetails()
	{
		try
		{
			// ������������
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
			
			$comm_order = $this->showOrderComments(true);
			$view['Managers'] = $comm_order['Managers'];
			$view['Clients'] = $comm_order['Clients'];
			$view['comments'] = $comm_order['comments'];
						
			$this->load->model('OrderModel', 'Orders');
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $view['order'] = $this->Orders->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'manager')
			{
				$view['order'] = $this->Orders->getManagerOrderById($this->uri->segment(3), $this->user->user_id);
			}
			else if ($this->user->user_group == 'client')
			{
				$view['order'] = $this->Orders->getClientOrderById($this->uri->segment(3), $this->user->user_id);
			}
			
			if (!$view['order'])
			{
				throw new Exception('���������� ���������� ������ ������. ���������� ��� ���.');
			}
			$this->load->model('CountryModel', 'CountryModel');
			$view['Countries'] = $this->CountryModel->getClientAvailableCountries($this->user->user_id);

			$view['order']->order_status_desc = $this->Orders->getOrderStatusDescription($view['order']->order_status);
			
			$view['order_statuses'] = $this->Orders->getAvailableOrderStatuses();
			
			// ���������� ������ ������
			$this->load->model('OdetailModel', 'Odetails');
			$view['odetails_statuses'] = $this->Odetails->getAvailableOrderDetailsStatuses();		    
			$view['odetails'] = $this->Odetails->getOrderDetails($view['order']->order_id);
			
			foreach($view['odetails'] as $key => $val)
			{
				$view['odetails'][$key]->odetail_status_desc = $this->Odetails->getOrderDetailsStatusDescription($val->odetail_status);
			}
			if (!$view['odetails'])
			{
				$this->result->m = '������ ������ �� �������.';
				Stack::push('result', $this->result);
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}

		// ���������� ������ ������
		View::showChild($this->viewpath.'/pages/showOrderDetails', $view);
	}
	
	protected function showDeclaration()
	{
		try
		{
			// ������������
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			$this->load->model('PackageModel', 'Packages');

			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $package = $this->Packages->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'manager')
			{
				$package = $this->Packages->getManagerPackageById($this->uri->segment(3), $this->user->user_id);
			}
			else if ($this->user->user_group == 'client')
			{
				$package = $this->Packages->getClientPackageById($this->uri->segment(3), $this->user->user_id);
			}			
		
			if (!$package)
			{
				throw new Exception('���������� ���������� ����������. ��������������� �� ������� ����������.');
			}

			// ���������� ���������� � �������
			$view['package'] = $package;
			$this->load->model('DeclarationModel', 'Declarations');
			$view['declarations'] = $this->Declarations->getDeclarationsByPackageId($this->uri->segment(3));
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			
			// ��������� ����� �������
			if ($this->user->user_group == 'client')
			{
				Func::redirect(BASEURL.$this->cname.'/showOpenPackages');
			}
			else
			{
				Func::redirect(BASEURL.$this->cname.'/showNewPackages');
			}
			
			return;
		}

		View::showChild($this->viewpath.'/pages/showPackageDeclaration', $view);
	}
	
	protected function showPackageComments()
	{
		try
		{
			// ������������
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			$this->load->model('PackageModel', 'Packages');
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $package = $this->Packages->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'manager')
			{
				$package = $this->Packages->getManagerPackageById($this->uri->segment(3), $this->user->user_id);
			}
			else if ($this->user->user_group == 'client')
			{
				$package = $this->Packages->getClientPackageById($this->uri->segment(3), $this->user->user_id);
			}
			
			if (!$package)
			{
				throw new Exception('���������� ���������� �����������. ��������������� ������� ����������.');
			}
			
			$this->load->model('ManagerModel', 'Managers');
			$package->Managers	= $this->Managers->getById($package->package_manager);
			
			$this->load->model('ClientModel', 'Clients');
			$package->Clients	= $this->Clients->getById($package->package_client);

			// ���������� ����������� � �������
			$this->load->model('PCommentModel', 'Comments');
			$view['comments'] = $this->Comments->getCommentsByPackageId($this->uri->segment(3));
			
			// ���������� ���� ������ �����������
			if ($this->user->user_group == 'client' &&
				$package->comment_for_client)
			{
				$package->comment_for_client = 0;
				$view['package'] = $this->Packages->savePackage($package);
			}
			else if ($this->user->user_group == 'manager' &&
				$package->comment_for_manager)
			{
				$package->comment_for_manager = 0;
				$view['package'] = $this->Packages->savePackage($package);
			}
			else
			{
				$view['package'] = $package;
			}

			if (!$view['package'])
			{
				throw new Exception('������ ����������� ������������. ���������� ��� ���.');
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			
			// ��������� ����� �������
			Func::redirect(BASEURL.$this->cname.'/showNewPackages');
			return;
		}

		// ���������� �����������
		View::showChild($this->viewpath.'/pages/showPackageComments', $view);
	}

	protected function showOrderComments($flag = false)
	{
		try
		{
			// ������������
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			$this->load->model('OrderModel', 'Orders');
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $order = $this->Orders->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'manager')
			{
				$order = $this->Orders->getManagerOrderById($this->uri->segment(3), $this->user->user_id);
			}
			else if ($this->user->user_group == 'client')
			{
				$order = $this->Orders->getClientOrderById($this->uri->segment(3), $this->user->user_id);
			}
			
			if (!$order)
			{
				throw new Exception('���������� ���������� �����������. ��������������� ����� ����������.');
			}
			
			$this->load->model('ManagerModel', 'Managers');
			$view['Managers']	=	$this->Managers->getById($order->order_manager);
			
			//var_dump($view['Managers']);
			
			$this->load->model('ClientModel', 'Clients');
			$view['Clients']	=	$this->Clients->getById($order->order_client);

			// ���������� ����������� � ������
			$this->load->model('OCommentModel', 'Comments');
			$view['comments'] = $this->Comments->getCommentsByOrderId($this->uri->segment(3));
			
			// ���������� ���� ������ �����������
			if ($this->user->user_group == 'client' &&
				$order->comment_for_client)
			{
				$order->comment_for_client = 0;
				$view['order'] = $this->Orders->saveOrder($order);
			}
			else if ($this->user->user_group == 'manager' &&
				$order->comment_for_manager)
			{
				$order->comment_for_manager = 0;
				$view['order'] = $this->Orders->saveOrder($order);
			}
			else
			{
				$view['order'] = $order;
			}

			if (!$view['order'])
			{
				throw new Exception('������ ����������� ������������. ���������� ��� ���.');
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			
			// ��������� ����� ������
			Func::redirect(BASEURL.$this->cname.'/showNewOrders');
			return;
		}

		// ���������� �����������
		if ($flag === true) return $view;
		
		View::showChild($this->viewpath.'/pages/showOrderComments', $view);
	}
	
	protected function showO2oComments()
	{
		try
		{
			// ������������
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			$this->load->model('Order2outModel', 'O2o');
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $o2o = $this->O2o->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'client')
			{
				$o2o = $this->O2o->getClientsO2oById($this->uri->segment(3), $this->user->user_id);
			}
			
			if (!$o2o)
			{
				throw new Exception('���������� ���������� �����������. ��������������� ������ ����������.');
			}

			// ���������� ����������� � ������
			$this->load->model('O2CommentModel', 'Comments');
			$view['comments'] = $this->Comments->getCommentsByO2oId($this->uri->segment(3));
			$view['o2o'] = $o2o;
			
			// ���������� ���� ������ �����������
			if ($this->user->user_group == 'client' &&
				$o2o->comment_for_client)
			{
				$o2o->comment_for_client = 0;
				$view['o2o'] = $this->O2o->addOrder($o2o);
			}
			else if ($this->user->user_group == 'admin' &&
				$o2o->comment_for_admin)
			{
				$o2o->comment_for_admin = 0;
				$view['o2o'] = $this->O2o->addOrder($o2o);
			}

			if (!$view['o2o'])
			{
				throw new Exception('������ ����������� ������������. ���������� ��� ���.');
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			
			// ��������� ����� ������
			//Func::redirect(BASEURL.$this->cname.'/showNewOrders');
			return;
		}

		// ���������� �����������
		View::showChild($this->viewpath.'/pages/showO2oComments', $view);
	}
	
	protected function addOrderComment($order_id = null)
	{
		try
		{
	
			$this->load->model('OrderModel', 'Orders');
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'manager')
			{
				$order = $this->Orders->getManagerOrderById((int) $this->uri->segment(3), $this->user->user_id);
				
			}else if ($this->user->user_group == 'client'){
				
				$order = $this->Orders->getClientOrderById((int) $this->uri->segment(3), $this->user->user_id);
				
			}else if ($this->user->user_group == 'admin'){
				$order = $this->Orders->getById((int) $this->uri->segment(3));
				
			}else{
				throw new Exception('������ ��������.');
			}

			if (!$order)
			{
				throw new Exception('���������� �������� �����������. ��������������� ����� ����������.');
			}

			// ��������� ����������������� �����
			$ocomment					= new stdClass();
			$ocomment->ocomment_comment	= Check::txt('comment', 8096, 1);
			$ocomment->ocomment_order	= $this->uri->segment(3);
			$ocomment->ocomment_user	= $this->user->user_id;
			$empties					= Check::get_empties();
		
			if ($empties) 
			{
				throw new Exception('����� ����������� �����������. ���������� ��� ���.');
			}
			
			// ���������� �����������
			$this->load->model('OCommentModel', 'Comments');
			
			$this->db->trans_begin();
			$new_comment = $this->Comments->addComment($ocomment);
			
			if (!$new_comment)
			{
				throw new Exception('����������� �� ��������. ���������� ��� ���.');
			}			
			
			// ���������� ���� ������ �����������
			if ($this->user->user_group == 'manager')
			{
				$order->comment_for_client = TRUE;
			}
			else if ($this->user->user_group == 'client')
			{
				$order->comment_for_manager = TRUE;
			}
			else if ($this->user->user_group == 'admin')
			{
				$order->comment_for_manager	= TRUE;
				$order->comment_for_client	= TRUE;
			}
			
			$order = $this->Orders->saveOrder($order);

			if (!$order)
			{
				throw new Exception('����������� �� ��������. ���������� ��� ���.');
			}
			
			$this->db->trans_commit();
		}
		catch (Exception $e) 
		{
			$this->db->trans_rollback();
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����������� � �������
		Func::redirect(BASEURL.$this->cname.'/showOrderDetails/'.$this->uri->segment(3));
	}

	protected function addO2oComment()
	{
		try
		{
			// ������������
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			$this->load->model('Order2outModel', 'O2o');
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
				$o2o = $this->O2o->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'client')
			{
				$o2o = $this->O2o->getClientsO2oById($this->uri->segment(3), $this->user->user_id);
			}

			if (!$o2o)
			{
				throw new Exception('���������� �������� �����������. ��������������� ������ ����������.');
			}

			// ��������� ����������������� �����
			$o2comment						= new stdClass();
			$o2comment->o2comment_comment	= Check::txt('comment', 8096, 1);
			$o2comment->o2comment_order2out	= $this->uri->segment(3);
			$o2comment->o2comment_user		= $this->user->user_id;
			$empties						= Check::get_empties();

			if ($empties) 
			{
				throw new Exception('����� ����������� �����������. ���������� ��� ���.');
			}
			
			// ���������� �����������
			$this->load->model('O2CommentModel', 'Comments');
			
			$this->db->trans_begin();
			$new_comment = $this->Comments->addComment($o2comment);
			
			if (!$new_comment)
			{
				throw new Exception('����������� �� ��������. ���������� ��� ���.');
			}			
			
			// ���������� ���� ������ �����������
			if ($this->user->user_group == 'admin')
			{
				$o2o->comment_for_client = TRUE;
			}
			else if ($this->user->user_group == 'client')
			{
				$o2o->comment_for_admin = TRUE;
			}
//var_dump($o2o);die();
			
			$o2o = $this->O2o->addOrder($o2o);
//var_dump($o2o);die();
			if (!$o2o)
			{
				throw new Exception('����������� �� ��������. ���������� ��� ���.');
			}
			
			$this->db->trans_commit();
		}
		catch (Exception $e) 
		{
			$this->db->trans_rollback();
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����������� � �������
		Func::redirect(BASEURL.$this->cname.'/showO2oComments/'.$this->uri->segment(3));
	}

	protected function filter($filterType, $pageName)
	{
		try
		{
			// ��������� ����������������� �����
			$filter	= $this->initFilter($filterType);
			if (isset($_POST['manager_user'])) $filter->manager_user						= Check::int('manager_user');
			if (isset($_POST['client_country'])) $filter->client_country					= Check::int('client_country');
			if (isset($_POST['search_id'])) $filter->search_id								= Check::txt('search_id', 11, 1, '');
			if (isset($_POST['search_client'])) $filter->search_client						= Check::txt('search_client', 11, 1, '');
			if (isset($_POST['pricelist_country_from'])) $filter->pricelist_country_from	= Check::int('pricelist_country_from');
			if (isset($_POST['pricelist_country_to'])) $filter->pricelist_country_to		= Check::int('pricelist_country_to');
			if (isset($_POST['pricelist_delivery'])) $filter->pricelist_delivery			= Check::int('pricelist_delivery');
			if (isset($_POST['period'])) $filter->period									= Check::txt('period', 5, 3, '');
			if (isset($_POST['id_type'])) $filter->id_type									= Check::txt('id_type', 13, 5, '');
			
			if ($filter->id_type == '')
			{
				$filter->search_id = '';
				$filter->search_client = '';
			}
			
			$_SESSION[$filterType.'Filter'] = $filter;
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����� �������
		Func::redirect(BASEURL.$this->cname.'/'.$pageName);
	}
	
	protected function initFilter($filterType)
	{
		if (!isset($_SESSION[$filterType.'Filter']))
		{
			$filter = new stdClass();
			$filter->manager_user			= '';
			$filter->client_country			= '';
			$filter->period					= '';
			$filter->search_id				= '';
			$filter->search_client			= '';
			$filter->id_type				= '';
			$filter->pricelist_country_from	= '';
			$filter->pricelist_country_to	= '';
			$filter->pricelist_delivery		= '';
			
			$_SESSION[$filterType.'Filter'] = $filter;
		}	

		return $_SESSION[$filterType.'Filter'];
	}
	
	protected function addPackage()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('������ ��������.');
			}
			
			// ��������� ����������������� �����
			Check::reset_empties();
			$package					= new stdClass();
			
			if ($this->user->user_group == 'admin')
			{
			    $package->package_manager	= Check::int('package_manager');
			}
			else if ($this->user->user_group == 'manager')
			{
				$package->package_manager	= $this->user->user_id;
			}
			
			$package->package_client			= Check::int('package_client');
			$package->package_weight			= Check::float('package_weight');
			$package->declaration_status		= 'not_completed';
			$package->package_status			= 'not_payed';
			$package->join_count				= 0;
			$package->package_comission 		= 0;
			$package->package_declaration_cost	= 0;
			$package->package_delivery_cost		= 0;
			$package->package_join_cost			= 0;
			$package->package_join_count		= 0;
			$empties							= Check::get_empties();
			
			if (is_array($empties)) 
			{
				throw new Exception('���� ��� ��������� ����� �� ���������.');
			}
			
			// ��������� ����� ������� � ���������
			$this->load->model('C2mModel', 'C2M');
			$c2m = $this->C2M->getC2M($package->package_client, $package->package_manager);
			
			if (!$c2m) 
			{
				throw new Exception('���������� �������� �������. ������ � ������� �� �������.');
			}
			
			$this->load->model('ManagerModel', 'Managers');
			$manager = $this->Managers->getById($c2m->manager_id);
			
			if (!$manager) 
			{
				throw new Exception('���������� �������� �������. ������� �� ��������.');
			}
			
			$this->load->model('ClientModel', 'Clients');
			$client = $this->Clients->getById($c2m->client_id);
			
			if (!$client) 
			{
				throw new Exception('���������� �������� �������. ������ �� ��������.');
			}
			
			$package->package_country_from		= $manager->manager_country;
			$package->package_country_to		= $client->client_country;

			// ��������� ��������� �������
			$this->load->model('ConfigModel', 'Config');
			$this->load->model('PackageModel', 'Packages');
			
			$package = $this->Packages->calculateCost($package, $this->Config);
			
			if (!$package) 
			{
				throw new Exception('��������� ������� �� ����������. ���������� ��� ���.');
			}
			
			// ��������� ����� �������
			$this->load->model('ClientModel', 'Clients');
			$client = $this->Clients->getById($package->package_client);
			if (!$client) 
			{
				throw new Exception('������ �� ������. ���������� ��� ���.');
			}
			
			$this->load->model('CountryModel', 'Countries');
			$country = $this->Countries->getById($client->client_country);
			if (!$country) 
			{
				throw new Exception('������ ���������� �� �������. ���������� ��� ���.');
			}
			
			$package->package_address = sprintf('%s %s / %s, %s, �.%s, %s<br />���. %s', 
				$client->client_surname,
				$client->client_name,
				$client->client_index,
				$client->client_address,
				$client->client_town,
				$country->country_name,
				$client->client_phone);
		
			// ���������� �����������
			$this->load->model('PackageModel', 'Packages');
			
			$new_package = $this->Packages->savePackage($package);
			
			if (!$new_package)
			{
				throw new Exception('������� �� ���������. ���������� ��� ���.');
			}			

			// ��������� ����� �������
			Func::redirect(BASEURL.$this->cname.'/showNewPackages');
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname.'/showAddPackage');
		}
	}
	
	protected function saveDeclaration()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			$this->load->model('PackageModel', 'Packages');
	
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $package = $this->Packages->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'manager')
			{
				$package = $this->Packages->getManagerPackageById($this->uri->segment(3), $this->user->user_id);
			}
			else if ($this->user->user_group == 'client')
			{
				$package = $this->Packages->getClientPackageById($this->uri->segment(3), $this->user->user_id);
			}
			
			if (!$package)
			{
				throw new Exception('���������� ��������� ����������. ��������������� ������� �� �������.');
			}

			if ($package->package_status == 'sent')
			{
				throw new Exception('���������� �������� ���������� ��� ������������ �������.');
			}
			
			if ($this->user->user_group == 'manager' && $package->package_status != 'help')
			{
				throw new Exception('�� �� ������ �������� ���������� ��� ������� �������.');
			}			

			$this->load->model('DeclarationModel', 'Declarations');

			// ��������� �� ������� � ����������
			$this->db->trans_begin();
			
			foreach($_POST as $key=>$value)
			{
				if (stripos($key, 'declaration_item') === 0) 
				{
					$declaration_id = str_ireplace('declaration_item', '', $key);
					$this->updateDeclarationItem($declaration_id);
				}
				else if (stripos($key, 'new_item') === 0) 
				{
					$declaration_id = str_ireplace('new_item', '', $key);
					$this->insertDeclarationItem($declaration_id);
				}
			}
			
			// ��������� ������ ����������
			$declarations = $this->Declarations->getDeclarationsByPackageId($package->package_id);
			
			if ($package->declaration_status == 'completed' &&
				!(isset($declarations) &&
				$declarations))
			{
				$package->declaration_status = 'not_completed';
			}
			else if ($package->declaration_status == 'not_completed' &&
				isset($declarations) &&
				$declarations)
			{
				$package->declaration_status = 'completed';
			}
			
			// ��������� ��������� �������
			$this->load->model('ConfigModel', 'Config');
			$this->load->model('PricelistModel', 'Pricelist');
			
			$package = $this->Packages->calculateCost($package, $this->Config, $this->Pricelist);
			
			if (!$package) 
			{
				throw new Exception('���������� ��������� ����������. ��������� ������� �� ����������.');
			}
			
			// ��������� �������
			$package = $this->Packages->savePackage($package);

			if (!$package)
			{
				throw new Exception('���������� �� ���������. ���������� ��� ���.');
			}
			
			$this->db->trans_commit();
			
			// ������� ���������
			$this->result->m = '���������� ������� ���������.';			
			Stack::push('result', $this->result);
		}
		catch (Exception $e) 
		{
			$this->db->trans_rollback();
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����������
		Func::redirect(BASEURL.$this->cname.'/showDeclaration/'.$this->uri->segment(3));
	}
	
	protected function deletePackage()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
			
			$this->load->model('PackageModel', 'Packages');

			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $package = $this->Packages->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'manager')
			{
				$package = $this->Packages->getManagerPackageById($this->uri->segment(3), $this->user->user_id);
			}
			
			if (!$package)
			{
				throw new Exception('������� �� �������. ���������� ��� ���.');
			}

			// ���������� �����������
			$package->package_status	= 'deleted';
			$deleted_package = $this->Packages->savePackage($package);
			
			if (!$deleted_package)
			{
				throw new Exception('������� �� �������. ���������� ��� ���.');
			}			

			$this->result->m = '������� ������� �������.';
			Stack::push('result', $this->result);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����� �������
		Func::redirect(BASEURL.$this->cname.'/showNewPackages');
	}
	
	protected function deleteOrder()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
			
			$this->load->model('OrderModel', 'Orders');

			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $order = $this->Orders->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'client')
			{
				$order = $this->Orders->getManagerOrderById($this->uri->segment(3), $this->user->user_id);
			}

			if (!$order)
			{
				throw new Exception('����� �� ������. ���������� ��� ���.');
			}			

			// ���������� �����������
			$order->order_status = 'deleted';
			$deleted_order = $this->Orders->saveOrder($order);
			
			if (!$deleted_order)
			{
				throw new Exception('����� �� ������. ���������� ��� ���.');
			}			

			$this->result->m = '����� ������� ������.';
			Stack::push('result', $this->result);
		}
		catch (Exception $e)
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ������
		Func::redirect(BASEURL.$this->cname.'/showOpenOrders');
	}
	
	protected function showAddPackage()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('������ ��������.');
			}
			
			$this->load->model('ClientModel', 'Clients');
	
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
				$view['clients'] = $this->Clients->getList();
			}
			else if ($this->user->user_group == 'manager')
			{
				$view['clients'] = $this->Clients->getClientsByManagerId($this->user->user_id);
			}

			if (!$view['clients'])
			{
				throw new Exception('������� �� �������. ���������� ��� ���.');
			}

			// ���������� ������ ��������� ��� ������
			if ($this->user->user_group == 'admin')
			{
				$this->load->model('ManagerModel', 'Managers');
				$view['managers'] = $this->Managers->getManagersData();
				
				if (!$view['managers'])
				{
					throw new Exception('�������� �� �������. ���������� ��� ���.');
				}
			}
			
			View::showChild($this->viewpath.'/pages/showAddPackage', $view);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}
	}
	
	protected function editPackageAddress()
	{
		try
		{
			// ������������
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			$this->load->model('PackageModel', 'Packages');
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $view['package'] = $this->Packages->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'manager')
			{
				$view['package'] = $this->Packages->getManagerPackageById($this->uri->segment(3), $this->user->user_id);
			}
			else if ($this->user->user_group == 'client')
			{
				$view['package'] = $this->Packages->getClientPackageById($this->uri->segment(3), $this->user->user_id);
			}
			
			if (!$view['package'])
			{
				throw new Exception('���������� �������� ����� �������. ��������������� ������� ����������.');
			}
			
			// ������������: �������������� ������ ������������ �������
			if ($view['package']->package_status != 'not_payed')
			{
				throw new Exception('���������� �������� ����� �������. ��������������� ������� ��� ��������.');
			}
			
			// ���������� ������ �����
			$this->load->model('CountryModel', 'Countries');

			$view['countries'] = $this->Countries->getDeliveryCountries($view['package']->package_country_from);
			
			if (!$view['countries']) 
			{
				throw new Exception('���������� �������� ����� �������. ������ ����� ����������.');
			}
			
			View::showChild($this->viewpath.'/pages/editPackageAddress', $view);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}
	}

	protected function updatePackageAddress()
	{
		try
		{
			// ������������
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			$this->load->model('PackageModel', 'Packages');
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $package = $this->Packages->getById($this->uri->segment(3));
			}
			else if ($this->user->user_group == 'manager')
			{
				$package = $this->Packages->getManagerPackageById($this->uri->segment(3), $this->user->user_id);
			}
			else if ($this->user->user_group == 'client')
			{
				$package = $this->Packages->getClientPackageById($this->uri->segment(3), $this->user->user_id);
			}
			
			if (!$package)
			{
				throw new Exception('���������� �������� ����� �������. ��������������� ������� ����������.');
			}
			
			// ������������: �������������� ������ ������������ �������
			if ($package->package_status != 'not_payed')
			{
				throw new Exception('���������� �������� ����� �������. ��������������� ������� ��� ��������.');
			}
			
			// ��������� ����������������� �����
			$prev_country = $package->package_country_to;
			
			Check::reset_empties();
			$package->package_country_to		= Check::int('package_country_to');
			$package->package_address			= Check::txt('package_address', 255, 1, '');
			$empties							= Check::get_empties();
			
			if (is_array($empties)) 
			{
				throw new Exception('���� ��� ��������� ����� �� ���������.');
			}
			
			// �������� ����������� ������� ��������
			$filter = new stdClass();
			
			$filter->pricelist_country_from = $package->package_country_from;
			$filter->pricelist_country_to = $package->package_country_to;
			$filter->pricelist_delivery = '';
			
			$this->load->model('PricelistModel', 'Pricelist');
			$pricelist = $this->Pricelist->getPricelist($filter);
			
			if (!$pricelist)
			{
				throw new Exception('���������� �������� ����� �������. �������� � ��������� ������ ����������.');
			}
			
			// ��������� ��������� �������
			if ($prev_country != $package->package_country_to)
			{
				$package->package_delivery = 0;
				$package->package_delivery_cost = 0;

				$this->load->model('ConfigModel', 'Config');
				$this->load->model('PackageModel', 'Packages');
				
				$package = $this->Packages->calculateCost($package, $this->Config);
				
				if (!$package) 
				{
					throw new Exception('���������� �������� ����� �������. ������ ���������� ��������� �������.');
				}
			}
			
			// ���������� �����������
			$this->load->model('PackageModel', 'Packages');
			$new_package = $this->Packages->savePackage($package);
			
			if (!$new_package)
			{
				throw new Exception('����� ������� �� �������. ���������� ��� ���.');
			}			

			// ��������� ����� �������
			$this->result->m = '����� ������� ������� �������.';			
			Stack::push('result', $this->result);
			
			if ($this->user->user_group == 'admin' ||
				$this->user->user_group == 'manager')
			{
				Func::redirect(BASEURL.$this->cname.'/showNewPackages');
			}
			else if ($this->user->user_group == 'client')
			{
				Func::redirect(BASEURL.$this->cname.'/showOpenPackages');
			}
			
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname.'/editPackageAddress/'.$this->uri->segment(3));
		}
	}
	
	protected function updateOrderDetails()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!isset($_POST['order_id']) ||
				!is_numeric($_POST['order_id']))
			{
				throw new Exception('������ ��������.');
			}
			
			$this->load->model('OrderModel', 'Orders');
			$order_id = $_POST['order_id'];
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $order = $this->Orders->getById($order_id);
			}
			else if ($this->user->user_group == 'manager')
			{
				$order = $this->Orders->getManagerOrderById($order_id, $this->user->user_id);
			}
			
			if (!$order)
			{
				throw new Exception('���������� ��������� ������ ������. ����� ����������.');
			}

			// ��������� ����������������� �����
			Check::reset_empties();
			$order->order_products_cost	= Check::float('order_products_cost');
			$order->order_delivery_cost	= Check::float('order_delivery_cost');
			$order->order_weight 		= Check::float('order_weight');
			if ($this->user->user_group == 'admin')
			{
				$order_cost	= Check::float('order_cost');
			}
			$empties				= Check::get_empties();
	
			if ($empties) 
			{
				throw new Exception('��������� ���� �� ���������. ���������� ��� ���.');
			}
			
			// ��������� ��������� ������
			if ($this->user->user_group == 'admin' &&
				$order_cost != $order->order_cost)
			{
				$order->order_cost = $order_cost;
			}
			else
			{
				$this->load->model('ConfigModel', 'Config');
				$order = $this->Orders->calculateCost($order, $this->Config);
				
				if (!$order) 
				{
					throw new Exception('���������� ��������� ��������� ������. ���������� ��� ���.');
				}
			}
			
			// ��������� ��������� ������������� ��������
			$this->load->model('PricelistModel', 'Pricelist');
			$this->Orders->setAvailableDeliveries($order, $this->Pricelist);
			$order->package_delivery_cost = '';
			
			if ($order->delivery_list)
			{
				foreach ($order->delivery_list as $delivery)
				{
					$order->package_delivery_cost .= $delivery->delivery_name.': '.$delivery->delivery_price.'�<br />';
				}
			}

			// ���������� �����������
			$new_order = $this->Orders->saveOrder($order);
			
			if (!$new_order)
			{
				throw new Exception('����� �� ��������. ���������� ��� ���.');
			}
			
			$this->result->m = '����� ������� ��������.';
			Stack::push('result', $this->result);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ������ ������
		Func::redirect(BASEURL.$this->cname.'/showOrderDetails/'.$order_id);
	}
	
	protected function updateStatus($status, $pageName, $modelName)
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('������ ��������.');
			}
			
			// �������� ���������� �������� ����������
			if (isset($_POST['declaration_status']) &&
				($_POST['declaration_status'] == 'completed' ||
				$_POST['declaration_status'] == 'not_completed'))
			{
				$declaration_status = $_POST['declaration_status'];
			}
			
			$this->load->model($modelName, 'Model');
			
			// ��������� �� �������� ��� �������
			$this->db->trans_begin();
			
			$this->load->model('PackageModel', 'Packages');
			$this->load->model('OrderModel', 'Orders');
			
			foreach($_POST as $key=>$value)
			{
				$this->updatePackageStatus($key, $value);
				$this->updateOrderStatus($key, $value);
			}

			$this->db->trans_commit();
		}
		catch (Exception $e) 
		{
			$this->db->trans_rollback();

			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}

		// ��������� �������
		Func::redirect(BASEURL.$this->cname."/$pageName");
	}

	private function updatePackageStatus($param, $value)
	{
		// ������� ��� ���?
		$is_package = false;
		
		if (stripos($param, 'package_status') !== false)
		{		
			$package_id = str_ireplace('package_status', '', $param);
			
			if (is_numeric($package_id))
			{				
				$package_status = $_POST['package_status'.$package_id];
				
				if ($package_status != 'not_payed' && 
					$package_status != 'payed' && 
					$package_status != 'sent')
				{
					throw new Exception('������ ����� ��� ���������� ������� �� ���������. ����������� ��� ���.');
				}
				
				$is_package = true;
			}
		}
		
		// ���������� ��� ���?
		$is_declaration = false;
		
		if (!$is_package &&
			isset($_POST['declaration_status']) &&
			stripos($param, 'help') !== false)
		{		
			$package_id = str_ireplace('help', '', $param);
			
			if (is_numeric($package_id))
			{	
				$declaration_status = $_POST['declaration_status'];
				
				if ($declaration_status != 'not_completed' && 
					$declaration_status != 'completed')
				{
					throw new Exception('������ ���������� �� ���������. ����������� ��� ���.');
				}
				
				$is_declaration = true;
			}
		}
		
		// ���� �� ������� � �� ����������, �������
		if (!$is_package && !$is_declaration) return;		
		
		// ���� � ������������� �������
		if ($this->user->user_group == 'admin')
		{
			$package = $this->Packages->getById($package_id);
		}
		else if ($this->user->user_group == 'manager')
		{
			$package = $this->Packages->getManagerPackageById($package_id, $this->user->user_id);
		}
		
		if (!$package)
		{
			throw new Exception('���� ��� ��������� ������� �� �������. ����������� ��� ���.');
		}
			
		// ������ ������ �������
		if ($is_package)
		{
			$package->package_status = $package_status;
		}
		
		// ������ ������ ����������
		if ($is_declaration)
		{
			$package->declaration_status = $declaration_status;
		}
		
		// ��������� trackingno
		if (isset($_POST['send_package'.$package_id]))
		{
			Check::reset_empties();
			$package->package_status		= 'sent';
			$package->package_trackingno 	= Check::txt('package_trackingno'.$package_id, 255, 1);
			$empties						= Check::get_empties();
	
			if ($empties) 
			{
				throw new Exception('��������� Tracking � �����������. ���������� ��� ���.');
			}
		}			
		
		// ���������� �����������
		$new_package = $this->Packages->savePackage($package);
		
		if (!$new_package)
		{
			throw new Exception('������� �������/���������� �� ��������. ����������� ��� ���.');
		}
	}

	private function updateOrderStatus($param, $value)
	{
		// ����� ��� ���?
		if (stripos($param, 'order_status') === FALSE) return;
		
		$order_id = str_ireplace('order_status', '', $param);
		if (!is_numeric($order_id)) return;
			
		$order_status = $_POST['order_status'.$order_id];
		
		if ($order_status != 'proccessing' && 
			strpos($order_status, 'not_available') === false && 
			$order_status != 'not_payed' && 
			$order_status != 'payed' && 
			$order_status != 'sended')
		{
			throw new Exception('������ ������ ��� ���������� ������� �� ���������. ����������� ��� ���.');
		}
		
		// ���� � ������������� �������
		if ($this->user->user_group == 'admin')
		{
			$order = $this->Orders->getById($order_id);
		}
		else if ($this->user->user_group == 'manager')
		{
			$order = $this->Orders->getManagerOrderById($order_id, $this->user->user_id);
		}
		
		if (!$order)
		{
			throw new Exception('���� ��� ��������� ������� �� �������. ����������� ��� ���.');
		}
			
		// ������ ������ ������
		$order->order_status = $order_status;
		
		// ���������� �����������
		$new_order = $this->Orders->saveOrder($order);
		
		if (!$new_order)
		{
			throw new Exception('������� ������� �� ��������. ����������� ��� ���.');
		}
	}
	
	protected function updateOdetailStatuses()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!isset($_POST['order_id']) ||
				!is_numeric($_POST['order_id']))
			{
				throw new Exception('������ ��������.');
			}
		
			$order_id = $_POST['order_id'];
			$this->load->model('OrderModel', 'Orders');

			// ���� � ������������� �������
			if ($this->user->user_group == 'admin')
			{
			    $order = $this->Orders->getById($order_id);
			}
			else if ($this->user->user_group == 'manager')
			{
				$order = $this->Orders->getManagerOrderById($order_id, $this->user->user_id);
			}
			
			if (!$order)
			{
				throw new Exception('���������� �������� ������� �������. ����� �� ������.');
			}
	
			// ��������� �� �������
			$this->load->model('OdetailModel', 'Odetails');
			$this->db->trans_begin();
			
			//������ �������			
			foreach($_POST as $key=>$value)
			{
				// ����� ���������� � �������
				if (stripos($key, 'odetail_status') === FALSE) continue;
			
				$odetail_id = str_ireplace('odetail_status', '', $key);
				if (!is_numeric($odetail_id)) continue;

				// ���������� �����������
				$odetail->odetail_price = $_POST['odetail_price'.$odetail_id];
				$odetail->odetail_pricedelivery = $_POST['odetail_pricedelivery'.$odetail_id];
				$odetail->odetail_status = (strpos($value, 'not_available') !== false) ? $value : 'available';
				$odetail->odetail_id = $odetail_id;
				
				$new_odetail = $this->Odetails->addOdetail($odetail);
				
				if (!$new_odetail)
				{
					throw new Exception('������� ��������� ������� �� ��������. ����������� ��� ���.');
				}
			}
			
			// ������ ������ ������
			$status = $this->Odetails->getTotalStatus($order_id);
			
			if (!$status)
			{
				throw new Exception('������ ������ �� ���������. ����������� ��� ���.');
			}
			
			if (strpos($status, 'not_available') !== false)
			{
				$order->order_status = $status;
			}
			else
			{
				$order->order_status = 'not_payed';
			}
			
			$new_order = $this->Orders->saveOrder($order);
			
			if (!$new_order)
			{
				throw new Exception('���������� �������� ������ ������. ����������� ��� ���.');
			}

			$this->db->trans_commit();
			
			$this->result->m = '������� ������� ������� ��������.';
			Stack::push('result', $this->result);
		}
		catch (Exception $e) 
		{
			$this->db->trans_rollback();
			
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		// ��������� ������ ������

		if (isset($order_id))
		{
			Func::redirect(BASEURL.$this->cname.'/showOrderDetails/'.$order_id);
		}
		else
		{
			Func::redirect(BASEURL.$this->cname);
		}
	}
	
	protected function deleteOrder2out($oid) 
	{
		try 
		{
			// ������������
			if (!isset($oid) ||
				!is_numeric($oid))
			{
				throw new Exception('������ ��������.');
			}
		
			// ��������� ����������������� �����
			$this->load->model('Order2outModel', 'Order2out');
		
			$o2o = $this->Order2out->getById((int) $oid);
			
			if (!$o2o ||
				($o2o->order2out_user != $this->user->user_id && $this->user->user_group != 'admin') ||
				$o2o->order2out_status != 'processing')
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}
			
			// ���������� ����������
			$payment_obj = new stdClass();
			$payment_obj->payment_from			= 1;
			$payment_obj->payment_to			= $this->user->user_id;
			$payment_obj->payment_amount_from	= $o2o->order2out_ammount;
			$payment_obj->payment_amount_to		= $o2o->order2out_ammount;
			$payment_obj->payment_amount_tax	= 0;
			$payment_obj->payment_purpose		= '������ ������ �� �����';
			$payment_obj->payment_comment		= '� '.$o2o->order2out_id;
			
			$this->load->model('PaymentModel', 'Payment');
				
			$this->db->trans_begin();
			
			if (!$this->Payment->makePayment($payment_obj)) 
			{
				throw new Exception('������ �������� �� ���� �������. ���������� ��� ���.');
			}
			
			if (!$this->Order2out->delete((int) $oid)) 
			{
				throw new Exception('������ �������� ������ �� �����. ���������� ��� ���.');
			}		
			
			$this->db->trans_commit();
			
			// ��������� ��������� � ������
			$this->session->set_userdata(array('user_coints' => $this->user->user_coints + $o2o->order2out_ammount));
			$this->result->r = 1;
			$this->result->m = '������ ������� �������.';
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			$this->result->r = $e->getCode();
			$this->result->m = $e->getMessage();
		}

		Stack::push('result', $this->result);
		
		if ($this->user->user_group == 'admin')
		{
			Func::redirect(BASEURL.$this->cname.'/showOrderToOut');
		}
		else
		{
			Func::redirect(BASEURL.$this->cname.'/showOutMoney');
		}
	}
	
	
	public function getPayments(){
		
		if (!$this->user)	return false;
		
		$this->load->model('PaymentModel', 'Payment');
		
	}

	
	/**
	 * ������� ���� ������� �� ����� ����� � �� �������, ��������� ����� ��� ���������� � ������ ������� ��������
	 *
	 * @param (int)		$pid
	 * @param (string)	$filename
	 */
	protected function showPackagePhoto($pid,$filename) {
		
		header('Content-type: image/jpg');
		
		$filename	= Check::var_str($filename, 255,1);
		(int) $pid;
		
		$this->load->model('PackageModel', 'Package');
		$package	= $this->Package->getById($pid);
		
		if ($this->user->user_group == 'admin'){

		}
		else if ($this->user->user_group == 'manager'){
			if ($this->user->user_id != $package->package_manager) die();
		}
		else if ($this->user->user_group == 'client'){
			if ($this->user->user_id != $package->package_client) die();
		}else{
			die();
		}

		if (!$package || $pid != $package->package_id){
			die();
		}
		
		
		if (file_exists(UPLOAD_DIR.'packages/'.$package->package_manager.'/'.$pid.'/'.$filename)){
			readfile(UPLOAD_DIR.'packages/'.$package->package_manager.'/'.$pid.'/'.$filename);
		}
		
		die();
	}
	
	
	protected function delPackageComment($package_id, $comment_id)
	{
		try
		{
		
			(int) $comment_id;
			(int) $package_id;
			$this->load->model('PackageModel', 'Packages');
			
			if ($this->user->user_group	== 'manager'){
				$package = $this->Packages->getManagerPackageById($package_id, $this->user->user_id);
				
			}elseif ($this->user->user_group == 'client'){
				$package = $this->Packages->getClientPackageById($package_id, $this->user->user_id);
				
			}elseif ($this->user->user_group == 'admin'){
				$package = $this->Packages->getById($package_id);
				
			}else{
				throw new Exception('������ ��������.');
			}
			

			if (!$package)
			{
				throw new Exception('���������� ������� �����������. ������� �� �������.');
			}

			
			// ���������� �����������
			$this->load->model('PCommentModel', 'Comments');
			
			if (!$this->Comments->delComment($comment_id))
			{
				throw new Exception('����������� �� ������. ���������� ��� ���.');
			}			
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����������� � �������
		Func::redirect(BASEURL.$this->cname.'/showPackageComments/'.$this->uri->segment(3));
	}
	
	protected function delOrderComment($order_id, $comment_id){
		
		try
		{
			(int)	$order_id;
			(int)	$comment_id;
			$this->load->model('OrderModel', 'Orders');
			
			// ���� � ������������� �������
			if ($this->user->user_group == 'manager')
			{
				$order = $this->Orders->getManagerOrderById($order_id, $this->user->user_id);
				
			}else if ($this->user->user_group == 'client'){
				
				$order = $this->Orders->getClientOrderById($order_id, $this->user->user_id);
				
			}else if ($this->user->user_group == 'admin'){
				$order = $this->Orders->getById($order_id);
				
			}else{
				throw new Exception('������ ��������.');
			}

			if (!$order)
			{
				throw new Exception('���������� ������� �����������. ��������������� ����� ����������.');
			}


		
			// ���������� �����������
			$this->load->model('OCommentModel', 'Comments');
			
			if (!$this->Comments->delComment($comment_id))
			{
				throw new Exception('����������� �� ������. ���������� ��� ���.');
			}			

		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����������� � �������
		Func::redirect(BASEURL.$this->cname.'/showOrderDetails/'.$order_id);
		
	}

}
?>