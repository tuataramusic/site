<?
/**
 * @author omni
 * @since 22.02.10
 * @abstract base model
 * !методы базовой модели возвращают объекты
 *
 */
abstract class BaseModel extends Model implements IBaseModel {
	
	protected 	$properties			= null;	// array of properties
	protected	$table				= '';	// table name
	protected	$PK					= null;	// primary key name
//	protected	$PK					= '';	// primary key name
	protected 	$result				= null;
	private 	$strict				= null;
	
	
	/**
	 * consturctor
	 * 
	 */
	public function __construct()
	{
		if (!$this->table || !$this->PK || !$this->properties){
			throw new Exception('Interface error');
			exit;
		}
		
		$this->result		= new stdClass();
		$this->result->e	= 0;
		$this->result->m	= '';
		$this->result->d	= '';
		
		parent::Model();
	}
	
	
	/**
	 * Использовать с осторожностью!
	 *
	 * @param (string)	$property
	 * @return mixed	
	 */
	public function _get($property=null)
	{
		if ($property){
			if (isset($this->properties->$property))
				return $this->properties->$property;
			else
				return false;
		}else{
			return $this->properties;
		}
	}
	
	
	/**
	 * Использовать с осторожностью!
	 *
	 * @param (string)	$property
	 * @return mixed	
	 */
	public function _set($property, $value)
	{
		if (isset($this->properties->$property)){
			$this->properties->$property = $value;
			return true;
		}else
			return false;
	}
	
	
	public function	_load($selfObj){
		if ($selfObj){
			foreach ($selfObj as $prop => $value){
				$this->_set($prop,$value);
			}
		}
	}
	
	
	public function _clear($selfObj){
		if ($selfObj){
			foreach ($selfObj as $prop => $value){
				$this->_set($prop,'');
			}
		}
	}
	
    
    /**
     * @author omni
     * Получить массив всех полей модели
     * 
	 * @return array($fields) ассоциативный массив field_name=>value
     */
    private function setProperties()
    {
		$getAllFields = array();
		foreach ($this->properties as $pname => $pval){
			$getAllFields[$pname]	= $pval;	
		}
		return $getAllFields;
    }
    
  /**
     * @author omni
	 * Выбрать массив записей из БД в соответствии с заданными условиями
	 * 
	 * @param $case	- условие запроса вида array('company_id' => $this->company_id)
	 *
	 * @return array(object) массив объектов (false в случае ошибки)
	 */
	public function select($case = null,$limit = null, $offset = null, $order_by = null)
	{
		$PK = $this->PK;
		if ($order_by){
			$this->db->order_by($order_by); 			
		}
		if ($case){
			$this->query = $this->db->get_where($this->table, $case, $limit, $offset);
		}elseif ($this->properties->$PK){
			$this->query = $this->db->get_where($this->table, array($this->PK => $this->properties->$PK), $limit, $offset);
		}else {
			$this->query = $this->db->get($this->table, $limit, $offset);
		}

		return ($this->query->num_rows ? $this->query->result() : false);
	}
	
	
	
	/**
	 * @author omni
	 * Insert или Update. Приватный метод.
	 *
	 * @param enum $type тип запроса: "insert" или "update"
	 * @param array $fields массив значений. если не указан - все поля
	 * @return boolean
	 */
	private function iou($type)
	{
		$PK = $this->PK;
		$prop  = $this->setProperties();
		foreach ($prop as $field => $value)
		{
			if ($this->strict){// запись нулей и пустых щзначений
				if (!empty($value) || ($value === 0 || $value === '0')){
					$this->db->set($field, $value);	
				}
			}elseif (!empty($value)){
				$this->db->set($field, $value);
			}
		}
		
		switch ($type) {
			case 'insert':
					$result = $this->db->insert($this->table);
					$result = $result ? $this->db->insert_id() : false;
				break;
			case 'update':
					$this->db->where($this->PK, $this->properties->$PK);
					$result = $this->db->update($this->table);
				break;
			default:
					return false;
				break;
		}
		$this->strict = null;
		return $result;
	}
	

	/**
	 * @author omni
	 * Обновить информацию о себе в соответствии с заданными условиями
	 * без предварительной проверки существования.
	 *
	 * @return bool (false в случае ошибки)
	 */
	public function update()
	{
		$result = $this->iou('update');
		return (bool) $result;
	}
	
	/**
	 * Добавить информацию о себе в БД без проверки существования
	 *
	 * @return bool (false в случае ошибки)
	 */
	public function insert()
	{
		$result = $this->iou('insert');
		return $result;
	}

	/**
	 * Обновить информацию о себе в БД, если информации нет - добавить
	 *
	 * @param  $strict_mode = null - режим записи данных, по-умолчанию записываются только не нуловские поля
	 * @return bool (false в случае ошибки)
	 */
	public function save($strict_mode = null)
	{
		$PK = $this->PK;
		$this->strict = $strict_mode;
		$query = $this->db->get_where($this->table, array($this->PK => $this->properties->$PK));
		//return (($this->properties->$PK && $query->num_rows) ? $this->update() : $this->insert());
		return ((!is_null($this->properties->$PK) && $query->num_rows) ? $this->update() : $this->insert());
	}	

	
	/**
	 * @author omni
	 * Удалить свою запись из БД
	 *
	 * @return bool (false в случае ошибки)
	 */
	public function delete($id = null)
	{
		$PK = $this->PK;
		
		if ($id == null){
			$id = $this->properties->$PK;
		}		
		
		if (!empty($id))
		{
			$this->db->where($this->PK, $id);
			$result = $this->db->delete($this->table);
		}
		else
		{
			$result = false;
		}
		return (bool) $result;		
	}
	
