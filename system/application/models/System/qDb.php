<?php
/**
 * Singleton
 * Very simple and quick database class
 * Don`t used core
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'ksi');
define('DB_PASS', 'ahZai7id');
define('DB_NAME', 'auto');

class DB
{
	private static $instance;
	private static $handle;
	private static $qdump	= array();
	private static $edump	= array(); 
	
	
	private function __construct()
	{
		self::$handle = mysql_pconnect(DB_HOST, DB_USER, DB_PASS);
		if (!self::$handle){
			self::$instance = null;
			throw new Exception(mysql_error(), mysql_errno());
		}
		
		mysql_selectdb(DB_NAME, self::$handle);
		
	}
	
	
	static public function getInstance()
	{
        if (!isset(self::$instance)) {
        	$self = __CLASS__;
            self::$instance = new $self;
        }

        return self::$handle;		
	}
	
	
	/**
	 * Select
	 *
	 * @param mixed		$fields
	 * @param mixed		$from
	 * @param string	$where - must be string condition
	 */
	static public function select($fields = '*', $from, $where = 1, $opt = '')
	{
		
		if (is_array($fields) || is_object($fields)){
			$fields = implode(",", $fields);
		}
		
		if (is_array($from) || is_object($from)){
			$from	= implode(",", $from);
		}
		
		$query	= "SELECT ".$fields." FROM ".$from." WHERE ".$where." ".$opt;
		
		return self::featchResult(self::query($query));
	}
	
	
	/**
	 * Insert
	 *
	 * @param	mixed	$fields
	 * @param	string	$into
	 * @param	string	$where
	 * @return	mixed
	 */
	static public function insert($fields, $into, $escape_fields = true)
	{
		if (is_object($fields)){
			$fields = (array) $fields;
		}
		
		if ($escape_fields){
			$fields = self::esc($fields, true);
		}
		
		$_f = implode("`,`", array_keys($fields));
		$_v = implode("','", array_values($fields));
		
		$query = "INSERT INTO `$into` (`$_f`) VALUES ('$_v')";
		
		if (!self::query($query))	return false;
		
		return true;
	}
	
	
	/**
	 * Raw mysql query
	 *
	 * @param	string 	$query
	 * @param	boolean	$esc
	 * @return	mixed
	 */
	static public function query($query, $esc = true)
	{
		
		if (!self::$handle) self::getInstance();
		
		if ($esc) $query = self::esc($query);
		
		$result = mysql_query($query);
		
		$dump	= new stdClass();
		$dump->query	= $query;
		$dump->result	= !$result ? 0 : 1;
		$dump->error	= !$result ? mysql_error() : '';
		self::$qdump[]	= $dump;
		if (!$result){
			self::$edump[] = $dump;
		}
		
		return $result;
		
	}
	
	/**
	 * Featch mysql query result
	 *
	 * @param	mysql resource	$source
	 * @param 	string			$returnAs - return as object or array
	 */
	static public function featchResult($source, $returnAs = 'object'/* object/array */)
	{
		if ($returnAs != 'array' && $returnAs != 'object') {
			$err = __CLASS__.'::'.__METHOD__.' ERROR: Wrong parameter "returnAs", must be "array" or "object"';
			throw new Exception($err);
			die($err);
		}
		
		if ($source){

			if ($returnAs == 'array')	$returnAs = 'assoc';
			
			$featch = "mysql_fetch_".$returnAs;
			$data	= array();
			
			while ($data[] = $featch($source)){}
			
			array_pop($data);
	
			mysql_free_result($source);
			
			return (count($data) == 1 ? array_shift($data) : $data);
			
		}

		return false;
	}
	
	
	/**
	 * Get last insert id
	 *
	 * @param	handle	$handle
	 * @return	int
	 */
	static public function getLastInsertID()
	{
		$err = __CLASS__.'::'.__METHOD__.' ERROR: Database not connected of handle fail';
		if (!self::$handle) throw new Exception($err);
		
		return mysql_insert_id();
	}
	
	
	/**
	 * Get queries dump
	 * 
	 */
	static public function getQueryDump($only_error_queries=0, $flag=0)
	{
		echo '<pre>';
		if ($flag == 1){
			var_dump($only_error_queries ? self::$edump : self::$qdump);
		}else{
			print_r($only_error_queries ? self::$edump : self::$qdump);
		}
		echo '</pre>';
	}
	
	
	/**
	 * Public escape function 
	 *
	 * @param	mixed	$var
	 * @return	mixed
	 */
	static public function esc($var, $is_inner_data = false)
	{
		return $var;
		if (!get_magic_quotes_gpc() || $is_inner_data){
			
			if (is_object($var)){
				foreach ($var as $key => $val){
					$var->$key = self::esc($val, true);
				}
				return $var;
			}else if (is_array($var)){
				foreach ($var as $key => $val){
					$var[$key] = self::esc($val, true);
				}
				return $var;
			}else{
				if (!is_numeric($var)){
					return mysql_real_escape_string($var);
				}else if (is_float($var)){
					return (float) $var;
				}else{
					return (int) $var;
				}
			}
				
		}else{
			return $var;
		}
	}
	
	
	/**
	 * Public function
	 *
	 * @param	string	$var
	 * @return	string
	 */
	public function trim_n_clear($var, $delete_spaces = false)
	{
		$killer = array("\r","\n","\t");
		
		if ($delete_spaces)
			array_push($killer, "\040");
		
		return str_replace($killer, "",trim($var));
	}
}