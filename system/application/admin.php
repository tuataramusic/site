<?php
require_once BASE_CONTROLLERS_PATH.'AdminBaseController'.EXT;

class Admin extends AdminBaseController {

	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		echo "<center><b>����� ��� � ����� �����������...</b></center>";
		
		View::showChild($this->viewpath.'/pages/main');
	}
	
	public function showPaymentHistory()
	{
		$this->load->model('PaymentModel', 'Payment');
		$view = array(
			'Payments'	=> $this->Payment->getRefillPayments()
		);
		View::showChild($this->viewpath.'/pages/payment_history', $view);
	}
	
	public function searchPayments() {
		 
		$from = $to = null;
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
		
		$this->load->model('PaymentModel', 'Payment');
		$view = array(
			'Payments'		=> $this->Payment->getFilteredPayments(array('payment_to' => 0, $_POST['sfield'] => $_POST['svalue']), $from, $to),
			'from_search'	=> true
		);
		View::showChild($this->viewpath.'/pages/payment_history', $view);
	}

	public function showClients()
	{
		View::showChild($this->viewpath.'/pages/clients');
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
	
	public function searchOrders2out() {
		$this->load->model('Order2outModel', 'Order2out');
		$Orders = $this->Order2out->getFilteredOrders(array(@$_POST['sfield'] => @$_POST['svalue']));
		
		$view = array(
			'Orders'	=> $Orders,
			'statuses'	=> $this->Order2out->getStatuses(),
			'status'	=> 'none'
		);
		
		View::showChild($this->viewpath.'/pages/order_to_out', $view);
	}
	
	public function saveOrders2out() {
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
						'price_for_marge'		=> Check::str('merge',			8096,1)
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
	
	
	// ��-������ ����� ��������� ����� ��������� �� �� ��������
	public function showPartners($operation=null, $uid=null) {
		
		//die('see code');
		
		/**
		 * ��-������ ��� �������� �������� �� ����������� ������� ������ �� ���� ������, ����� ������� ������
		 * ������ �� ������� ����, ��� ������� ����� ������� ���� ������
		 * 
		 * �-������� ����������� ������
		 * ��� �������� ������������ ����� ���������� ��������� ������:
		 * 1. ������ ������������ � ���� ���
		 * 2. ��������� ������������ �� ����������� ������ ������
		 * 3. �������� ����������� ������
		 * 
		 * ��� �� ������ �������� ��� ��� ���������� ������ �������� ������������ ��������
		 * ��� ��������� �������� ��������� ��������� �������� ������
		 * 
		 * � ����� ������� - ������������ ��������� �� ���������! � ���� ���� ���� user_deleted,
		 * ���� ������������ ������ �� ����� ��� � 1
		 */
		
		$this->load->model('ManagerModel', 'Manager');
		
		/*if ($operation && $operation == 'del') {
			
			//��������� ������������� ��������
			if ($this->Manager->select(array('manager_user' => intval($uid)))) {
				$this->db->trans_start();
				$this->Manager->delete($uid);
				$this->load->model('UserModel', 'User');
				$this->User->delete($uid);
				$this->db->trans_complete();	
			}
		}*/
		#######
		/*$this->load->model('UserModel', 'User');
		
		$_u = $this->User->getById((int) $uid);
		
		if ($_u && $_u->_get('user_group') == 'manager'){
			$this->User->_set('user_id', $uid);
			$this->User->_set('user_deleted', 1);
			// �� ������ ����� ������ �������������� ����� � ������ ����, ���� deleteUser($uid), ������� ��������� ������� ���
			if ($this->save()){
				$this->result->r = 1;
				$this->result->m = '������������ ������� �������';
			}else{
				$this->result->r = -1;
				$this->result->m = '��������� �����-�� �����, �� ���� ����� ������';
			}
		}else{
			$this->result->r = -2;
			$this->result->m = '������ ����� � ���� �� �����������...';
		}
		
		Stack::push('result',$this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showPartners');*/
		// kind of
		#######
		
		$Managers = $this->Manager->getManagersData();
		
		$this->load->model('CountryModel', 'Country');
		$Countries	= $this->Country->getList();
		$countries = array();
		foreach ($Countries as $Country)
			$countries[$Country->country_id] = $Country->country_name;	
		
		$view = array(
			'managers' 	=> $Managers,
			'countries'	=> $countries,
			'statuses'	=> $this->Manager->getStatuses()
		);
		
		View::showChild($this->viewpath.'/pages/show_partners', $view);
	}
	
	public function deletePartner($uid=null) {
		
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
	
	public function showUpdatePartner($uid) {
		$this->load->model('UserModel', 'User');
		$_u = $this->User->getById((int) $uid);
		if ($_u && $_u->user_group == 'manager'){
			try {
				$_u->user_password = '';
				$this->result->d = $_u;
				
				$view = array('update' => 1);
				
				$this->load->model('ManagerModel', 'Manager');
				$_m = $this->Manager->getById((int) $uid);
				
				$view['manager'] = $_m;
				
				$this->load->model('CountryModel', 'Country');		
				$view['countries'] = $this->Country->getList();
				Stack::push('countries', $view['countries']);
				
				$this->load->model('ManagerModel', 'Manager');
				$view['statuses'] = $this->Manager->getStatuses();
				
				View::showChild($this->viewpath.'/pages/show_add_partner', $view);
				return true;				
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
	
	public function updatePartner($uid) {
		
		$this->load->model('UserModel', 'User');
		$_u = $this->User->getById((int) $uid);
		
		if ($_u && $_u->user_group == 'manager'){
			
			$countries = '';
			if (Stack::size('all_countries')>0){
				$countries	= Stack::last('all_countries');
			}else{
				$this->load->model('CountryModel', 'Country');
				$countries	= $this->Country->getList();			
			}
			$countries_ids = array();
			if ($countries) {
				foreach ($countries as $country)
					$countries_ids[] = $country->country_id;
			}
			
			$this->load->model('ManagerModel', 'Manager');
			$statuses = $this->Manager->getStatuses();
			
			$_m = $this->Manager->getById((int) $uid);
			
			Check::reset_empties();
			$user = clone $_u;
			$user->user_password = '';
			$user->user_email = Check::email(Check::str('user_email',128,4));
			if ($_POST['user_pass'])
				$user->user_password	= Check::str('user_pass',32,2);
			$user->user_group		= 'manager';
			
			$manager = clone $_m;
			$manager->manager_name			= Check::latin('manager_name',128,2);
			$manager->manager_surname		= Check::latin('manager_surname',128,2);
			$manager->manager_otc			= Check::latin('manager_otc',128,2);
			$manager->manager_country		= Check::int('manager_country');
			$manager->manager_addres		= Check::latin('manager_addres',512,2);
			$manager->manager_phone			= Check::int('manager_phone',999999999999,11111111111);
			$manager->manager_max_clients	= Check::int('manager_max_clients');
			$manager->manager_delivery		= Check::latin('manager_delivery',512,2);
			$manager->manager_status		= Check::int('manager_status');
			
			$empties = Check::get_empties();
			
			try{
				if (!$user->user_email)
					throw new Exception('�� ������ e-mail.', -13);
				if ($empties)
					throw new Exception('���� ��� ��������� ����� �� ���������.', -11);
				if ($empties && in_array('_latin',$empties))
					throw new Exception('������ ���������� ������� ���������.', -14);
				if (!in_array($manager->manager_country, $countries_ids))
					throw new Exception('�������� ������.', -19);
				if (!key_exists($manager->manager_status, $statuses))
					throw new Exception('�������� ������.', -20);
					
				if (isset($user->user_password))
					$user->user_password = md5($user->user_password);
					
				$this->db->trans_begin();
					
				$this->User->updateUser($user);
				$this->Manager->updateManager($manager);
				
				//��������� ������ ������-�������� ����� ������ �������			
				if ($_m->manager_status == 1 && $manager->manager_status == 2) {					
					$neighbor_managers = $this->Manager->select(array('manager_country' => $_m->manager_country, 'manager_status' => 1));
					$this->load->model('C2mModel', 'C2m');
					if (!$neighbor_managers) { // ������� ������������ � ������, ������� ������
						$this->C2m->deletePartnerRelations($_m->manager_user);
					}
					else {
						$all_count = $this->C2m->getPartnerClientsCount($uid);
						if ($all_count) {
							$updated_managers_count = count($neighbor_managers);
							$updated_managers = array(); //������ ��� ���� - id ��������, � �������� - ���-�� ����������� ��� ���������� ������
							$base = floor($all_count / $updated_managers_count);
							foreach ($neighbor_managers as $neighbor) {
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
							}
						}
					}
				}
				
				if ($this->db->trans_status() === FALSE) {				
					throw new Exception('���������� ����������.',-3);
				}
					
				$this->db->trans_commit();
				$this->result->m	= '������� ��������';
			}catch (Exception $e){
				$this->db->trans_rollback();
				
				$this->result->e	= $e->getCode();			
				$this->result->m	= $e->getMessage();				
		
				switch ($this->result->e){
					case -13:
					case -2:
						$user->user_email		= $_u->user_email;
						break;				
				}
				
				$user->user_password = '';
				$this->result->d	= $user;
				
				$view = array(
					'countries'		=> $countries,
					'manager'		=> $manager,
					'statuses'		=> $statuses,
					'update'		=> 1
				);
				
				View::showChild($this->viewpath.'pages/show_add_partner', $view);
				return true;
			}
			
		}else{
			$this->result->r = -2;
			$this->result->m = '������� �� ������';						
		}		
		
		Stack::push('result', $this->result);		
		Func::redirect(BASEURL.$this->cname.'/showPartners');
	}
	
	public function showAddPartner() {
		
		$view = array();
		
		$this->load->model('CountryModel', 'Country');		
		$view['countries'] = $this->Country->getList();
		Stack::push('countries', $view['countries']);
		
		$this->load->model('ManagerModel', 'Manager');
		$view['statuses'] = $this->Manager->getStatuses();
		
		View::showChild($this->viewpath.'/pages/show_add_partner', $view);
	}
	
	public function addPartner() {
		
		$countries = '';
		if (Stack::size('all_countries')>0){
			$countries	= Stack::last('all_countries');
		}else{
			$this->load->model('CountryModel', 'Country');
			$countries	= $this->Country->getList();			
		}
		$countries_ids = array();
		if ($countries) {
			foreach ($countries as $country)
				$countries_ids[] = $country->country_id;
		}
		
		$this->load->model('ManagerModel', 'Manager');
		$statuses = $this->Manager->getStatuses();
		
		Check::reset_empties();
		$user					= new stdClass();
		$user->user_login		= Check::str('user_login',32,2);
		$user->user_password	= Check::str('user_pass',32,2);
		$user->user_email		= Check::email(Check::str('user_email',128,4));
		$user->user_group		= 'manager';
		
		$manager						= new stdClass();
		$manager->manager_name			= Check::latin('manager_name',128,2);
		$manager->manager_surname		= Check::latin('manager_surname',128,2);
		$manager->manager_otc			= Check::latin('manager_otc',128,2);
		$manager->manager_country		= Check::int('manager_country');
		$manager->manager_addres		= Check::latin('manager_addres',512,2);
		$manager->manager_phone			= Check::int('manager_phone',999999999999,11111111111);
		$manager->manager_max_clients	= Check::int('manager_max_clients');
		$manager->manager_delivery		= Check::latin('manager_delivery',512,2);
		$manager->manager_status		= Check::int('manager_status');
		$empties						= Check::get_empties();
	
		try{
			if (!$user->user_email)
				throw new Exception('�� ������ e-mail.', -13);			
			if ($empties && in_array('_latin',$empties))
				throw new Exception('������ ���������� ������� ���������.', -14);			
			if ($empties)
				throw new Exception('���� ��� ��������� ����� �� ���������.', -11);
			if (!in_array($manager->manager_country, $countries_ids))
				throw new Exception('�������� ������.', -19);
			if (!key_exists($manager->manager_status, $statuses))
				throw new Exception('�������� ������.', -20);
			
			$this->load->model('UserModel', 'User');
			
			$user->user_password = md5($user->user_password);			

			$this->db->trans_begin();			

			$u = $this->User->addUser($user);
			
			if ($u)
				$this->Manager->addManagerData($u->user_id, $manager);
			
			if ($this->db->trans_status() === FALSE) {				
				throw new Exception('����������� ����������.',-3);
			}
			else {
				
				// ��������� �������� ��������
				if ($manager->manager_status == 1) {
					$neighbour_managers = $this->Manager->select(array('manager_country' => $manager->manager_country, 'manager_status' => 1));
					if (count($neighbour_managers) == 1) { // ������� ������������ � ������, ������ ��� ���� ��������
						$this->load->model('ClientModel', 'Client');
						$Clients = $this->Client->getList();
						if ($Clients) {
							$this->load->model('C2mModel', 'C2m');
							foreach ($Clients as $Client) {
								$relation = new stdClass();
								$relation->client_id = $Client->client_user;
								$relation->manager_id = $u->user_id;
								
								$this->C2m->addRelation($relation);
							}
						}
					}
				}
				
				$this->db->trans_commit();
				Func::redirect(BASEURL.$this->cname.'/showPartners');			
				return true;
			}
				
		}catch (Exception $e){
			
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
			'statuses'		=> $statuses
		);
		
		View::showChild($this->viewpath.'pages/show_add_partner', $view);
	}
	
	
	public function testPays(){
		
		// ���������� ��������� �������� ������
		// ������� � �������� �� �������
		$payment_obj = new stdClass();
		$payment_obj->payment_from			= 2;		// ������� � �������� ����������� ������
		$payment_obj->payment_to			= 3;		// ������� �� ������� ����������� ������
		$payment_obj->payment_tax			= '10%';	// ����� ��������� �������� ��� ������� ��������, �������: 10% �� ������� ������ �������
		$payment_obj->payment_amount_from	= 4950;		// ����� ������� ����� ����� � �������� ������� �����
		$payment_obj->payment_amount_to		= 4500;		// ����� ������� ����� ����������
		$payment_obj->payment_amount_tax	= 450;		// ����� ������� ����� ���������� ������� (�����)
		$payment_obj->payment_purpose		= '������� � ������ ����� �� ������'; // ���������� �������
		$payment_obj->payment_comment		= 'something comments'; // ���������� (����� �������������)
		
		
//		$payment_obj = new stdClass();
//		$payment_obj->payment_from			= 0;// ���������� �� ���� ������������
//		$payment_obj->payment_to			= 2;
//		$payment_obj->payment_tax			= 0;
//		$payment_obj->payment_amount_from	= 5500;
//		$payment_obj->payment_amount_to		= 5000;
//		$payment_obj->payment_amount_tax	= 0;
//		$payment_obj->payment_purpose		= '���������� �� ���� ������������';
//		$payment_obj->payment_comment		= 'something comments';
		
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
	
	public function showNewPackages()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('������ ��������.');
			}
			
			// ��������� �������
			$view['filter'] = $this->initPackageFilter('not_payed');
			
			$this->load->model('ManagerModel', 'Managers');
		    $view['managers'] = $this->Managers->getList();
						
			// ���������� ����� �������
			$this->load->model('PackageModel', 'Packages');
		    $view['packages'] = $this->Packages->getPackages($view['filter'], 'not_payed');
			
			if (!$view['packages'])
			{
				$this->result->m = '����� ������� �� �������.';
				Stack::push('result', $this->result);
			}

			View::showChild($this->viewpath.'/pages/showNewPackages', $view);
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
			View::showChild($this->viewpath.'/pages/showNewPackages', $view);
		}
	}
	
	public function showPayedPackages()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('������ ��������.');
			}
			
			// ��������� �������
			$view['filter'] = $this->initPackageFilter('payed');
			
			$this->load->model('ManagerModel', 'Managers');
		    $view['managers'] = $this->Managers->getList();
						
			// ���������� ���������� �������
			$this->load->model('PackageModel', 'Packages');
		    $view['packages'] = $this->Packages->getPackages($view['filter'], 'payed');
			
			if (!$view['packages'])
			{
				$this->result->m = '���������� ������� �� �������.';
				Stack::push('result', $this->result);
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}

		View::showChild($this->viewpath.'/pages/showPayedPackages', $view);
	}
	
	public function showSentPackages()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('������ ��������.');
			}
			
			// ��������� �������
			$view['filter'] = $this->initPackageFilter('sent');
			
			$this->load->model('ManagerModel', 'Managers');
		    $view['managers'] = $this->Managers->getList();
						
			// ���������� ���������� �������
			$this->load->model('PackageModel', 'Packages');
		    $view['packages'] = $this->Packages->getPackages($view['filter'], 'sent');
			
			if (!$view['packages'])
			{
				$this->result->m = '���������� ������� �� �������.';
				Stack::push('result', $this->result);
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}

		View::showChild($this->viewpath.'/pages/showSentPackages', $view);
	}
	
	public function showPackageComments()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			// ������� �������
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getById($this->uri->segment(3));
		
			if (!$package)
			{
				throw new Exception('���������� ���������� �����������. ������� �� �������.');
			}

			// ���������� ����������� � �������
			$view['package'] = $package;
			$this->load->model('PCommentModel', 'Comments');
			$view['comments'] = $this->Comments->getCommenstByPackageId($this->uri->segment(3));
			
			if (!$view['comments'])
			{
				throw new Exception('���������� ���������� �����������. ��������������� ������� �� �������.');
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
	
	public function showDeclaration()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			// ������� �������
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getById($this->uri->segment(3));
		
			if (!$package)
			{
				throw new Exception('���������� ���������� ����������. ��������������� ������� �� �������.');
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
			Func::redirect(BASEURL.$this->cname.'/showNewPackages');
			return;
		}

		View::showChild($this->viewpath.'/pages/showPackageDeclaration', $view);
	}
	
	public function saveDeclaration()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('������ ��������.');
			}
		
			// ������� �������
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getById($this->uri->segment(3));
			
			if (!$package)
			{
				throw new Exception('���������� ��������� ����������. ��������������� ������� �� �������.');
			}

			$this->load->model('DeclarationModel', 'Declarations');

			// ��������� �� ������� � ����������
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
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����������
		Func::redirect(BASEURL.$this->cname.'/showDeclaration/'.$this->uri->segment(3));
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

	public function updatePackagesStatus()
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
			
			$this->load->model('PackageModel', 'Packages');
			
			// ��������� �� ��������
			foreach($_POST as $key=>$value)
			{
				// ��������� ����������������� �����
				if (stripos($key, 'package_status') === FALSE) continue;
				
				$package_id = str_ireplace('package_status', '', $key);
				if (!is_numeric($package_id)) continue;
					
				$package_status = $_POST['package_status'.$package_id];
				
				if ($package_status != 'not_payed' && 
					$package_status != 'payed' && 
					$package_status != 'sent')
				{
					throw new Exception('������ ����� ��� ���������� ������� �� ���������. ����������� ��� ���.');
				}
				
				// ������� �������
				$this->load->model('PackageModel', 'Packages');
				$package = $this->Packages->getById($package_id);

				if (!$package)
				{
					throw new Exception('���� ��� ��������� ������� �� �������. ����������� ��� ���.');
				}
					
				// ����� ���������� ������� ����������
				if ($package->declaration_status == 'help' &&
					isset($declaration_status) &&
					isset($_POST['help'.$package_id]))
				{
					$package->declaration_status = $declaration_status;
				}
				
				// ���������� �����������
				$package->package_status = $package_status;
				
				$new_package = $this->Packages->savePackage($package);
				
				if (!$new_package)
				{
					throw new Exception('������� ��������� ������� �� ��������. ����������� ��� ���.');
				}
			}
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
	
	public function deletePackage()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('������ ��������.');
			}
			
			// ��������� ����������������� �����
			$package					= new stdClass();
			$package->package_status	= 'deleted';
			$package->package_id		= $this->uri->segment(3);
			$package->package_manager	= $this->user->user_id;
			
			if (!$package->package_id)
			{
				throw new Exception('�� ������ � �������. ���������� ��� ���.');
			}
			
			// ���������� �����������
			$this->load->model('PackageModel', 'Packages');
			$deleted_package = $this->Packages->savePackage($package);
			
			if (!$deleted_package)
			{
				throw new Exception('������� �� �������. ���������� ��� ���.');
			}			
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
	
	public function filterNewPackages()
	{
		$this->filterPackages('not_payed');
	}
	
	public function filterPayedPackages()
	{
		$this->filterPackages('payed');
	}
	
	public function filterSentPackages()
	{
		$this->filterPackages('sent');
	}
	
	private function filterPackages($filterType)
	{
		try
		{
			// ��������� ����������������� �����
			$filter					= new stdClass();
			$filter->manager_user	= Check::int('manager_user');
			$filter->period			= Check::txt('period', 5, 3);
			$filter->search_id		= Check::int('search_id');
			$filter->id_type		= Check::txt('id_type', 7, 6);
			
			$_SESSION[$filterType.'PackageFilter'] = $filter;
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// ��������� ����� �������
		$pageName = '';
		
		if ($filterType == 'not_payed') $pageName = 'showNewPackages';
		else if ($filterType == 'payed') $pageName = 'showPayedPackages';
		else if ($filterType == 'sent') $pageName = 'showSentPackages';
		
		Func::redirect(BASEURL.$this->cname.'/'.$pageName);
	}
	
	private function initPackageFilter($filterType)
	{
		if (!isset($_SESSION[$filterType.'PackageFilter']))
		{
			$filter					= new stdClass();
			$filter->manager_user	= '';
			$filter->period			= '';
			$filter->search_id		= '';
			$filter->id_type		= '';
			
			$_SESSION[$filterType.'PackageFilter'] = $filter;
		}	

		return $_SESSION[$filterType.'PackageFilter'];
	}
	
	public function updatePackagesTrackingNo()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id)
			{
				throw new Exception('������ ��������.');
			}
			
			$this->load->model('PackageModel', 'Packages');
			
			foreach($_POST as $key=>$value)
			{
				// ����� ���������� � �������
				if (stripos($key, 'package') === FALSE) continue;
			
				$package_id = str_ireplace('package', '', $key);
				if (!is_numeric($package_id)) continue;

				// ������� �������
				$this->load->model('PackageModel', 'Packages');
				$package = $this->Packages->getById($package_id, $this->user->user_id);

				if (!$package)
				{
					throw new Exception('���� ��� ��������� ������� �� �������. ����������� ��� ���.');
				}
					
				// ��������� ����������������� �����
				Check::reset_empties();
				$package->package_status		= 'sent';
				$package->package_trackingno 	= Check::txt('package_trackingno'.$package_id, 255, 1);
				$empties						= Check::get_empties();
		
				if ($empties) 
				{
					throw new Exception('��������� Tracking � �����������. ���������� ��� ���.');
				}
				
				// ���������� �����������
				$new_package = $this->Packages->savePackage($package);
				
				if (!$new_package)
				{
					throw new Exception('��������� ������� �� ����������. ����������� ��� ���.');
				}
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}

		// ��������� ���������� �������
		Func::redirect(BASEURL.$this->cname.'/showPayedPackages');
	}
	

}

/* End of file admin.php */
/* Location: ./system/application/controllers/admin.php */