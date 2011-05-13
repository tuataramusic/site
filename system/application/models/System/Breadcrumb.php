<?php
/**
 * Класс работы с хлебными крошками
 * @uses Stack
 * 
 * $crumb = array('index' => 'name');
 * 
 */
class Breadcrumb {
	
	static private $crumbs	= array();
	static private $instance;
	

	private function getCrumbStack(){
		
	}
	
	static public function clearCrumb(){
		
	}
	
	
	/**
	 * Устанавливает крошку в определенный сегмент
	 *
	 * @param array $crumb
	 * @param int	$segment
	 * @param bool	$clearAfter - чистит крошки после указанного сегмента
	 */
	static public function setCrumb(array $crumb,$segment, $clearAfter = false){

		if (!isset($_SESSION['breadcrumb'])){
			$_SESSION['breadcrumb'] = array();
		}
		
		$_SESSION['breadcrumb'][$segment]	= $crumb;
		
		if ($clearAfter){
			array_splice($_SESSION['breadcrumb'], $segment+1);
		}
	}
	
	static public function getCrumbs($segment = null){
		
		ksort($_SESSION['breadcrumb']);
		$crumbs = array();
		$cState = null;
		
		foreach ($_SESSION['breadcrumb'] as $crumb)		{
			list($method, $pagename) = each($crumb);
			if (!$cState){
				$crumbs[]	= '<a href="/'.$method.'">'.$pagename.'</a>';	
				$cState = $method;
			}else{
				$crumbs[]	= '<a href="/'.$cState.'/'.$method.'">'.$pagename.'</a>';
			}
			
		}
		
		return join('->', $crumbs);
	}
	
	static public function showCrumbs($segment = null){
		echo self::getCrumbs($segment);
	}
	
}



?>
