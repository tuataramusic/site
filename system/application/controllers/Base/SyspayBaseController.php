<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
 * Ѕазовый контроллер клиента.
 * »мена переменных и загружаемых сдесь моделей начинаютс€ с "__", дабы исключить возможность перегрузки онных
 *
 */

require_once BASE_CONTROLLERS_PATH.'BaseController'.EXT;

class SyspayBaseController extends BaseController 
{
	function __construct()
	{
		parent::__construct();
		
//		$user	= Check::user();
//		if (!$user){
//			Func::redirect('/');
//			die('Access restricted');
//		}
		
		#$this->load->helper('ssl');
		#ssl_on();
	}
}
?>