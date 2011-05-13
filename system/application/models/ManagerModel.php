<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author omni
 * 
 * моделька для магазина
 * 1. в модели не делаем проверок на валидность i\o это должно делаться в контролере
 * 2. допустимы только ошибки уровня БД
 * 3. разрешатся передавать списки параметров функции, только в случает отсутствия публичного 
 * атрибута соответствующего объекта
 *
 */
class ManagerModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'managers';			// table name
	protected	$PK					= 'manager_user';		// primary key name	
	
	private $statuses = array(
		1	=> 'В работе',
		2	=> 'Приостановлен'
	);
	
	public function getStatuses() {
		return $this->statuses;
	}
	
	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->manager_user				='';
    	$this->properties->manager_country			='';
    	$this->properties->manager_max_clients		='';
    	$this->properties->manager_name				='';
    	$this->properties->manager_surname			='';
    	$this->properties->manager_otc				='';
    	$this->properties->manager_addres			='';
    	$this->properties->manager_phone			='';
    	$this->properties->manager_status			='';
    	$this->properties->user_login				='';
    	$this->properties->country_name				='';
    	$this->properties->last_client_added		='';
		$this->properties->manager_credit           =0;
   	
		parent::__construct();
    }
    
   /**
     * @see IModel
     * Инкапсуляция
     *
     * @return string
     */
	public function getPK()
	{
		return $this->PK;
	}
	
    /**
     * @see IModel
     * Инкапсуляция
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
	public function addManagerData($user_id, $manager_obj){
		
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($manager_obj->$prop)){
				$this->_set($prop, $manager_obj->$prop);
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
	
	public function getManagersData() {
		return $this->db->query('
			SELECT `'.$this->table.'`.*, `users`.`user_login`, COUNT(c2m.manager_id) AS `clients_count`
			FROM `'.$this->table.'`
				LEFT JOIN `c2m` ON `c2m`.`manager_id` = `'.$this->table.'`.`manager_user`
				INNER JOIN `users` ON `users`.`user_id` = `'.$this->table.'`.`manager_user`				
			WHERE `users`.`user_deleted` = 0
			GROUP BY `'.$this->table.'`.`manager_user`
		')->result();
	}
	
	public function getById($id){
		$r = $this->select(array(
			$this->getPK()	=> (int) $id,
		));					
		
		return ((count($r==1) &&  $r) ? array_shift($r) : false);
	}
	
	public function updateManager($manager_obj) {
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($manager_obj->$prop)){
				$this->_set($prop, $manager_obj->$prop);
			}
		}
		
		if ($this->save(true))
			return $this->getInfo(array($manager_obj->manager_user));
		else
			return false;
	}
	
	/**
	 * Список партенров с незаполненными до максимума клиентами по странам
	 */
	public function getIncompleteManagers() {
		$managers = array();
	 
		$rows = $this->db->query('
			SELECT *
			FROM 	(
						SELECT `'.$this->table.'`.`manager_user`, `'.$this->table.'`.`manager_country`, `'.$this->table.'`.`manager_max_clients`, COUNT(c2m.manager_id) AS `clients_count`, `'.$this->table.'`.`last_client_added`
						FROM `'.$this->table.'`
							LEFT JOIN `c2m` ON `c2m`.`manager_id` = `'.$this->table.'`.`manager_user`
						WHERE `'.$this->table.'`.`manager_status` = 1
						GROUP BY `'.$this->table.'`.`manager_user`
					) AS `managers_data`
			WHERE manager_max_clients > clients_count
		')->result();
		
		foreach ($rows as $row) {
			if (!array_key_exists($row->manager_country, $managers) || $managers[$row->manager_country]->last_client_added > $row->last_client_added)
				$managers[$row->manager_country] = $row;
		}
		return $managers;
	}
	
	public function getCompleteManagers($ids) {
		$managers = array();
		$rows = $this->db->query('
			SELECT `'.$this->table.'`.`manager_user`, 
				`'.$this->table.'`.`manager_country`, 
				`'.$this->table.'`.`manager_max_clients`, 
				COUNT(c2m.manager_id) AS `clients_count`, 
				`'.$this->table.'`.`last_client_added`
				FROM `'.$this->table.'`
					LEFT JOIN `c2m` ON `c2m`.`manager_id` = `'.$this->table.'`.`manager_user`
				WHERE `'.$this->table.'`.`manager_status` = 1 AND `'.$this->table.'`.`manager_country` IN ('.implode(', ', $ids).')
				GROUP BY `'.$this->table.'`.`manager_user`
		')->result();
		
		foreach ($rows as $row) 
		{
			if (!array_key_exists($row->manager_country, $managers) || $managers[$row->manager_country]->last_client_added > $row->last_client_added)
			{
				$managers[$row->manager_country] = $row;
			}
		}
		
		return $managers;
	}


	/**
	 * Список партенров
	 */
	public function getManagers() {
		$managers =$count  = array();
		$rows_count = $this->db->query('SELECT * , count( manager_id ) AS count FROM `c2m` GROUP BY manager_id')->result();
		
		foreach($rows_count as $r){
			$count[$r->manager_id]=$r->count;
		}
		
		$rows = $this->db->query('
			SELECT `'.$this->table.'`.`manager_user`, `'.$this->table.'`.`manager_country`, `'.$this->table.'`.`manager_max_clients`, `'.$this->table.'`.`last_client_added`
						FROM `'.$this->table.'`
		 				WHERE `'.$this->table.'`.`manager_status` = 1
						GROUP BY `'.$this->table.'`.`manager_user`		
		')->result();
		
		foreach ($rows as $row) {
			if (!array_key_exists($row->manager_country, $managers) || $managers[$row->manager_country]->last_client_added > $row->last_client_added){
				$managers['all'][$row->manager_country] = $row;
				
				if(isset($count[$row->manager_user]) && $count[$row->manager_user]>$row->manager_max_clients){
					$managers['addons'][$row->manager_country] = $row;
				}
			}
			
		}
		return $managers;
	}
	
	public function getActiveManagers() {		
		$result = $this->select(array('manager_status' => '1'));
		
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	public function getClientManagersById($client_id)
	{
		$result = $this->db->query('
			SELECT DISTINCT `'.$this->table.'`.*, `users`.`user_login`, `countries`.`country_name`
			FROM `'.$this->table.'`
				LEFT JOIN `c2m` ON `c2m`.`manager_id` = `'.$this->table.'`.`manager_user`
				INNER JOIN `users` ON `users`.`user_id` = `'.$this->table.'`.`manager_user`				
				INNER JOIN `countries` ON `countries`.`country_id` = `'.$this->table.'`.`manager_country`
				INNER JOIN `pricelist` pl ON `pl`.`pricelist_country_from` = `managers`.`manager_country` and `pl`.`pricelist_country_to` = (
					SELECT client_country FROM clients WHERE client_user='.$client_id.'
				)
			WHERE `users`.`user_deleted` = 0
				AND `c2m`.`client_id` = \''.$client_id.'\'
		')->result();
		
		return ($result) ? $result : false;		
	}
	
	public function fixMaxClientsCount($manager_id)
	{
		$manager = $this->db->query('
			SELECT `'.$this->table.'`.*, COUNT(c2m.manager_id) AS `clients_count`
			FROM `'.$this->table.'`
				LEFT JOIN `c2m` ON `c2m`.`manager_id` = `'.$this->table.'`.`manager_user`
				INNER JOIN `users` ON `users`.`user_id` = `'.$this->table.'`.`manager_user`				
			WHERE `users`.`user_deleted` = 0 AND `users`.`user_id` = \''.$manager_id.'\'
			GROUP BY `'.$this->table.'`.`manager_user`
		')->result();
		
		if ($manager && count($manager) == 1)
		{
			$manager = $manager[0];
			if ($manager->clients_count > $manager->manager_max_clients)
			{
				$manager->manager_max_clients = $manager->clients_count;
				unset($manager->clients_count);
				$manager = $this->updateManager($manager);
			}
		
			return $manager;
		}
		
		return false;		
	}

}
?>