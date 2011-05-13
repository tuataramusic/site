<?php
require_once BASE_CONTROLLERS_PATH.'BaseController'.EXT;

class User extends BaseController {

	function User()
	{
		parent::__construct();	
	}
	
	function index()
	{
		echo "<center><b>User->index</b></center>";
	}
	
	public function login ($l=null, $p=null, $redirect = true){
		
		$login		= $l ? Check::var_str($l,32,1) : Check::str('login',	32,1);
		$password	= $p ? Check::var_str($p,32,1) : Check::str('password',	32,1);
		$password	= md5($password);
		$this->load->model('UserModel', 'User');
		
		/**
		 * можно так:
		 * $this->User->_set('login',$login);
		 * $this->User->_set('password',$password);
		 * $result = $this->select();
		 * 
		 */
		 
		if ($login && $password){
			
			$user = $this->User->getUserForLogin($login,$password);
			if ($user){
				$this->session->set_userdata((array) $user);
				$this->user = Check::user();

				/**
				 * сдесь можно сделать всякую хрень типо перенаправления на личный кабинет и тп
				 */
				// запоминаем в сессию статистику по платежам для админа
				if ($user->user_group == 'admin') {
					$this->load->model('PaymentModel', 'Payment');
					$stat = $this->Payment->getSummaryStat();
					Stack::push('admin_summary_stat', $stat);
				}
				if ($redirect)
					header('Location: '.BASEURL.$user->user_group);
					
				return true;
			}
		}
		
		echo 'Wrong password or login';
		die();		
		
		return false;
	}
	
	public function logout(){
		
		$this->load->model('UserModel', 'User');
		foreach ($this->User->getPropertyList() as $prop){
			$this->session->unset_userdata(array($prop=>''));
		}
		
		header('Location: '.BASEURL);
	}
	
	public function showRegistration()
	{	
		$this->load->model('CountryModel', 'Country');
		
		//$all_countries	= $this->Country->getList();
		// при регистрации выводятся только те страны, в которые указана цена доставки
		$all_countries  = $this->Country->getToCountries();
		Stack::push('all_countries', $all_countries);
		
		View::showChild($this->viewpath.'pages/registration', array('Countries' => $all_countries));
	}
	
