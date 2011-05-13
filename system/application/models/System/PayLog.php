<?
/**
 * Обертка для класса Log
 * 
 */
class PayLog extends Log {
	
	static public function put($preffix = 'payments', $message = null){
		
		
		self::$logpath	= BASEPATH.'logs/payments/';
		self::$logfile	= $preffix.'-'.date('dmY').'.log';
		
		if (!is_dir(self::$logpath)){
			mkdir(self::$logpath);
			chmod(self::$logpath, 0777);
		}
		
		$log	= View::get('tools/elements/paymentLog', array('msg'	=> $message));
		
		self::putLog($log);
	}
}

?>