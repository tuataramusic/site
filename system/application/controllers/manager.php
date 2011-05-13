<?php
require_once BASE_CONTROLLERS_PATH.'ManagerBaseController'.EXT;

class Manager extends ManagerBaseController {

	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		//$this->showNewPackages();
		$this->addPackage();
		//View::showChild($this->viewpath.'/pages/main');
	}

	public function showAddPackage()
	{
		parent::showAddPackage();
	}
	
	public function addPackage()
	{
		parent::addPackage();
	}
	
	public function updateOdetailStatuses()
	{
		parent::updateOdetailStatuses();
	}
	
	public function updatePackagesTrackingNo()
	{
		try
		{
			$this->load->model('PackageModel', 'Packages');
			$this->db->trans_begin();
			
			foreach($_POST as $key=>$value)
			{
				// поиск параметров в запросе
				if (stripos($key, 'package') === FALSE) continue;
			
				$package_id = str_ireplace('package', '', $key);
				if (!is_numeric($package_id)) continue;

				// безопасность: проверяем связку менеджера и посылки
				$this->load->model('PackageModel', 'Packages');
				$package = $this->Packages->getManagerPackageById($package_id, $this->user->user_id);

				if (!$package)
				{
					throw new Exception('Одна или несколько посылок не найдены. Попоробуйте еще раз.');
				}
					
				// валидация пользовательского ввода
				Check::reset_empties();
				$package->package_status		= 'sent';
				$package->package_trackingno 	= Check::txt('package_trackingno'.$package_id, 255, 1);
				$empties						= Check::get_empties();
		
				if ($empties) 
				{
					throw new Exception('Некоторые Tracking № отсутствуют. Попробуйте еще раз.');
				}
				
				// сохранение результатов
				$new_package = $this->Packages->savePackage($package);
				
				if (!$new_package)
				{
					throw new Exception('Некоторые посылки не отправлены. Попоробуйте еще раз.');
				}
			}
			
			$this->db->trans_commit();
			
			$this->result->m = 'Посылки успешно отправлены.';
			Stack::push('result', $this->result);
		}
		catch (Exception $e) 
		{
			$this->db->trans_rollback();
			
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}

		// открываем оплаченные посылки
		Func::redirect(BASEURL.$this->cname.'/showPayedPackages');
	}
	
	public function closeOrders()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('Доступ запрещен.');
			}
			
			$this->load->model('OrderModel', 'Orders');
			$this->db->trans_begin();
			
			foreach($_POST as $key=>$value)
			{
				// поиск параметров в запросе
				if (stripos($key, 'order') === FALSE) continue;
			
				$order_id = str_ireplace('order', '', $key);
				if (!is_numeric($order_id)) continue;

				// безопасность: проверяем связку менеджера и заказа
				$this->load->model('OrderModel', 'Orders');
				$order = $this->Orders->getManagerOrderById($order_id, $this->user->user_id);

				if (!$order)
				{
					throw new Exception('Один или несколько заказов не найдены. Попоробуйте еще раз.');
				}
					
				// сохранение результатов
				$order->order_status = 'sended';
				$new_order = $this->Orders->saveOrder($order);
				
				if (!$new_order)
				{
					throw new Exception('Некоторые заказы не отправлены. Попоробуйте еще раз.');
				}
			}
			
			$this->db->trans_commit();
			
			$this->result->m = 'Заказы успешно закрыты.';
			Stack::push('result', $this->result);
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}

		// открываем заказы
		Func::redirect(BASEURL.$this->cname.'/showOpenOrders');
	}
	
	public function updateOrderDetails()
	{
		parent::updateOrderDetails();
	}
	
	public function showNewPackages()
	{
		$this->showPackages('not_payed', 'showNewPackages');
	}
	
	public function showPayedPackages()
	{
		$this->showPackages('payed', 'showPayedPackages');
	}
	
	public function showSentPackages()
	{
		$this->showPackages('sent', 'showSentPackages');
	}
	
	public function showOpenOrders()
	{
		$this->showOrders('open', 'showOpenOrders');
	}
	
	public function showSentOrders()
	{
		$this->showOrders('sended', 'showSentOrders');
	}

	public function addPackageComment($package_id, $comment_id = null)
	{
		try
		{
			if (!is_numeric($package_id))
			{
				throw new Exception('Доступ запрещен.');
			}
		
			// безопасность: проверяем связку менеджера и посылки
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getManagerPackageById($package_id, $this->user->user_id);

			if (!$package)
			{
				throw new Exception('Невозможно добавить комментарий. Партнер не обрабатывает данную посылку.');
			}

			// валидация пользовательского ввода
			$pcomment					= new stdClass();
			$pcomment->pcomment_comment	= Check::txt('comment', 8096, 1);
			$pcomment->pcomment_package	= $package_id;
			$pcomment->pcomment_user	= $this->user->user_id;
			$empties					= Check::get_empties();
		
			if ($empties) 
			{
				throw new Exception('Текст комментария отсутствует. Попробуйте еще раз.');
			}
			
			// сохранение результатов
			$this->load->model('PCommentModel', 'Comments');
			
			if (is_numeric($comment_id)) $pcomment->pcomment_id = $comment_id;
			
			if (!$this->Comments->addComment($pcomment))
			{
				throw new Exception('Комментарий не добавлен. Попробуйте еще раз.');
			}			
			
			// выставляем флаг нового комментария
			$package->comment_for_client = TRUE;
			$package = $this->Packages->savePackage($package);

			if (!$package)
			{
				throw new Exception('Комментарий не добавлен. Попробуйте еще раз.');
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// открываем комментарии к посылке
		Func::redirect(BASEURL.$this->cname.'/showPackageComments/'.$package_id);
	}
	
	public function delPackageComment($package_id, $comment_id){
		parent::delPackageComment((int) $package_id, (int) $comment_id);
	}

	public function deletePackage()
	{
		parent::deletePackage();
	}

	public function editPackageAddress()
	{
		parent::editPackageAddress();
	}

	public function showOrderDetails()
	{
		parent::showOrderDetails();
	}
	
	public function showDeclaration()
	{
		parent::showDeclaration();
	}
	
	public function previewDeclaration($package_id){
		
		$this->load->model('PackageModel', 'Packages');
		$this->load->model('DeclarationModel', 'Declarations');
		
		(int) $package_id;
		$declarations	= null;
		
		$package		= $this->Packages->getManagerPackageById($package_id, $this->user->user_id);
		
		if ($package){
			$declarations	= $this->Declarations->getDeclarationsByPackageId($package_id);	
		}
		
		View::showChild($this->viewpath.'/pages/previewPackageDeclaration', array(
			'package'		=> $package,
			'declarations'	=> $declarations
		));
	}
	
	public function showPackageComments()
	{
		parent::showPackageComments();
	}
	
	public function showOrderComments($flag = false)
	{
		return parent::showOrderComments($flag);
	}
	
	public function addOrderComment()
	{
		parent::addOrderComment();
	}
	
	public function saveDeclaration()
	{
		parent::saveDeclaration();
	}	

	public function updatePackageAddress()
	{
		parent::updatePackageAddress();
	}
	
	public function updateNewPackagesStatus()
	{
		$this->updateStatus('not_payed', 'showNewPackages', 'PackageModel');
	}
	
	public function showPaymentHistory($view = null)
	{
		if (!$view){
			$this->load->model('PaymentModel', 'Payment');
			$view = array(
				'Payments'	=> $this->Payment->getFilteredPayments("user_to.user_id={$this->user->user_id} OR user_from.user_id={$this->user->user_id}"),
			);
		}


		View::showChild($this->viewpath.'pages/showPaymentHistory', $view);
	}
	
	public function addPackageFoto(){
		
		$package_id	= Check::int('package_id');
		
		// загрузка файла
		$config['upload_path']			= UPLOAD_DIR.'packages/'.$this->user->user_id.'/'.$package_id.'/';
		$config['allowed_types']		= 'jpg';
		$config['max_size']				= '4096';
		//$config['max_width'] 			= '2048';
		//$config['max_height'] 		= '2048';
		$config['remove_spaces'] 		= FALSE;
		$config['overwrite'] 			= FALSE;
		$config['encrypt_name'] 		= TRUE;
		$max_width						= 1024;
		$max_height						= 768;
		
		try{
			$this->load->model('PackageModel', 'Package');
			$package	= $this->Package->getById($package_id);
			
			if (!$package || $package_id != $package->package_id){
				throw new Exception('Не верный номер посылки!');
			}
			
			if (!is_dir($config['upload_path']) && !(mkdir($config['upload_path'], 0777, true) || chmod($config['upload_path'], 0777))){
				throw new Exception('Ошибка файловой системы. Обратитесь к администратору.');
			}
	
			$this->load->library('upload', $config);
			$uploaded = false;
			foreach(array('userfile1','userfile2','userfile3','userfile4','userfile5') as $val)
			{
				if ($this->upload->do_upload($val))	
				{
					$uploaded = true;
				
					$uploadedImg = $this->upload->data();
					$imageInfo = getimagesize($uploadedImg['full_path']);
					if ($imageInfo[0]>$max_width || $imageInfo[1]>$max_height)
					{
						$config['image_library']	= 'gd2';
						$config['source_image']		= $uploadedImg['full_path'];
						$config['maintain_ratio']	= TRUE;
						$config['width']			= $max_width;
						$config['height']			= $max_height;

						$this->load->library('image_lib', $config); // загружаем библиотеку
						$this->image_lib->resize(); // и вызываем функцию
					}
				}
			}
			if (! $uploaded)
			{
				throw new Exception((strip_tags(trim($this->upload->display_errors()))));
			}
			//$uFile	= $this->upload->data();
			//if (!rename($uFile["full_path"],$uFile["file_path"].$package_id.'.jpg')){
			//	throw new Exception('Can`t rename filename!');
			//}
			
		}catch (Exception $e){
			$this->result->m	= $e->getMessage();
			Stack::push('result', $this->result);
		}
		
		Func::redirect('/'.$this->cname.'/showNewPackages');
	}
	
	public function showPackageFoto($pid, $filename){
		$this->showPackagePhoto($pid, $filename);
	}
	
	
	public function showScreen($oid=null) {
		header('Content-type: image/jpg');
		$this->load->model('OdetailModel', 'OdetailModel');
		if ($Detail = $this->OdetailModel->getInfo(array('odetail_manager' => $this->user->user_id, 'odetail_id' => intval($oid)))) {
			readfile($_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$Detail->odetail_client.'/'.$Detail->odetail_id.'.jpg');
		}
		die();
	}
}

/* End of file main.php */
/* Location: ./system/application/controllers/main.php */