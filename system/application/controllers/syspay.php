<?php
require_once BASE_CONTROLLERS_PATH.'SyspayBaseController'.EXT;

class Syspay extends SyspayBaseController {

	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		if (!Check::user()){
			Func::redirect('/main');
			return;
		}
		
		$this->load->library('cbr');
		
		View::showChild($this->viewpath.'/pages/main', array('usd' => (float) $this->cbr->getRate('USD')));
	}
	
	
	public function showSuccess(){
		
		View::showChild($this->viewpath.'/pages/showSuccess');
	}
	
	public function showFail(){
		
		View::showChild($this->viewpath.'/pages/showFail');
	}
	
	public function showResult($user_id = null){
		
		if (isset($_POST['OutSum'])){
			$this->getResultRK();
			
		}elseif (isset($_POST['operation_xml'])){
			$this->getResultLP($user_id);
			
		}elseif (isset($_POST['WMI_PAYMENT_NO'])){
			$this->getResultW1();
			
		}elseif (isset($_POST['LMI_PAYMENT_AMOUNT'])){
			$this->showResultWM();
			
		}else{
			View::showChild($this->viewpath.'/pages/showFail');
		}
	}
	
	public function showGate(){
		
		die();
		$this->output->enable_profiler(false);
		
		if (!$this->user){
			Func::redirect('/main');
			return false;
		}
		
		// идентификатор платежа, фотрмируется именно таким образом специально!
		$number		= $this->user->user_id.date('Y')+date('m')+date('d')+date('h')+date('i')+date('s');
		$send		= Check::str('send', 4,4);
		$amount		= Check::int('amount');
		$green		= Check::int('green');
		$ps			= Check::str('ps',2,2);
		$comment	= Check::txt('comment',512);
		
		$view_names	= array('wm', 'w1', 'lp', 'rk');
		
		if (!$send || !in_array($ps,$view_names)){
			Func::redirect('/syspay');
			return;
		}
		
		Stack::clear('syspay');
		Stack::push('syspay', array(
									'amount'	=> $amount,
									'user_id'	=> $this->user->user_id,
									'comment'	=> $comment,
									'number'	=> $number,
		));
		
		$view_form	= array(
							'number'	=> $number,
							'send'		=> $send,
							'amount'	=> $amount,
							'comment'	=> $comment,
							'green'		=> $green,
		);
		
		View::show($this->viewpath.'gate', array(
												'ps'		=> $ps,
												'psform'	=> $view_form,
		));
		
	}
	
	/**
	 * показывает описание платежной системы
	 *
	 */
	public function showPays($pay_id = null){
		
		View::showChild('syspay/pages/showPays', array('pay_id'	=> (int)$pay_id));
	}
	
