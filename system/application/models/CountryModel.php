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
class CountryModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'countries';			// table name
	protected	$PK					= 'country_id';		// primary key name	
	
	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->country_id			='';
    	$this->properties->country_name			='';
    	
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
	 * Получаем список стран с существующими партнерами для текущего пользователя
	 */
	public function getClientAvailableCountries($client_id) {
		return $this->db->query('
			SELECT `'.$this->table.'`.*, `managers`.`manager_user`
			FROM `'.$this->table.'`
				INNER JOIN `managers` ON `managers`.`manager_country` = `'.$this->table.'`.`country_id`
				INNER JOIN `c2m` ON `c2m`.`manager_id` = `managers`.`manager_user` AND `c2m`.`client_id` = '.intval($client_id).'
			ORDER BY `country_name`
		')->result();
	}
	
	/**
	 * Get country by id
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
	 * Get countries from pricelist
	 *
	 * @return array
	 */
	public function getCountriesWithDelivery() 
	{
		$result = $this->db->query('
			SELECT DISTINCT `countries`.*, 
				p1.`pricelist_country_from` AS is_from,
				p2.`pricelist_country_to` AS is_to
			FROM `countries` 
			LEFT JOIN `pricelist` AS p1 on p1.`pricelist_country_from` = `countries`.`country_id`
			LEFT JOIN `pricelist` AS p2 on p2.`pricelist_country_to` = `countries`.`country_id`
			ORDER BY `countries`.`country_name`'
		)->result();
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	public function getCountriesFromDelivery() 
	{
		$result = $this->db->query('
			SELECT DISTINCT `countries`.* 
			FROM `countries` 
			INNER JOIN `pricelist` AS p1 on p1.`pricelist_country_from` = `countries`.`country_id`
			ORDER BY `countries`.`country_name`'
		)->result();
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}

	/**
	 * Get countries from pricelist
	 *
	 * @return array
	 */
	public function getDeliveryCountries($countryFrom) 
	{
		if (!isset($countryFrom) ||
			!is_numeric($countryFrom))
		{
			return false;
		}
		
		// выборка
		$result = $this->db->query('
			SELECT DISTINCT `countries`.*
			FROM `countries`
			INNER JOIN `pricelist` on `pricelist`.`pricelist_country_to` = `countries`.`country_id`
			WHERE `pricelist`.`pricelist_country_from` = "'.$countryFrom.'"
			ORDER BY `countries`.`country_name`'
		)->result();

		return ((count($result) > 0 &&  $result) ? $result : false);		
	}

	/**
	 * Get countries from pricelist
	 *
	 * @return array
	 */
	public function getToCountries() 
	{
		$result = $this->db->query('
			SELECT DISTINCT `countries`.*
			FROM `countries`
			INNER JOIN `pricelist` on `pricelist`.`pricelist_country_to` = `countries`.`country_id`
			ORDER BY `countries`.`country_name`'
		)->result();

		return ((count($result) > 0 &&  $result) ? $result : false);		
	}

	/**
	 * Get countries from pricelist
	 *
	 * @return array
	 */
	public function getToCountriesFrom($country) 
	{
		$result = $this->db->query('
			SELECT DISTINCT `countries`.*
			FROM `countries`
			INNER JOIN `pricelist` on `pricelist`.`pricelist_country_to` = `countries`.`country_id`
			WHERE `pricelist`.`pricelist_country_from`='.$country.'
			ORDER BY `countries`.`country_name`'
		)->result();

		return ((count($result) > 0 &&  $result) ? $result : false);		
	}

	
	/**
	 * Get countries from pricelist
	 *
	 * @return array
	 */
	public function getFromCountries() 
	{
		$result = $this->db->query('
			SELECT DISTINCT `countries`.*
			FROM `countries`
			INNER JOIN `pricelist` on `pricelist`.`pricelist_country_from` = `countries`.`country_id`
			ORDER BY `countries`.`country_name`'
		)->result();

		return ((count($result) > 0 &&  $result) ? $result : false);		
	}

	/**
	 * Добавление/изменение страны
	 * Выкидывает исключения на некорректные данные
	 * 
	 * @param (object) 	- $country
	 * @return (mixed)	- объект country или false в случае ошибки записи в базу
	 */
	public function saveCountry($country){
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($country->$prop)){
				$this->_set($prop, $country->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if (!$new_id) return false;
		
		return $this->getInfo(array($new_id));
	}
	

}
?>