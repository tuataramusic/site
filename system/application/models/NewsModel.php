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
class NewsModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'news';			// table name
	protected	$PK					= 'news_id';		// primary key name	
	
	/**
	 * �����������
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->news_id				='';
    	$this->properties->news_title			='';
    	$this->properties->news_body			='';
    	$this->properties->news_addtime			='';
    	
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
	
}
?>