	/**
	 * Регистрация пользователя
	 *
	 * @param unknown_type $type
	 */
	public function registration(){
		
		/**
		 * load country list form stack, if it exists;
		 * so, we dont touch models every time
		 */
		$countries = '';
		if (Stack::size('all_countries')>0){
			$countries	= Stack::last('all_countries');
		}else{
			$this->load->model('CountryModel', 'Country');
			$countries	= $this->Country->getList();			
		}		
		
		Check::reset_empties();
		$user					= new stdClass();
		$user->user_login		= Check::latin('login',32,1);
		$user->user_password		= Check::latin('password',32,1);
		$user->repassword		= Check::latin('repassword',32,1);
		$user->user_email		= Check::email(Check::str('email',128,6));
		$user->user_deleted		= 2; // ожидается подтверждение регистрации
		$user->user_group		= 'client';
		
		$c				= new stdClass();
		$c->client_name			= Check::latin('name',128,1);
		$c->client_otc			= Check::latin('otc',128,1);
		$c->client_surname		= Check::latin('surname',128,1);
		$c->client_country		= Check::int('country');
		$c->client_index		= Check::int('index');
		$c->client_town			= Check::latin('town',64,1);
		$c->client_address		= Check::latin('address',512,1);
		$c->client_phone		= Check::int('phone',99999999999999);
		$empties			= Check::get_empties();
		
		/**
		 * код ошибки регистрации
		 * <0	- ошибка регистрации
		 * 0	- регистрация не происходила
		 * >0	- регистрация успешна
		*//*
		$result		= new stdClass();
		$result->e	= 0;
		$result->m	= '';	// сообщение
		$result->d	= '';	// возвращаемые данные
		*/
		
		try{

			if ($empties && in_array('_latin',$empties)){
				throw new Exception('Данные должны быть введены латиницей!', -14);
			}
			
			if ($user->user_password !== $user->repassword){
				throw new Exception('Пароли не совпадают.', -15);
			}			
			
			if ($empties){
				throw new Exception('Одно или несколько полей не заполнено!', -11);
			}
			
			if (!$user->user_email){
				throw new Exception('Не верный E-mail.', -13);
			}	
			
			$this->load->library('alcaptcha');
			if (!$this->alcaptcha->check($this->input->post('captchacode'))) {
				throw new Exception('Проверочный код введен не верно!', -18);
			}
			
			$this->load->model('UserModel', 'User');


			if ($this->User->select(array('user_login'=> $user->user_login, 'user_deleted'=>'0'))){
				throw new Exception('Пользователь с таким ником уже существует!', -17);
			}
			
			
			if ($this->User->select(array('user_email'=> $user->user_email))){
				throw new Exception('Пользователь с такой электронной почтой уже существует!', -16);
			}
			
			
			$this->load->model('ClientModel', 'Client');
			
			$user->user_password = md5($user->user_password);
			
			/**
			 * transactions
			 */
			$this->db->trans_start();
			// something same to lazzy load
			$u = $this->User->addUser($user);
			
			if ($u && $this->Client->addClientData($u->user_id, $c))
			{
				Stack::push('user_confirm', $u);
				Stack::push('repassword', $user->repassword);
				
				$this->db->trans_complete();
				
				$this->result->e	= 1;
				$this->result->m	= 'Вы успешно зарегистрированы. Для подтверждения регистрации пройдите по ссылке, которая выслана на вашу электронную почту';// '.$u->user_email.' http://'.$_SERVER['HTTP_HOST'].'/user/confirmRegistration/'.md5(session_id());
				$headers = 'From: info@countrypost.ru' . "\r\n" .
					'Reply-To: info@countrypost.ru' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				mail($u->user_email, 'Подтверждение регистрации', 'http://'.$_SERVER['HTTP_HOST'].'/user/confirmRegistration/'.md5(session_id()), $headers);
				
				View::showChild($this->viewpath.'pages/confirmation');
				
				#$this->login($user->user_login, $user->repassword);
				#Stack::push('just_registered', 1);
				#Func::redirect(BASEURL.'/client/');
				return true;
			}
			else
			{
				throw new Exception('Регистрация невозможна.',-12);
			}
				
		}
		catch (Exception $e)
		{
			
			$this->result->e	= $e->getCode();			
			$this->result->m	= $e->getMessage();
			
			switch ($this->result->e){
				case -1:	$user->user_login		= '';	break;	
				case -15:	
					$user->user_password			= '';
					$user->repassword				= '';
				break;
				case -2:
				case -13:	$user->user_email		= '';	break;
				case -11:
				case -12:
					 break;
			}
		
			$this->result->d	= $user;
		}
		
		if ($this->db->trans_status())	{
			$this->db->trans_complete();
		}
		
		if ($user->repassword){
			$user->user_password = $user->repassword;
		}
		
		$view = array(
						'client'		=> $c,
						'Countries'		=> $countries,
						'empties'		=> $empties,
		);
		
		if (isset($u) && $u)	$view['user'] = $u;
		
		View::showChild($this->viewpath.'pages/registration', $view);
	}
	
	
	/**
	 * Confirmation of registration
	 *
	 * @param string	$code
	 */
	public function confirmRegistration($code = null){
	
		/**
		 * load country list form stack, if it exists;
		 * so, we dont touch models every time
		 */
		$countries = '';
		if (Stack::size('all_countries')>0){
			$countries	= Stack::last('all_countries');
		}else{
			$this->load->model('CountryModel', 'Country');
			$countries	= $this->Country->getList();			
		}	
		
		try{
			/**
			 * open transaction
			 */
			$this->db->trans_begin();
			
			if ($code != md5(session_id()))
				throw new Exception('Не возможно произвести регистрацию. Не верный код подтверждения или прошло слишком много времени.', -2);

			$this->load->model('UserModel', 'User');
			
			$user	= Stack::last('user_confirm');
			
			if (!$user)
				throw new Exception('Операция невозможна. Необходимо пройти процедуру регистрации!', -3);
				
			$user->user_deleted	= 0;
			$this->User->_load($user);
			
			if (!$this->User->save(true))
				throw new Exception("DB_ERROR: Не возможно изменить запись!", -1);

			// добавляем партнеров этому клиенту
			$this->load->model('ManagerModel', 'Manager');
			$this->load->model('C2mModel', 'C2m');

/*			$managers = $this->Manager->getIncompleteManagers();
		
			// отсутсвующие страны партнеров
			$addon_countries = array();
			foreach ($countries as $country) {
				if (!array_key_exists($country->country_id, $managers)) {
					$addon_countries[] = $country->country_id;
				}
			}
			$addon_managers = array();
			if (count($addon_countries)) 
			{
				$addon_managers = $this->Manager->getCompleteManagers($addon_countries);
			}
			$managers = array_merge($managers, $addon_managers);
				*/

			$managers = $this->Manager->getManagers();

		// добавляем связку и выставляем дату добавления клиента
			if ($managers['all']) 
			{
				foreach ($managers['all'] as $manager) 
				{
					$relation = new stdClass();
					$relation->client_id = $user->user_id;
					$relation->manager_id = $manager->manager_user;
					
					//$managers = $this->C2m->addRelation($relation);
					$this->C2m->addRelation($relation);
					
					$manager->last_client_added = date('Y-m-d H:m:s');
					$manager = $this->Manager->updateManager($manager); 
					
					if (!$manager)
					{
						throw new Exception('Невозможно добавить нового клиента к партнеру. Попробуйте еще раз.');
					}
				}
			}

			// увеличиваем счетчик клиентов у заполненных партнеров
			if (isset($managers['addons']))
			{
				foreach ($managers['addons'] as $manager) 
				{
					$manager->manager_max_clients += 1;
					$manager = $this->Manager->updateManager($manager);
					
					if (!$manager)
					{
						throw new Exception('Невозможно обновить данные по клиентам у партнера. Попробуйте еще раз.');
					}
				}
			}

			$this->db->trans_complete();
			
			$this->login($user->user_login, Stack::last('repassword'));
			Stack::clear('repassword');
			Stack::clear('user_confirm');
			Stack::push('just_registered', 1);
			#Func::redirect(BASEURL.'/client/');
			return true;			
			
			// никогда так не делайте, это удобно но не правильно!!!
			#throw new Exception('Вы успешно зарегистрированы', 2);
				
		}catch (Exception $e){
			
			$this->db->trans_rollback();
			
			$this->result->e	= $e->getCode();
			$this->result->m	= $e->getMessage();
		}

		View::showChild($this->viewpath.'pages/confirmation');
	}
	
	
	public function showCaptchaImage(){
        $this->load->library('alcaptcha');
		echo $this->alcaptcha->image();
	}
	
	
	public function showPasswordRecovery()
	{
		View::showChild($this->viewpath.'pages/recovery');
	}	
	
