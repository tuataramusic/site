<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author omni
 * 
 */
class PaymentModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'payments';		// table name
	protected	$PK					= 'payment_id';		// primary key name	
	
	/**
	 * конструктор
	 *
	 */
	function __construct()
    {
    	$this->properties	= new stdClass();
    	$this->properties->payment_id					='';
    	$this->properties->payment_amount_rur			='';
    	$this->properties->payment_from					='';
    	$this->properties->payment_to					='';
    	$this->properties->payment_tax					='';
    	$this->properties->payment_amount_from			='';
    	$this->properties->payment_amount_to			='';
    	$this->properties->payment_amount_tax			='';
    	$this->properties->payment_purpose				='';
    	$this->properties->payment_time					='';
    	$this->properties->payment_comment				='';
    	$this->properties->payment_type					='';
    	$this->properties->payment_status				='';
    	$this->properties->payment_transfer_info		='';
    	$this->properties->payment_transfer_order_id	='';
    	$this->properties->payment_transfer_sign		='';
    	
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
	
	
	public function getPaymentsByUser($user_id, $vector = 'from'){
		
		$vectors = array('from', 'to');
		
		if (!in_array($vector, $vectors))
			return false;
		
			
		$p = $this->select(array(
									'payment_'.$vector	=> $user_id
		),null,null,array(
									'payment_time'		=> 'asc'
		));
		
		return $p;
		
	}
	
	
	/**
	 * Перевод денег внутри системы
	 *
	 * @param	object	$payment_obj
	 * @return 	mixed
	 */
	public function makePayment($payment_obj = null, $skip_inner_tran = null)
	{
		try
		{
			// инициализация платежа
			if ($payment_obj)
			{
				$this->_load($payment_obj);
			}
			//var_dump($this->_get('payment_amount_from'));die();
			//var_dump($payment_obj);die();
			$ci = get_instance();
			$ci->load->model('UserModel', 'User');
			
			// валидация платежа
			$ufrom	= $ci->User->getById($this->_get('payment_from'));
			$uto	= $this->_get('payment_to') > 0 ? $ci->User->getById($this->_get('payment_to')) : false;
			$system	= $ci->User->getById(1);
			
			if (!$ufrom)
			{
				throw new Exception('User not found');
			}
			
			// если $ufrom->user_id == 0 - значит это системный аккаунт, на нем баланс не проверяем
			if ($ufrom->user_id != 0 && $ufrom->user_coints < $this->_get('payment_amount_from'))
			{
				throw new Exception('Недостаточно денег на счете!');
			}
			if ($ufrom->user_coints < 0 || 
				$this->_get('payment_amount_from') < 0)
			{
				throw new Exception('Сумма не может быть отрицательной!');
			}
			if ($system->user_group !== 'admin')
			{
				throw new Exception('Не определен системный счет!');
			}
	
			// определение направления платежа
			if ($ufrom->user_group == 'system' && $uto)
			{
				$this->_set('payment_type', 'in');
			}
			elseif ($ufrom && !$uto)
			{
				$this->_set('payment_type', 'out');
			}
			else
			{
				$this->_set('payment_type', 'inner');
			}
			
			// опускаем транзакцию
			if (!isset($skip_inner_tran)) 
			{
				$this->db->trans_begin();
			}
			
			// переводим деньги на счет
			if ($uto && 
				!$ci->User->chargeCoints($uto->user_id, $this->_get('payment_amount_to')))
			{
				throw new Exception('Невозможно зачислить средства на счет.');
			}
			
			// переводим комиссию на счет системы
			if ($uto && 
				$ufrom && 
				$this->_get('payment_amount_tax') &&
				!$ci->User->chargeCoints($system->user_id, $this->_get('payment_amount_tax')))
			{
				throw new Exception('Невозможно начислить комиссию.');
			}
			
			// снимаем деньги с счета
			// значение суммы должно быть отрицательным
			if ($ufrom && 
				!$ci->User->chargeCoints($ufrom->user_id, -$this->_get('payment_amount_from')))
			{
				throw new Exception('Невозможно списать средства со счета.');
			}

			// сохраняем историю платежа
			$payment_id = $this->makePaymentLog($this->_get());
			
			if (!$payment_id)
			{
				throw new Exception('Невозможно сохранить историю платежа.');
			}

			
			// опускаем транзакцию
			if (!isset($skip_inner_tran))
			{
				if ($this->db->trans_status() !== FALSE)
				{
					$this->db->trans_commit();
					return $payment_id;
				}
				else
				{
					throw new Exception('Transaction fail!');
				}
			}
			
			return $payment_id;
		}
		catch (Exception $e)
		{
			// опускаем транзакцию
			if (!isset($skip_inner_tran))
			{
				$this->db->trans_rollback();
			}
			
			throw $e;
		}
	}	
	
	
	/**
	 * Зачисление денег на счет
	 *
	 * @param unknown_type $payment_obj
	 * @param unknown_type $skip_inner_tran
	 * @return unknown
	 */
	public function makeCharge($payment_obj = null, $skip_inner_tran = null)
	{
		try
		{
			// инициализация платежа
			if ($payment_obj)
			{
				$this->_load($payment_obj);
			}
			//var_dump($this->_get('payment_amount_from'));die();
			//var_dump($payment_obj);die();
			$ci = get_instance();
			$ci->load->model('UserModel', 'User');
			
			// валидация платежа
			$ufrom	= $this->_get('payment_from'); // строчное значение - название платежной ситемы и внутреннего номера кошелька в ней
			$uto	= $this->_get('payment_to') > 0 ? $ci->User->getById($this->_get('payment_to')) : false;
			$system	= $ci->User->getById(1);

			if (!$ufrom)
			{
				throw new Exception('Source not found');
			}
			
			if ($system->user_group !== 'admin')
			{
				throw new Exception('Не определен системный счет!');
			}
	
			// опускаем транзакцию
			if (!isset($skip_inner_tran)) 
			{
				$this->db->trans_begin();
			}
			
			$this->_set('payment_type', 'in');
			
			// переводим деньги на счет
			if ($uto && 
				!$ci->User->chargeCoints($uto->user_id, $this->_get('payment_amount_to')))
			{
				throw new Exception('Невозможно зачислить средства на счет.');
			}
			
			// переводим комиссию на счет системы
			if ($uto && 
				$ufrom && 
				$this->_get('payment_amount_tax') &&
				!$ci->User->chargeCoints($system->user_id, $this->_get('payment_amount_tax')))
			{
				throw new Exception('Невозможно начислить комиссию.');
			}

			// сохраняем историю платежа
			$payment_id = $this->makePaymentLog($this->_get());
			
			if (!$payment_id)
			{
				throw new Exception('Невозможно сохранить историю платежа.');
			}

			
			// опускаем транзакцию
			if (!isset($skip_inner_tran))
			{
				if ($this->db->trans_status() !== FALSE)
				{
					$this->db->trans_commit();
					return $payment_id;
				}
				else
				{
					throw new Exception('Transaction fail!');
				}
			}
			
			return $payment_id;
		}
		catch (Exception $e)
		{
			// опускаем транзакцию
			if (!isset($skip_inner_tran))
			{
				$this->db->trans_rollback();
			}
			
			// прокидываем исключение вверх по цепочке
			throw $e;
		}
	}	
	
	/**
	 * Запись истории перевода в базу
	 *
	 */
	private function makePaymentLog($payment_obj = null){
		
		if ($payment_obj){
			$this->_load($payment_obj);
		}
		
		$payment_id = $this->insert();
		
		if ($payment_id){
			return $payment_id;
		}
		
		return false;
	}	
	
	/**
	 * Сводка по статистике платежей для админа
	 */
	public function getSummaryStat() {
		$week_day = intval(date('w'));
		$stat = array(
			'day'	=> $this->getStatForPeriod(date('Y-m-d 00:00:00'), date('Y-m-d H:i:s')),
			'week' 	=> $this->getStatForPeriod( $week_day ? date('Y-m-d 00:00:00', time()-($week_day-1)*24*60*60) : date('Y-m-d 00:00:00', time()-6*24*60*60), date('Y-m-d H:i:s')),		
			'month' => $this->getStatForPeriod(date('Y-m-01 00:00:00'), date('Y-m-d H:i:s'))
		);
		return $stat;
	}
	
	/**
	 * Получаем суммарную статистику платежей за выбранный период
	 */
	public function getStatForPeriod($from, $to) {
		$res = $this->db->query('
			SELECT SUM(`payment_amount_from`) AS `stat`
			FROM `'.$this->table."`
			WHERE `payment_time` BETWEEN '$from' AND '$to' AND `payment_from` = 1
		")->result();
		
		$negative = $res[0]->stat ? $res[0]->stat : 0;

		$res = $this->db->query('
			SELECT SUM(`payment_amount_from`) AS `stat`
			FROM `'.$this->table."`
			WHERE `payment_time` BETWEEN '$from' AND '$to' AND `payment_to` = 1
		")->result();
		
		$positive = $res[0]->stat ? $res[0]->stat : 0;
		
		return $positive - $negative;
	}
	
	/**
	 * Получаем платежи пользователей системе
	 */
	public function getRefillPayments() {
		return $this->db->query('
			SELECT `'.$this->table.'`.*, `users`.`user_login`
			FROM `'.$this->table.'`
				INNER JOIN `users` ON `'.$this->table.'`.`payment_from` = `users`.`user_id`
			ORDER BY `payment_time` DESC
		')->result();//WHERE `payment_to` = 1
	}
	
//	public function getFilteredPayments($filter, $from=null, $to=null) {
//				
//		$where = '';
//		if (count($filter)) {
//			$where = '';			
//			foreach ($filter as $key=>$val) {
//				$where .= "`$key` = '$val' AND ";
//			}
//			$where = substr($where, 0, strlen($where)-5);
//		}
//		if (strlen($where)) {
//			$where = ($from && $to) ? $where." AND `payment_time` BETWEEN '$from' AND '$to'" : $where;
//		}
//		else {
//			$where = ($from && $to) ? "`payment_time` BETWEEN '$from' AND '$to'" : '1';
//		}
//		
//		
//		return $this->db->query('
//			SELECT `'.$this->table.'`.*, `users`.`user_login`
//			FROM `'.$this->table.'`
//				INNER JOIN `users` ON `'.$this->table.'`.`payment_from` = `users`.`user_id` 
//			WHERE '.$where.'
//			ORDER BY `payment_time` DESC
//		')->result();
//	}


	public function getFilteredPayments($filter = array(), $from=null, $to=null) 
	{
		$where = '1';
		if (is_string($filter)){
			$where	= $filter;
		}else{
			foreach ($filter as $key=>$val) {
				$where .= " AND $key = '$val'";
			}
		}

		if ($from && $to) {
			$where .= " AND `payment_time` BETWEEN '$from' AND '$to'";
		}
		
		return $this->db->query('
			SELECT `'.$this->table.'`.*, `user_from`.`user_login` user_from, `user_to`.`user_login` user_to
			FROM `'.$this->table.'`
				INNER JOIN `users` `user_from` ON `'.$this->table.'`.`payment_from` = `user_from`.`user_id` 
				INNER JOIN `users` `user_to` ON `'.$this->table.'`.`payment_to` = `user_to`.`user_id` 
			WHERE '.$where.'
			ORDER BY `payment_time` DESC
		')->result();
	}
}
?>