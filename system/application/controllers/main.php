<?php
require_once BASE_CONTROLLERS_PATH.'BaseController'.EXT;

class Main extends BaseController {
    var $data = null;
    var $root='/static/images/';

	function __construct()
	{
		parent::__construct();	

		$this->data				= new stdClass();
		$this->data->width		= '0';
		$this->data->height		= '0';
		$this->data->dir		= 'screenshots';
		$this->data->err		= 'NULL';
		$this->data->id			= '0';
		$this->data->fullpath 	= '';
		$this->data->name		= '';
	}
	
	function index()
	{
		$this->load->library('cbr');
		
		$currency		= new stdClass();
		$currency->USD	= (string) $this->cbr->getRate('USD');
		$currency->CNY	= (string) $this->cbr->getRate('CNY');
		$currency->EUR	= (string) $this->cbr->getRate('EUR');
		$currency->KRW	= (string) $this->cbr->getRate('KRW');
		
		View::showChild($this->viewpath.'/pages/main',array(
			'currency'	=> $currency,
		));
	}
	
	public function showPays()
	{
		
		View::showChild($this->viewpath.'/pages/pays');
	}
	
	public function showShopCatalog()
	{
		

		// получаем категории магазинов с числом магазинов в них		
		$this->load->model('CategoryModel', 'Category');
		$view = array();		
		$view['Categories'] = $this->Category->getCategoriesWithShopsNum();
		$view['is_authorized'] = $this->user ? true : false;
		
		if (Stack::size('add_shop') > 0) {
			$view['is_added'] = 1;
			Stack::shift('add_shop');
		}

		View::showChild($this->viewpath.'pages/shop_catalog', $view);
	}
	
	public function showAddShop()
	{
		if (!$this->user) {
			header('Location: '.BASEURL.'/main/showShopCatalog');			
			return true;
		}
		$view = array();
		
		$this->load->model('CountryModel', 'Country');		
		$view['countries'] = $this->Country->getList();
		Stack::push('countries', $view['countries']);
		
		$this->load->model('CategoryModel', 'Category');
		$view['categories'] = $this->Category->getList();
		Stack::push('categories', $view['categories']);
		
		View::showChild($this->viewpath.'/pages/add_shop', $view);
	}
	
	public function addShop()
	{
		
		if (!$this->user) {
			header('Location: '.BASEURL.'/main/showShopCatalog');			
			return true;
		}
		
		$countries = '';
		if (Stack::size('countries')>0){
			$countries	= Stack::last('countries');
		}else{
			$this->load->model('CountryModel', 'Country');
			$countries	= $this->Country->getList();			
		}
		
		$categories = '';
		if (Stack::size('categories')>0){
			$categories	= Stack::last('categories');
		}else{
			$this->load->model('CategoryModel', 'Category');
			$categories	= $this->Category->getList();			
		}
		
		Check::reset_empties();
		$shop					= new stdClass();
		$shop->shop_name		= Check::str('sname', 100, 7);
		$shop->shop_desc		= Check::str('sdescription',1000,4);
		$shop->shop_country		= Check::int('scountry');
		$shop->shop_scategory	= Check::int('scategory');
		$shop->shop_user		= $this->user->user_id;
		$empties				= Check::get_empties();
		
		$result		= new stdClass();
		$result->e	= 0;
		$result->m	= '';	// сообщение
		$result->d	= '';	// возвращаемые данные
		
		try {
			if ($empties){
				throw new Exception('Одно или несколько полей не заполнено.', -10);
			}
			
			if (!Check::url($shop->shop_name))
				throw new Exception('Укажите верный адрес сайта.', -7);
			
			$counties_ids = array();
			foreach ($countries as $country)
				$counties_ids[] = $country->country_id;
			if (!in_array($shop->shop_country, $counties_ids))
				throw new Exception('Укажите верную страну.', -5);
				
			$categories_ids = array();
			foreach ($categories as $category)
				$categories_ids[] = $category->scategory_id;
			if (!in_array($shop->shop_scategory, $categories_ids))
				throw new Exception('Укажите верную категорию.', -6);
			
			$this->load->model('ShopModel', 'Shop');
			
			$s = $this->Shop->addShop($shop);
			if (!$s) {
				throw new Exception('Добавление магазина временно невозможно.',-12);
			}
			
			Stack::push('add_shop', 1);
			Func::redirect(BASEURL.'/main/showShopCatalog');			
			return true;
		} catch (Exception $e){
			
			$result->e	= $e->getCode();			
			$result->m	= $e->getMessage();

			switch ($result->e){
				case -1:
				case -7:
					$shop->shop_name = '';
					break;
				case -5:
					$shop->shop_country = 0;
					break;
				case -6:
					$shop->shop_scategory = 0;
					break;
			}
			
			$result->d	= $shop;
		}
		
		$view = array(
			'result'		=> $result,
			'categories'	=> $categories,
			'countries'		=> $countries
		);
		
		View::showChild($this->viewpath.'pages/add_shop', $view);
	}
	