	public function passwordRecovery(){
		$email		= Check::email(Check::str('email', 128,4));
		
		$result		= new stdClass();
		$result->e	= 0;
		$result->m	= '';	// сообщение
		$result->d	= '';	// возвращаемые данные		
		
		if ($email){
			$this->load->model('UserModel', 'User');
			$user = $this->User->getUserByEmail($email);
			
			if ($user){
				
				$new_passwd = Func::randStr(6,8);
				$this->User->_load($user);
				$this->User->_set('user_password', md5($new_passwd));
				
				$headers = 'From: info@countrypost.ru' . "\r\n" .
					'Reply-To: info@countrypost.ru' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				
				if (mail($user->user_email,"Восстановление пароля", "Ваш новый пароль: $new_passwd", $headers) && $this->User->save()){
					$result->m	=	'Новый пароль установлен и выслан на указанный вами адрес электронной почты.';
					$result->e	=	1;
				}else {
					$result->m	=	'Не возможно восстановить пароль. Вероятно указанный вами почтовый ящик не существует или не работает';
					$result->e	=	-1;
				}
			}else{
				$result->e	= -2;
				$result->m	= 'Такой e-mail в системе не зарегистрирован';				
			}
		}else{
			$result->e	= -3;
			$result->m	= 'Вы ввели не правильны адрес электронной почты';
		}
		
		View::showChild($this->viewpath.'pages/recovery', array('result'=>$result));
	}

	
	
