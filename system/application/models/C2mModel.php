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
class C2mModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'c2m';			// table name
	protected	$PK					= 'client_id';		// primary key name	
	
	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->client_id				='';
    	$this->properties->manager_id				='';

    	
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
	
	public function addRelation($relation_obj){
		
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($relation_obj->$prop)){
				$this->_set($prop, $relation_obj->$prop);
			}
		}
		
		$new_id = $this->insert();
		
		if ($new_id){
			return $this->getInfo(array($new_id));
		}
		
		return false;
	}
	
	public function deletePartnerRelations($uid) {
		return $this->db->delete($this->table, array('manager_id' => intval($uid)));
	}
	
	public function deleteClientRelations($uid) {
		return $this->db->delete($this->table, array('client_id' => intval($uid)));
	}
	
	public function getPartnerClientsCount($uid) {
		$row = $this->db->query('
			SELECT COUNT(*) AS `clients_count`
			FROM `'.$this->table.'`
			WHERE `manager_id` = '.intval($uid).'
		')->result();
		return $row[0]->clients_count;
	}
	
	public function changePartner($last_manager, $new_manager, $limit) {
		$this->db->where('manager_id', $last_manager);
		$this->db->limit($limit);
		$this->db->update($this->table, array('manager_id' => $new_manager));
	}
	
	public function moveClient($client_id, $manager_id) 
	{
		//находим старого партнера
		$result = $this->db->query('
			SELECT `'.$this->table.'`.*, `countries`.`country_name`, `countries`.`country_id`, `managers`.`manager_name`
			FROM `'.$this->table.'`
			INNER JOIN `managers` ON `managers`.`manager_user` = `'.$this->table.'`.`manager_id`
			INNER JOIN `countries` ON `countries`.`country_id` = `managers`.`manager_country`
			INNER JOIN `managers` AS `new_manager` ON `new_manager`.`manager_country` = `countries`.`country_id`
			WHERE `client_id` = '.intval($client_id).'
				AND `new_manager`.`manager_user` = '.intval($manager_id).'
		')->result();
		
		if (!$result || count($result) != 1)
		{
			return false;
		}
		
		// измен€ем св€зь
		$link = $result[0];
		
		$this->db->where('manager_id', $link->manager_id);
		$this->db->where('client_id', $link->client_id);
		$this->db->limit(1);

		return $this->db->update($this->table, array('manager_id' => $manager_id));
	}
	
	/**
	 * Get payed packages by manager id
	 *
	 * @return array
	 */
	public function getC2M($client_id, $manager_id) {		
		$result = $this->select(array('client_id' => $client_id, 'manager_id' => $manager_id));
		
		return ((count($result == 1) &&  $result) ? array_shift($result) : false);
	}
}
?>