	public function saveShop($id)
	{
		//проверка прав на доступ администратора
		try
		{
			$check = ($this->user ? ($this->user->user_group == 'admin') : false);
			if ( ! $check ) 
			{
				throw new Exception('Для удаления нужны права администратора');
				return ;
			}
			
			
			Check::reset_empties();

			$shop_name	    = Check::txt('shop_name',	128,1);
			$shop_country	= Check::int('country');
			$shop_scategory	= Check::int('scategory');
			$shop_desc	    = Check::txt('shop_desc',	8096);
			
			// fild all fields
			if (!Check::get_empties())
			{
				$this->load->model('ShopModel', 'ShopModel');
				
				$this->ShopModel->_set('shop_name',      $shop_name);
				$this->ShopModel->_set('shop_country',   $shop_country);
				$this->ShopModel->_set('shop_scategory', $shop_scategory);
				$this->ShopModel->_set('shop_desc',      $shop_desc);
				$this->ShopModel->_set('shop_user',      $this->user->user_id);
				
				if ($id) 
					$this->ShopModel->_set('shop_id',	$id);			
				
				if (!$this->ShopModel->save()){
					$this->result->e	= -1;
					$this->result->m	= 'Невозожно добавить запись.';
				}else{
					$this->result->e	= 1;
					$this->result->m	= 'Запись успешно добавлна.';
				}
			}
			else
			{
				$this->result->e	= -1;
				$this->result->m	= 'Невозожно добавить запись. Возможно незаполнено одно или несколько полей.';
			}
		}
		catch(Exception $e)
		{
			$this->result->e	= $e->getCode();			
			$this->result->m	= $e->getMessage();
		}
		Stack::push('result', $this->result);
		
		Func::redirect(BASEURL.'/main/showCategory/'.$shop_scategory);
		
		
		
	}
	
	public function showEditShop($id=null)
	{
		//проверка прав на доступ администратора
		$check = ($this->user ? ($this->user->user_group == 'admin') : false);
		if ( ! $check ) 
		{
			throw new Exception('Для удаления нужны права администратора');
			return ;
		}
		//загрузка модели
		$this->load->model('ShopModel', 'ShopModel');		
		if ( ! ($Shop = $this->ShopModel->select(array('shop_id' => intval($id))) ) ) 
		{
			header('Location: '.BASEURL.'/main/showShopCatalog');			
			return true;
		}
		
		$this->load->model('CountryModel', 'Country');
		$Countries	= $this->Country->getList();
		
		$this->load->model('CategoryModel', 'SCategory');
		$SCategory	= $this->SCategory->getList();

		$view = array(
			'scategories'  => $SCategory,
			'shop'         => $Shop[0],
			'countries'    => $Countries
		);

		View::showChild($this->viewpath.'pages/edit_shop', $view);
		
	}
	
	public function deleteShop($id=null)
	{
		//проверка прав на доступ администратора
		$check = ($this->user ? ($this->user->user_group == 'admin') : false);
		if ( ! $check ) 
		{
			throw new Exception('Для удаления нужны права администратора');
			return ;
		}
		//загрузка модели
		$this->load->model('ShopModel', 'ShopModel');
		
		//удаление 
		$this->ShopModel->delete($id);
		
		header('Location: '.BASEURL.'/main/showShopCatalog');
	}
	
