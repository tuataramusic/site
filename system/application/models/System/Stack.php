<?
/**
 * Вспомогательные функции
 *
 * @author omni
 * 
 * Класс эмулирует работу многомерного стека (использует встроенные пхп-сессии)
 * Класс служит для внутренней межсессионной передачи данных
 * 
 */
class Stack
{
	private static $stack;
	private static $stack_name = 'default_stack';
	
	
	/**
	 * Check stack
	 *
	 */
	static private function create($stack_name = null){
		
		if (!session_id()){
			session_start();
		}
		
		if ($stack_name){
			self::$stack_name = $stack_name;
		}
		
		if (!isset($_SESSION['stack'])){
			$_SESSION['stack']	= array(self::$stack_name => array());
		}elseif (!isset($_SESSION['stack'][self::$stack_name])){
			$_SESSION['stack'][self::$stack_name]	= array();
		}
		
		if (!self::$stack){
			self::$stack = &$_SESSION['stack'];
		}
	}
	
	
	static public function getStackName(){
		return self::$stack_name;
	}
	
	
	static public function setStackName($stack_name){
		self::$stack_name = $stack_name;
	}	
	
	
	/**
	 * Get all steck
	 *
	 */
	static public function getall(){
		
	}
	
	
	/**
	 * Get current
	 *
	 */
	static public function get(){
		
	}	
	
	
	/**
	 * Get size of stack
	 *
	 */
	static public function size($stack_name = null){
		self::create($stack_name);
		
		return count(self::$stack[self::$stack_name]);
	}
	
	
	/**
	 * Get last item in stack
	 *
	 */
	static public function last($stack_name = null){

		self::create($stack_name);
		
		if (self::size() > 0){
			return self::$stack[self::$stack_name][self::size()-1];
		}else{
			return false;			
		}		
		
	}
	
	
	/**
	 * Insert item in stack move frame
	 *
	 */
	static public function push($stack_name = null, $data){
		
		self::create($stack_name);
		
		if (array_push(self::$stack[self::$stack_name], $data)){
			return true;
		}else{
			return false;			
		}
	}
	
	
	/**
	 * Extract last item in stack move frame
	 *
	 */
	static public function shift($stack_name = null, $clear = false){
		
		self::create($stack_name);
		
		if (count(self::$stack[self::$stack_name])>0){
			$data = array_shift(self::$stack[self::$stack_name]);
			if ($clear){
				self::clear($stack_name);
			}
			return $data;
		}else{
			return false;			
		}		
		
	}
	
	
	/**
	 * Clear stack
	 *
	 */
	static public function clear($stack_name = null){
		
		self::create($stack_name);
		
		self::$stack[self::$stack_name] = array();
	}
	
	
	
	
}
?>