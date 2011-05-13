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
class ConfigModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= '_config';			// table name
	protected	$PK					= 'config_name';		// primary key name	
	
	/**
	 * �����������
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->config_name				='';
    	$this->properties->config_value				='';

    	
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
	
	
	/**
	 * Get sorted config
	 *
	 */
	public function getConfig()
	{
		$confObj = $this->getList();
		if ($confObj){
			$conf = array();
			$pk = $this->PK;
			foreach ($confObj as $item){
				$conf[$item->$pk] = $item;
			}
			return $conf;
		}
		
		return false;
	}
	
	public function setConfig($key,$value){
		$this->_set('config_name', $key);
		$this->_set('config_value', $value);
		return $this->save(true);
	}
	
}
?>