<?php
require_once BASE_CONTROLLERS_PATH.'AdminBaseController'.EXT;

class Admin extends AdminBaseController {

	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$this->showNewPackages();
		//View::showChild($this->viewpath.'/pages/main');
	}
	
	public function showPaymentHistory($view = null)
	{
		if (!$view){
			$this->load->model('PaymentModel', 'Payment');
			$view = array(
				'Payments'	=> $this->Payment->getFilteredPayments(),
			);
		}


		View::showChild($this->viewpath.'/pages/payment_history', $view);
	}
	
	public function searchPayments() {
		 
		$from	= $to		= null;
		$sfield	= $svalue	= null;
		$filter	= array();
		$fields	= array(
								'payment_from',
								'user_from',
								'user_to',
								'payment_from'
		);
		
		switch ($_POST['sdate']) {
			case 'day':
				$from = date('Y-m-d 00:00:00');
				break;
			case 'week':
				$from = intval(date('w')) ? date('Y-m-d 00:00:00', time()-(intval(date('w'))-1)*24*60*60) : date('Y-m-d 00:00:00', time()-6*24*60*60);
				break;
			case 'month':
				$from = date('Y-m-01 00:00:00');
				break;
		}
		if ($from)
			$to = date('Y-m-d H:i:s');
			
		$sfield	= Check::str('sfield',64,1);
		$stype	= Check::str('stype',4,2);
		$svalue	= Check::str('svalue',64,1);
		
		if (!in_array($sfield.'_'.$stype, $fields)){
			$sfield	= null;
			$stype	= null;
			$this->result->e	= -2;
			$this->result->m	= '�� ������ ���� �������';
		}
		
		if ($sfield == 'payment' && !is_numeric($svalue)){
			$sfield	= null;
			$this->result->e	= -1;
			$this->result->m	= '�� ������ ������ �������';
		}
		
		if ($sfield == 'user'){
			$sfield = "user_$stype.user_login";
		}
		
		if ($sfield){
			$filter = array($sfield => $svalue);
		}
			
		$this->load->model('PaymentModel', 'Payment');
		
		$view = array(
			'Payments'		=> $this->Payment->getFilteredPayments($filter, $from, $to),
			'from_search'	=> true,
			'postback'		=> $_POST,
			'result'			=> $this->result,
		);
		$this->showPaymentHistory($view);
	}
	
	

	public function showOrderToOut($status = null)
	{
		$status = ($status == 'payed') ? $status : 'processing';
		
		$this->load->model('Order2outModel', 'Order2out');
		$Orders = $this->Order2out->getFilteredOrders(array('order2out_status' => $status));
		
		$view = array(
			'Orders'	=> $Orders,
			'statuses'	=> $this->Order2out->getStatuses(),
			'status'	=> $status
		);
		
		View::showChild($this->viewpath.'/pages/order_to_out', $view);
	}
	
	public function searchOrders2out() 
	{
		$this->load->model('Order2outModel', 'Order2out');
		$Orders = $this->Order2out->getFilteredOrders(array(@$_POST['sfield'] => @$_POST['svalue']));
		
		$view = array(
			'Orders'	=> $Orders,
			'statuses'	=> $this->Order2out->getStatuses(),
			'status'	=> 'none'
		);
		
		View::showChild($this->viewpath.'/pages/order_to_out', $view);
	}
	
	public function saveOrders2out() 
	{
		$ids = Check::idsByFilter('status_');
		
		// ���� ������ � ������ id
		if (count($ids)) {
			$this->load->model('Order2outModel', 'Order2out');
			$Orders = $this->Order2out->getOrdersByIds($ids);
			
			$updated = 0;
			
			foreach ($Orders as $Order) {
				// ������ ������ ������ ��� ������� � ���������
				if ($Order->order2out_status == 'processing' && $Order->order2out_status != $_POST['status_'.$Order->order2out_id]) {
					$this->Order2out->_set('order2out_status', $_POST['status_'.$Order->order2out_id]);
					$this->Order2out->_set('order2out_id', $Order->order2out_id);
					if ($this->Order2out->save())
						$updated++;	
				}
			}
			
			if($updated) {
				$this->result->r = 1;
				$this->result->m = '������ ������� ���������: '.$updated;		
				Stack::push('result', $this->result);
			}
			
		}
		Func::redirect(BASEURL.$this->cname.'/showOrderToOut');
	}
	
	public function deleteOrder2out($oid) 
	{
		parent::deleteOrder2out($oid);
	}

	/*
	public function deleteOrder2out($oid=null) {
		
		$this->load->model('Order2outModel', 'Order2out');
		
		$_o = $this->Order2out->getById((int) $oid);
		if ($_o && $_o->order2out_status == 'processing'){
			try {
				$this->db->trans_begin();
				
				$payment_obj = new stdClass();
				$payment_obj->payment_from			= 0;
				$payment_obj->payment_to			= $_o->order2out_user;
				$payment_obj->payment_amount_from	= $_o->order2out_ammount;
				$payment_obj->payment_amount_to		= $_o->order2out_ammount;
				$payment_obj->payment_amount_tax	= 0;
				$payment_obj->payment_purpose		= '������ �������� �����';
				
				$this->load->model('PaymentModel', 'Payment');
				$this->Payment->_load($payment_obj);
				if (!$this->Payment->makePayment()) {
					throw new Exception('������ �������� ��������.', -4);
				}
				
				if (!$this->Order2out->delete((int) $oid)) {
					throw new Exception('������ �������� ��������.', -5);
				}
				
				$this->db->trans_commit();
				$this->result->r = 1;
				$this->result->m = '������ ������� �������';
			} catch (Exception $e){
				$this->db->trans_rollback();
				$this->result->r = $e->getCode();
				$this->result->m = $e->getMessage();
			}
		}else{
			$this->result->r = -2;
			$this->result->m = '������ �� �������';
		}
		
		Stack::push('result', $this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showOrderToOut');
	}
	*/
	#---------------------------------------------------------------------------
	#
	#	News
	#
	#---------------------------------------------------------------------------
	public function showEditNews()
	{
		if (!isset($this->News))
			$this->load->model('NewsModel', 'News');
		
		$news	= $this->News->select(null, 10);
				
		if (!$news) $news = array();
			
		View::showChild($this->viewpath.'/pages/news', array('news'=> $news));
	}
	
	public function saveNews(){
		Check::reset_empties();
		$title	= Check::txt('title',	8096,1);
		$body	= Check::txt('body',	8096,1);
		
		
		// fild all fields
		if (!Check::get_empties()){
			$this->load->model('NewsModel', 'News');
			$this->News->_set('news_title', $title);
			$this->News->_set('news_body', $body);
			
			$id		= Check::int('id');
			if ($id) 
				$this->News->_set('news_id',	$id);			
			
			if (!$this->News->save()){
				$this->result->e	= -1;
				$this->result->m	= '��������� �������� ������.';
			}else{
				$this->result->e	= 1;
				$this->result->m	= '������ ������� ��������.';
			}
		}else{
			$this->result->e	= -1;
			$this->result->m	= '��������� �������� ������. �������� ����������� ���� ��� ��������� �����.';
		}
		
		Stack::push('result', $this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showEditNews');
		
	}
	
	public function deleteNews($news_id){
		
		$id	= Check::var_int($news_id);
		
		if ($id){
			$this->load->model('NewsModel', 'News');
			if ($this->News->delete($id)){
				$this->result->e	= 1;
				$this->result->m	= '������ ������� �������';
			}else{
				$this->result->e	= -1;
				$this->result->m	= "�� ���������� ������ � ��������� ID($id) ��� ������ �� ����� ���� �������.";
			}
		}else{
			$this->result->e		= -2;
			$this->result->m		= "�� ���������� ID($news_id).";
		}
		
		Stack::push('result',$this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showEditNews');		

	}
	
	#---------------------------------------------------------------------------
	#
	#	FAQ
	#
	#---------------------------------------------------------------------------
	public function showEditFAQ()
	{
		if (!isset($this->Faq))
			$this->load->model('FaqModel', 'Faq');
			
		$faq	= $this->Faq->select(null, 10);
		if (!$faq) $faq = array();

		View::showChild($this->viewpath.'/pages/faq', array('faq'=> $faq));
	}
	
	public function saveFaq(){
		Check::reset_empties();
		$question	= Check::txt('question',	8096,1);
		$answer		= Check::txt('answer',		8096,1);
		
		// fild all fields
		if (!Check::get_empties()){
			$this->load->model('FaqModel', 'Faq');
			
			$id		= Check::int('id');
			if ($id) 
				$this->Faq->_set('faq_id',			$id);
			
			$this->Faq->_set('faq_question',	$question);
			$this->Faq->_set('faq_answer',		$answer);
			
			if (!$this->Faq->save()){
				$this->result->e	= -1;
				$this->result->m	= '��������� �������� ������.';
			}else{
				$this->result->e	= 1;
				$this->result->m	= '������ ������� ��������.';
			}
		}else{
			$this->result->e	= -1;
			$this->result->m	= '��������� �������� ������. �������� ����������� ���� ��� ��������� �����.';
		}
		
		Stack::push('result', $this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showEditFaq');
		
	}
	
	public function deleteFaq($faq_id){
		
		$id	= Check::var_int($faq_id);
		
		if ($id){
			$this->load->model('FaqModel', 'Faq');
			if ($this->Faq->delete($id)){
				$this->result->e	= 1;
				$this->result->m	= '������ ������� �������';
			}else{
				$this->result->e	= -1;
				$this->result->m	= "�� ���������� ������ � ��������� ID($id) ��� ������ �� ����� ���� �������.";
			}
		}else{
			$this->result->e		= -2;
			$this->result->m		= "�� ���������� ID($faq_id).";
		}
		
		Stack::push('result',$this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showEditFaq');

	}
	
	#---------------------------------------------------------------------------
	#
	#	Tariffs
	#
	#---------------------------------------------------------------------------
	public function showEditServicesPrice()
	{
		$this->load->model("ConfigModel", "ConfigModel");
		
		View::showChild($this->viewpath.'/pages/services', array(
			'config'	=> $this->ConfigModel->getConfig()
		));
	}
	
	public function saveServicesPrice()
	{
		$this->load->model("ConfigModel", "ConfigModel");
		
		$conf	= array(
						'price_for_trasmission'	=> Check::str('transmission',	8096,1),
						'price_for_help'		=> Check::str('help',			8096,1),
						'price_for_declaration'	=> Check::str('declaration',	8096,1),
						'price_for_marge'		=> Check::str('merge',			8096,1),
						'price_for_insurance'	=> Check::str('insurance',		8096,1)
						
		);
		
		$this->result->d = array();
		foreach ($conf as $key => $value){
			if (!$this->ConfigModel->setConfig($key,$value)){
				$this->result->e		= -1;
				$this->result->m		= 'Can`t save one or more options!';
				$this->result->d[$key]	= $value;
			}else{
				$this->result->m		= SAVE_SUCCESS;
			}
		}
		
		Stack::push('result',$this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showEditServicesPrice');

	}

	#---------------------------------------------------------------------------
	#
	#	Partners
	#
	#---------------------------------------------------------------------------
	public function showPartners($operation=null, $uid=null) 
	{
		try
		{
			$this->load->model('ManagerModel', 'Manager');
			$managers = $this->Manager->getManagersData();
			
			$this->load->model('CountryModel', 'Country');
			$Countries	= $this->Country->getList();
			$countries = array();
			foreach ($Countries as $Country)
			{
				$countries[$Country->country_id] = $Country->country_name;
			}
			
			$view = array(
				'managers' 	=> $managers,
				'countries'	=> $countries,
				'statuses'	=> $this->Manager->getStatuses()
			);
		
			View::showChild($this->viewpath.'/pages/showPartners', $view);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}		
	}
	
	public function showClients() 
	{
		try
		{
			// ��������� �������
			$view['filter'] = $this->initFilter('clients');
				
			// �������� ������ ��������
			$this->load->model('ClientModel', 'Client');
			$view['clients'] = $this->Client->getClients($view['filter']);
			
			if (!$view['clients'])
			{
				$this->result->m = '������� �� �������. ���������� ��� ���.';
				Stack::push('result', $this->result);
			}
			
			$view['clients_count'] = $this->Client->getClientsCount();

			if (!$view['clients_count'])
			{
				throw new Exception('���������� �������� �� ����������. ���������� ��� ���.');
			}

			// �������� ������ ���������
			$this->load->model('ManagerModel', 'Managers');
			$view['managers'] = $this->Managers->getManagersData();
		
			if (!$view['managers'])
			{
				throw new Exception('�������� �� �������. ���������� ��� ���.');
			}

			// �������� ������ �������� � ���������
			$this->load->model('C2mModel', 'C2m');
			
			foreach ($view['clients'] as $client)
			{
				$client->managers = $this->Managers->getClientManagersById($client->client_user);
			////	var_dump( $client->managers);
			}
			
			// �������� ������ �����
			$this->load->model('CountryModel', 'Country');
			$view['countries']	= $this->Country->getList();
			
			if (!$view['countries'])
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}

			$view['country_list'] = array();
			foreach ($view['countries'] as $country)
			{
				$view['country_list'][$country->country_id] = $country->country_name;
			}
			
			// ������ ��� �������
			$view['countries']	= $this->Country->getToCountries();
			
			if (!$view['countries'])
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}

			View::showChild($this->viewpath.'/pages/showClients', $view);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}		
	}
	
	public function showCountries() 
	{
		try
		{
			$this->load->model('CountryModel', 'Country');
			$view['countries'] = $this->Country->getCountriesWithDelivery();
			
			if (!$view['countries'])
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}
			
			View::showChild($this->viewpath.'/pages/showCountries', $view);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}		
	}
	
	public function deletePartner($uid=null) 
	{
		$this->load->model('UserModel', 'User');
		
		$_u = $this->User->getById((int) $uid);
		if ($_u && $_u->user_group == 'manager'){
			try {
				$this->User->deleteUser($_u);
				
				$this->load->model('ManagerModel', 'Manager');
				$_m = $this->Manager->getById((int) $uid);
				
				//��������� ������ ������-��������			
				if ($_m->manager_status == 1) {					
					$neighbor_managers = $this->Manager->select(array('manager_country' => $_m->manager_country, 'manager_status' => 1));
					$this->load->model('C2mModel', 'C2m');
					if (count($neighbor_managers) == 1) { // ������� ������������ � ������, ������� ������
						$this->C2m->deletePartnerRelations($_m->manager_user);
					}
					else {
						$all_count = $this->C2m->getPartnerClientsCount($uid);
						if ($all_count) {
							$updated_managers_count = count($neighbor_managers)-1;
							$updated_managers = array(); //������ ��� ���� - id ��������, � �������� - ���-�� ����������� ��� ���������� ������
							$base = floor($all_count / $updated_managers_count);
							foreach ($neighbor_managers as $neighbor) {
								if ($neighbor->manager_user != $_m->manager_user)
									$updated_managers[$neighbor->manager_user] = $base;
							}
							if ($delta = ($all_count % $updated_managers_count)) {
								foreach ($updated_managers as $key=>$value) {
									if ($delta) {
										$updated_managers[$key] = $value+1;
										$delta--;
									}
								}
							}
							
							// ��������� ������
							foreach ($updated_managers as $key=>$value) {
								$this->C2m->changePartner($_m->manager_user, $key, $value);
								$manager = $this->Manager->fixMaxClientsCount($key);
								
								if (!$manager)
								{
									throw new Exception('���������� ������� ��������. ���������� ��� ���.');
								}
							}
						}
					}
				}				
				
				$_m->manager_status = 2;
				$this->Manager->updateManager($_m);
				
				
				$this->result->r = 1;
				$this->result->m = '������� ������� ������';
			} catch (Exception $e){
				$this->result->r = $e->getCode();
				$this->result->m = $e->getMessage();
			}
		}else{
			$this->result->r = -2;
			$this->result->m = '������� �� ������';
		}
		
		Stack::push('result', $this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showPartners');
	}
	
	public function deleteClient($uid) 
	{
		try 
		{
			if (!$uid ||
				!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
			
			// ��������� ����������������� �����
			$this->load->model('UserModel', 'User');
			$this->db->trans_begin();
			
			$user = $this->User->getById((int)$uid);
		
			if (!$user ||
				$user->user_group != 'client')
			{
				throw new Exception('������ �� ������. ���������� ��� ���.');
			}
			
			// ������� �������
			$user = $this->User->deleteUser($user);
			
			if (!$user)
			{
				throw new Exception('���������� ������� �������. ���������� ��� ���.');
			}
				
			//��������� ������ ������-��������
			$this->load->model('C2mModel', 'C2m');
			$this->C2m->deleteClientRelations($uid);
					
			// �������� ����������
			if ($this->db->trans_status() === FALSE) 
			{
				throw new Exception('���������� ������� �������. ���������� ��� ���.');
			}
			
			$this->result->m = '������ ������� ������.';
			$this->db->trans_commit();			
		} 
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			
			$this->result->r = $e->getCode();
			$this->result->m = $e->getMessage();
		}
		
		Stack::push('result', $this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showClients');
	}
	
	public function deletePricelistCountries($from, $to) 
	{
		try 
		{
			// ��������� ����������������� �����
			if (!$from ||
				!is_numeric($from) ||
				!$to ||
				!is_numeric($to))
			{
				throw new Exception('������ ��������.');
			}
			
			$this->load->model('PricelistModel', 'Pricelist');
			
			// �������� �������
			$pricelist = $this->Pricelist->deletePricelistCountries($from, $to);
		
			if (!$pricelist)
			{
				throw new Exception('����� �� ������. ���������� ��� ���.');
			}
			
			$this->result->m = '����� ������� ������.';
		} 
		catch (Exception $e)
		{
			$this->result->r = $e->getCode();
			$this->result->m = $e->getMessage();
		}
		
		Stack::push('result', $this->result);
		Func::redirect(BASEURL.$this->cname.'/editPricelist');
	}
	
	public function deleteCountry($uid) 
	{
		try 
		{
			if (!$uid ||
				!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
			
			// ��������� ����������������� �����
			$this->load->model('CountryModel', 'Country');
			
			$country = $this->Country->getById((int)$uid);
		
			if (!$country)
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}
			
			// ������� ������
			$deleted = $this->Country->delete($uid);
				
			if (!$deleted)
			{
				throw new Exception('���������� ������� ������. ����������� ��� ���.');
			}
	
			$this->result->m = '������ ������� �������.';
		} 
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			
			$this->result->r = $e->getCode();
			$this->result->m = $e->getMessage();
		}
		
		Stack::push('result', $this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showCountries');
	}
	
	public function editPartner($uid) 
	{
		try
		{
			// ������������
			if (!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
				
			$view['update'] = 1;
			
			// ������� ������������
			$this->load->model('UserModel', 'User');
			$view['manager_user'] = $this->User->getById((int) $uid);

			if (!$view['manager_user'])
			{
				throw new Exception('������������ �� ������. ���������� ��� ���.');
			}

			// ������� ��������
			$this->load->model('ManagerModel', 'Manager');
			$view['manager'] = $this->Manager->getById((int) $uid);

			if (!$view['manager'])
			{
				throw new Exception('������� �� ������. ���������� ��� ���.');
			}

			// ������� ������
			$this->load->model('CountryModel', 'Country');		
			$view['countries'] = $this->Country->getList();
			
			if (!$view['countries'])
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}

			// ������� �������
			$view['statuses'] = $this->Manager->getStatuses();
				
			if (!$view['statuses'])
			{
				throw new Exception('������� �� �������. ���������� ��� ���.');
			}

			// ������� ������� �������� ��������
			$this->load->model('ManagerDeliveryModel', 'MD');		
			$view['deliveries'] = $this->MD->getByManagerId($view['manager']->manager_user);
				
			if (!$view['deliveries'])
			{
				throw new Exception('������� �������� �� �������. ���������� ��� ���.');
			}

			// ��������� �������
			$view['filter'] = $this->initFilter('editPartner');
	
			// ���������� ������� � ������
			$this->load->model('PackageModel', 'Packages');
			$view['packages'] = $this->Packages->getPackages($view['filter'], 'sent', null, $uid);		
			
			$this->load->model('OrderModel', 'Orders');
			$view['orders'] = $this->Orders->getOrders($view['filter'], 'sended', null, $uid);	

			if (!$view['packages'])
			{
				$view['packages'] = array();
			}
			
			if ($view['orders'])
			{
				$view['packages'] = array_merge($view['packages'], $view['orders']);
			}

			// ���������� ��������
			View::showChild($this->viewpath.'/pages/editPartner', $view);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}		
	}
	
	public function moveClients() 
	{
		try
		{
			// ������������
			if (!isset($_POST['newPartnerId']) ||
				!is_numeric($_POST['newPartnerId']))
			{
				throw new Exception('������ ��������.');
			}
				
			$newPartnerId = $_POST['newPartnerId'];
			
			// ������� ��������
			$this->load->model('ManagerModel', 'Manager');
			$manager = $this->Manager->getById($newPartnerId);

			if (!$manager)
			{
				throw new Exception('����� ������� �� ������. ���������� ��� ���.');
			}

			// ��������� �� ������������ ��������
			$this->load->model('C2mModel', 'C2M');
			$this->load->model('ClientModel', 'Clients');
			$this->db->trans_start();
			
			foreach($_POST as $key=>$value)
			{
				if (stripos($key, 'move') === 0) 
				{
					// ������� �������
					$client_id = str_ireplace('move', '', $key);
					if (!is_numeric($client_id))
					{
						continue;
					}
			
					$client = $this->Clients->getClientById($client_id);

					if (!$client)
					{
						throw new Exception('��������� ������� �� �������. ���������� ��� ���.');
					}
					
					// ��������� ����������������� �����
					$relation = $this->C2M->getC2M($client_id, $manager->manager_user);

					if ($relation)
					{
						throw new Exception('��������� ������� �� ����� ���� ����������. ����� � ������ �������� ���������.');
					}
					
					// ��������� ���������
					$relation = $this->C2M->moveClient($client_id, $manager->manager_user);
					
					if (!$relation)
					{
						throw new Exception('��������� ������� �� ����� ���� ����������. ���������� ��� ���.');
					}
				}
			}
			
			// ��������� ������������ ����� ��������
			$manager = $this->Manager->fixMaxClientsCount($manager->manager_user);
				
			if (!$manager)
			{
				throw new Exception('������ ���������� ������������� ����� �������� � ��������. ���������� ��� ���.');
			}

			// �������� ����������
			if ($this->db->trans_status() === FALSE) 
			{
				throw new Exception('���������� ����������� ��������. ���������� ��� ���.');
			}
					
			$this->db->trans_commit();
			$this->result->m = '������� ������� ����������.';
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
		}		

		Stack::push('result', $this->result);
		Func::redirect(BASEURL.$this->cname.'/showClients');
	}
	
	public function editClient($uid) 
	{
		try
		{
			// ������������
			if (!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
				
			// ������������
			$this->load->model('UserModel', 'User');
			$view['client_user'] = $this->User->getById($uid);			

			if (!$view['client_user'] || 
				$view['client_user']->user_deleted)
			{
				throw new Exception('������������ �� ������. ���������� ��� ���.');
			}
			
			// ������
			$this->load->model('ClientModel', 'Client');
			$view['client'] = $this->Client->getClientById($uid);			

			if (!$view['client'])
			{
				throw new Exception('������ �� ������. ���������� ��� ���.');
			}
			
			// ������
			$this->load->model('CountryModel', 'Country');
			$view['countries'] = $this->Country->getList();
			
			if (!$view['countries'])
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}
		
			// ��������� �������
			$view['filter'] = $this->initFilter('editClient');
	
			// ���������� ������� � ������
			$this->load->model('PackageModel', 'Packages');
			$view['packages'] = $this->Packages->getPackages($view['filter'], 'sent', $uid, null);		
			
			$this->load->model('OrderModel', 'Orders');
			$view['orders'] = $this->Orders->getOrders($view['filter'], 'sended', $uid, null);	

			if (!$view['packages'])
			{
				$view['packages'] = array();
			}
			
			if ($view['orders'])
			{
				$view['packages'] = array_merge($view['packages'], $view['orders']);
			}
			
			View::showChild($this->viewpath.'pages/editClient', $view);
		}
		catch (Exception $e)
		{
			$result->e	= $e->getCode();			
			$result->m	= $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}
	}
	
	public function editClientBalance($uid) 
	{
		try
		{
			// ������������
			if (!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
				
			// ������������
			$this->load->model('UserModel', 'User');
			$view['client_user'] = $this->User->getById($uid);			

			if (!$view['client_user'] || 
				$view['client_user']->user_deleted)
			{
				throw new Exception('������������ �� ������. ���������� ��� ���.');
			}
			
			// ������
			$this->load->model('ClientModel', 'Client');
			$view['client'] = $this->Client->getById($uid);			

			if (!$view['client'])
			{
				throw new Exception('������ �� ������. ���������� ��� ���.');
			}
			
			View::showChild($this->viewpath.'pages/editClientBalance', $view);
		}
		catch (Exception $e)
		{
			$result->e	= $e->getCode();			
			$result->m	= $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname.'/showClients');
		}
	}
	
	public function editCountry($uid) 
	{
		try
		{
			// ������������
			if (!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
				
			// ������
			$this->load->model('CountryModel', 'Country');
			$view['country'] = $this->Country->getById($uid);			

			if (!$view['country'])
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}
			
			View::showChild($this->viewpath.'pages/editCountry', $view);
		}
		catch (Exception $e)
		{
			$result->e	= $e->getCode();			
			$result->m	= $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname.'/showCountries');
		}
	}
	
	public function updateClient($uid)
	{
		try
		{
			// ������������
			if (!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
				
			// ������������
			$this->load->model('UserModel', 'User');
			$user = $this->User->getById($uid);			

			if (!$user || 
				$user->user_deleted)
			{
				throw new Exception('������������ �� ������. ���������� ��� ���.');
			}
			
			// ������
			$this->load->model('ClientModel', 'Client');
			$client = $this->Client->getClientById($uid);			

			if (!$client)
			{
				throw new Exception('������ �� ������. ���������� ��� ���.');
			}
			
			// ��������� ����������������� �����
			Check::reset_empties();
			$user->user_login		= Check::str('login',32,1);
			if (isset($_POST['password']) &&
				$_POST['password'])
			{
				$user->user_password = Check::str('password',32,1);
				if (isset($user->user_password))
				{
					$user->user_password = md5($user->user_password);
				}
			}
			$user->user_email			= Check::email(Check::str('email',128,6));
			
			$client->client_name		= Check::latin('name',128,1);
			$client->client_otc			= Check::latin('otc',128,1);
			$client->client_surname		= Check::latin('surname',128,1);
			$client->client_country		= Check::int('country');
			$client->client_index		= Check::int('index');
			$client->client_town		= Check::latin('town',64,1);
			$client->client_address		= Check::latin('address',512,1);
			$client->client_phone		= Check::int('phone');
			$empties					= Check::get_empties();
		
			if (!$user->user_email){
				throw new Exception('�� ������ E-mail.', -13);
			}			
			
			if ($empties && in_array('_latin', $empties)){
				throw new Exception('������ ������ ���� ������� ���������.', -14);
			}
		
			if ($empties){
				throw new Exception('���� ��� ��������� ����� �� ���������.', -11);
			}
			
			// ���������� ����������
			$this->db->trans_start();
			$user = $this->User->updateUser($user);
			
			if (!$user || 
				$user->user_deleted)
			{
				throw new Exception('������������ �� ��������. ���������� ��� ���.');
			}
			
		//var_dump($client);	
		/*unset($client->client_country);*/
			$client = $this->Client->updateClient($client);
			
			if (!$client)
			{
				throw new Exception('������ �� ��������. ���������� ��� ���.');
			}
		
			// �������� ����������
			if ($this->db->trans_status() === FALSE) 
			{
				throw new Exception('���������� ��������� ������ ��������. ���������� ��� ���.');
			}
					
			$this->db->trans_commit();
			$this->result->m = '������ ������� ��������.';
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
		}
	
		Stack::push('result', $this->result);
		Func::redirect(BASEURL.$this->cname.'/editClient/'.$uid);
		//View::showChild($this->viewpath);
	}

	public function updateClientBalance($uid)
	{
		try
		{
			// ������������
			if (!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
				
			// ������������
			$this->load->model('UserModel', 'User');
			$user = $this->User->getById($uid);			

			if (!$user || 
				$user->user_deleted)
			{
				throw new Exception('������������ �� ������. ���������� ��� ���.');
			}
			
			// ��������� ����������������� �����
			Check::reset_empties();
			$user->user_coints = Check::int('user_coints');
			$empties = Check::get_empties();
		
			if ($empties){
				throw new Exception('������� ���������� ������.');
			}
			
			// ���������� ����������
			$user = $this->User->updateUser($user);
			
			if (!$user || 
				$user->user_deleted)
			{
				throw new Exception('������������ �� ��������. ���������� ��� ���.');
			}
			
			$this->result->m = '������ ������� ��������.';
		}
		catch (Exception $e)
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
		}
	
		Stack::push('result', $this->result);
		Func::redirect(BASEURL.$this->cname.'/editClientBalance/'.$uid);
	}

	public function updateCountry($uid)
	{
		try
		{
			// ������������
			if (!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
				
			// ������
			$this->load->model('CountryModel', 'Country');
			$country = $this->Country->getById($uid);			

			if (!$country)
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}
			
			// ��������� ����������������� �����
			Check::reset_empties();
			$country->country_name = Check::str('country_name', 64, 1);
			$empties = Check::get_empties();
		
			if ($empties){
				throw new Exception('������� ���������� �������� ������.');
			}
			
			// ���������� ����������
			$country = $this->Country->saveCountry($country);
			
			/*if (!$country)
			{
				throw new Exception('������ �� ���������. ���������� ��� ���.');
			}*/
			
			$this->result->m = '������ ������� ���������.';
		}
		catch (Exception $e)
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
		}
	
		Stack::push('result', $this->result);
		Func::redirect(BASEURL.$this->cname.'/editCountry/'.$uid);
	}

	public function updatePartner($uid) 
	{
		try
		{
			// ������������
			if (!is_numeric($uid))
			{
				throw new Exception('������ ��������.');
			}
				
			// ������� ������������
			$this->load->model('UserModel', 'User');
			$user = $this->User->getById((int) $uid);

			if (!$user)
			{
				throw new Exception('������������ �� ������. ���������� ��� ���.');
			}

			// ������� ��������
			$this->load->model('ManagerModel', 'Manager');
			$manager = $this->Manager->getById((int) $uid);

			if (!$manager)
			{
				throw new Exception('������� �� ������. ���������� ��� ���.');
			}

			$prev_status	= $manager->manager_status;
			$prev_country	= $manager->manager_country;
			
			// ��������� ����������������� �����
			Check::reset_empties();

			$user->user_email = Check::email(Check::str('user_email',128,4));
			
			if (isset($_POST['user_password']) &&
				$_POST['user_password'])
			{
				$user->user_password = Check::str('user_password',32,1);
			
				if (isset($user->user_password))
				{
					$user->user_password = md5($user->user_password);
				}
			}
			
			$manager->manager_name			= Check::latin('manager_name',128,1);
			$manager->manager_surname		= Check::latin('manager_surname',128,1);
			$manager->manager_otc			= Check::latin('manager_otc',128,1);
			$manager->manager_country		= Check::int('manager_country');
			$manager->manager_addres		= Check::latin('manager_addres',512,1);
			$manager->manager_phone			= Check::int('manager_phone',999999999999,1);
			$manager->manager_max_clients	= Check::int('manager_max_clients');
			$manager->manager_status		= Check::int('manager_status');
			$manager->manager_credit		= Check::int('manager_credit');
			
			
			$empties = Check::get_empties();			
			
			if ($empties)
			{
				if (in_array('_latin', $empties))
				{
					throw new Exception('������ ������ ���� ������� ���������. ���������� ��� ���.', -14);
				}
				else if (!$user->user_email)
				{
					throw new Exception('�� ������ e-mail. ���������� ��� ���.', -13);
				}
				else
				{
					throw new Exception('���� ��� ��������� ����� �� ���������. ���������� ��� ���.', -11);
				}
			}
			
			$this->db->trans_begin();
					
			$user = $this->User->updateUser($user);
			$manager = $this->Manager->updateManager($manager);
			
			if (!$user || !$manager)
			{
				throw new Exception('������� �� ��������. ���������� ��� ���.');
			}
			
			// ��������� ������������ ����� ��������
			if ($manager->manager_status == 1)
			{
				$manager = $this->Manager->fixMaxClientsCount($uid);
				
				if (!$manager)
				{
					throw new Exception('������ ���������� ������������� ����� ��������. ���������� ��� ���.');
				}
			}			
			
			// ��������� ������� ��������
			$this->load->model('ManagerDeliveryModel', 'Delivery');
			$this->Delivery->clearManagerDelivery($manager->manager_user);
			
			if (isset($_POST['delivery']) && is_array($_POST['delivery']) && !empty($_POST['delivery'])){
				foreach ($_POST['delivery'] as $delivery_id => $delivery_name){
					if (is_numeric($delivery_id))
					{	
						$delivery = new stdClass();
						$delivery->manager_id = $manager->manager_user;
						$delivery->delivery_id = $delivery_id;
						$delivery = $this->Delivery->saveManagerDelivery($delivery);
						if (isset($delivery) && !$delivery)
						{
							throw new Exception('�� ��������� ������ ��������. ���������� ��� ���.');
						}
					}
				}
			}
			
			// ��������� ������ ������-�������� ����� ������ �������:
			// ����������� �������� ��������� �� ��������� ����������
			if ($prev_status == 1 && $manager->manager_status == 2)
			{
				$neighbour_managers = $this->Manager->select(array('manager_country' => $prev_country, 'manager_status' => 1));
				$this->load->model('C2mModel', 'C2M');
				
				// ������� ������������ � ������, ������� ������
				if (!$neighbour_managers) 
				{ 
					$this->C2M->deletePartnerRelations($manager->manager_user);
				}
				else 
				{
					$all_count = $this->C2M->getPartnerClientsCount($uid);
				
					if ($all_count) 
					{
						$updated_managers_count = count($neighbour_managers);
						$updated_managers = array(); //������ ��� ���� - id ��������, � �������� - ���-�� ����������� ��� ���������� ������
						$base = floor($all_count / $updated_managers_count);
					
						foreach ($neighbour_managers as $neighbor) {
							$updated_managers[$neighbor->manager_user] = $base;
						}
						
						if ($delta = ($all_count % $updated_managers_count)) 
						{
							foreach ($updated_managers as $key=>$value) 
							{
								if ($delta) 
								{
									$updated_managers[$key] = $value + 1;
									$delta--;
								}
							}
						}
						
						// ��������� ������
						foreach ($updated_managers as $key=>$value) 
						{
							$this->C2M->changePartner($manager->manager_user, $key, $value);
							
							// ��������� ������������ ����� ��������
							$manager = $this->Manager->fixMaxClientsCount($key);
								
							if (!$manager)
							{
								throw new Exception('������ �������� �������� � �������� ���������. ���������� ��� ���.');
							}
						}
					}
				}
			}
			
			// �������� ����������
			if ($this->db->trans_status() === FALSE) 
			{
				throw new Exception('���������� ��������� ������ ��������. ���������� ��� ���.');
			}
					
			$this->db->trans_commit();

			$this->result->m = '������� ������� ��������.';
			Stack::push('result', $this->result);
		}
		catch (Exception $e) 
		{
			$this->db->trans_rollback();
			
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		#$this->output->enable_profiler(true);
		#return;
		// ��������� ������ ��������
		Func::redirect(BASEURL.$this->cname.'/showPartnerInfo/'.$uid);
	}
	
	public function showAddPartner(){
		$this->showPartnerInfo();
	}
	
	public function showPartnerInfo($partner_id = 0)
	{
		try
		{
			//$view['user'] = false;
			
			// ������� ������
			$this->load->model('CountryModel', 'Country');		
			
			//$view['countries'] = $this->Country->getCountriesFromDelivery();
			
			// ��� ����������� ��������� ������ �� ������, � ������� ������� ���� ��������
			$view['countries']  = $this->Country->getFromCountries();
			
			if (!$view['countries'])
			{
				throw new Exception('������ �� �������. ���������� ��� ���.');
			}

			// ������� �������
			$this->load->model('ManagerModel', 'Manager');		
			$view['statuses'] = $this->Manager->getStatuses();
				
			if (!$view['statuses'])
			{
				throw new Exception('������� �� �������. ���������� ��� ���.');
			}
			
			if ((int)$partner_id){
				$this->load->model('UserModel', 'User');
				
				$view['manager_user']	= $this->User->getById($partner_id);
				$view['manager']	= $this->Manager->getById($partner_id);
			}
			

			// ������� ������� �������� ��������
			$this->load->model('ManagerDeliveryModel', 'MD');		
			$view['deliveries'] = $this->MD->getByManagerId($partner_id);
			
			if (!$view['deliveries'])
			{
				throw new Exception('������� �������� �� �������. ���������� ��� ���.');
			}
			
			if (Stack::size('view')>0){
				//$view	= Stack::shift('view');
			}
			
			$this->load->model('PackageModel', 'Package');
			$view['packages']	= $this->Package->getByManagerId($partner_id);

			//View::showChild($this->viewpath.'/pages/editPartner', $view);
			View::showChild($this->viewpath.'/pages/showPartnerInfo', $view);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}		
	}
	
	public function addPartner() 
	{
		
		$countries = '';
		if (Stack::size('all_countries') > 0)
		{
			$countries	= Stack::last('all_countries');
		}
		else
		{
			$this->load->model('CountryModel', 'Country');
			//$countries	= $this->Country->getList();
			$countries  = $this->Country->getFromCountries();

		}
		
		$countries_ids = array();
		if ($countries) 
		{
			foreach ($countries as $country)
			{
				$countries_ids[] = $country->country_id;
			}
		}
		
		$this->load->model('ManagerModel', 'Manager');
		$statuses = $this->Manager->getStatuses();
		
		// ������� ������� �������� ��������
		$this->load->model('ManagerDeliveryModel', 'MD');		
		$deliveries = $this->MD->getByManagerId(0);
			
		if (!$deliveries)
		{
			throw new Exception('������� �������� �� �������. ���������� ��� ���.');
		}

		// ��������� ����������������� �����	
		Check::reset_empties();
		$user							= new stdClass();
		$user->user_login				= Check::str('user_login',32,1);
		$user->user_password			= Check::str('user_password',32,1);
		$user->user_email				= Check::email(Check::str('user_email',128,6));
		$user->user_group				= 'manager';
		
		$manager						= new stdClass();
		$manager->manager_name			= Check::latin('manager_name',128,1);
		$manager->manager_surname		= Check::latin('manager_surname',128,1);
		$manager->manager_otc			= Check::latin('manager_otc',128,1);
		$manager->manager_country		= Check::int('manager_country');
		$manager->manager_addres		= Check::latin('manager_addres',512,1);
		$manager->manager_phone			= Check::int('manager_phone');
		$manager->manager_max_clients	= Check::int('manager_max_clients');
		$manager->manager_status		= Check::int('manager_status');
		$manager->manager_credit		= Check::float('manager_credit');
		$empties						= Check::get_empties();
		
		try{
			if (!$user->user_email)
			{
				throw new Exception('�� ������ e-mail.', -13);
			}
			
			if ($empties && in_array('_latin',$empties))
			{
				throw new Exception('������ ������ ���� ������� ���������.', -14);			
			}
			if ($empties)
			{
				throw new Exception('���� ��� ��������� ����� �� ���������.', -11);
			}
				
			
			if (!in_array($manager->manager_country, $countries_ids))
			{
				throw new Exception('�������� ������.', -19);
			}
			if (!key_exists($manager->manager_status, $statuses))
			{
				throw new Exception('�������� ������.', -20);
			}
			
			$this->load->model('UserModel', 'User');			
			
			if ($this->User->select(array('user_email'=> $user->user_email))){
				throw new Exception('������������ � ����� ����������� ������ ��� ����������!', -16);
			}
			
			$user->user_password = md5($user->user_password);
  
			// ������� ������������ � ��������
			$this->db->trans_begin();			

			$u = $this->User->addUser($user);
			
			if ($u)
				$this->Manager->addManagerData($u->user_id, $manager);
			
			if ($this->db->trans_status() === FALSE) {				
				throw new Exception('����������� �������� ����������.',-3);
			}
			
			// ��������� �������� ��������
			if ($manager->manager_status == 1) 
			{
				$neighbour_managers = $this->Manager->select(array('manager_country' => $manager->manager_country, 'manager_status' => 1));
			
				// ������� ������������ � ������, ������ ��� ���� ��������
				if (count($neighbour_managers) == 1) 
				{ 
					$this->load->model('ClientModel', 'Client');
					$clients = $this->Client->getList();
				
					if ($clients) 
					{
						// ��������� �����
						$this->load->model('C2mModel', 'C2m');
						foreach ($clients as $client) 
						{
							$relation = new stdClass();
							$relation->client_id = $client->client_user;
							$relation->manager_id = $u->user_id;
							
							$this->C2m->addRelation($relation);
						}
						
						// ��������� ������������ ����� ��������
						$manager = $this->Manager->fixMaxClientsCount($u->user_id);
							
						if (!$manager)
						{
							throw new Exception('������ ���������� ������������� ����� ��������. ���������� ��� ���.');
						}
					}
				}
			}
			
			// ��������� ������� ��������
			$this->load->model('ManagerDeliveryModel', 'Delivery');
			
			foreach($_POST as $key=>$value)
			{
				if (stripos($key, 'delivery') !== false)
				{		
					$delivery_id = str_ireplace('delivery', '', $key);
					
					if (is_numeric($delivery_id))
					{	
						$delivery = new stdClass();
						$delivery->manager_id = $u->user_id;
						$delivery->delivery_id = $delivery_id;
						$delivery = $this->Delivery->saveManagerDelivery($delivery);
						if (!$delivery)
						{
							throw new Exception('���������� ��������� ������� ��������. ���������� ��� ���.');
						}
					}
				}
			}
			
			$this->db->trans_commit();
			Func::redirect(BASEURL.$this->cname.'/showPartners');	
			return true;
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			
			$this->result->e	= $e->getCode();			
			$this->result->m	= $e->getMessage();
			
			switch ($this->result->e){
				case -1:	
					$user->user_login		= '';
					break;
				case -2:
				case -13:
					$user->user_email		= '';
					break;				
			}		
			$this->result->d	= $user;
		}		
		
		$view = array(
			'countries'		=> $countries,
			'manager'		=> $manager,
			'statuses'		=> $statuses,
			'deliveries'	=> $deliveries,
			'manager_user'	=> $user // �������������� ���������� $user (������ � ��� �������� ��������)
		);
		
		Stack::push('view', $view);
		View::showChild($this->viewpath.'pages/showPartnerInfo', $view);
		//Func::redirect(BASEURL.$this->cname.'/showPartnerInfo');
	}

	
	public function testPays(){
		
		// ���������� ��������� �������� ������
		// ������� � �������� �� �������
//		$payment_obj = new stdClass();
//		$payment_obj->payment_from			= 2;		// ������� � �������� ����������� ������
//		$payment_obj->payment_to			= 3;		// ������� �� ������� ����������� ������
//		$payment_obj->payment_tax			= '10%';	// ����� ��������� �������� ��� ������� ��������, �������: 10% �� ������� ������ �������
//		$payment_obj->payment_amount_from	= 4950;		// ����� ������� ����� ����� � �������� ������� �����
//		$payment_obj->payment_amount_to		= 4500;		// ����� ������� ����� ����������
//		$payment_obj->payment_amount_tax	= 450;		// ����� ������� ����� ���������� ������� (�����)
//		$payment_obj->payment_purpose		= '������� � ������ ����� �� ������'; // ���������� �������
//		$payment_obj->payment_comment		= 'something comments'; // ���������� (����� �������������)
		
		
		$payment_obj = new stdClass();
		$payment_obj->payment_from			= 0;// ���������� �� ���� ������������
		$payment_obj->payment_to			= 2;
		$payment_obj->payment_tax			= 0;
		$payment_obj->payment_amount_from	= 5500;
		$payment_obj->payment_amount_to		= 5000;
		$payment_obj->payment_amount_tax	= 0;
		$payment_obj->payment_purpose		= '���������� �� ���� ������������';
		$payment_obj->payment_comment		= 'something comments';
		
//		$payment_obj = new stdClass();
//		$payment_obj->payment_from			= 2;// �������� � ����� ������������
//		$payment_obj->payment_to			= 0;
//		$payment_obj->payment_tax			= '10%';
//		$payment_obj->payment_amount_from	= 5500;
//		$payment_obj->payment_amount_to		= 5000;
//		$payment_obj->payment_amount_tax	= 500;
//		$payment_obj->payment_purpose		= '�������� � ����� ������������';
//		$payment_obj->payment_comment		= 'something comments';
		
		$this->load->model('PaymentModel', 'Payment');
		$this->Payment->_load($payment_obj);
		$r = $this->Payment->makePayment();
		
		if (is_object($r)){
			echo $r->getMessage();
		}elseif((int)$r){
			echo 'new payment id:'.$r;
		}

		
	}
	
	public function refreshSummary() {
		$this->load->model('PaymentModel', 'Payment');
		$stat = $this->Payment->getSummaryStat();
		Stack::clear('admin_summary_stat');
		Stack::push('admin_summary_stat', $stat);
		Func::redirect(BASEURL.$this->cname);
	}
	
	public function deleteOrder()
	{
		parent::deleteOrder();
	}
	
	public function filterNewPackages()
	{
		$this->filter('not_payed', 'showNewPackages');
	}
	
	public function filterPayedPackages()
	{
		$this->filter('payed', 'showPayedPackages');
	}
	
	public function filterSentPackages()
	{
		$this->filter('sent', 'showSentPackages');
	}
	
	public function filterOpenOrders()
	{
		$this->filter('open', 'showOpenOrders');
	}
	
	public function filterSentOrders()
	{
		$this->filter('sended', 'showSentOrders');
	}
	
	public function filterClients()
	{
		$this->filter('clients', 'showClients');
	}
	
	public function showNewPackages()
	{
		
		Breadcrumb::setCrumb(array('showNewPackages'=> '����� �������'),1,true);
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
	
	public function updateNewPackagesStatus()
	{
		$this->updateStatus('not_payed', 'showNewPackages', 'PackageModel');
	}
	
	public function updatePayedPackagesStatus()
	{
		$this->updateStatus('payed', 'showPayedPackages', 'PackageModel');
	}
	
	public function updateSentPackagesStatus()
	{
		$this->updateStatus('sent', 'showSentPackages', 'PackageModel');
	}
	
	public function updateOpenOrdersStatus()
	{
		$this->updateStatus('open', 'showOpenOrders', 'OrderModel');
	}
	
	public function updateSentOrdersStatus()
	{
		$this->updateStatus('sended', 'showSentOrders', 'OrderModel');
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
	
	public function showOpenOrders()
	{
		$this->showOrders('open', 'showOpenOrders');
	}
	
	public function showSentOrders()
	{
		$this->showOrders('sended', 'showSentOrders');
	}
	
	public function showAddDelivery()
	{
		try
		{
			View::showChild($this->viewpath.'/pages/showAddDelivery');
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}
	}
	
	public function showAddCountry()
	{
		try
		{
			View::showChild($this->viewpath.'/pages/showAddCountry');
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname);
		}
	}
	
	public function addCountry()
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
			$country				= new stdClass();
			$country->country_name	= Check::txt('country_name', 32, 1);
			$empties				= Check::get_empties();
			
			if (is_array($empties)) 
			{
				throw new Exception('��������� �������� ������.');
			}
			
			// ���������� �����������
			$this->load->model('CountryModel', 'Countries');
			$new_country = $this->Countries->saveCountry($country);
			
			if (!$new_country)
			{
				throw new Exception('������ �� ���������. ���������� ��� ���.');
			}			

			$this->result->m = '������ ������� ���������.';
			Stack::push('result', $this->result);
			
			// ��������� ������
			Func::redirect(BASEURL.$this->cname.'/editPricelist');
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname.'/showAddDelivery');
		}
	}
	
	public function addDelivery()
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
			$delivery					= new stdClass();
			$delivery->delivery_name	= Check::txt('delivery_name', 32, 1);
			$delivery->delivery_time	= Check::txt('delivery_time', 32, 1);
			$empties					= Check::get_empties();
			
			if (is_array($empties)) 
			{
				throw new Exception('���� ��� ��������� ����� �� ���������.');
			}
			
			// ���������� �����������
			$this->load->model('DeliveryModel', 'Deliveries');
			$new_delivery = $this->Deliveries->saveDelivery($delivery);
			
			if (!$new_delivery)
			{
				throw new Exception('������ �������� �� ��������. ���������� ��� ���.');
			}			

			// ��������� ������
			Func::redirect(BASEURL.$this->cname.'/editPricelist');
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			Func::redirect(BASEURL.$this->cname.'/showAddDelivery');
		}
	}
	
	public function editPricelist()
	{
		try
		{
			// ��������� �������
			$view['filter'] = $this->initFilter('editPricelist');
			
			$this->load->model('CountryModel', 'Countries');
			$view['countries'] = $this->Countries->getList();
			
			$this->load->model('DeliveryModel', 'Deliveries');
			$view['deliveries'] = $this->Deliveries->getList();
			
			if ($view['filter']->pricelist_country_from == '' ||
				$view['filter']->pricelist_country_to == '' ||
				$view['filter']->pricelist_delivery == '')
			{
					throw new Exception('�������� ������ � ������ ��������.');
			}
			
			$view['delivery'] = $this->Deliveries->getById($view['filter']->pricelist_delivery);
			
			if (!$view['delivery'])
			{
				throw new Exception('������ �������� �� ������. ���������� ��� ���.');
			}
			
			// ���������� ������
			$this->load->model('PricelistModel', 'Pricelist');
			$view['pricelist'] = $this->Pricelist->getPricelist($view['filter']);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}

		View::showChild($this->viewpath.'/pages/editPricelist', $view);
	}
	
	public function savePricelist()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)) ||
				!is_numeric($this->uri->segment(4)) ||
				!is_numeric($this->uri->segment(5)))
			{
				throw new Exception('������ ��������.');
			}
		
			// ������� �������
			$this->load->model('DeliveryModel', 'Deliveries');
			$delivery = $this->Deliveries->getById($this->uri->segment(5));
			
			if (!$delivery)
			{
				throw new Exception('���������� ��������� ������. ������ �������� �� ������.');
			}
			
			$this->load->model('CountryModel', 'Countries');
			$countryFrom = $this->Countries->getById($this->uri->segment(3));
			$countryTo = $this->Countries->getById($this->uri->segment(4));
			
			if (!$countryFrom || !$countryTo)
			{
				throw new Exception('���������� ��������� ������. ������ �� �������.');
			}

			$this->load->model('PricelistModel', 'Pricelist');

			// ��������� �� ����� � ����������
			$this->db->trans_begin();
			
			foreach($_POST as $key=>$value)
			{
				if (stripos($key, 'pricelist_weight') === 0) 
				{
					$price_id = str_ireplace('pricelist_weight', '', $key);
					$this->updatePricelistItem($price_id);
				}
				else if (stripos($key, 'new_weight') === 0) 
				{
					$price_id = str_ireplace('new_weight', '', $key);
					$this->insertPricelistItem($price_id, $this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5));
				}
			}
			
			$this->db->trans_commit();
			
			// ������� ���������
			$this->result->m = '������ ������� ���������.';
			Stack::push('result', $this->result);
		}
		catch (Exception $e) 
		{
			$this->db->trans_rollback();
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ���������
		Func::redirect(BASEURL.$this->cname.'/editPricelist');
	}
	
	protected function updatePricelistItem($pricelist_id)
	{
		if (!is_numeric($pricelist_id) ||
			!isset($_POST['pricelist_weight'.$pricelist_id]) ||
			!isset($_POST['pricelist_price'.$pricelist_id])) return;

		// ������� ������ � ������
		$pricelist = $this->Pricelist->getById($pricelist_id);

		if (!$pricelist)
		{
			throw new Exception('���������� ��������� �����. ��������� ������ �� �������.');
		}

		// �������� ������ �� ������
		if ($_POST['pricelist_weight'.$pricelist_id] == '')
		{
			$deleted = $this->Pricelist->delete($pricelist_id);
				
			if (!$deleted)
			{
				throw new Exception('���������� ��������� �����. ����������� ��� ���.');
			}
			
			return;
		}
			
		// ��������� ����������������� �����
		Check::reset_empties();
		$pricelist->pricelist_price 		= Check::float('pricelist_price'.$pricelist_id);
		$pricelist->pricelist_weight 		= Check::float('pricelist_weight'.$pricelist_id);
		$empties							= Check::get_empties();
		
		if ($empties)
		{
			throw new Exception('��������� ���� ������ �� ���������. ���������� ��� ���.');
		}
				
		// ���������� ������
		$new_pricelist = $this->Pricelist->savePricelist($pricelist);

		if ($new_pricelist === FALSE)
		{
			throw new Exception('���������� ��������� �����. ����������� ��� ���.');
		}
	}
	
	protected function insertPricelistItem($pricelist_id, $country_from, $country_to, $delivery)
	{
		// ��������� ������ ����������� ������
		if (!is_numeric($pricelist_id) ||
			!isset($_POST['new_weight'.$pricelist_id]) ||
			!isset($_POST['new_price'.$pricelist_id])) return;

		// ��������� ����������������� �����
		$pricelist = new stdClass();
		$pricelist->pricelist_price 		= Check::float('new_price'.$pricelist_id);
		$pricelist->pricelist_weight 		= Check::float('new_weight'.$pricelist_id);
		$pricelist->pricelist_country_from	= $country_from;
		$pricelist->pricelist_country_to	= $country_to;
		$pricelist->pricelist_delivery		= $delivery;
		$empties							= Check::get_empties();

		if ($empties)
		{
			throw new Exception('��������� ���� ������ �� ���������. ���������� ��� ���.');
		}

		// ���������� ������
		$pricelist->pricelist_id = '';
		$new_pricelist = $this->Pricelist->savePricelist($pricelist);
				
		if (!$new_pricelist)
		{
			throw new Exception('���������� ��������� �����. ����������� ��� ���.');
		}
	}
	
	public function payPackageToManager()
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
			
			// ������������: ��������� ������������� �������
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getById($this->uri->segment(3));

			if (!$package ||
				$package->package_status != 'sent' ||
				$package->package_payed_to_manager)
			{
				throw new Exception('������� �� �������. ���������� ��� ���.');
			}			

			// ���������� �������
			$payment_obj = new stdClass();
			$payment_obj->payment_from			= 1;
			$payment_obj->payment_to			= $package->package_manager;
			$payment_obj->payment_amount_from	= $package->package_manager_cost;
			$payment_obj->payment_amount_to		= $package->package_manager_cost;
			$payment_obj->payment_amount_tax	= 0;
			$payment_obj->payment_purpose		= '������� �������� �� �������';
			$payment_obj->payment_comment		= '� '.$package->package_id;
			
			$this->load->model('PaymentModel', 'Payment');
			
			$this->db->trans_begin();

			if (!$this->Payment->makePayment($payment_obj, true)) 
			{
				throw new Exception('������ ������� ��������. ���������� ��� ���.');
			}			
			
			// ���������� �������
			$package->package_payed_to_manager = true;
			$payed_package = $this->Packages->savePackage($package);
			
			if (!$payed_package)
			{
				throw new Exception('������ �� ��������. ���������� ��� ���.');
			}

			if ($this->db->trans_status() !== FALSE)
			{
				$this->db->trans_commit();
			}
			
			$this->result->m = '������ �������� ������� ��������.';
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
		
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
		}
		
		// ��������� ��������
		Stack::push('result', $this->result);
		
		if (isset($package) && isset($package->package_manager))
		{
			Func::redirect(BASEURL.$this->cname.'/editPartner/'.$package->package_manager);
		}
		else
		{
			Func::redirect(BASEURL.$this->cname);
		}
	}
	
	public function payOrderToManager()
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
			
			// ������������: ��������� ������������� ������
			$this->load->model('OrderModel', 'Orders');
			$order = $this->Orders->getById($this->uri->segment(3));

			if (!$order ||
				$order->order_status != 'sended' ||
				$order->order_payed_to_manager)
			{
				throw new Exception('����� �� ������. ���������� ��� ���.');
			}			

			// ���������� �������
			$payment_obj = new stdClass();
			$payment_obj->payment_from			= 1;
			$payment_obj->payment_to			= $order->order_manager;
			$payment_obj->payment_amount_from	= $order->order_manager_cost;
			$payment_obj->payment_amount_to		= $order->order_manager_cost;
			$payment_obj->payment_amount_tax	= 0;
			$payment_obj->payment_purpose		= '������� �������� �� �����';
			$payment_obj->payment_comment		= '� '.$order->order_id;
			
			$this->load->model('PaymentModel', 'Payment');
			
			$this->db->trans_begin();

			if (!$this->Payment->makePayment($payment_obj, true)) 
			{
				throw new Exception('������ ������� ��������. ���������� ��� ���.');
			}			
			
			// ���������� �������
			$order->order_payed_to_manager = true;
			$payed_order = $this->Orders->saveOrder($order);
			
			if (!$payed_order)
			{
				throw new Exception('������ �� ��������. ���������� ��� ���.');
			}

			if ($this->db->trans_status() !== FALSE)
			{
				$this->db->trans_commit();
			}
			
			$this->result->m = '������ �������� ������� ��������.';
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
		
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
		}
		
		// ��������� ��������
		Stack::push('result', $this->result);
		
		if (isset($order) && isset($order->order_manager))
		{
			Func::redirect(BASEURL.$this->cname.'/editPartner/'.$order->order_manager);
		}
		else
		{
			Func::redirect(BASEURL.$this->cname);
		}
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
	
	public function showPackageComments($package_id = null)
	{
		Breadcrumb::setCrumb(array("showPackageComments/$package_id"=> '����������� � ������� �'.$package_id),2, false);
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
	
	public function filterEditPricelist()
	{
		$this->filter('editPricelist', 'editPricelist');
	}

	public function filterClientReport($client_id)
	{
		$this->filter('editClient', 'editClient/'.$client_id);
	}

	public function filterPartnerReport($partner_id)
	{
		$this->filter('editPartner', 'editPartner/'.$partner_id);
	}

	public function updatePackageAddress()
	{
		parent::updatePackageAddress();
	}
	
	public function updateOrderDetails()
	{
		parent::updateOrderDetails();
	}
	
	public function showO2oComments()
	{
		parent::showO2oComments();
	}

	public function addO2oComment()
	{
		parent::addO2oComment();
	}

	
	
	public function addPackageComment($package_id, $comment_id = null)
	{
		try
		{
		
			// ������������: ��������� ������ ��������� � �������
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getById((int) $package_id);

			if (!$package)
			{
				throw new Exception('���������� �������� �����������. ������� �� �������.');
			}

			// ��������� ����������������� �����
			$pcomment					= new stdClass();
			$pcomment->pcomment_comment	= Check::txt('comment', 8096, 1);
			$pcomment->pcomment_package	= $package_id;
			$pcomment->pcomment_user	= $this->user->user_id;
			$empties					= Check::get_empties();
		
			if ($empties) 
			{
				throw new Exception('����� ����������� �����������. ���������� ��� ���.');
			}
			
			// ���������� �����������
			$this->load->model('PCommentModel', 'Comments');
			
			if (is_numeric($comment_id)) $pcomment->pcomment_id = $comment_id;

			if (!$this->Comments->addComment($pcomment))
			{
				throw new Exception('����������� �� ��������. ���������� ��� ���.');
			}			
			
			// ���������� ���� ������ �����������
			$package->comment_for_client	= TRUE;
			$package->comment_for_manager	= TRUE;
			$package = $this->Packages->savePackage($package);

			if (!$package)
			{
				throw new Exception('����������� �� ��������. ���������� ��� ���.');
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����������� � �������
		Func::redirect(BASEURL.$this->cname.'/showPackageComments/'.$package_id);
	}
	
	public function delPackageComment($package_id, $comment_id){
		parent::delPackageComment((int) $package_id, (int) $comment_id);
	}
	
	public function getDeliveries(){
		
		$this->load->model('ManagerDeliveryModel', 'MD');		
		$deliveries['items'] = $this->MD->getDeliveries($_POST['country_id']);
		echo json_encode($deliveries);
		exit;
	}
}

/* End of file admin.php */
/* Location: ./system/application/controllers/admin.php */
