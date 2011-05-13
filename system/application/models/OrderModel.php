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
class OrderModel extends BaseModel implements IModel{

	protected   $properties			= null;				// array of properties
	protected   $table				= 'orders';			// table name
	protected   $PK					= 'order_id';		// primary key name	
	
    private $_order_status_desc = array(
					'proccessing'   => '��������������', 
					'not_available' => '��� � �������', 
					'not_available_color' => '��� ������� �����',
					'not_available_size' => '��� ������� �������',
					'not_available_count' => '��� ���������� ���-��',
					'not_payed' => '�� �������',
					'payed' => '�������',
					'sended' => '���������'
					);

	public function getOrderStatusDescription($order_status)
    {
		if($order_status != '')
		    return $this->_order_status_desc[$order_status]; 
		return '';
    }
	
	
	public function getAvailableOrderStatuses()
    {
	    return $this->_order_status_desc; 
    }

	/**
	 * �����������
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->order_id					= '';
    	$this->properties->order_client				= '';
    	$this->properties->order_manager			= '';
    	$this->properties->order_weight				= '';
    	$this->properties->order_cost				= '';
    	$this->properties->order_country			= '';
    	$this->properties->order_date				= '';    	
    	$this->properties->order_status				= 'proccessing';
    	$this->properties->order_shop_name			= '';
    	$this->properties->comment_for_manager		= '';
    	$this->properties->comment_for_client		= '';
    	$this->properties->order_address			= '';
    	$this->properties->order_login				= '';
    	$this->properties->order_delivery_cost		= '';
    	$this->properties->package_delivery_cost	= '';
    	$this->properties->order_manager_login		= '';
    	$this->properties->order_manager_country	= '';
    	$this->properties->order_age				= '';
    	$this->properties->order_products_cost		= '';
    	$this->properties->order_comission			= '';
    	$this->properties->order_country_from		= '';
    	$this->properties->order_country_to			= '';
		$this->properties->order_manager_cost		= '';
		$this->properties->order_manager_comission	= '';
		$this->properties->order_payed_to_manager	= '';
		
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
	 * Get order by id
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
	 * Get property list
	 *
	 * @return array
	 */
	public function getPropertyList()
	{
		return array_keys((array) $this->properties);
	}	
	
