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
class FaqModel extends BaseModel implements IModel{

	protected 	$properties			= null;			// array of properties
	protected	$table				= 'faq';		// table name
	protected	$PK					= 'faq_id';		// primary key name	
	
	/**
	 * �����������
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->faq_id					='';
    	$this->properties->faq_question				='';
    	$this->properties->faq_answer				='';

    	
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