################## ROBOKASSA #####################
/*
Оповещение об оплате (ResultURL)

В случае успешного проведения оплаты робот системы проводит запрос по Result URL, с указанием следующих параметров (методом, выбранным в настройках):

OutSum=nOutSum&
InvId=nInvId&
SignatureValue=sSignatureValue
[&пользовательские_параметры]

Если в настройках в качестве метода отсылки данных был выбран Email, то в случае успешного проведения оплаты робот системы отправит вам сообщение на email, указанный в качестве ResultURL, с указанием параметров, указанных выше.

nOutSum
    -полученная сумма. Сумма будет передана в той валюте, которая была указана при регистрации магазина. Формат представления числа - разделитель точка.
nInvId
    - номер счета в магазине
sSignatureValue
    - контрольная сумма MD5 - строка представляющая собой 32-разрядное число в 16-ричной форме и любом регистре (всего 32 символа 0-9, A-F). Формируется по строке, содержащей некоторые параметры, разделенные ':', с добавлением sMerchantPass2 - (устанавливается через интерфейс администрирования) т.е. nOutSum:nInvId:sMerchantPass2[:пользовательские параметры, в отсортированном порядке]
    К примеру если при инициализации операции были переданы пользовательские параметры shpb=xxx и shpa=yyy то подпись формируется из строки ...:sMerchantPass2:shpa=yyy:shpb=xxx 


Скрипт, находящийся по Result URL должен проверить правильность контрольной суммы и соответствия суммы платежа ожидаемой сумме. При формировании строки подписи учтите формат представления суммы (в строку при проверке подписи вставляйте именно полученное значение, без какого-либо форматирования).

Данный запрос производится после получения денег, однако, до того как пользователь сможет перейти на Success URL. Перед скриптом магазина, расположенным по Success URL обязательно отрабатывает скрипт запроса к Result URL.

Факт успешности сообщения магазину об исполнении операции определяется по результату, возвращаемому обменному пункту. Результат должен содержать "OKnMerchantInvId", т.е. для счета #5 должен быть возвращен текст "OK5".

В случае невозможности связаться со скриптом по адресу Result URL (связь прерывается по time-out-у либо по отсутствию DNS-записи, либо получен не ожидаемый ответ) на email-адрес администратора магазина отправляется письмо и запрос Result URL считается завершенным успешно. В случае системаческого отсутствия связи между серверами магазина и обменного пункта лучше использовать метод определения оплаты с применением интерфейсов XML, а самый желательный и защищенный способ - совмещенный.
*/
	private function getResultRK(){
		
		#
		$this->output->enable_profiler(false);
//		error_reporting(~E_ALL);
		#
		
		try{
			Check::reset_empties();
			$user_id		= Check::int('ShpUser');
			$amount			= Check::float('OutSum');
			$ptransfer		= Check::int('InvId');
			$rawSign		= Check::str('SignatureValue', 320,32);
			
			if (Check::get_empties())
				throw new Exception('Invalid params/one or more fields is empty!');

			$user_comment	= Check::str('ShpComment',255,0);
			$sign			= strtoupper(md5(join(':', array($amount,$ptransfer,RK_PASS2,'ShpComment='.$user_comment,'ShpUser='.$user_id))));
			
			if ($sign != $rawSign)
				throw new Exception("Invalid signum! [$rawSign<==>$sign]");
				
			##########	
			// TODO: OK
			##########
			
					$user_comment							= Check::var_str(base64_decode($user_comment), 512,1);
					
					// конвертируем в валюту сайта
					$this->load->library('cbr');
					$amount_usd								= $amount / (float) $this->cbr->getRate('USD');
					$amount_to_usd							= $amount / (1+(RK_IN_TAX / 100)) / (float) $this->cbr->getRate('USD');
					$tax_usd								= $amount_usd - $amount_to_usd;
					
					$payment_obj = new stdClass();
					$payment_obj->payment_from				= 'RK payment';// зачисление на счет пользователя
					$payment_obj->payment_to				= $user_id;
					$payment_obj->payment_tax				= RK_IN_TAX.'%';
					$payment_obj->payment_amount_rur		= $amount;
					$payment_obj->payment_amount_from		= $amount_usd;
					$payment_obj->payment_amount_tax		= $tax_usd;
					$payment_obj->payment_amount_to			= $amount_to_usd;
					$payment_obj->payment_purpose			= 'зачисление на счет пользователя';
					$payment_obj->payment_comment			= $user_comment;
			    	$payment_obj->payment_type				= 'in';
			    	$payment_obj->payment_status			= 'complite';
			    	$payment_obj->payment_transfer_info		= 'RK Transfer';
			    	$payment_obj->payment_transfer_order_id	= $ptransfer;
			    	$payment_obj->payment_transfer_sign		= $rawSign;
			    	

			    	$this->load->model('PaymentModel', 'Payment');
					$this->Payment->_load($payment_obj);
					$r = $this->Payment->makeCharge();
					
					if (is_object($r)){
						throw new Exception($r->getMessage());
					}elseif((int)$r){
						$status	= 'OK'.$ptransfer;
						$addLog	= "Status: OK! [payment_id=$r]\n";
					}else{
						throw new Exception("unknown merchant error!");
					}
			
			
			$status	= 'OK'.$ptransfer;
			
		}catch (Exception $e){
			##########	
			// TODO: FAIL!
			##########
			$status	= 'Fail! ('.$e->getMessage().')';
			$addLog	= "Status: FAIL! ".$e->getMessage()."\n";
		}
			
		echo $status;
		
		PayLog::put('RK', "Status:$status\n");
	}
