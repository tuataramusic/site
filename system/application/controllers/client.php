<?php

/**
 * @todo сделать что бы скрины брались с локальной машины, а не заново грузились с сайта
 * @todo функции showHelpForbBuy и showOpenOrders разные, но в чем именно не понятно, надо бы укточнить...
 * 
 */


require_once BASE_CONTROLLERS_PATH.'ClientBaseController'.EXT;

class Client extends ClientBaseController {

	function Client()
	{
		parent::__construct();	
	}
	
	function index()
	{
		
		$this->load->model('NewsModel', 'News');
		$this->load->model('PackageModel', 'Packages');
		
		$news	= $this->News->getInfo(null,null,array(
				'news_addtime'	=> 'desc'
			),3);
		$openp = $this->Packages->getPackages(null, 'open', $this->user->user_id, null);
		$sentp = $this->Packages->getPackages(null, 'sent', $this->user->user_id, null);
		View::showChild($this->viewpath.'/pages/main', array(
			'news'				=> $news,
			'just_registered'	=> Stack::shift('just_registered', true),
			'package_open'		=> ($openp?count($openp):0),
			'package_sent'		=> ($sentp?count($sentp):0),
		));
	}
	
	public function showWaitForSend()
	{		
		$this->showOpenPackages();
	}	
	
	public function showSended()
	{
		$this->showSentPackages();
	}


	public function showShop() 
	{	
		foreach ($_POST as $key => $val){
			$$key = $val;
		}
		
		$error		= new stdClass();
		$error->m	= '';
		$shop = array();
		
		try{								
			if (!$sname || !$surl) 
				throw new Exception(iconv('UTF-8', 'Windows-1251', 'Пожалуйста, введите название магазина!'));
				
			$shop['name'] = $sname;
				
			if (!Check::url($surl))
				throw new Exception(iconv('UTF-8', 'Windows-1251', 'Пожалуйста, введите адрес магазина!'));
			
			$shop['url'] = substr($surl, 7);
			
		}catch (Exception $e){	
			$error->m	= $e->getMessage();				
		}
		
		$view = array(
			'error'		=> $error,
			'shop'		=> $shop			
		);
		
		$this->load->model('OdetailModel', 'OdetailModel');
		$Odetails = $this->OdetailModel->getFilteredDetails(array('odetail_client' => $this->user->user_id, 'odetail_order' => 0));
		
		if (count($Odetails)) {
			$view['country'] = false;
		}
		else {
			$view['country'] = true;
			$this->load->model('CountryModel', 'CountryModel');
			$view['countries'] = $this->CountryModel->getClientAvailableCountries($this->user->user_id);
		}		
		
		View::showChild($this->viewpath.'/pages/show_shop', $view);
	}
	
