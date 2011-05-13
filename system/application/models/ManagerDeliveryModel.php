<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author tua
 * 
 * ������ ��� �������� �������� ���������
 * 1. � ������ �� ������ �������� �� ���������� i\o ��� ������ �������� � ����������
 * 2. ��������� ������ ������ ������ ��
 * 3. ���������� ���������� ������ ���������� �������, ������ � ������� ���������� ���������� 
 * �������� ���������������� �������
 *
 */
class ManagerDeliveryModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'manager_delivery';		// table name
	protected	$PK					= 'manager_delivery_id';		// primary key name	
	
	/**
	 * �����������
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->manager_delivery_id			='';
    	$this->properties->manager_id		='';
    	$this->properties->delivery_id		='';
    	$this->properties->checked		='';

        parent::__construct();
    }
    
   /**
     * @see IModel
     * ������������
     *
     * @return string
     */
	public function getPK()
	{
		return $this->PK;
	}
	
    /**
     * @see IModel
     * ������������
     *
     * @return string
     */	
	public function getTable()
	{
		return $this->table;
	}     
    
    /**
     * Get delivery list
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
	 * Get delivery by id
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
	 * ����������/��������� ��������
	 * 
	 * @param (object) 	- $delivery
	 * @return (mixed)	- ������ delivery ��� false � ������ ������ ������ � ����
	 */
	public function saveManagerDelivery($delivery){
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($delivery->$prop)){
				$this->_set($prop, $delivery->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if (!$new_id) return false;
		
		return $this->getInfo(array($new_id));
	}
	
	/**
	 * Get manager's deliveries
	 *
	 * @return array
	 */
	public function getByManagerId($manager_id) {
		$result = $this->db->query('
			SELECT DISTINCT `deliveries`.*, IF(`manager_delivery`.`delivery_id` IS NULL, \'\', \'checked="checked"\') as checked
			FROM `deliveries`
			LEFT JOIN `manager_delivery` 
				ON `deliveries`.`delivery_id` = `manager_delivery`.`delivery_id`
				AND `manager_delivery`.`manager_id` = '.$manager_id.'
			ORDER BY `deliveries`.`delivery_name`'
		)->result();

		return $result ? $result : false;	
	}

	public function clearManagerDelivery($uid) {
		return $this->db->delete($this->table, array('manager_id' => intval($uid)));
	}

	public function getDeliveries($id){
		$result = $this->db->query("
			SELECT DISTINCT (
			delivery_id
			) AS id
			FROM `pricelist`
			LEFT JOIN deliveries ON pricelist.pricelist_delivery = deliveries.delivery_id
			WHERE `pricelist_country_from` =".$id)
		->result();

		return $result ? $result : false;	
	}
	
}
?>