	public function showProfile(){
		/**
		 * load country list form stack, if it exists;
		 * so, we dont touch models every time
		 */
		$countries = '';
		if (Stack::size('all_countries')>0){
			$countries	= Stack::last('all_countries');
		}else{
			$this->load->model('CountryModel', 'Country');
			//$countries	= $this->Country->getList();
			$countries	= $this->Country->getToCountries();
		}
		
		if (!$this->user){
			$this->showRegistration();
			return false;
		}
		
		if (!Check::str('action',6,1)){
			$this->load->model('ClientModel', 'Client');
		
			View::showChild($this->viewpath. 'pages/profile' , array(
					'client'			=> $this->Client->getById($this->user->user_id),
					'Countries'		=> $countries,
			));
			return;
		}
		
		Check::reset_empties();
		$this->user->user_login			= Check::latin('login',32,1);
		$this->user->user_email			= Check::email(Check::str('email',128,6));
		
		$c											= new stdClass();
		$c->client_user						= $this->user->user_id;
		$c->client_name						= Check::latin('name',128,1);
		$c->client_otc						= Check::latin('otc',128,1);
		$c->client_surname				= Check::latin('surname',128,1);
		$c->client_country					= Check::int('country');
		$c->client_index						= Check::int('index');
		$c->client_town						= Check::latin('town',64,1);
		$c->client_address					= Check::latin('address',512,1);
		$c->client_phone					= Check::int('phone',99999999999999);
		$empties								= Check::get_empties();
		
		$this->user->user_password	= Check::latin('password',32,1);
		$this->user->repassword		= Check::latin('repassword',32,1);
		
		try{

			if ($empties && in_array('_latin',$empties)){
				throw new Exception('Данные должны быть введены латиницей!', -14);
			}
			
			if ($this->user->user_password !== $this->user->repassword){
				throw new Exception('Пароли не совпадают.', -15);
			}			
			
			if ($empties){
				throw new Exception('Одно или несколько полей не заполнено!', -11);
			}

			if (!$this->user->user_email){
				throw new Exception('Не верный E-mail.', -13);
			}	

			$this->load->model('UserModel', 'User');
			
			$nu	= $this->User->getUserByLogin( $this->user->user_login);
			if ($nu && $nu->user_id != $this->user->user_id){
				throw new Exception('Пользователь с таким ником уже существует!', -17);
			}
			
			$ne	= $this->User->getUserByEmail( $this->user->user_email);
			if ($ne && $ne->user_id != $this->user->user_id){
				throw new Exception('Пользователь с такой электронной почтой уже существует!', -16);
			}
			
			if ($this->user->user_password)
				$this->user->user_password = md5($this->user->user_password);
			
			/**
			 * transactions
			 */
			$this->db->trans_start();
			// something same to lazzy load
			$this->load->model('ClientModel', 'Client');
			
			$this->User->_load($this->user);
			$this->Client->_load($c);
			
			if ($this->User->save() && $this->Client->save())
			{
				// меняем данные о пользователе в сесии
				$this->session->set_userdata((array) $this->user);
				
				$this->db->trans_complete();
				
				$this->result->e	= 1;
				$this->result->m	= 'Данные сохранены.';
				
			}
			else
			{
				throw new Exception('Регистрация невозможна.',-12);
			}
				
		}
		catch (Exception $e)
		{
			
			$this->result->e		= $e->getCode();			
			$this->result->m	= $e->getMessage();
			
			switch ($this->result->e){
				case -1:	$this->user->user_login		= '';	break;	
				case -15:	
					$this->user->user_password			= '';
					$this->user->repassword				= '';
				break;
				case -2:
				case -13:	$this->user->user_email		= '';	break;
				case -11:
				case -12:
					 break;
			}
		}
		
		View::showChild($this->viewpath.'pages/profile', array(
																'client'		=> $c,
																'Countries'		=> $countries,
																'empties'		=> $empties,
		));
	}
	
}

/* End of file user.php */
/* Location: ./system/application/controllers/user.php */
