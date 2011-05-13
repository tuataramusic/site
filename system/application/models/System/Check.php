<?
/**
 * ����� �������� ����������, ����������� �� ������������
 *
 */
class Check
{
	private static $empties = array();	
	
	/**
	 * �������� ��������� ���������� �� ������������ �������
	 *
	 * @param string $str
	 * @return string
	 */
	public static function stripStr($str)
	{
		if ($str === false)
		{
			return '';
		}
		$chars0_31 = '';
		for ($i = 0; $i <= 31; $i ++)
		{
			$chars0_31 .= chr($i);
		}		
		
		$str = htmlspecialchars(stripslashes($str));
		$str = str_replace("\r\n", " <br>", $str);
		$str = str_replace("\\", "", $str);
		$str = str_replace("'", "&#39;", $str);
		$str = str_replace('"', "&quot;", $str);
		$str = strtr($str, $chars0_31, str_repeat(" ", 32));
		$str = trim($str);
		return $str;
	}
		
	/**
	 * �������� ��������� ���������� �� POST �� ������� 'y', 'n'
	 *
	 * @param string $$str
	 * @return string
	 */
	public static function yn($varname, $def=N_STR)
	{
		$yn = isset($_POST[$varname])?$_POST[$varname]:$def;
		$n_def = ($def == N_STR) ? Y_STR : N_STR; 
		return ($yn == $n_def) ? $n_def : $def;
	}
	
	/**
	 * �������� ��������� ���������� �� ������������ �������
	 *
	 * @param string $str
	 * @return string
	 */
	public static function stripText($str)
	{
		if ($str === false)
		{
			return '';
		}
		$str = htmlspecialchars(stripslashes($str), ENT_QUOTES);
		$str = trim($str, " \r\n\t\0\x0B\\");
		return $str;
	}
	
	/**
	 * �������� ������ �������� �� POST ��� GET
	 *
	 * @param string $varname ��� ����������
	 * @param int $to ������������ ��������
	 * @param int $from ����������� ��������
	 * @param int $def_value �������� �� ���������. ���� �� �����������, �� ������� ��������� ������
	 * @return int
	 */
	public static function int($varname, $to=MaxInt, $from=0, $def_value=null)
	{
		$int = isset($_POST[$varname])?$_POST[$varname]:(isset($_GET[$varname])?$_GET[$varname]:$def_value);
		
		if ($int !== false && is_numeric($int) && $int >= $from && $int <= $to)
		{
			return (int) $int;
		}
		else
		{
			self::$empties[] = $varname;
			if (is_null($def_value))
			{
				return false;
			}
			else
			{
				return $def_value;
			}
		}
	}
	
	/**
	 * �������� �������� � ��������� ������ �� POST ��� GET
	 *
	 * @param string $varname ��� ����������
	 * @param float $to ������������ ��������
	 * @param float $from ����������� ��������
	 * @param float $def_value �������� �� ���������. ���� �� �����������, �� ������� ��������� ������
	 * @return float
	 */
	public static function float($varname, $def_value=null)
	{
		$float = isset($_POST[$varname])?$_POST[$varname]:(isset($_GET[$varname])?$_GET[$varname]:$def_value);
	
		if ($float !== false && (is_float($float) || is_numeric($float)))
		{
			return (float) $float;
		}
		else
		{
			self::$empties[] = $varname;
			if (is_null($def_value))
			{
				return false;
			}
			else
			{
				return $def_value;
			}
		}
	}

	/**
	 * �������� ��������� ���������� �� POST ��� GET
	 *
	 * @param string $varname ��� ����������
	 * @param int $maxlen ������������ �����
	 * @param int $minlen ����������� �����
	 * @param string $def �������� �� ���������
	 * @return string
	 */
	public static function str($varname, $maxlen, $minlen=0, $def=null, $charset_from = "WINDOWS-1251", $charset_to = "WINDOWS-1251")
	{
		$str = isset($_POST[$varname])?$_POST[$varname]:(isset($_GET[$varname])?$_GET[$varname]:$def);
		if ($charset_from != $charset_to && $str !== false)
		{
			$str = iconv($charset_from, $charset_to, $str);
		}
		
		if (/*$str !== false && */strlen($str) >= $minlen && strlen($str) <= $maxlen)
		{
			return Check::stripStr($str);
		}
		elseif (!is_null($def))
		{
			self::$empties[] = $varname;
			return $def;
		}
		self::$empties[] = $varname;
		return false;
	}
	