	public function addOrder($order_obj) {
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($order_obj->$prop)){
				$this->_set($prop, $order_obj->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if ($new_id){
			return $this->getInfo(array($new_id));
		}
		
		return false;
	}
	
	public function getClientOrders($client_id) {
		return $this->db->query('
			SELECT `'.$this->table.'`.*, `countries`.`country_name`
			FROM `'.$this->table.'`
				INNER JOIN `countries` ON `orders`.`order_country` = `countries`.`country_id`
		')->result();
	}
	
	/**
	 * Get open orders by manager id
	 *
	 * @return array
	 */
	public function getOpenOrdersByManagerId($id){
		$result = $this->select(array('order_manager' => $id));
		
		$result = $this->db->query('
			SELECT `'.$this->table.'`.*
			FROM `'.$this->table.'`
			WHERE `'.$this->table.'`.`order_manager` = '.$id.'
				AND `'.$this->table.'`.`order_status` <> "sended"
		')->result();
		
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	/**
	 * Get all open orders
	 *
	 * @return array
	 */
	public function getOrders($filter=null, $orderStatus='open', $clientId=null, $managerId=null) {
		$managerFilter = '';
		$periodFilter = '';
		$idFilter = '';
		$clientIdAccess = '';
		$managerIdAccess = '';
		$statusFilter = '';
		
		// ��������� �������
		if ($orderStatus != 'open')
		{
			$statusFilter = '`orders`.`order_status` = "'.$orderStatus.'"';
		}
		else
		{
			$statusFilter = '`orders`.`order_status` <> "deleted" AND `orders`.`order_status` <> "sended"';		
		}		
		
		// ��������� �������
		if (isset($filter))
		{
			if (is_numeric($filter->manager_user))
			{
				$managerFilter = ' AND `managers`.`manager_user` = '.$filter->manager_user;
			}

			if (is_numeric($filter->search_id))
			{
				if ($filter->id_type == 'order')
				{
					$idFilter = ' AND `orders`.`order_id` = '.$filter->search_id;
				}
				else if ($filter->id_type == 'client')
				{
					$idFilter = ' AND `orders`.`order_client` = '.$filter->search_id;
				}
			}

			if ($filter->period == 'day' ||
				$filter->period == 'week' ||
				$filter->period == 'month')
			{
				$periodFilter = ' AND TIMESTAMPDIFF('.strtoupper($filter->period).', `orders`.`order_date`, NOW()) < 1';
			}
		}
		
		// ��������� ����������� ������� ������� � ���������
		if (isset($clientId))
		{
			$clientIdAccess = ' AND `orders`.`order_client` = '.$clientId;		
		}
		else if (isset($managerId))
		{
			$managerIdAccess = ' AND `orders`.`order_manager` = '.$managerId;		
		}		
		
		// �������
		$result = $this->db->query('
			SELECT `orders`.*, @package_day:=TIMESTAMPDIFF(DAY, `orders`.`order_date`, NOW()) as package_day,
				`users`.`user_login`  as `order_manager_login`, 
				`countries`.`country_name` as `order_manager_country`,
				TIMESTAMPDIFF(HOUR, `orders`.`order_date`, NOW() - INTERVAL @package_day DAY) as `package_hour`
			FROM `orders`
			INNER JOIN `users` on `users`.`user_id` = `orders`.`order_manager`
			INNER JOIN `managers` on `managers`.`manager_user` = `orders`.`order_manager`
			INNER JOIN `countries` on `managers`.`manager_country` = `countries`.`country_id`
			WHERE '
				.$statusFilter
				.$managerFilter
				.$periodFilter
				.$idFilter
				.$clientIdAccess
				.$managerIdAccess.'
			ORDER BY `orders`.`order_date` DESC'
		)->result();
		
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	/**
	 * ���������� �����, ���� �� ���� � ��������
	 *
	 * @return array
	 */
	public function getManagerOrderById($order_id, $manager_id){
		$order = $this->getById($order_id);
		
		if ($order &&
			$order->order_manager == $manager_id)
		{
			return $order;
		}

		return false;
	}
	
	/**
	 * ���������� �����, ���� �� ���� � �������
	 *
	 * @return array
	 */
	public function getClientOrderById($order_id, $client_id){
		$order = $this->getById($order_id);
		
		if ($order &&
			$order->order_client == $client_id)
		{
			return $order;
		}

		return false;
	}
	
	/**
	 * ����������/��������� ������
	 * ���������� ���������� �� ������������ ������
	 * 
	 * @param (object) 	- $order
	 * @return (mixed)	- ������ order ��� false � ������ ������ ������ � ����
	 */
	public function saveOrder($order){
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($order->$prop)){
				$this->_set($prop, $order->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if (!$new_id) return false;
		
		return $this->getInfo(array($new_id));
	}
	
	/**
	 * Get sent orders by manager id
	 *
	 * @return array
	 */
	public function getSentOrdersByManagerId($id){
		
		$result = $this->select(array('order_manager' => $id, 'order_status' => 'sended'));
		
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	/**
	 * Updates order with available deliveries
	 *
	 * @return array
	 */
	public function setAvailableDeliveries($order, $pricelist) 
	{
		if (!$order->order_country_from ||
			!$order->order_country_to)
		{
			$order->delivery_list = false;
		}
		else
		{		
			$order->delivery_list = $pricelist->getPricesByWeight(
				$order->order_weight,
				$order->order_country_from, 
				$order->order_country_to);
		}
	}
	
	/**
	 * ������������ ��������� ������
	 *
	 * @return array
	 */
	public function calculateCost($order, $config)
	{
		$config = $config->getConfig();
			
		if (!$config)
		{
			throw new Exception('���������� ���������� ��������� ������. ������ ��� ������� ����������.');
		}
		
		// ���������, ������� ������� ������
		$order->order_cost = $order->order_products_cost +
									$order->order_delivery_cost;			
	
		$order->order_comission = $config['price_for_help']->config_value;
		
		$order->order_cost += ($order->order_cost * $order->order_comission) / 100;
				
		// ��������� ��� ������� ��������
		$order->order_manager_comission = $order->order_comission / 2;
		
		$order->order_manager_cost = $order->order_products_cost +
									$order->order_delivery_cost;			
				
		$order->order_manager_cost += ($order->order_manager_cost * $order->order_manager_comission) / 100;
				
		return ($order->order_cost && $order->order_manager_cost) ? $order : false;
	}
	

}
?>