	public function showCategory($id=null, $order=null/*, $p=null*/) 
	{		
		
		$this->load->model('CategoryModel', 'CategoryModel');		
		if (!($Category = $this->CategoryModel->select(array('scategory_id' => intval($id))))) {
			header('Location: '.BASEURL.'/main/showShopCatalog');			
			return true;
		}
		
		$this->load->model('ShopModel', 'ShopModel');
		
		$avail_orders = array('id', 'country', 'comments');
		$orders_by = array('id' => 'shop_id', 'country' => 'country_name', 'comments' => 'count');
		$orders_addon = array('id' => null, 'country' => 'INNER JOIN `countries` ON `countries`.`country_id` = `'.$this->ShopModel->getTable().'`.`shop_country`', 'comments' => null);
		$orders_order = array('id' => 'ASC', 'country' => 'ASC', 'comments' => 'DESC');
		$order = (in_array($order, $avail_orders)) ? $order : $avail_orders[0];
		
		/*$per_page = 2;
		$p = (intval($p)) ? intval($p) : 1;
		$offset = ($p-1) * $per_page;	*/	 
		
		$Shops = $this->ShopModel->getShopsByCategory($Category[0]->scategory_id, array('by' => $orders_by[$order], 'addon' => $orders_addon[$order], 'order' => $orders_order[$order])/*, 'LIMIT '.$offset.', '.$per_page*/);

		/*$all = $this->db->query('SELECT FOUND_ROWS() as rowcount')->result();
		$this->load->library('pagination');
		$config['base_url'] = BASEURL.'/main/showCategory/'.$Category[0]->scategory_id.'/'.$order.'/';
		$config['total_rows'] = $all[0]->rowcount;
		$config['per_page'] = $per_page;
		$config['page_query_string'] = false;
		$config['query_string_segment'] = 'p';
		$config['uri_segment'] = 5;
		
		$this->pagination->initialize($config);
		echo $this->pagination->create_links();*/
		
		$this->load->model('CountryModel', 'Country');
		$Countries	= $this->Country->getList();
		$countries = array();
		foreach ($Countries as $Country)
			$countries[$Country->country_id] = $Country->country_name;	
		
		$view = array(
			'category'		=> $Category[0],
			'shops'			=> $Shops,
			'is_authorized' => $this->user ? true : false,
			'countries'		=> $countries, 
			'showActions'   => $this->user ? ($this->user->user_group == 'admin') : false
		);
		
		View::showChild($this->viewpath.'pages/show_category', $view);
	}
	
	public function showShop($id=null) {
		
		$this->load->model('ShopModel', 'ShopModel');		
		if (!($Shop = $this->ShopModel->select(array('shop_id' => intval($id))))) {
			header('Location: '.BASEURL.'/main/showShopCatalog');			
			return true;
		}
		
		$this->load->model('CountryModel', 'Country');
		$Country	= $this->Country->select(array('country_id' => $Shop[0]->shop_country));
		
		$this->load->model('SCommentModel', 'SCommentModel');		
		
		// Добавляем коммент		
		$comment					= new stdClass();
		$comment->scomment_comment	= Check::str('comment', 1000, 10);
		if ($comment->scomment_comment && $this->user) {
			
			$comment->scomment_user	= $this->user->user_id;
			$comment->scomment_shop	= intval($id);
			
			$this->SCommentModel->addComment($comment);
			
		}
		
		
		######### вытаскиваем данные о коментах, вместе с данными пользователя, который этот комент оставил ###########
		$Comments	= $this->SCommentModel->select(array('scomment_shop' => intval($id)));
		$users		= array();
		
		if ($Comments){
			foreach ($Comments as $comment){
				if (!in_array($comment->scomment_user, $users)){
					array_push($users, $comment->scomment_user);
				}
			}
		}
		
		$SUsers	= null;
		if (!empty($users)){
			$this->load->model('UserModel', 'User');
			
			foreach ($this->User->getInfo($users,null,null,null,null,false) as $user){
				$SUsers[$user->user_id] = $user;
			}
		}
		###############################################################################################################
		
		$view = array(
			'shop'			=> $Shop[0],
			'country'		=> $Country[0]->country_name,
			'comments'		=> $Comments,
			'susers'		=> $SUsers,
		);
		
		View::showChild($this->viewpath.'pages/show_shop', $view);
	}
	
	public function showHowItWork(){
		
		View::showChild($this->viewpath.'/pages/how_it_work');
	}
	
	public function showContacts(){
		
		View::showChild($this->viewpath.'/pages/contacts');
	}
	
	
	public function showCollaboration(){
		
		View::showChild($this->viewpath.'/pages/collaboration');
	}		
	
