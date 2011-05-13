<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
 * Базовый контроллер
 *
 */

require_once BASE_CONTROLLERS_PATH.'BaseController'.EXT;

class ManagerBaseController extends BaseController 
{
	function __construct()
	{
		parent::__construct();
		
		$user	= Check::user();
		if (!$user || $user->user_group !== 'manager'){
			Func::redirect('/');
			die('Access restricted');
		}
	}
}
?>