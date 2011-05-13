<?
define('CORRECT_CASE_SINGLE_NOMINATIVE', 1); // ������������ �����,  ������������ �����. ��������, �����,  ����,   �������
define('CORRECT_CASE_SINGLE_GENITIVE', 2); // ������������ �����, ����������� �����. ��������, ������, ����, �������
define('CORRECT_CASE_PLURAL_GENITIVE', 3); // ������������� �����, ����������� �����.  ��������, �������, �����, ������

/**
 * ��������������� �������
 *
 * @author ������ ������
 */
class Func
{
	/**
	 * ������ �������� � ������ = 0..31
	 *
	 * @var string
	 */
	static $chars0_31 = '';

	/**
	 * ����������� ������ ��� ������� ����� �� ��������� Win-1251 � UTF-8
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
	 * ����������� ������ �� ��������� UTF-8 � Win-1251
	 *
	 * @param string $html
	 * @return string
	 */
	static public function utf2win ($html)
	{
		return iconv("UTF-8", "WINDOWS-1251", $html);
	}

	/**
	 * ����� HTML ��������� �����
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
	 * ��������� ��������� ������.
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
	 * �������� captcha-������.
	 * ���� ���������� ������ �� ������� � �������� � ������, ��������� ������ wrong_captcha, ������� false.
	 *
	 * @param string $captcha_str	Captcha-������
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
	 * �������������� ������ ��� JS ������
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
	 * ��������� ���������� ��� �����
	 *
	 * @param string $file_name
	 *
	 * @author ������ ������
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

		if (($ETag and isset($_SERVER['HTTP_IF_NONE_MATCH']) and $_SERVER['HTTP_IF_NONE_MATCH'] == $ETag)      // ��� ETag
		||
		($modified_str and isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) and $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $modified_str)) // ���  Last-Modified
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
	 * ����������� ������ �������� ��� ������� � ������� �� ��� ��, ��:
	 * 
	 * 	array(6) {
  	 *	[0]=>
  	 *		object(stdClass)#4056 (12) {
     *			["manager_user"]=>string(1) "3"
     * 			.......
     * � 
	 * 	array(6) {
  	 *	[3]=>
  	 *		object(stdClass)#4056 (12) {
     *			["manager_user"]=>string(1) "3"
     * 			.......
	 *
	 * @param array		$array			- ������ ��������
	 * @param string	$PKFieldName	- ���� �� �������� ����������� ����������
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
	 * ������� �����
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
	 * ������������ � ���������������
	 *
	 * @param string $str
	 */
	static public function CorrectCountryAdjective($str){
		$str			= strtolower($str);
		$lastLiter		= $str[strlen($str)-1];
		$preLastLiter	= $str[strlen($str)-2];
		if ($lastLiter == '�' || $lastLiter == '�'){
			return substr($str,0,strlen($str)-1).'�����';
		}
		if ($lastLiter == '�' && $preLastLiter != '�'){
			return $str.'�����';
		}
		if ($preLastLiter.$lastLiter == '��'){
			return substr($str,0,strlen($str)-1).'����';
		}
		
		return $str.'����';
	}
	
	
	/**
	 * ������� ������� ������ � ��������� � ���������
	 *
	 * @param 	string	$text
	 * @return	string	
	 */
	function win2translit($text)
	{
		return iconv("WINDOWS-1251", "ISO-8859-1//TRANSLIT", $text);
	}
	
	
	/**
	 * ��������� �� 0.5
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