	public function setStatusUndelivered()
	{
		print Check::int('odetail_id');
		$this->load->model('OdetailModel', 'OdetailModel');
		$this->db->trans_begin();	
		$this->OdetailModel->setStatus(Check::int('odetail_id'),'not_delivered');
		$this->db->trans_commit();	
		return 'ok' ;
	}
	public function addProductManual() {
		
		Check::reset_empties();
		$detail									= new stdClass();
		$detail->odetail_link					= Check::str('olink', 500, 10);
		$detail->odetail_shop_name				= Check::str('shop', 255, 1);
		$detail->odetail_product_name			= Check::str('oname', 255, 1);
		$detail->odetail_product_amount			= Check::int('oamount');
		$detail->odetail_product_color			= Check::str('ocolor', 32, 1);
		$detail->odetail_product_size			= Check::str('osize', 32, 1);
		$detail->odetail_client					= $this->user->user_id;
		$detail->odetail_order					= Check::int('order_id');
		$detail->odetail_manager				= 0;
		$country_manager						= Check::str('ocountry', 255, 1);
		$empties								= Check::get_empties();		
		try {
			
			if (!$detail->odetail_link)
				throw new Exception('Не верная ссылка на товар!');				
			
			if ($empties)
				throw new Exception('Ошибка переданных данных! Возможно одно или несколько полей не заполненно!');
				
			$this->load->model('OdetailModel', 'OdetailModel');
			$Odetails = $this->OdetailModel->getFilteredDetails(array('odetail_client' => $this->user->user_id, 'odetail_order' => 0));
				
			if (count($Odetails)) {
				$detail->odetail_manager = $Odetails[0]->odetail_manager;
			}
			else {
				$this->load->model('CountryModel', 'CountryModel');
				$Countries = $this->CountryModel->getClientAvailableCountries($this->user->user_id);

				foreach ($Countries as $Country) {
					if ($Country->country_id == $country_manager) {
						$detail->odetail_manager = $Country->manager_user;
					}
				}

				if (!$detail->odetail_manager)
					throw new Exception('Ошибка переданных данных');
			}
			
			$this->db->trans_begin();	
 
			$detail = $this->OdetailModel->addOdetail($detail);
			
			$old = umask(0);
			// загрузка файла
			//$config['upload_path'] = BASEPATH.'../upload/orders/'.$this->user->user_id.'/';
			if (!is_dir($_SERVER['DOCUMENT_ROOT']."/upload/orders/{$this->user->user_id}")){
				mkdir($_SERVER['DOCUMENT_ROOT']."/upload/orders/{$this->user->user_id}",0777);
			}

			$config['upload_path']			= $_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$this->user->user_id;
			$config['allowed_types']		= 'gif|jpg|png';
			$config['max_size']				= '4096';
			$config['encrypt_name'] 		= TRUE;
			//$config['max_width'] 			= '2048';
			//$config['max_height'] 		= '2048';
			$max_width						= 1024;
			$max_height						= 768;
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload()) {
				throw new Exception(strip_tags(trim($this->upload->display_errors())));
			}
			
			$uploadedImg = $this->upload->data();
			if (!rename($uploadedImg['full_path'],$_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$this->user->user_id.'/'.$detail->odetail_id.'.jpg')){
				throw new Exception("Bad file name!");
			}
			
			$uploadedImg	= $_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$this->user->user_id.'/'.$detail->odetail_id.'.jpg';
			$imageInfo		= getimagesize($uploadedImg);
			if ($imageInfo[0]>$max_width || $imageInfo[1]>$max_height){
				
				$config['image_library']	= 'gd2';
				$config['source_image']		= $uploadedImg;
				$config['maintain_ratio']	= TRUE;
				$config['width']			= $max_width;
				$config['height']			= $max_height;
				
				$this->load->library('image_lib', $config); // загружаем библиотеку
				
				$this->image_lib->resize(); // и вызываем функцию
			}
			
			$this->db->trans_commit();

			if ($detail->odetail_order > 0 )
			{
				Func::redirect(BASEURL.$this->cname.'/showOpenOrders');
			}
			else
			{
				Func::redirect(BASEURL.$this->cname.'/showBasket');
			}
			return;
			
		}catch (Exception $e){
			$this->db->trans_rollback();
			$this->result->m = $e->getMessage();		
			Stack::push('result', $this->result);
			if ($detail->odetail_order > 0 )
			{
				Func::redirect(BASEURL.$this->cname.'/showOpenOrders');
			}
			else
			{
				Func::redirect(BASEURL.$this->cname.'/showBasket');
			}
			return ;
		}
		Func::redirect(BASEURL.$this->cname.'/showOpenOrders');

	}
	
	
	public function addProduct() {
		
		Check::reset_empties();
		$detail							= new stdClass();
		$detail->odetail_link			= Check::str('olink', 500, 10);
		$detail->odetail_shop_name		= Stack::shift('shop', true);
		$detail->odetail_product_name	= Check::str('oname', 255, 1);
		$detail->odetail_product_amount	= Check::int('oamount');
		$detail->odetail_product_color	= Check::str('ocolor', 255, 1);
		$detail->odetail_product_size	= Check::str('osize', 255, 1);
		$detail->odetail_client			= $this->user->user_id;
		$detail->odetail_order			= 0;
		$detail->odetail_manager		= 0;
		$x1								= Check::int('x1');
		$x2								= Check::int('x2');
		$y1								= Check::int('y1');
		$y2								= Check::int('y2');
		$width							= Check::int('sh_width');
		$fname							= Check::str('fname', 255, 1);
		$empties						= Check::get_empties();
		
		$country_manager				= Check::str('ocountry', 255, 1);
		
		try {
			if ($empties)
				throw new Exception('Ошибка переданных данных! Одно или несколько полей не заполнено!');
				
			$this->load->model('OdetailModel', 'OdetailModel');
			$Odetails = $this->OdetailModel->getFilteredDetails(array(
																'odetail_client' => $this->user->user_id, 
																'odetail_order' => 0
			));
			
			if (count($Odetails)) {
				$detail->odetail_manager = $Odetails[0]->odetail_manager;
			}
			else {
				
				if (!$country_manager)
					throw new Exception('Не указанна страна.');
				
				$this->load->model('CountryModel', 'CountryModel');
				$Countries = $this->CountryModel->getClientAvailableCountries($this->user->user_id);
				foreach ($Countries as $Country) {
					if ($Country->country_id == $country_manager) {
						$detail->odetail_manager = $Country->manager_user;
					}
				}
				if (!$detail->odetail_manager)
					throw new Exception('Ошибка переданных данных');
			}
			
			$this->db->trans_begin();	
			
			$detail->odetail_link = str_replace($this->config->item('base_url').'proxy/?url=', '', $detail->odetail_link);
			$detail->odetail_link = urldecode($detail->odetail_link);
			
			if (strpos($detail->odetail_link, 'http://') !== 0)
				$detail->odetail_link = 'http://'.$detail->odetail_link;
				
			$detail = $this->OdetailModel->addOdetail($detail);
			
			$this->OdetailModel->makeScreenshot($detail, $x1, $y1, $x2+$x1, $y2+$y1, $width);
			
			$this->db->trans_commit();
			
			Func::redirect(BASEURL.$this->cname.'/showBasket');
			
		}catch (Exception $e){
			$this->db->trans_rollback();
			$this->result->m = $e->getMessage();		
			Stack::push('result', $this->result);
		}
		
		$this->proxy($detail->odetail_link);
	}
	
	
	
	public function deleteDetail($oid) {
		$this->load->model('OdetailModel', 'OdetailModel');
		
		$_o = $this->OdetailModel->getById((int) $oid);
		if ($_o && $_o->odetail_order == 0 && $_o->odetail_client == $this->user->user_id){
			try {				
				if (!$this->OdetailModel->delete((int) $oid)) {
					throw new Exception('Невозможно удалить товар.');//throw new Exception(iconv('UTF-8', 'Windows-1251', 'Невозможно удалить товар.'));
				}
				$this->result->m = 'Товар успешно удален';//iconv('UTF-8', 'Windows-1251', 'Товар успешно удален');
			} catch (Exception $e){
				$this->result->m = $e->getMessage();
			}
		}else{
			$this->result->m = iconv('UTF-8', 'Windows-1251', 'Неверно выбран товар');
		}
		
		Stack::push('result', $this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showBasket');
	}
	
	public function showBasket() {		
		$this->load->model('OdetailModel', 'OdetailModel');
		$Odetails = $this->OdetailModel->getFilteredDetails(array(
			'odetail_client' => $this->user->user_id,
			'odetail_order' => 0
		));
		
		if (!$Odetails){
			Func::redirect(BASEURL.$this->cname.'/showOpenOrders');
		}
		
		$this->load->model('CountryModel', 'CountryModel');
		$Countries	= $this->CountryModel->getClientAvailableCountries($this->user->user_id);
		
		$view = array(
			'Odetails'	=> $Odetails,
			'Countries'	=> $Countries,
		);		
		View::showChild($this->viewpath.'/pages/show_basket', $view);
	}
	
	public function showScreen($oid=null) {
		header('Content-type: image/jpg');
		$this->load->model('OdetailModel', 'OdetailModel');
		if ($Detail = $this->OdetailModel->getInfo(array('odetail_client' => $this->user->user_id, 'odetail_id' => intval($oid)))) {
			readfile($_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$Detail->odetail_client.'/'.$Detail->odetail_id.'.jpg');
		}
		die();
	}
	
	public function checkout() {
		$this->load->model('OdetailModel', 'OdetailModel');
		$Odetails = $this->OdetailModel->getFilteredDetails(array('odetail_client' => $this->user->user_id, 'odetail_order' => 0));
		
		if (!count($Odetails)) {
			$this->result->m = 'Отсутствуют детали заказа';
		}
		else {
			try {
				$this->db->trans_begin();
				
				$this->load->model('OrderModel', 'OrderModel');
				$order							= new stdClass();
				$order->order_client			= $this->user->user_id;
				$order->order_manager			= $Odetails[0]->odetail_manager;
				$order->order_status			= 'proccessing';
				$order->order_date				= date('Y-m-d H:i:s');
				$order->order_country			= $Odetails[0]->country_id;
				$order->order_shop_name			= $Odetails[0]->odetail_shop_name;
				
				if (!($Order = $this->OrderModel->addOrder($order))) {
					throw new Exception('Ошибка создания заказа.');
				}
				
				$this->OdetailModel->checkoutClientDetails($this->user->user_id, $Order->order_id);
				$this->result->m = 'Заказ сформирован';
				$this->db->trans_commit();
				
			} catch (Exception $e){
				$this->db->trans_rollback();
				$this->result->r = $e->getCode();
				$this->result->m = $e->getMessage();
			}			
		}
		Stack::push('result', $this->result);
		
		Func::redirect(BASEURL.$this->cname.'/showOpenOrders');
	}
	
	public function showNewsList($limit = 0, $offset = 0, $news_id = null)
	{
		$this->load->model('NewsModel', 'News');
		
		$news	= $this->News->getInfo($news_id,null,array(
														'news_addtime'	=> 'desc'
		), (int) $limit, (int) $offset,false);
		
		
		/**
		 * надо написать свою пагинацию, а то эта оч кривая...
		 */
//		$this->load->library('pagination');
//		$this->pagination->initialize(array(
//											'base_url'		=> BASEURL.$this->cname.'/showNewsList',
//											'total_rows'	=> $this->News->getCountOfRecords(),
//											'per_page'		=> $limit,
//											'uri_segment'	=> 4,
//											'num_links'		=> 2,
//											'first_link'	=> 'В начало',
//											'last_link'		=> 'В конец'
//		));
//		$pagination	= $this->pagination->create_links();
//		$pagination = preg_replace(
//									"/(showNewsList)/",
//									"showNewsList/",
//									$pagination
//		);

		View::showChild($this->viewpath.'/pages/news', array(
																'news'			=> $news,
																'pagination'	=> '',
		));
		
	}
	
	public function showAddBalance() {
		
		Func::redirect('/syspay');
		return;
		
		$this->load->model('Order2outModel', 'Order2out');
		$Orders = $this->Order2out->getUserOrders($this->user->user_id);
		
		$view = array (
			'Orders' => $Orders,
			'statuses'	=> $this->Order2out->getStatuses()
		);
		
		View::showChild($this->viewpath.'/pages/showAddBalance', $view);
	}
	
	
	public function showOutMoney() {
		
		$this->load->model('Order2outModel', 'Order2out');
		$Orders = $this->Order2out->getUserOrders($this->user->user_id);
		
		$view = array (
			'Orders' => $Orders,
			'statuses'	=> $this->Order2out->getStatuses()
		);
		
		View::showChild($this->viewpath.'/pages/showOutMoney', $view);
	}
	
	
	public function order2out() 
	{
		Check::reset_empties();	
		$order2out	= new stdClass();
		$order2out->order2out_ammount = Check::int('ammount');
		$empties	= Check::get_empties();
		
		try
		{
			if ($empties || $order2out->order2out_ammount <=0)
			{
				throw new Exception('Одно или несколько полей не заполнено.');
			}
			
			if ($this->user->user_coints < $order2out->order2out_ammount)
			{
				throw new Exception('У Вас недостаточно средств для вывода.');
			}
			
			$order2out->order2out_tax = 0;
			$order2out->order2out_user = $this->user->user_id;
			$order2out->order2out_status = 'processing';
			
			$this->db->trans_begin();
			
			$this->load->model('Order2outModel', 'Order2out');
			$order2out = $this->Order2out->addOrder($order2out);

			if (!$order2out) 
			{
				throw new Exception('Ошибка создания заявки на вывод.');
			}
			
			$payment_obj = new stdClass();
			$payment_obj->payment_from			= $this->user->user_id;
			$payment_obj->payment_to			= 1;
			$payment_obj->payment_amount_from	= $order2out->order2out_ammount * 103 / 100;
			$payment_obj->payment_amount_to		= $order2out->order2out_ammount;
			$payment_obj->payment_amount_tax	= $order2out->order2out_ammount * 3 / 100;
			$payment_obj->payment_purpose		= 'заявка на вывод';
			$payment_obj->payment_comment		= '№ '.$order2out->order2out_id;

			$this->load->model('PaymentModel', 'Payment');
			
			if (!$this->Payment->makePayment($payment_obj)) 
			{
				throw new Exception('Ошибка перевода средств между счетами. Попробуйте еще раз.');
			}			
			
			$this->session->set_userdata(array('user_coints' => $this->user->user_coints - $payment_obj->payment_amount_from));
			$this->db->trans_commit();
			$this->result->m = 'Заявка на вывод денег успешно добавлена.';
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			$this->result->e	= $e->getCode();			
			$this->result->m	= $e->getMessage();
		}
		
		Stack::push('result', $this->result);		
		Func::redirect(BASEURL.$this->cname.'/showOutMoney');
	}
	
	public function deleteOrder2out($oid) 
	{
		parent::deleteOrder2out($oid);
	}
	
	public function createOrder2out() {
		
		// ищем макс id заказа на вывод
		$this->load->model('Order2outModel', 'Order2out');
		$last_id = $this->Order2out->getMaxId();		
		echo($last_id[0]->max ? $last_id[0]->max + 1 : 1);
		die();
	}
	
	public function getScreenshot($fname)
	{
		header('Content-Type: image/jpeg');
		echo file_get_contents($_SERVER['DOCUMENT_ROOT']."/upload/orders/{$this->user->user_id}/tmp/$fname.jpg");
	}
	
	public function getScreenshotHtml($fname)
	{
		echo "<img src='/client/getScreenshot/$fname' />";
	}
	
	private function putScreenshot($url, $fname)
	{
		Stack::clear('screenshot');
		//@unlink("/home/omni/kio.teralabs.ru/html/upload/orders/{$this->user->user_id}/tmp/");
		
		if (!is_dir($_SERVER['DOCUMENT_ROOT']."/upload/orders/{$this->user->user_id}/tmp/")){
			mkdir($_SERVER['DOCUMENT_ROOT']."/upload/orders/{$this->user->user_id}/tmp/", 0777, true);
		}
		
		exec("wkhtmltoimage-amd64 --load-error-handling ignore --width 1266 '$url' ".$_SERVER['DOCUMENT_ROOT']."/upload/orders/{$this->user->user_id}/tmp/$fname.jpg");
	}
	
	public function proxy($url=null) 
	{
		$this->output->enable_profiler(false);
		
		error_reporting(E_ERROR);
		header("Content-Type: text/html; charset=windows-1251");
		parse_str($_SERVER['QUERY_STRING'],$_GET);
		
		if (!$url){
			$url	= @$_GET['url'];
		}
		
		preg_match("/^.+?\.(jpg|gif|png|jpeg|bmp)$/",$url,$img_ch);
		preg_match("/^.+?\.(css|js)$/",$url,$res_ch);
		$url		= (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0  || strpos($src, '//') === 0) ? $url : 'http://'.$url;
		$parse		= parse_url($url);
		$host		= $parse['host'];
		$server_host= $_SERVER['HTTP_HOST'];

		if (!Stack::last('curHost'))
			Stack::push('curHost',$host);

		$fname = md5(time().$this->user->user_id.$this->user->user_group);
		$this->putScreenshot($url, $fname);
		
		$this->load->model('OdetailModel', 'OdetailModel');
		$Odetails	= $this->OdetailModel->getFilteredDetails(array(
																'odetail_client' => $this->user->user_id, 
																'odetail_order' => 0
		));
		
		$this->load->model('CountryModel', 'CountryModel');
		$Countries	= $this->CountryModel->getClientAvailableCountries($this->user->user_id);
			
		View::show($this->viewpath.'proxy3', array(
													'fname'			=> $fname,
													'Odetails'		=> $Odetails,
													'Countries'		=> $Countries,
													'url'			=> $url,
													'server_host'	=> $server_host,
													'host'			=> $host,
		));
	}

	public function addDeclarationHelp()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('Доступ запрещен.');
			}
		
			// безопасность: проверяем связку клиента и посылки
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getClientPackageById($this->uri->segment(3), $this->user->user_id);
			
			if (!$package)
			{
				throw new Exception('Невозможно сохранить декларацию. Посылка недоступна.');
			}

			// меняем статус декларации
			$package->declaration_status = 'help';

			// вычисляем стоимость посылки
			$this->load->model('ConfigModel', 'Config');
			$this->load->model('PricelistModel', 'Pricelist');
			
			$package = $this->Packages->calculateCost($package, $this->Config, $this->Pricelist);
			
			if (!$package) 
			{
				throw new Exception('Стоимость посылки не определена. Попробуйте еще раз.');
			}
			
			// сохраняем декларацию
			$package = $this->Packages->savePackage($package);

			if (!$package)
			{
				throw new Exception('Декларация не сохранена. Попробуйте еще раз.');
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// открываем посылки
		Func::redirect(BASEURL.$this->cname.'/showDeclaration/'.$this->uri->segment(3));
	}
	
	public function addPackageComment($package_id, $comment_id = null)
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($package_id))
			{
				throw new Exception('Доступ запрещен.');
			}
		
			// безопасность: проверяем связку клиента и посылки
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getClientPackageById($package_id, $this->user->user_id);

			if (!$package)
			{
				throw new Exception('Невозможно добавить комментарий. Посылка недоступна.');
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
			$package->comment_for_manager = TRUE;
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
	

	public function showOpenPackages()
	{
		$this->showPackages('open', 'showOpenPackages', TRUE);
	}
	
	public function showSentPackages()
	{
		$this->showPackages('sent', 'showSentPackages');
	}

	
	public function showHelpForBuy()
	{	
		$this->load->model('OdetailModel', 'OdetailModel');
		if ($this->OdetailModel->getFilteredDetails(array(
															'odetail_client' => $this->user->user_id,
															'odetail_order' => 0))){
			Func::redirect(BASEURL.$this->cname.'/showBasket');
		}
		
		$this->load->model('OrderModel', 'OrderModel');
		$this->load->model('CountryModel', 'CountryModel');
		$this->load->model('OdetailModel', 'OdetailModel');
		
		$Orders		= $this->OrderModel->getClientOrders($this->user->user_id);
		$Odetails	= $this->OdetailModel->getFilteredDetails(array('odetail_client' => $this->user->user_id, 'odetail_order' => 0));
		$Countries	= $this->CountryModel->getClientAvailableCountries($this->user->user_id);
			
		$view = array (
			'Orders'	=> $Orders,
			'Odetails'	=> $Odetails,
			'Countries'	=> $Countries,
		);
		View::showChild($this->viewpath.'/pages/help_for_buy', $view);		
	}
	
	public function showOpenOrders()
	{
		$this->load->model('OdetailModel', 'OdetailModel');
		if ($this->OdetailModel->getFilteredDetails(array(
															'odetail_client' => $this->user->user_id,
															'odetail_order' => 0))){
			Func::redirect(BASEURL.$this->cname.'/showBasket');
			return;
		}
		
		$this->showOrders('open', 'showOpenOrders');
	}
	
	public function showSentOrders()
	{
		$this->showOrders('sended', 'showSentOrders');
	}
	
	public function deleteOrder()
	{
		parent::deleteOrder();
	}
	
	public function deleteProduct($odid)
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($odid))
			{
				throw new Exception('Доступ запрещен.');
			}
			
			// безопасность: проверяем связку клиента и товара
			$this->load->model('OdetailModel', 'ODetails');
			$odetail = $this->ODetails->getClientOdetailById($odid, $this->user->user_id);

			if (!$odetail)
			{
				throw new Exception('Товар не найден. Попробуйте еще раз.');
			}			

			// сохранение результатов
			$odetail->odetail_status = 'deleted';
			
			$this->db->trans_begin();
			$deleted_odetail = $this->ODetails->addOdetail($odetail);
			
			if (!$deleted_odetail)
			{
				throw new Exception('Товар не удален. Попробуйте еще раз.');
			}
			
			// меняем статус заказа
			$status = $this->ODetails->getTotalStatus($deleted_odetail->odetail_order);
			
			if (!$status)
			{
				throw new Exception('Невожможно изменить статус заказа. Попоробуйте еще раз.');
			}
			
			if (strpos($status, 'not_available') !== false)
			{
				$order->order_status = $status;
			}
			else
			{
				$order->order_status = 'not_payed';
			}
			
			$this->load->model('OrderModel', 'Orders');
			$new_order = $this->Orders->saveOrder($order);
			
			if (!$new_order)
			{
				throw new Exception('Невожможно изменить статус заказа. Попоробуйте еще раз.');
			}
			
			$this->db->trans_commit();
			
			$this->result->m = 'Товар успешно удален.';
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
		//Func::redirect(BASEURL.$this->cname.'/showOpenOrders');
		Func::redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function payOrder()
	{
		try
		{
			if (!$this->user ||
				!$this->user->user_id ||
				!is_numeric($this->uri->segment(3)))
			{
				throw new Exception('Доступ запрещен.');
			}
			
			// безопасность: проверяем связку клиента и заказа
			$this->load->model('OrderModel', 'Orders');
			$order = $this->Orders->getClientOrderById($this->uri->segment(3), $this->user->user_id);

			if (!$order)
			{
				throw new Exception('Заказ не найден. Попробуйте еще раз.');
			}			

			// добавление платежа
			$payment_obj = new stdClass();
			$payment_obj->payment_from			= $order->order_client;
			$payment_obj->payment_to			= 1;
			$payment_obj->payment_amount_from	= $order->order_cost;
			$payment_obj->payment_amount_to		= 
				$order->order_products_cost +
				$order->order_delivery_cost;
			$payment_obj->payment_amount_tax	= 
				$order->order_cost - 
				$payment_obj->payment_amount_to;
			$payment_obj->payment_purpose		= 'оплата заказа';
			$payment_obj->payment_comment		= '№ '.$order->order_id;
			
			$this->load->model('PaymentModel', 'Payment');
			
			$this->db->trans_begin();

			if (!$this->Payment->makePayment($payment_obj, true)) 
			{
				throw new Exception('Ошибка оплаты заказа. Попробуйте еще раз.');
			}			
			
			// сохранение результатов
			$order->order_status = 'payed';
			$payed_order = $this->Orders->saveOrder($order);
			
			if (!$payed_order)
			{
				throw new Exception('Заказ не оплачен. Попробуйте еще раз.');
			}			
			
			if ($this->db->trans_status() !== FALSE)
			{
				$this->db->trans_commit();
			}
			
			$this->session->set_userdata(array('user_coints' => $this->user->user_coints - $payed_order->order_cost));
			$this->result->m = 'Заказ успешно оплачен.';

		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
		
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
		}
		
		// открываем заказы
		Stack::push('result', $this->result);
		Func::redirect(BASEURL.$this->cname.'/showOpenOrders');
	}
	
	public function payPackage($package_id)
	{
		try
		{
			// нах.... такую безопасность!!!! (с)омни
			// безопасность
//			if (!$this->user ||
//				!$this->user->user_id ||
//				!is_numeric($this->uri->segment(3)))
//			{
//				throw new Exception('Доступ запрещен.');
//			}


			
			// безопасность: проверяем связку клиента и посылки
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getClientPackageById((int) $package_id, $this->user->user_id);

			if (!$package)
			{
				throw new Exception('Посылка не найдена. Попробуйте еще раз.');
			}			

			// добавление платежа
			$payment_obj = new stdClass();
			$payment_obj->payment_from			= $package->package_client;
			$payment_obj->payment_to			= 1;
			$payment_obj->payment_amount_from	= $package->package_cost;
			$payment_obj->payment_amount_to		= $package->package_delivery_cost;
			$payment_obj->payment_amount_tax	= 
				$package->package_declaration_cost + 
				$package->package_join_cost + 
				$package->package_comission;
			$payment_obj->payment_purpose		= 'оплата посылки';
			$payment_obj->payment_comment		= '№ '.$package->package_id;
			
			$this->load->model('PaymentModel', 'Payment');
			
			$this->db->trans_begin();

			if (!$this->Payment->makePayment($payment_obj, true)) 
			{
				throw new Exception('Ошибка оплаты посылки. Попробуйте еще раз.');
			}			
			
			// сохранение посылки
			$package->package_status = 'payed';
			$payed_package = $this->Packages->savePackage($package);
			
			if (!$payed_package)
			{
				throw new Exception('Посылка не оплачена. Попробуйте еще раз.');
			}

			if ($this->db->trans_status() !== FALSE)
			{
				$this->db->trans_commit();
			}
			
			$this->session->set_userdata(array('user_coints' => $this->user->user_coints - $payed_package->package_cost));
			$this->result->m = 'Посылка успешно оплачена.';
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
		
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
		}
		
		// открываем посылки
		Stack::push('result', $this->result);
		Func::redirect(BASEURL.$this->cname.'/showOpenPackages');
	}
	
	public function updatePackageDelivery($package_id, $delivery_id)
	{
		try
		{
			if (!(int)$package_id && !(int)$delivery_id)
			{
				throw new Exception('Неверные параметры запроса.');
			}
			
			// безопасность: проверяем связку клиента и посылки
			$this->load->model('PackageModel', 'Packages');
			$package = $this->Packages->getClientPackageById($package_id, $this->user->user_id);

			if (!$package)
			{
				throw new Exception('Посылка не найдена. Попробуйте еще раз.');
			}			

			// безопасность: проверяем доступность способа доставки
			$deliveryDetails = new stdClass();
			$deliveryDetails->pricelist_country_from	= $package->package_country_from;
			$deliveryDetails->pricelist_country_to		= $package->package_country_to;
			$deliveryDetails->pricelist_delivery		= $delivery_id;

			$this->load->model('PricelistModel', 'Pricelist');
			$pricelist = $this->Pricelist->getPricelist($deliveryDetails);

			if (!$pricelist)
			{
				throw new Exception('Способ доставки недоступен. Попробуйте еще раз.');
			}			

			$package->package_delivery					= $delivery_id;
			
			// вычисляем стоимость посылки
			$this->load->model('ConfigModel', 'Config');
			
			$package = $this->Packages->calculateCost($package, $this->Config, $this->Pricelist);
			
			if (!$package) 
			{
				throw new Exception('Стоимость посылки не определена. Попробуйте еще раз.');
			}
			
			// сохранение результатов
			$this->load->model('PackageModel', 'Packages');
			$new_package = $this->Packages->savePackage($package);
			
			if (!$new_package)
			{
				throw new Exception('Способ доставки не изменен. Попробуйте еще раз.');
			}			
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		
		// открываем посылки
		Func::redirect(BASEURL.$this->cname.'/showOpenPackages');
	}
	
	public function joinPackages()
	{
		try
		{
			$this->load->model('PackageModel', 'Packages');
	
			$new_package	= new stdClass();
			$new_package->package_join_count	= 1;
			$new_package->package_status		= 'not_payed';
			$new_package->declaration_status	= 'not_completed';
			$new_package->package_client		= $this->user->user_id;
			$new_package->package_delivery_cost	= 0;
			$new_package->package_join_cost		= 0;

			// итерируем по посылкам
			$joined_packages	= 0;
			$reset_delivery		= false;
			$this->db->trans_begin();
			$files2join			= array();
			$packsIds			= array();
				
			foreach($_POST as $key=>$value)
			{
				if (stripos($key, 'join') === 0) 
				{
					// находим посылку
					$package_id = str_ireplace('join', '', $key);
					
					if (!is_numeric($package_id)) continue;

					$package = $this->Packages->getClientPackageById($package_id, $this->user->user_id);
					
					if (!$package)
					{
						throw new Exception('Невозможно объединить посылки. Некоторые посылки не найдены.',-1);
					}
					
					$packsIds[]	= $package_id;
					
					// добавляем ее к новой посылке
					$new_package->package_join_count += $package->package_join_count;
					$new_package->package_weight += $package->package_weight;
					
					// валидация пользовательского ввода
					if (!isset($new_package->package_manager))
					{
						$new_package->package_manager = $package->package_manager;
					}
					else if ($new_package->package_manager != $package->package_manager)
					{
						throw new Exception('Невозможно объединить посылки разных партнеров.',-2);
					}
			
					if (!$reset_delivery)
					{
						if (!isset($new_package->package_delivery))
						{
							$new_package->package_delivery = $package->package_delivery;
						}
						else if ($new_package->package_delivery != $package->package_delivery)
						{
							$new_package->package_delivery = 0;
							$reset_delivery = true;
						}
					}
					
					if (!isset($new_package->package_address))
					{
						$new_package->package_address = $package->package_address;
					}
					else if ($new_package->package_address != $package->package_address)
					{
						throw new Exception('Невозможно объединить посылки с разными адресами доставки. Попробуйте еще раз.',-3);
					}
					
					if (!isset($new_package->package_country_to))
					{
						$new_package->package_country_to = $package->package_country_to;
					}
					else if ($new_package->package_country_to != $package->package_country_to)
					{
						throw new Exception('Невозможно объединить посылки в разные страны. Попробуйте еще раз.',-4);
					}					

					if (!isset($new_package->package_country_from))
					{
						$new_package->package_country_from = $package->package_country_from;
					}
					else if ($new_package->package_country_from != $package->package_country_from)
					{
						throw new Exception('Невозможно объединить посылки из разных стран. Попробуйте еще раз.',-5);
					}
					
					if ($package->package_status != 'not_payed')
					{
						throw new Exception('Разрешается объединять только неоплаченные посылки. Попробуйте еще раз.',-6);
					}
					
					// собираем фото старых посылок
					$packFotos	= $this->Packages->getPackagesFoto(array($package));
					if (count($packFotos) > 0){
						$files2join[$package->package_id]	= $packFotos[$package->package_id];						
					}
					
					// удаляем старую посылку
					$package->package_status = 'deleted';
					$package = $this->Packages->savePackage($package);

					// подсчитываем количество объединенных посылок
					$joined_packages++;
				}
			}
			
			// валидация пользовательского ввода
			if ($joined_packages < 2)
			{
				throw new Exception('Невозможно объединить посылки. Выберите хотя бы 2 посылки для объединения.',-7);
			}
			
			// ограничение по весу в 30 кг
			if ($new_package->package_weight > 30)
			{
				throw new Exception('Невозможно объединить посылки. Привышен максимальный вес одной посылки.',-8);
			}
			
			// вычисляем стоимость объединенной посылки
			$this->load->model('ConfigModel', 'Config');
			$this->load->model('PricelistModel', 'Pricelist');
			
			$new_package = $this->Packages->calculateCost($new_package, $this->Config, $this->Pricelist);
			
			if (!$new_package) 
			{
				throw new Exception('Невозможно объединить посылки. Стоимость объединенной посылки не определена.',-9);
			}
			
			// сохраняем посылку
			$this->load->model('PackageModel', 'JointPackage');
			$new_package->package_id = null;
			$new_package->package_join_ids	= join('+',$packsIds);
			$new_package = $this->JointPackage->savePackage($new_package);

			if (!$new_package)
			{
				throw new Exception('Невозможно объединить посылки. Попробуйте еще раз или обратитесь в службу поддержки.',-10);
			}
			
			// переносим фото посылок
			if (count($files2join)>0){
				if (!is_dir(UPLOAD_DIR.'packages/'.$new_package->package_manager.'/'.$new_package->package_id)){
					mkdir(UPLOAD_DIR.'packages/'.$new_package->package_manager.'/'.$new_package->package_id, 0777, true);
					chmod(UPLOAD_DIR.'packages/'.$new_package->package_manager.'/'.$new_package->package_id, 0777);
				}
				foreach ($files2join as $dir => $files){
					$errRen	= 0;
					foreach ($files as $file){
						if (!rename(UPLOAD_DIR.'packages/'.$new_package->package_manager.'/'.$dir.'/'.$file, UPLOAD_DIR.'packages/'.$new_package->package_manager.'/'.$new_package->package_id.'/'.$file)) $errRen++;
					}
					if (!$errRen) @rmdir(UPLOAD_DIR.'packages/'.$new_package->package_manager.'/'.$dir);
				}
			}
			
			// закрываем транзакцию
			$this->db->trans_commit();

			$this->result->m = 'Посылки успешно объединены. Номер объединенной посылки: '.$new_package->package_id;
			$this->result->e = 1;
			Stack::push('result', $this->result);
		}
		catch (Exception $e) 
		{
			$this->db->trans_rollback();
			
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			
			Stack::push('result', $this->result);
		}
		//var_dump($this->result);
		// открываем посылки
		Func::redirect(BASEURL.$this->cname.'/showOpenPackages');
	}

	public function showPackageFoto($pid, $filename){
		$this->showPackagePhoto($pid, $filename);
	}
	
	public function editPackageAddress()
	{
		parent::editPackageAddress();
	}

	public function showOrderDetails()
	{
		/*if (count($Odetails)) 
		{
			$view['country'] = false;
		}
		else 
		{
			$view['country'] = true;
			$this->load->model('CountryModel', 'CountryModel');
			$view['countries'] = $this->CountryModel->getClientAvailableCountries($this->user->user_id);
		}*/
		parent::showOrderDetails();
	}
	
	public function showDeclaration()
	{
		parent::showDeclaration();
	}
	
	public function showPackageComments()
	{
		parent::showPackageComments();
	}
	
	public function showOrderComments($flag = false)
	{
		return parent::showOrderComments($flag);
	}
	
	public function showO2oComments()
	{
		parent::showO2oComments();
	}
	
	public function addOrderComment()
	{
		parent::addOrderComment();
	}
	
	public function addO2oComment()
	{
		parent::addO2oComment();
	}
	
	public function saveDeclaration()
	{
		parent::saveDeclaration();
	}	

	public function updatePackageAddress()
	{
		parent::updatePackageAddress();
	}

	public function showAddresses($partner_id = null)
	{
		$view	= array(
						'client'	=> $this->__client,
						'partners'	=> $this->__partners,
		);

		if (isset($this->__partners[$partner_id])){

			$view['partner_id']	= $partner_id;
		}
		
		View::showChild($this->viewpath.'/pages/showAddresses', $view);
	}
	
	public function showAddImage()
	{
		View::showChild($this->viewpath.'/pages/showAddImage');
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
	
	public function searchPayments() 
	{
		 
		return;
		
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
			$this->result->m	= 'Не верный поле фильтра';
		}
		
		if ($sfield == 'payment' && !is_numeric($svalue)){
			$sfield	= null;
			$this->result->e	= -1;
			$this->result->m	= 'Не верный формат фильтра';
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
	/* END OF public function searchPayments() */

	
	
	######################################
	public function do_ajax_ProductPreview()
	{
		$error = "";
		$msg = "";
		$fileElementName = 'uploadLead';
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;
				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES['uploadLead']['tmp_name']) || $_FILES['uploadLead']['tmp_name'] == 'none')
		{
			$error = 'No file was uploaded..';
		}else {
			$uploaddir	= BASE_DIR_NAME.'html/img/upload/';
			$uploadfile	= $uploaddir . basename($_FILES['uploadLead']['name']);
			$filename	= $_FILES['uploadLead']['name'];
	
			if (move_uploaded_file($_FILES['uploadLead']['tmp_name'], $uploadfile)) {
	
				$msg .= " File Name: " . $_FILES['uploadLead']['name'] . ", ";
				$msg .= " File Size: " . @filesize($uploadfile);
				//for security reason, we force to remove all uploaded file
			}else{
				@unlink($_FILES['uploadLead']);
			}
		}		
		
		echo json_encode(array(
								'error'		=> $error, 
								'msg'		=> $msg,
								'filename'	=> $filename,
								'fileURL'	=> IMG_PATH.'upload/'.$filename,
								));		
	}
	############################
}
/* End of file main.php */
/* Location: ./system/application/controllers/main.php */