	public function showPricelist()
	{
		try
		{
			// обработка фильтра
			$view['filter'] = $this->initFilter('pricelist'); 
			$this->load->model('CountryModel', 'Countries');
			
	/*		if (Check::access('admin'))
			{
				$view['from_countries'] = $view['to_countries'] = $this->Countries->getList();
			}
			else
			{
				$view['from_countries'] = $this->Countries->getFromCountries();
				$view['to_countries'] = $this->Countries->getToCountries();
			}
				 */
			
			$country_from = !empty($view['filter']->pricelist_country_from) ? (int)$view['filter']->pricelist_country_from :0;
			
			$view['from_countries'] = $this->Countries->getFromCountries();
			$view['to_countries'] = $this->Countries->getToCountriesFrom($country_from);
	
			if ($view['filter']->pricelist_country_from == '' ||
				$view['filter']->pricelist_country_to == '')
			{
				throw new Exception('Выберите страны доставки.');
			}
			
			// отображаем тарифы
			$this->load->model('PricelistModel', 'Pricelist');
			$view['pricelist'] = $this->Pricelist->getPricelist($view['filter']);
			
			if (!$view['pricelist'])
			{
				throw new Exception('Тарифы не найдены. Попробуйте еще раз.');
			}
		}
		catch (Exception $e) 
		{
			$this->result->e = $e->getCode();			
			$this->result->m = $e->getMessage();
			Stack::push('result', $this->result);
		}

		View::showChild($this->viewpath.'/pages/showPricelist', $view);
	}
	
	public function filterPricelist()
	{
		$this->filter('pricelist', 'showPricelist');
	}
	

	function getFile($dir, $id)
	{
		try
		{
			$this->data->dir = $dir;
			$this->data->id = $id;
			
			if (isset($this->data->id) &&
				$this->data->id !== '0' && 
				$this->data->id !== 0)
			{
				$this->load->model('FileModel', 'File');
				$data = $this->File->getById((int)$this->data->id);

				if (!$data)
				{
					$this->data->id = '0';
				}
				else
				{
					$this->data = $data;
				}
			}

			$this->data->err = 'NULL';
		}
		catch (Exception $e) 
		{
			$this->data->err = $e->getMessage();
		}

		View::show($this->viewpath.'/pages/upload', array('data' => $this->data));
	}

	function uploadFile($dir, $id)
	{
		try
		{
			// валидация пользовательского ввода
			$this->data->dir = $dir;
			$this->data->id = $id;

			// загрузка файла
			$this->data->upload_path = BASEURL.'static/images/'.$this->data->dir.'/';
			$config['upload_path'] = BASEPATH.'static/images/'.$this->data->dir.'/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '4096';
			$config['max_width']  = '2048';
			$config['max_height']  = '2048';
			
			var_dump($config['upload_path']);
			
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload())
			{
				throw new Exception(strip_tags(trim($this->upload->display_errors())));
			} 
			else
			{
				// сохраняем файл в базе
				$this->data->err = '';
				$this->update($this->upload->data());

				Func::redirect(BASEURL.'main/getFile/'.$this->data->dir.'/'.$this->data->id);
			}
		}
		catch (Exception $e) 
		{
			$this->data->err = $e->getMessage();
			View::show($this->viewpath.'/pages/upload', array('data' => $this->data));
		}
	}

    private function update($f)
    {
		$this->load->model('FileModel', 'File');

		// удаляем старый файл
        if ($this->data->id)
        {
			$data = $this->File->getById($this->data->id);
          
			if (!$data)
			{
				$this->data->id = 0;
			}
			else
			{
				$old_file = str_replace($f['file_name'], '', $f['full_path']).$data->name;
				@unlink($old_file);
			}
        }

		// сохраняем результат
		$this->data->name = $f['file_name'];
        $this->data->fullpath = $this->data->upload_path.$f['file_name'];
        $this->data->ext = $f['file_ext'];
        $this->data->size = $f['file_size'];
        $this->data->width = $f['image_width'];
        $this->data->height = $f['image_height'];

    	$data = $this->File->addFile($this->data);

		if (!$data)
		{
			throw new Exception('Файл не загружен. Попробуйте еще раз.');
		}
		
		$this->data->id = $data->id;
	}
	
	public function showCurrencyCalc()
	{
		$this->load->library('cbr');
		
		$curencies	= $this->cbr->getAllCurrencyInfo(); //не смотря на название, функция получает полную информацию по волютам
		
		$currencies = array();
		foreach ($curencies as $currency)
		{
			$currency->Vcurs = str_replace(",",".",$currency->Vcurs) * CBR::addpercent;
			$currencies[(string) $currency->VchCode]	= $currency;
		}
		
		View::show($this->viewpath.'/pages/showCurrencyCalc',array(
			'currencies'	=> $currencies,
		));
	}
	
	public function showFAQ()
	{
		if (!isset($this->Faq))
			$this->load->model('FaqModel', 'Faq');
			
		$faq	= $this->Faq->select(null, 10);
		if (!$faq) $faq = array();

		View::showChild($this->viewpath.'/pages/faq', array('faq'=> $faq));
	}

}

/* End of file main.php */
/* Location: ./system/application/controllers/main.php */
