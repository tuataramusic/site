<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author omni
 * 
 * �������� ��� ��������
 * 1. � ������ �� ������ �������� �� ���������� i\o ��� ������ �������� � ����������
 * 2. ��������� ������ ������ ������ ��
 * 3. ���������� ���������� ������ ���������� �������, ������ � ������� ���������� ���������� 
 * �������� ���������������� �������
 *
 */
class CategoryModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'scategories';			// table name
	protected	$PK					= 'scategory_id';		// primary key name	
	
	/**
	 * �����������
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->scategory_id				='';
    	$this->properties->scategory_name				='';

    	
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
	
	public function getCategoriesWithShopsNum()
	{
		return $this->db->query('
			SELECT `'.$this->table.'`.*, COUNT(`shop_id`) AS `count`
			FROM `'.$this->table.'`
				LEFT JOIN `shops` ON `shops`.`shop_scategory` = `'.$this->table.'`.`scategory_id`
			GROUP BY `scategory_id`
		')->result();
	}
}
?>