##################################################



	
###################### LP ########################
	private function getResultLP($User_id){
		
		$resp_sig	= $_POST['signature'];
		$enc_resp	= base64_decode($_POST['operation_xml']);
		$gen_sig	= base64_encode(sha1(LP_MERCHANT_SIG2.($enc_resp).LP_MERCHANT_SIG2,1));
		$status		= 'FAIL!';
		$addLog		= '';
		
		if ($gen_sig == $resp_sig){
			
			/**
			 * сдесь производим транзакции внутри системы
			 */
					$paymentXML			= new SimpleXMLElement($enc_resp);
					Check::reset_empties();
					$user_id			= (int) $User_id;
					$amount				= (int) $paymentXML->amount;
					$LP_transfer_id		= (int) $paymentXML->transaction_id;
					$transfer_order_id	= (int) $paymentXML->order_id;
					$status				= Check::var_str((string) $paymentXML->status,16,1);
					$action				= Check::var_str((string) $paymentXML->action,16,1);
					$user_comment		= Check::var_str((string) $paymentXML->description,512,0);
					$payment_from		= Check::var_str((string) $paymentXML->sender_phone,16,0);
			
					
					if ($status == 'success' && !Check::get_empties() && $action == 'server_url'){
						
						// конвертируем в валюту сайта
						$this->load->library('cbr');
						$amount_usd								= $amount / (float) $this->cbr->getRate('USD');
						$amount_to_usd							= $amount / (1+(RK_IN_TAX / 100)) / (float) $this->cbr->getRate('USD');
						$tax_usd								= $amount_usd - $amount_to_usd;
						
						$payment_obj = new stdClass();
						$payment_obj->payment_from				= 'LP[sender_phone]:'.$payment_from;// зачисление на счет пользователя
						$payment_obj->payment_to				= $user_id;
						$payment_obj->payment_tax				= LP_IN_TAX.'%';
						$payment_obj->payment_amount_rur		= $amount;
						$payment_obj->payment_amount_from		= $amount_usd;
						$payment_obj->payment_amount_tax		= $tax_usd;
						$payment_obj->payment_amount_to			= $amount_to_usd;
						$payment_obj->payment_purpose			= 'зачисление на счет пользователя';
						$payment_obj->payment_comment			= Func::utf2win($user_comment);
				    	$payment_obj->payment_type				= 'in';
				    	$payment_obj->payment_status			= 'complite';
				    	$payment_obj->payment_transfer_info		= 'LP Transfer ID:'.$LP_transfer_id;
				    	$payment_obj->payment_transfer_order_id	= $transfer_order_id;
				    	$payment_obj->payment_transfer_sign		= $resp_sig;
				    	
						try{
					    	$this->load->model('PaymentModel', 'Payment');
							$this->Payment->_load($payment_obj);
							$r = $this->Payment->makeCharge();
							
							if (is_object($r)){
								$status	= "FAIL";
								$desc	= $r->getMessage();
								$addLog	= "Status: FAIL! ".$r->getMessage()."\n";
							}elseif((int)$r){
								$status	= "OK";
								$desc	= "Заказ #" . $transfer_order_id . " оплачен!";
								$addLog	= "Status: OK! [payment_id=$r]\n";
							}else{
								$status	= "FAIL";
								$desc	= "unknown merchant error!";
								$addLog	= "Status: FAIL! (unknown merchant error)\n";
							}
						
						}catch (Exception $e){
							$status	= "FAIL";
							$desc	= $e->getMessage();
							$addLog	= "Status: FAIL! ($desc)\n";
						}
						
					}elseif ($action == 'result_url'){
						$desc = 'перенаправление клиента';
						if ($status == 'success') $this->showSuccess();
						elseif ($status == 'failure') $this->showFail();
						elseif ($status	== 'wait_secure') $this->showWaitLp();
					}
		}
		
		$addLog	= $enc_resp."\nUser_id:$user_id\nStatus:	$status ($desc)\n";
		
		PayLog::put('LP',$addLog);
		
		return $status == 'success' ? 1 : 0;
	}

	
	private function showWaitLP(){
		View::showChild($this->viewpath.'/pages/showWaitLP');
	}
	
###################### /LP ########################


