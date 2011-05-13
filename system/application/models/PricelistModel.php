<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author tua
 * 
 * модель для прайслиста
 * 1. в модели не делаем проверок на валидность i\o это должно делаться в контролере
 * 2. допустимы только ошибки уровня БД
 * 3. разрешатся передавать списки параметров функции, только в случает отсутствия публичного 
 * атрибута соответствующего объекта
 *
 */
class PricelistModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'pricelist';		// table name
	protected	$PK					= 'pricelist_id';	// primary key name	
	
	/**
	 * конструктор
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->pricelist_id				='';
    	$this->properties->pricelist_weight			='';
    	$this->properties->pricelist_price			='';
    	$this->properties->pricelist_delivery		='';
    	$this->properties->pricelist_country_from	='';
    	$this->properties->pricelist_country_to		='';
    	$this->properties->delivery_name			='';
    	$this->properties->delivery_time			='';

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
	 * Get pricelist by id
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
	 * Get price by weight
	 *
	 * @return array
	 */
	public function getPriceByWeight($weight, $countryFrom, $countryTo, $delivery)
	{
		$result = $this->db->query('
			SELECT MIN(`pricelist`.`pricelist_weight`), `pricelist`.`pricelist_price`
			FROM `pricelist`
			WHERE `pricelist`.`pricelist_weight` >= '.floatval($weight).'
				AND `pricelist`.`pricelist_country_from` = '.intval($countryFrom).'
				AND `pricelist`.`pricelist_country_to` = '.intval($countryTo).'
				AND `pricelist`.`pricelist_delivery` = '.intval($delivery).'
			GROUP BY `pricelist`.`pricelist_weight`
		')->result();

		return ((count($result == 1) &&  $result) ? $result[0]->pricelist_price : false);
	}
	
	/**
	 * Get pricelist by weight
	 *
	 * @return array
	 */
	public function getPricesByWeight($weight, $countryFrom, $countryTo)
	{
		$result = $this->db->query('
			SELECT `deliveries`.`delivery_name`, `pricelist`.`pricelist_price`  AS `delivery_price`
			FROM (
				SELECT MIN(`pricelist`.`pricelist_weight`) as `pricelist_weight`, `pricelist`.`pricelist_delivery`
				FROM `pricelist`
				WHERE `pricelist`.`pricelist_weight` >= '.floatval($weight).'
					AND `pricelist`.`pricelist_country_from` = '.intval($countryFrom).'
					AND `pricelist`.`pricelist_country_to` = '.intval($countryTo).'
				GROUP BY `pricelist`.`pricelist_delivery`) AS q 
			INNER JOIN `pricelist`
				ON q.`pricelist_delivery` = `pricelist`.`pricelist_delivery` 
				AND q.`pricelist_weight` = `pricelist`.`pricelist_weight`
			INNER JOIN `deliveries` 
				ON `deliveries`.`delivery_id` = `pricelist`.`pricelist_delivery`
			ORDER BY `deliveries`.`delivery_name`
		')->result();

		return ((count($result > 0) &&  $result) ? $result : false);
		
		/*SELECT MIN(`pricelist`.`pricelist_weight`), `pricelist`.`pricelist_price` AS `delivery_price`, `deliveries`.`delivery_name`
			FROM `pricelist`
			INNER JOIN `deliveries` ON `deliveries`.`delivery_id` = `pricelist`.`pricelist_delivery`
			WHERE `pricelist`.`pricelist_weight` >= '.floatval($weight).'
				AND `pricelist`.`pricelist_country_from` = '.intval($countryFrom).'
				AND `pricelist`.`pricelist_country_to` = '.intval($countryTo).'
			GROUP BY `pricelist`.`pricelist_delivery`
			ORDER BY `deliveries`.`delivery_name`*/
	}
	
	/**
	 * Get filtered pricelist
	 *
	 * @return array
	 */
	public function getPricelist($filter) {
		$countryFromFilter = '';
		$countryToFilter = '';
		$deliveryFilter = '';
		
		// обработка фильтра
		if (isset($filter))
		{
			if (is_numeric($filter->pricelist_country_from))
			{
				$countryFromFilter = ' AND `pricelist`.`pricelist_country_from` = '.$filter->pricelist_country_from;
			}
			
			if (is_numeric($filter->pricelist_country_to))
			{
				$countryToFilter = ' AND `pricelist`.`pricelist_country_to` = '.$filter->pricelist_country_to;
			}
			
			if (is_numeric($filter->pricelist_delivery))
			{
				$deliveryFilter = ' AND `pricelist`.`pricelist_delivery` = '.$filter->pricelist_delivery;
			}
		}
		
		// выборка
		$result = $this->db->query('
			SELECT `pricelist`.*, `deliveries`.*
			FROM `pricelist`
			INNER JOIN `deliveries` on `deliveries`.`delivery_id` = `pricelist`.`pricelist_delivery`
			WHERE 1 = 1'
			.$countryFromFilter
			.$countryToFilter
			.$deliveryFilter
			.' ORDER BY `deliveries`.`delivery_id`, `pricelist`.`pricelist_weight`'
		)->result();

		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	/**
	 * Изменение тарифа
	 * 
	 * @param (object) 	- $pricelist
	 * @return (mixed)	- объект pricelist или false в случае ошибки записи в базу
	 */
	public function savePricelist($pricelist){
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($pricelist->$prop)){
				$this->_set($prop, $pricelist->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if (!$new_id) return false;
		
		return $this->getInfo(array($new_id));
	}

	/**
	 * Добавление тарифа
	 * 
	 * @param (object) 	- $pricelist
	 * @return (mixed)	- объект pricelist или false в случае ошибки записи в базу
	 */
	public function addPricelist($pricelist){
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($pricelist->$prop)){
				$this->_set($prop, $pricelist->$prop);
			}
		}
		
		$new_id = $this->save();
		
		if (!$new_id) return false;
		
		return $this->getInfo(array($new_id));
	}

	/**
	 * Удаление тарифа для пары стран
	 * 
	 * @param (object) 	- $pricelist
	 * @return (mixed)	- объект pricelist или false в случае ошибки записи в базу
	 */
	public function deletePricelistCountries($from, $to) 
	{
		return $this->db->delete($this->table, array('pricelist_country_from' => intval($from), 'pricelist_country_to' => intval($to)));
	}
}
?>