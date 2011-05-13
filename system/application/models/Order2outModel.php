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
class Order2outModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'orders2out';			// table name
	protected	$PK					= 'order2out_id';		// primary key name	
	
	private $statuses = array(
		'processing'		=> 'В обработке',
		'payed'				=> 'Выплачено',
	);
	
	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->order2out_id				='';
    	$this->properties->order2out_user				='';
    	$this->properties->order2out_ammount				='';
    	$this->properties->order2out_tax				='';
    	$this->properties->order2out_time				='';
    	$this->properties->order2out_status				='';
    	$this->properties->order2out_comment				='';
    	$this->properties->comment_for_admin		= '';
    	$this->properties->comment_for_client		= '';

    	
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
	
	public function getById($id){
		$r = $this->select(array(
			$this->getPK()	=> (int) $id,
		));					
		
		return ((count($r==1) &&  $r) ? array_shift($r) : false);
	}
	
	public function getClientsO2oById($id, $client_id)
	{
		$o2o = $this->getById($id);
		
		if ($o2o && $o2o->order2out_user == $client_id)
		{
			return $o2o;
		}
		
		return false;
	}
	
	public function addOrder($order_obj) {
		$props = $this->getPropertyList();
		foreach ($props as $prop){
			if (isset($order_obj->$prop)){
				$this->_set($prop, $order_obj->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if ($new_id){
			return $this->getInfo(array($new_id));
		}
		
		return false;
	}
	
	public function getMaxId() {
		return $this->db->query('
			SELECT MAX(`order2out_id`) AS `max`
			FROM `'.$this->table.'`
		')->result();
	}
	
	public function getUserOrders($user_id) {
		return $this->db->query('
			SELECT *
			FROM `'.$this->table.'`
			WHERE `order2out_user` = '.intval($user_id).'
			ORDER BY `order2out_time` ASC
		')->result();
	}
	
	public function getStatuses() {
		return $this->statuses;
	}
	
	public function getFilteredOrders($filter) {
		
		$where = 1;
		if (count($filter)) {
			$where = '';			
			foreach ($filter as $key=>$val) {
				$where .= "`$key` = '$val' AND ";
			}
			$where = substr($where, 0, strlen($where)-5);
		}
		
		return $this->db->query('
			SELECT `'.$this->table.'`.*, `users`.`user_login`
			FROM `'.$this->table.'`
				INNER JOIN `users` ON `'.$this->table.'`.`order2out_user` = `users`.`user_id` 
			WHERE '.$where.'
			ORDER BY `order2out_time` ASC
		')->result();
	}
	
	public function getOrdersByIds($ids) {
		return $this->db->query('
			SELECT `'.$this->table.'`.*
			FROM `'.$this->table.'`
			WHERE `order2out_id` IN('.implode(', ', $ids).')
		')->result();
	}
}
?>