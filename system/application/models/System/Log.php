<?
/**
 * Базовый клас для лог-классов
 *
 */
class Log{
	
	static $logpath	= null;
	static $logfile	= null;
	static $stopOnError	= 0;
	static $rectype	= FILE_APPEND;//FILE_APPEND/null
	
	static private function prepareLog(){
		if (!self::$logpath)
			self::$logpath	= BASEPATH.'logs/';
			
		if (!self::$logfile)
			self::$logfile	= 'defaultLog-'.date('d-m-Y').'log';
	}
	
	static public function putLog($log){

		self::prepareLog();
		
		$result = file_put_contents(self::$logpath.self::$logfile, $log, self::$rectype);
		
		if ($result === false){
			show_error("syscall::Log::putLog =>>> FAIL! (at ".__FILE__." in line ".__LINE__.")");
			
			if (self::$stopOnError) die();
		}
	}
}


?>