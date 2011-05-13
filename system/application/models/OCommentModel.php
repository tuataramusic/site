<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author tua
 * 
 * модель дл€ комментариев к заказу
 * 1. в модели не делаем проверок на валидность i\o это должно делатьс€ в контролере
 * 2. допустимы только ошибки уровн€ Ѕƒ
 * 3. разрешатс€ передавать списки параметров функции, только в случает отсутстви€ публичного 
 * атрибута соответствующего объекта
 *
 */
class OCommentModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'ocomments';		// table name
	protected	$PK					= 'ocomment_id';	// primary key name	
	
	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->ocomment_id				='';
    	$this->properties->ocomment_user			='';
    	$this->properties->ocomment_order			='';
    	$this->properties->ocomment_comment			='';
    	$this->properties->order_manager_login	='';
		
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
	
	public function addComment($com_obj){
		
		$props = $this->getPropertyList();
				
		foreach ($props as $prop){
			if (isset($com_obj->$prop)){
				$this->_set($prop, $com_obj->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if ($new_id){
			return $this->getInfo(array($new_id));
		}
		
		return false;
	}
	
	
	public function delComment($comment_id){
		
		$this->_set($this->getPK(), $comment_id);
		
		return $this->delete();
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
	 * Get order's comments
	 *
	 * @return array
	 */
	public function getCommentsByOrderId($id){
		$result = $this->db->query('
			SELECT `ocomments`.*, `users`.`user_login`  as `order_manager_login`
			FROM `ocomments`
			INNER JOIN `orders` on `ocomments`.`ocomment_order` = `orders`.`order_id`
			INNER JOIN `users` on `users`.`user_id` = `orders`.`order_manager`
			WHERE `ocomments`.`ocomment_order` = '.intval($id).'
		')->result();

		return (isset($result)) ? $result : false;
	}
}
?>