<?
define('CORRECT_CASE_SINGLE_NOMINATIVE', 1); // единственное число,  именительный падеж. например, комар,  очко,   секунда
define('CORRECT_CASE_SINGLE_GENITIVE', 2); // единственное число, родительный падеж. например, комара, очка, секунды
define('CORRECT_CASE_PLURAL_GENITIVE', 3); // множественное число, родительный падеж.  например, комаров, очков, секунд

/**
 * Вспомогательные функции
 *
 * @author Павлов Михаил
 */
class Func
{
	/**
	 * Строка символов с кодами = 0..31
	 *
	 * @var string
	 */
	static $chars0_31 = '';

	/**
	 * Конвертация строки или массива строк из кодировки Win-1251 в UTF-8
	 *
	 * @param string $html
	 * @return string
	 */
	static public function win2utf ($var)
	{
		if (is_array($var) || (is_object($var)))
		{
			foreach ($var as &$value)
			{
				$value = Func::win2utf($value);
			}
		} elseif (is_string($var))
		{
			$var = iconv("WINDOWS-1251", "UTF-8", $var);
		}
		return $var;
	}

	/**
	 * Конвертация строки из кодировки UTF-8 в Win-1251
	 *
	 * @param string $html
	 * @return string
	 */
	static public function utf2win ($html)
	{
		return iconv("UTF-8", "WINDOWS-1251", $html);
	}

	/**
	 * Вывод HTML переводов строк
	 *
	 * @param unknown_type $nbr
	 */
	static function br ($nbr = 1)
	{
		for ($i = 0; $i < $nbr; $i ++)
		{
			echo "<br>\n";
		}
	}

	/**
	 * Генерация случайной строки.
	 *
	 * @return string
	 */
	function randStr ($min_len = 0, $max_len = 32)
	{
		$char_set = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		$length = rand($min_len, $max_len);
		$result = '';
		for ($i = 0; $i < $length; $i ++)
		{
			$result .= substr($char_set, rand(0, strlen($char_set) - 1), 1);
		}
		return $result;
	}

	/**
	 * Проверка captcha-строки.
	 * Если переданная строка не совпала с исходной в сессии, сгенерить ошибку wrong_captcha, вернуть false.
	 *
	 * @param string $captcha_str	Captcha-строка
	 * @return boolean
	 */
	private function checkCaptcha ($captcha_str)
	{
		if ($captcha_str == $_SESSION['captcha'])
		{
			return true;
		}
		return false;
	}

	/**
	 * Конвертировать строку для JS вывода
	 *
	 * @param string $string
	 * @return string
	 */
	static function convertHTML2JS($string)
	{
		if (AJAX_REQUEST)
		{
			$string = str_replace("\n", '\n', $string);
			$string = strip_tags($string);
			$string = addslashes($string);
		}
		return $string;
	}

	/**
	 * Установка заголовков для файла
	 *
	 * @param string $file_name
	 *
	 * @author Михаил Павлов
	 */
	public function SetFileHeaders($file_name)
	{
		setlocale(LC_TIME, "C");

		$mtime = filemtime($file_name);
		$local_time = mktime();
		$gmt_time = gmmktime();
		$modified_time = $mtime - $gmt_time + $local_time;
		$modified_str = strftime ("%a, %d %b %Y %H:%M:%S GMT", $modified_time);

		$ETag = sprintf('%x-%x-%x', fileinode($file_name), filesize($file_name), $mtime);

		if (($ETag and isset($_SERVER['HTTP_IF_NONE_MATCH']) and $_SERVER['HTTP_IF_NONE_MATCH'] == $ETag)      // при ETag
		||
		($modified_str and isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) and $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $modified_str)) // при  Last-Modified
		{
			header('HTTP/1.1 304 Not Modified');
			exit;
		}

		$this->output->set_header('Content-Type: text/javascript; charset='.HTML_CHARSET);
		$this->output->set_header('ETag: '.$ETag);
		$this->output->set_header('Last-Modified: '.$modified_str);
		$this->output->set_header('Pragma: public');
		$this->output->set_header('Cache-Control: public');
	}
	
	static public function redirect($link){
		header("Location: $link");
	}
	
	
	/**
	 * Индексируем массив объектов для доступа к объекту по его ИД, те:
	 * 
	 * 	array(6) {
  	 *	[0]=>
  	 *		object(stdClass)#4056 (12) {
     *			["manager_user"]=>string(1) "3"
     * 			.......
     * в 
	 * 	array(6) {
  	 *	[3]=>
  	 *		object(stdClass)#4056 (12) {
     *			["manager_user"]=>string(1) "3"
     * 			.......
	 *
	 * @param array		$array			- массив объектов
	 * @param string	$PKFieldName	- поле по которому производить индексацию
	 */
	static public function reIndexArrayOfObjects(array $array, $PKFieldName){
		
		$iArray	= array();
		if (!empty($array)){
			foreach ($array as $object){
				if (is_object($object) && isset($object->$PKFieldName)){
					$iArray[$object->$PKFieldName]	= $object;
				}
			}
		}
		
		return $iArray;
	}

	/**
	 * Вернуть падеж
	 *
	 * @param integer $str
	 * @return enum
	 */
	static public function CorrectCase($str) {
		$num = (int)$str;
		if ($num>99) $num = $num - (100*floor($num/100));
		if ($num>20) $num = $num - (10*floor($num/10));

		switch ($num){
			case 1 :
				return CORRECT_CASE_SINGLE_NOMINATIVE;
			case 2 :
			case 3 :
			case 4 :
				return CORRECT_CASE_SINGLE_GENITIVE;
			default:
				return CORRECT_CASE_PLURAL_GENITIVE;
		}
	}
	
	/**
	 * Пересобачить в прилоагатеьлное
	 *
	 * @param string $str
	 */
	static public function CorrectCountryAdjective($str){
		$str			= strtolower($str);
		$lastLiter		= $str[strlen($str)-1];
		$preLastLiter	= $str[strlen($str)-2];
		if ($lastLiter == 'я' || $lastLiter == 'и'){
			return substr($str,0,strlen($str)-1).'йский';
		}
		if ($lastLiter == 'а' && $preLastLiter != 'н'){
			return $str.'нский';
		}
		if ($preLastLiter.$lastLiter == 'на'){
			return substr($str,0,strlen($str)-1).'ский';
		}
		
		return $str.'ский';
	}
	
	
	/**
	 * функция превода текста с кириллицы в траскрипт
	 *
	 * @param 	string	$text
	 * @return	string	
	 */
	function win2translit($text)
	{
		return iconv("WINDOWS-1251", "ISO-8859-1//TRANSLIT", $text);
	}
	
	
	/**
	 * округлние до 0.5
	 *
	 * @param unknown_type $val
	 */
	static function round2half($val)
	{
		if ($val > (floor($val)+0.5))
			return ceil($val);
			
		elseif ($val > floor($val))
			return floor($val)+0.5;
		else return $val;
	}
	
	
}

for ($i = 0; $i <= 31; $i ++)
{
	Func::$chars0_31 .= chr($i);
}
?>