###################### W1  ########################
	private function getResultW1(){
		
		#
		$this->output->enable_profiler(false);
		error_reporting(~E_ALL);
		#
		
		$state	= "";
		$desc	= "";
		
		// Проверка наличия необходимых параметров в POST-запросе
		if (!isset($_POST["WMI_SIGNATURE"])){
			$state	= "RETRY";
			$desc	= "Отсутствует параметр WMI_SIGNATURE";
			
		}elseif (!isset($_POST["WMI_PAYMENT_NO"])){
			$state	= "RETRY";
			$desc	= "Отсутствует параметр WMI_PAYMENT_NO";
			
			
		}elseif (!isset($_POST["WMI_ORDER_STATE"])){
			$state	= "RETRY";
			$desc	= "Отсутствует параметр WMI_ORDER_STATE";
		}
		
		// Извлечение всех параметров POST-запроса, кроме WMI_SIGNATURE
		foreach($_POST as $name => $value)
		{
			if ($name !== "WMI_SIGNATURE") $params[$name] = $value;
		}
		
		// Сортировка массива по именам ключей в порядке возрастания
		// и формирование сообщения, путем объединения значений формы
		uksort($params, "strcasecmp"); $values = "";
		
		$values	= join(null, $params);
		
		// Формирование подписи для сравнения ее с параметром WMI_SIGNATURE
		$signature = base64_encode(pack("H*", sha1($values . W1_KEY)));
		
		//Сравнение полученной подписи с подписью W1
		if ($signature == $_POST["WMI_SIGNATURE"])
		{
			if (strtoupper($_POST["WMI_ORDER_STATE"]) == "ACCEPTED" || strtoupper($_POST["WMI_ORDER_STATE"]) == "PROCESSING"){
			
				##########	
				// TODO: OK
				##########
				
					$user_comment		= Check::str('User_comment', 512,1);
					$user_id			= Check::int('User_id');
					$amount				= Check::int('WMI_PAYMENT_AMOUNT');
					$w1_transfer_id		= Check::str('WMI_ORDER_ID', 64,1);
					$transfer_order_id	= Check::int('WMI_PAYMENT_NO');
					$user_from			= Check::str('WMI_TO_USER_ID', 64,1);// не смотря на название поля, это кошелек клиента
					
					$user_comment		= Check::var_str(base64_decode(substr($user_comment, 6)), 512,1);
					
					
					// конвертируем в валюту сайта
					$this->load->library('cbr');
					$amount_usd								= $amount / (float) $this->cbr->getRate('USD');
					$amount_to_usd							= $amount / (1+(W1_IN_TAX / 100)) / (float) $this->cbr->getRate('USD');
					$tax_usd								= $amount_usd - $amount_to_usd;
					
					$payment_obj = new stdClass();
					$payment_obj->payment_from				= 'W1[WMI_TO_USER_ID]:'.$user_from;// зачисление на счет пользователя
					$payment_obj->payment_to				= $user_id;
					$payment_obj->payment_tax				= W1_IN_TAX.'%';
					$payment_obj->payment_amount_rur		= $amount;
					$payment_obj->payment_amount_from		= $amount_usd;
					$payment_obj->payment_amount_tax		= $tax_usd;
					$payment_obj->payment_amount_to			= $amount_to_usd;
					$payment_obj->payment_purpose			= 'зачисление на счет пользователя';
					$payment_obj->payment_comment			= Func::utf2win($user_comment);
			    	$payment_obj->payment_type				= 'in';
			    	$payment_obj->payment_status			= 'complite';
			    	$payment_obj->payment_transfer_info		= 'W1 Transfer ID:'.$w1_transfer_id;
			    	$payment_obj->payment_transfer_order_id	= $transfer_order_id;
			    	$payment_obj->payment_transfer_sign		= $signature;
			    	
					$this->load->model('PaymentModel', 'Payment');
					$this->Payment->_load($payment_obj);
					$r = $this->Payment->makeCharge();
					
					if (is_object($r)){
						$state	= "CANCEL";
						$desc	= $r->getMessage();
						$addLog	= "Status: FAIL! ".$r->getMessage()."\n";
					}elseif((int)$r){
						$state	= "OK";
						$desc	= "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " оплачен!";
						$addLog	= "Status: OK! [payment_id=$r]\n";
					}else{
						$state	= "CANCEL";
						$desc	= "unknown merchant error!";
						$addLog	= "Status: FAIL! (unknown merchant error)\n";
					}
				
//			}else if (strtoupper($_POST["WMI_ORDER_STATE"]) == "PROCESSING"){
//				
//				##########
//				// TODO: OK with manual commit
//				##########
//				
//				$state	= "OK";
//				$desc	= "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " оплачен!";
//		
//				// Данная ситуация возникает, если в платежной форме WMI_AUTO_ACCEPT=0.
//				// В этом случае интернет-магазин может принять оплату или отменить ее.
				
			}else if (strtoupper($_POST["WMI_ORDER_STATE"]) == "REJECTED"){
				
				##########
				// TODO: FAIL
				##########
		
				$state	= "OK";
				$desc	= "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " отменен!";
				
			}else{
				// Случилось что-то странное, пришло неизвестное состояние заказа
		
				$state	= "RETRY";
				$desc	= "Неверное состояние ". $_POST["WMI_ORDER_STATE"];
				
			}
			
		}else{
			// Подпись не совпадает, возможно вы поменяли настройки интернет-магазина... или вас пытаются наипать...
			$state	= "CANCEL";
			$desc	= "Неверная подпись " . $_POST["WMI_SIGNATURE"]. "<==>$signature";
		}

		$addLog	= "Answer:\"WMI_RESULT=$state&WMI_DESCRIPTION=".(urlencode($desc))."\"\nDecoded Desc:\"$desc\"\n";
		
		PayLog::put('W1',$addLog);
		
		echo "WMI_RESULT=".$state.'&WMI_DESCRIPTION='.urlencode($desc);
		
	}
