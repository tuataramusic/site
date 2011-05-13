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
class OdetailModel extends BaseModel implements IModel{

	protected  $properties			= null;				// array of properties
	protected  $table				= 'odetails';			// table name
	protected  $PK					= 'odetail_id';		// primary key name	
	private    $_status_desc = array(
					'-------' => '-------', 
					'not_available' => 'Нет в наличии', 
					'not_available_color' => 'Нет данного цвета',
					'not_available_size' => 'Нет данного размера',
					'not_available_count' => 'Нет указанного кол-ва',
					'not_delivered' => 'Не доставлен',
					'available' => 'Есть в наличии'
					);

	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->odetail_id				='';
    	$this->properties->odetail_client			='';
    	$this->properties->odetail_manager			='';
    	$this->properties->odetail_order			='';
    	$this->properties->odetail_link				='';
    	$this->properties->odetail_shop_name		='';
    	$this->properties->odetail_product_name		='';
    	$this->properties->odetail_product_color	='';
    	$this->properties->odetail_product_size		='';
    	$this->properties->odetail_product_amount	='';
    	$this->properties->odetail_status			='';
		$this->properties->odetail_price			='';
    	$this->properties->odetail_pricedelivery    ='';
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
	
	public function addOdetail($odetail_obj) {
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($odetail_obj->$prop)){
				$this->_set($prop, $odetail_obj->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if ($new_id){
			return $this->getInfo(array($new_id));
		}
		
		return false;
	}
	
	public function getFilteredDetails($filter) {
		
		$where = 1;
		if (count($filter)) {
			$where = '';			
			foreach ($filter as $key=>$val) {
				$where .= "`$key` = '$val' AND ";
			}
			$where = substr($where, 0, strlen($where)-5);
		}
		
		return $this->db->query('
			SELECT `'.$this->table.'`.*, `countries`.`country_name`, `countries`.`country_id`
			FROM `'.$this->table.'`
				INNER JOIN `managers` ON `'.$this->table.'`.`odetail_manager` = `managers`.`manager_user` 
				INNER JOIN `countries` ON `managers`.`manager_country` = `countries`.`country_id`
			WHERE '.$where
		)->result();
	}
	
	public function makeScreenshot($odetail_obj, $x1, $y1, $x2, $y2, $width) {
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$odetail_obj->odetail_client.'/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$odetail_obj->odetail_client.'/', 0777);
		}
		
		exec('wkhtmltoimage-amd64 --width '.$width.'  --crop-x '.$x1.' --crop-y '.$y1.' --crop-w '.($x2-$x1).' --crop-h '.($y2-$y1).' '.escapeshellarg($odetail_obj->odetail_link).' '.$_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$odetail_obj->odetail_client.'/'.$odetail_obj->odetail_id.'.jpg');
		
		var_dump('wkhtmltoimage-amd64 --width '.$width.'  --crop-x '.$x1.' --crop-y '.$y1.' --crop-w '.($x2-$x1).' --crop-h '.($y2-$y1).' '.escapeshellarg($odetail_obj->odetail_link).' '.$_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$odetail_obj->odetail_client.'/'.$odetail_obj->odetail_id.'.jpg');
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$odetail_obj->odetail_client.'/'.$odetail_obj->odetail_id.'.jpg')) {
			throw new Exception('Ошибка создания скриншота',134);
		}
	}
	
	/**
	 * Формируем заказы юзера (ставим им статус proccessing)
	 */
	public function checkoutClientDetails($client_id, $order_id) {		
		return $this->db->update($this->table, array('odetail_order' => $order_id), array('odetail_client' => $client_id, 'odetail_order' => 0));
	}
	
	public function getById($id){
		$r = $this->select(array(
			$this->getPK()	=> (int) $id,
		));					
		
		return ((count($r==1) &&  $r) ? array_shift($r) : false);
	}

	public function getAvailableOrderDetailsStatuses()
	{
		return $this->_status_desc;
	}


	public function getOrderDetailsStatusDescription($detail_status)
	{
		return $this->_status_desc[$detail_status];
	}

	/**
	 * Get order details
	 *
	 * @return array
	 */
	public function getOrderDetails($id){
		
		$result = $this->db->query('
			SELECT `odetails`.*
			FROM `odetails`
			WHERE `odetails`.`odetail_status` <> "deleted"
				AND `odetails`.`odetail_order` = "'.intval($id).'"
		')->result();

		return ((count($result) > 0 &&  $result) ? $result : false);		

		$result = $this->select(array('odetail_order' => $id));
		
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}

/**
	 * Calculate order status
	 *
	 * @return array
	 */
	public function getTotalStatus($id){
		$row = $this->db->query('
			SELECT MAX(`odetails`.`odetail_status`) as `status`
			FROM `odetails`
			WHERE `odetails`.`odetail_order` = '.intval($id).'
			GROUP BY `odetails`.`odetail_order`
		')->result();
		
		if (!$row || count($row) != 1)
		{
			return 'not_available';
		}

		return $row[0]->status;		
	}
	
	public function setStatus($id, $status){
		$this->db->query('
			UPDATE `odetails` 
			SET `odetail_status` = \''.$status.'\'
			WHERE `odetails`.`odetail_id` = '.intval($id).'
		');
		
		return ;
	}
	
	/**
	 * Get new packages by manager id
	 *
	 * @return array
	 */
	public function getClientOdetailById($id, $client_id) {		
		$row = $this->db->query('
			SELECT `odetails`.*
			FROM `odetails`
			INNER JOIN `orders` ON `odetails`.`odetail_order` = `orders`.`order_id`
			WHERE `odetails`.`odetail_id` = '.intval($id).'
				AND `orders`.`order_client` = '.intval($client_id).'
		')->result();
		
		if (!$row || count($row) != 1)
		{
			return false;
		}

		return $row[0];		
	}
	

}
?>