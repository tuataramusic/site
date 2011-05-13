<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author omni
 * 
 * моделька дл€ магазина
 * 1. в модели не делаем проверок на валидность i\o это должно делатьс€ в контролере
 * 2. допустимы только ошибки уровн€ Ѕƒ
 * 3. разрешатс€ передавать списки параметров функции, только в случает отсутстви€ публичного 
 * атрибута соответствующего объекта
 *
 */
class ClientModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'clients';		// table name
	protected	$PK					= 'client_user';	// primary key name	
	
	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->client_user			='';
    	$this->properties->client_name			='';
    	$this->properties->client_otc			='';
    	$this->properties->client_surname		='';
    	$this->properties->client_country		='';
    	$this->properties->client_address		='';
    	$this->properties->client_index			='';
    	$this->properties->client_town			='';
    	$this->properties->client_phone			='';
    	$this->properties->client_country		='';
    	$this->properties->manager_login		='';
    	$this->properties->manager_country		='';
    	$this->properties->user_coints			='';
    	$this->properties->package_count		='';
    	$this->properties->order_count			='';
    	
        parent::__construct();
    }
    
   /**
     * @see IModel
     * »нкапсул€ци€
     *
     * @return string
     */
	public function getPK()
	{
		return $this->PK;
	}
	
	
	
    /**
     * @see IModel
     * »нкапсул€ци€
     *
     * @return string
     */	
	public function getTable()
	{
		return $this->table;
	}     
    
    
    /**
     * Get user list
     *
     */
	public function getList()
	{
		$sql = $this->select();
		return ($sql)?($sql):(false);
	}
	
	
	/**
	 * Get property list
	 *
	 * @return array
	 */
	public function getPropertyList()
	{
		return array_keys((array) $this->properties);
	}	
	
	
	/**
	 * Add client data
	 *
	 * @param	int		$user_id
	 * @param	object	$client_obj
	 * @return	object 
	 */
	public function addClientData($user_id, $client_obj){
		
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($client_obj->$prop)){
				$this->_set($prop, $client_obj->$prop);
			}
		}
		
		$this->_set($this->getPK(), $user_id);
		
		/**
		 * if primary key of table is not AI,
		 * insert_id will return false
		 */
		$this->save(true);
		
		if ($user_id){
			return $this->getInfo(array($user_id));
		}
		
		return false;
	}
	
	/**
	 * Get client by id
	 *
	 * @return array
	 */
	public function getById($id){
		$r = $this->select(array(
			$this->getPK()	=> (int) $id,
		));					
		
		return ((count($r==1) &&  $r) ? array_shift($r) : false);
	}
	
	/**
	 * Get client list by manager id
	 *
	 */
	public function getClientsByManagerId($uid)
	{
		$row = $this->db->query('
			SELECT `clients`.*
			FROM `clients`
			INNER JOIN `c2m` on `clients`.`client_user` = `c2m`.`client_id`
			WHERE `c2m`.`manager_id` = '.intval($uid).'
		')->result();

		return $row;
	}

	public function getClients($filter=null) 
	{
		$managerFilter = '';
		$countryFilter = '';
		$clientIdFilter = '';
		$clientLoginFilter = '';
		$packagePeriodFilter = '';
		$orderPeriodFilter = '';
		
		// обработка фильтра
		if (isset($filter))
		{
			if (is_numeric($filter->manager_user))
			{
				$managerFilter = ' AND `managers`.`manager_user` = \''.$filter->manager_user.'\'';
			}

			if (is_numeric($filter->client_country))
			{
				$countryFilter = ' AND `clients`.`client_country` = \''.$filter->client_country.'\'';
			}

			if ($filter->id_type == 'login')
			{
				$clientLoginFilter = ' AND `users`.`user_login` = \''.$filter->search_client.'\'';				
			}

			if ($filter->id_type == 'client_number')
			{
				$clientIdFilter = ' AND `clients`.`client_user` = \''.$filter->search_client.'\'';				
			}

			if ($filter->period == 'day' ||
				$filter->period == 'week' ||
				$filter->period == 'month')
			{
				$packagePeriodFilter = ' AND TIMESTAMPDIFF('.strtoupper($filter->period).', `packages`.`package_date`, NOW()) < 1';
				$orderPeriodFilter = ' AND TIMESTAMPDIFF('.strtoupper($filter->period).', `orders`.`order_date`, NOW()) < 1';
			}
		}
	
		// выборка
		return $this->db->query('
			SELECT `'.$this->table.'`.*, 
				`users`.`user_login`, 
				`users`.`user_coints`, 
				p.package_count,
				o.order_count
			FROM `'.$this->table.'`
				INNER JOIN `users` ON `users`.`user_id` = `'.$this->table.'`.`client_user`				
				INNER JOIN `c2m` ON `c2m`.`client_id` = `'.$this->table.'`.`client_user`
				INNER JOIN `managers` ON `managers`.`manager_user` = `c2m`.`manager_id`
				INNER JOIN `users` AS users2 ON users2.`user_id` = `managers`.`manager_user`
				LEFT JOIN (SELECT `packages`.`package_client`,
						COUNT(`package_id`) AS package_count
						FROM `packages`
						WHERE `packages`.`package_status` <> \'deleted\''.$packagePeriodFilter.'
						GROUP BY `packages`.`package_client`) AS p
					ON p.`package_client` = `'.$this->table.'`.`client_user`
				LEFT JOIN (SELECT `orders`.`order_client`,
						COUNT(`order_id`) AS order_count
						FROM `orders`
						WHERE `orders`.`order_status` <> \'deleted\''.$orderPeriodFilter.'
						GROUP BY `orders`.`order_client`) AS o
					ON o.`order_client` = `'.$this->table.'`.`client_user`
			WHERE `users`.`user_deleted` = 0 AND users2.`user_deleted` = 0'
				.$countryFilter
				.$managerFilter
				.$clientLoginFilter
				.$clientIdFilter.
			' GROUP BY `users`.`user_id`'
		)->result();
	}
	
	public function getClientsCount() 
	{
		$r = $this->db->query('
			SELECT COUNT(*) AS count
			FROM `'.$this->table.'`
				INNER JOIN `users` ON `users`.`user_id` = `'.$this->table.'`.`client_user`				
			WHERE `users`.`user_deleted` = 0'
		)->result();
		
		return ((count($r==1) &&  $r) ? $r[0]->count : false);
	}
	
	public function getClientById($uid) 
	{
		$r = $this->db->query('
			SELECT `'.$this->table.'`.*
			FROM `'.$this->table.'`
				INNER JOIN `users` ON `users`.`user_id` = `'.$this->table.'`.`client_user`				
			WHERE `users`.`user_deleted` = 0'
		)->result();
		
		return ((count($r==1) &&  $r) ? $r[0] : false);
	}
	
	public function updateClient($user_obj) {
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($user_obj->$prop)){
				$this->_set($prop, $user_obj->$prop);
			}
		}
		
		$new_id = $this->save();
		
		if (!$new_id) return false;
		
		return $this->getInfo(array($new_id));
	}
}
?>