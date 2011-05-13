<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author tua
 * 
 * модель для декларации посылки
 * 1. в модели не делаем проверок на валидность i\o это должно делаться в контролере
 * 2. допустимы только ошибки уровня БД
 * 3. разрешатся передавать списки параметров функции, только в случает отсутствия публичного 
 * атрибута соответствующего объекта
 *
 */
class DeclarationModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'declarations';	// table name
	protected	$PK					= 'declaration_id';	// primary key name	
	
	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->declaration_id				='';
    	$this->properties->declaration_package			='';
    	$this->properties->declaration_item				='';
    	$this->properties->declaration_amount			='';
    	$this->properties->declaration_cost				='';

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
	
	public function saveDeclaration($com_obj){
		
		$props = $this->getPropertyList();
				
		foreach ($props as $prop){
			if (isset($com_obj->$prop)){
				$this->_set($prop, $com_obj->$prop);
			}
		}
		
		$new_id = $this->save();
		
		if ($new_id){
			return $this->getInfo(array($new_id));
		}
		
		return false;
	}
	
	/**
	 * Get comment by id
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
	 * Get package's declarations
	 *
	 * @return array
	 */
	public function getDeclarationsByPackageId($id){
		
		$result = $this->select(array('declaration_package' => $id));
		
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	/**
	 * Возвращает товар декларации, если он есть у партнера
	 *
	 * @return array
	 */
	public function getManagerDeclarationById($declaration_id, $manager_id){
		$row = $this->db->query('
			SELECT `declarations`.*
			FROM `declarations`
			INNER JOIN `packages` on `packages`.`package_id` = `declarations`.`declaration_package`
			WHERE `declarations`.`declaration_id` = '.intval($declaration_id).' AND `packages`.`package_manager` = '.intval($manager_id).'
		')->result();

		return ((count($row==1) &&  $row) ? array_shift($row) : false);		
	}
}
?>