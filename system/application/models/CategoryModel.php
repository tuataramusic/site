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
class CategoryModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'scategories';			// table name
	protected	$PK					= 'scategory_id';		// primary key name	
	
	/**
	 * конструктор
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