<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author tua
 * 
 * ������ ��� ������� ��������
 * 1. � ������ �� ������ �������� �� ���������� i\o ��� ������ �������� � ����������
 * 2. ��������� ������ ������ ������ ��
 * 3. ���������� ���������� ������ ���������� �������, ������ � ������� ���������� ���������� 
 * �������� ���������������� �������
 *
 */
class DeliveryModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'deliveries';		// table name
	protected	$PK					= 'delivery_id';		// primary key name	
	
	/**
	 * �����������
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->delivery_id			='';
    	$this->properties->delivery_name		='';
    	$this->properties->delivery_time		='';

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
	public function saveDelivery($delivery){
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
	 * Get deliveries by origin and destination countries
	 *
	 * @return array
	 */
	public function getAvailableDeliveries($package_country_from, $package_country_to) {
		$result = $this->db->query('
			SELECT DISTINCT `deliveries`.*
			FROM `deliveries`
			INNER JOIN `pricelist` on `deliveries`.`delivery_id` = `pricelist`.`pricelist_delivery`
			WHERE `pricelist`.`pricelist_country_from` = '.$package_country_from.'
				AND `pricelist`.`pricelist_country_to` = '.$package_country_to.'
			ORDER BY `deliveries`.`delivery_name`'
		)->result();

		return $result ? $result : false;
	}
}
?>