	/**
	 * �������� ������� ������� �� POST
	 *
	 * @param string $varname ��� ����������
	 * @param int $maxlen ������������ �����
	 * @return array
	 */
	public static function arrayText($varname, $maxlen)
	{
		$arr = $_POST[$varname];
		$new_arr = array();
		
		if ($arr !== false && is_array($arr))
		{
			foreach ($arr as $k=>$v)
			{
				if (strlen($v) <= $maxlen)
				{
					$new_arr[$k] = Check::stripText($v);
				}
			}
		}
		return $new_arr;
	}

	/**
	 * �������� ������� �������� ���������� �� POST
	 *
	 * @param string $varname ��� ����������
	 * @param int $max_key ������������ �������� �����
	 * @param int $min_key ����������� �������� �����
	 * @param string $max_value ������������ �������� ����������
	 * @param string $min_value ������������ �������� ����������
	 * @return array
	 */
	public static function arrayInt($varname, $max_key=MaxInt, $min_key=0, $max_value=MaxInt, $min_value=0)
	{
		$arr = $_POST[$varname];
		$new_arr = array();
		
		if ($arr !== false && is_array($arr))
		{
			foreach ($arr as $k=>$v)
			{
				if (is_numeric($k) && $k <= $max_key && $k >= $min_key)
				{
					if (is_numeric($v) && $v <= $max_value && $v >= $min_value)
					{
						$new_arr[(int)$k] = (int)($v);
						continue;
					}
				}
				//Error::setError(ERROR_INPUT_INT_KEY, array($min_key, $max_key), array('html_input_id' => $varname));
			}
		}
		return $new_arr;
	}

	/**
	 * �������� ��������, ����������� �� checkbox-� ����� POST
	 *
	 * @param string $varname ��� ����������
	 * @return array
	 */
	public static function chkbox($varname)
	{
		$str = $_POST[$varname];
		return (empty($str)) ? 0 : 1;
	}

	/**
	 * �������� ��������� ���������� �� POST
	 *
	 * @param string $varname ��� ����������
	 * @param int $maxlen ������������ �����
	 * @param int $minlen ����������� �����
	 * @return string
	 */
	public static function txt($varname, $maxlen, $minlen=0, $def=null, $charset_from = "WINDOWS-1251", $charset_to = "WINDOWS-1251")
	{
		$txt = $_POST[$varname];
		if ($charset_from != $charset_to && $txt !== false)
		{
			$txt = iconv($charset_from, $charset_to, $txt);
		}
		if ($txt !== false && strlen($txt) >= $minlen && strlen($txt) <= $maxlen)
		{
			return Check::stripText($txt);
		}
		else
		{
			self::$empties[] = $varname;
			if (is_null($def))
			{
				return false;
			}
			else 
			{
				return $def;
			}
		}
	}
	
	/**
	 * �������� ������ ��������
	 *
	 * @param int $int ��������, ������� ����� ���������
	 * @param int $to ������������ ��������
	 * @param int $from ����������� ��������
	 * @param int $def_value �������� �� ���������. ���� �� �����������, �� ������� ��������� ������
	 * @return int
	 */
	public static function var_int($int, $to=MaxInt, $from=0, $def_value=null)
	{
		if ($int !== false && is_numeric($int) && $int >= $from && $int <= $to)
		{
			return (int) $int;
		}
		else
		{
			if (is_null($def_value))
			{
				return false;
			}
			else
			{
				return $def_value;
			}
		}
	}
	
	/**
	 * �������� �������� � ��������� ������
	 *
	 * @param float $float ��������, ������� ����� ���������
	 * @param float $to ������������ ��������
	 * @param float $from ����������� ��������
	 * @param float $def_value �������� �� ���������. ���� �� �����������, �� ������� ��������� ������
	 * @return float
	 */
	public static function var_float($float, $to=MaxFloat, $from=0, $def_value=null)
	{
		if ($float !== false && is_numeric($float) && $float >= $from && $float <= $to)
		{
			return (float) $float;
		}
		else
		{
			if (is_null($def_value))
			{
				return false;
			}
			else
			{
				return $def_value;
			}
		}
	}
	
	/**
	 * �������� ��������� ����������
	 *
	 * @param string $str ��������, ������� ����� ���������
	 * @param int $maxlen ������������ �����
	 * @param int $minlen ����������� �����
	 * @param string $def �������� �� ���������
	 * @return string
	 */
	public static function var_str($str, $maxlen, $minlen=0, $def=null, $charset_from = "WINDOWS-1251", $charset_to = "WINDOWS-1251")
	{
		if ($charset_from != $charset_to && $str !== false)
		{
			$str = iconv($charset_from, $charset_to, $str);
		}
		
		if (/*$str !== false && */strlen($str) >= $minlen && strlen($str) <= $maxlen)
		{
			return Check::stripStr($str);
		}
		elseif (!is_null($def))
		{
			self::$empties[] = '_str';
			return $def;
		}
		self::$empties[] = '_str';
		return false;
	}
	
