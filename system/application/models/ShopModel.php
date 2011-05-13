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
class ShopModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'shops';			// table name
	protected	$PK					= 'shop_id';		// primary key name	
	
	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->shop_id				='';
    	$this->properties->shop_name				='';
    	$this->properties->shop_country				='';
    	$this->properties->shop_scategory				='';
    	$this->properties->shop_desc				='';
    	$this->properties->shop_user				='';

    	
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
	
	public function addShop($shop_obj){
		
		$props = $this->getPropertyList();
		
		if ($this->select(array('shop_name' => $shop_obj->shop_name))){
			throw new Exception('Такой магазин уже есть', -1);
		}
		
		foreach ($props as $prop){
			if (isset($shop_obj->$prop)){
				$this->_set($prop, $shop_obj->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if ($new_id){
			return $this->getInfo(array($new_id));
		}
		
		return false;
	}
	
	public function getShopsByCategory($category_id, $order=null, $limit='')
	{
		return $this->db->query('
			SELECT SQL_CALC_FOUND_ROWS `'.$this->table.'`.*, COUNT(`scomment_id`) AS `count`
			FROM `'.$this->table.'`
				LEFT JOIN `scomments` ON `scomments`.`scomment_shop` = `'.$this->table.'`.`shop_id`
				'.(($order && $order['addon']) ? $order['addon'] : '').'
			WHERE `'.$this->table.'`.`shop_scategory` = '.intval($category_id).'
			GROUP BY `shop_id`
			'.(($order) ? 'ORDER BY `'.$order['by'].'` '.$order['order'] : '').'
			'.$limit.'
		')->result();
	}
	
}
?>