####################### W1 ########################



####################### WM ########################
	public function showResultWM(){
		
		#
		$this->output->enable_profiler(false);
		#error_reporting(~E_ALL);
		#
		PayLog::put('WM');
		
		$addLog	= '';
		// обрабатываем предзапрос
		if (Check::int('LMI_PREREQUEST')){
			##########
			// TODO: делаем какую-нить пакость, если она нужна...
			##########
			echo "YES";
			// $addLog	= "Status: CANCEL! Отменено прадовцом!\n"
			
		}else{
			// впринципе нам не надо проверять по предзапросу сумму перевода и некоторые другие данные, тк мы начислим именно столько сколько нам перевели
			$signStr	= WM_PURSE.$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].$_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].WM_SECRET_KEY.$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
			$sign		= strtoupper(md5($signStr));
			
			if ($sign != $_POST['LMI_HASH']){
				##########
				// TODO: FAIL! НЕ ВЕРНАЯ ПОДПИСЬ!
				##########
				echo 'NO';
				$addLog	= "Status: FAIL! Не верная цифровая подпись!\nSignStr:$signStr\nCalcSign:$sign\nRespSign:".$_POST['LMI_HASH']."\n";
			}else{
				##########	
				// TODO: OK
				##########
//				if (isset($_POST['LMI_MODE']) && $_POST['LMI_MODE']){
//
//					echo "YES";
//			    	$addLog	= "Status: OK! (test mode)\n";
//			    	
//				}else{
					
					$user_comment		= Check::str('User_comment', 512,1);
					$user_id			= Check::int('User_id');
					$amount				= Check::int('LMI_PAYMENT_AMOUNT');
					$wm_transfer_id		= Check::int('LMI_SYS_TRANS_NO');
					$transfer_order_id	= Check::int('LMI_PAYMENT_NO');
					$user_from			= Check::str('LMI_PAYER_PURSE', 64,1);
					
					// конвертируем в валюту сайта
					$this->load->library('cbr');
					$amount_usd								= $amount / (float) $this->cbr->getRate('USD');
					$amount_to_usd							= $amount / (1+(WM_IN_TAX / 100)) / (float) $this->cbr->getRate('USD');
					$tax_usd								= $amount_usd - $amount_to_usd;
					
					$payment_obj = new stdClass();
					$payment_obj->payment_from				= 'WM[LMI_PAYER_PURSE]:'.$user_from;// зачисление на счет пользователя
					$payment_obj->payment_to				= $user_id;
					$payment_obj->payment_tax				= WM_IN_TAX.'%';
					$payment_obj->payment_amount_rur		= $amount;
					$payment_obj->payment_amount_from		= $amount_usd;
					$payment_obj->payment_amount_tax		= $tax_usd;
					$payment_obj->payment_amount_to			= $amount_to_usd;
					$payment_obj->payment_purpose			= 'зачисление на счет пользователя';
					$payment_obj->payment_comment			= $user_comment;
			    	$payment_obj->payment_type				= 'in';
			    	$payment_obj->payment_status			= 'complite';
			    	$payment_obj->payment_transfer_info		= 'WM Transfer ID:'.$wm_transfer_id;
			    	$payment_obj->payment_transfer_order_id	= $transfer_order_id;
			    	$payment_obj->payment_transfer_sign		= $sign;
			    	
					$this->load->model('PaymentModel', 'Payment');
					$this->Payment->_load($payment_obj);
					$r = $this->Payment->makeCharge();
					
					if (is_object($r)){
						echo 'NO ->>'.$r->getMessage();
						$addLog	= "Status: FAIL! ".$r->getMessage()."\n";
					}elseif((int)$r){
						echo "YES";
						$addLog	= "Status: OK! [payment_id=$r]\n";
					}else{
						echo "NO ->> unknown merchant error!\n" ;
						$addLog	= "Status: FAIL! (unknown merchant error)\n";
					}
//				}
			}
		}
		
		PayLog::put('WM', $addLog);
		return;
	}


	private function test(){
		View::show($this->viewpath.'/pages/test');
	}
}

/* End of file syspay.php */
/* Location: ./system/application/controllers/syspay.php */