	public static function reset_empties()
	{
		self::$empties = array();
	}
	
	public static function get_empties()
	{
		return count(self::$empties) ? self::$empties : false;
	}
	
	
	public function latin($varname, $maxlen, $minlen=0, $def=null, $charset_from = "WINDOWS-1251", $charset_to = "WINDOWS-1251"){
		
		$str = isset($_POST[$varname])?$_POST[$varname]:(isset($_GET[$varname])?$_GET[$varname]:$def);
		
		$str = self::var_latin($str, $maxlen, $minlen, $def, $charset_from, $charset_to);
		
		if ($str === $def){
			self::$empties[] = $varname;
		}
		
		return $str;
		
	}
	
	public function var_latin($str, $maxlen, $minlen=0, $def=null, $charset_from = "WINDOWS-1251", $charset_to = "WINDOWS-1251"){
		
		$str = self::var_str($str, $maxlen, $minlen, $def, $charset_from, $charset_to);

		if ($str !== $def){
			preg_match("/[�-�]/s",$str, $m);
			
			if (!count($m) || $str ===''){
				return $str;
			}
		}

		self::$empties[]	= '_latin';
		return $def;		

	}
	
	
   /**
	* �������� �� ������������� ������
	*
	* @author CopyCat (Alekseenko E.)
	* @param string $domain  ��� ������, ������� ����� ���������
	* @return false - ���� �� ����������, �� ���� ������ ������� ���������
	*/
    public static  function validate_exist_domain($domain)
    {
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); 
		$connection = @socket_connect($socket, $domain, 80);
		return $connection; 		
	}
	
	
    /**
     * @author omni
     * ��������� ��������� ������� � ��
     * �� ����� �� ��� �� �����? ������ � ��� �������?
     * 
     * @param (object)		- ������ ��
     */
    public static function query($result)
    {
		if (empty($result))
		{
			return false;
		}
		else if ($result->num_rows())
		{
			return $result;	
		}    	
    }
    
    /**
     * ��������� ����� �� ����������
     *
     */
    public static function user()
    {
    	$ci		= get_instance();
    	$ci->load->model('UserModel', 'User');
    	
    	$props	= $ci->User->getPropertyList();
    	
		$user	= new stdClass();
		foreach ($props as $prop){
			$user->$prop	= $ci->session->userdata($prop);
		}
		
    	if ($user->user_id && !$user->user_deleted){
    		return $user;
    	}		
    	
    	return false;
    }
    
    /**
     * �������� ������ ������� ������������
     *
     * @param string $user_group - ������ � ������� ������ ������������ ������������ ������������ (admins/managers/clients/all(all exists))
     * @return boolean
     */
    public static function access($user_group)
    {
    	$user = self::user();
    	if ($user && $user->user_group==$user_group){
    		return true;
    	}elseif ($user && $user_group=='all'){
    		return true;
    	}
    	
    	return false;
    }
    
    
    /**
     * �������� ����
     * 
     * @param string $mail
     * @return boolean
     */
	public function email($mail)
	{
// !�������� ����� filter_var �������� ������� � ����� ���������� �� ���������� ���������
//		$m = filter_var($mail, FILTER_VALIDATE_EMAIL);
//		if (!$m){
		if (!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $mail))
		{
			self::$empties[] = '_email';
		}
		return $mail;
		

	}
	
	/**
	 * �������� ������ �����
	 * 
	 * @param string $url
	 * @return boolean
	 */
	public static function url($url)
	{		
		if (preg_match('/^(http|https):\/\/([a-z0-9][-a-z\d]*[a-z\d]\.)+[a-z][-a-z\d]*[a-z]/i', $url))
			return true;
		else
			return false;		
	} 
	
	public static function idsByFilter($pattern, $checkValue=null, $request_method = 'POST') {
		$checked_ids 	= array();
		$pattern 		= preg_quote($pattern);
		
		if ($request_method == 'POST') 	$request_array	= $_POST;
		if ($request_method == 'GET') 	$request_array	= $_GET;
		
		foreach ($request_array as $key => $value) // ������� ���������� ��������
	    {
	        if (preg_match('#^'.$pattern.'([0-9]+)$#', $key, $matches))
	        {
	            if (@$request_array[$pattern.$matches[1]] == $checkValue || $checkValue === NULL)
	            {
	            	$checked_ids[] = $matches[1];
	            }
	        }
	    }
	    return $checked_ids;
	}
	
}