    /**
     * Вернуть количество записей в таблице
     *
     * @param bool $active_mode - режим извлечения данных из базы (если $active_mode=true, 
     * то используется активный запрос, инача значения берутся по средствам изпользования функции found_rows,
     * !запрос должен быть с дерективой SQL_CALC_FOUND_ROWS!
     * 
     * @return int
     */
    public function getCountOfRecords($active_mode = null)
    {
    	if (!is_null($active_mode))
    		return (int) $this->db->count_all($this->table);
    	else {
    		$sql = 'select found_rows() found_rows';
    		return (int) array_shift($this->db->query($sql)->result())->found_rows;
    	}
    }	

	/**
	 * Получить информацию об объекте (списке объектов)
	 *
	 * @param array $arr_id		- список ИД юзеров по которым надо получить информацию
	 * @param array $arr_model	- массив моделей, из которых будет браться дополнительная информация
	 */
	final public function getInfo($arr_id = null, array $arr_model = null, array $order = null, $limit = null, $offset = null, $single = true)
	{
		$fr 	= (int) $limit ? 'SQL_CALC_FOUND_ROWS' : '';
		$query	= "Select $fr * from " .$this->table;
		$sTable = $this->table;
		$sPK	= $this->PK;
		
		if (count($arr_model)){
			foreach ($arr_model as $model){
				$table	= $model->getTable();
				$PK		= $model->getPK;
				$query .= " JOIN $table ON $table.$PK=$sTable.$sPK ";
			}
		}
		
		if ($arr_id){
			if (is_array($arr_id) && !empty($arr_id)){
				$query .= ' WHERE '.$this->PK.' IN ('.implode(",",$arr_id).')';
			}else if(is_string($arr_id) || is_numeric($arr_id)){
				$query .= ' WHERE '.$this->PK.' = '.$arr_id;
			}
		}
		if ($order){
			$orders = array();
			$cord	= array('desc','asc');
			$clist	= $this->getPropertyList();
			foreach ($order as $order_by => $order_type){
				if (in_array($order_type,$cord) && in_array($order_by,$clist)){
					$orders[]	= "$order_by $order_type";	
				}
				
			}
			if (count($orders)>0){
				$query	.= " ORDER BY ".implode(",", $orders);
			}
		}
		
		if ((int) $limit && (int) $offset)		{
			$query .= " LIMIT $offset,$limit";
		}elseif ((int) $limit){
			$query .= " LIMIT $limit";
		}
		
		$r = $this->db->query($query)->result();
		
		if (count($r)==1 && $single){
			return array_shift($r);
		}
		
		return $r;
	}    
    
    /**
     * Get filtred and sorted data
     *
     * @param array $search_arr
     * @param array $case_arr		- example: array('ant_L1'	=> 'decline')
     * @param array $order_arr		- example: array('ant_email' => 'desc')
     * @return array of objects		- example: array('ant_email' => 'omni@')
     */
    function getFiltredData(array $case_arr = null, array $search_arr = null, array $order_arr = null)
    {
    	$search	= '';
    	$order	= '';
    	$case	= '';
		$access_arr = (array) $this->properties;    	
		
    	if (!empty($order_arr)){
	    	$order = '';
	    	foreach ($order_arr as $order_key => $order_value){
	    		$order_key = Check::var_str($order_key, 32, 6);
	    		if (($order_value=='asc' || $order_value=='desc') && $order_key){
	    			if (empty($order)){
	    				$order .= " ORDER BY $order_key $order_value ";
	    			}else{
	    				$order .= " , $order_key $order_value ";
	    			}
	    		}
	    	}    		
    	}
    	
    	if (!empty($case_arr)){
    		$case = array();
    		foreach ($case_arr as $case_key => $case_value){
    			$case_value = Check::var_str($case_value, 255, 1);
    			if (array_key_exists($case_key, $access_arr) && $case_value){
    				$case[] = " $case_key = ".(is_numeric($case_value) ? $case_value : "'$case_value' ");
    			}
    		}
    		if (!empty($case)){
    			$case = ' WHERE '.implode(" AND ", $case);
    		}else{
    			$case = '';
    		}
    	}
    	
    	if (!empty($search_arr)){
    		foreach ($search_arr as $search_key => $search_value){
    			$search_value = Check::var_str($search_value, 255, 1);
    			if (array_key_exists($search_key, $access_arr) && $search_value){
	    			if (empty($search)){
	    				$search .= " $search_key LIKE \"%$search_value%\" ";
	    			}else{
	    				$search .= " OR $search_key LIKE \"%$search_value%\" ";
	    			}    				
    			}
    		}
    		if (!empty($case) &&  !empty($search)){
    			$search = " AND ( $search ) ";
    		}elseif(!empty($search)){
    			$search = " WHERE $search ";
    		}
    	}

    	$sql = "SELECT * from ".$this->table." $case $search $order ;";
    	
    	$result = $this->db->query($sql)->result();
    	
		return	(empty($result) ? false : $result );
    }
	
}
?>