<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
require_once BASE_CONTROLLERS_PATH.'UpdateController'.EXT;

/**
 * Базовый контроллер
 *
 */
class AdminBaseController extends UpdateController 
{
	function __construct()
	{
		parent::__construct();
		
		if (!Check::access('admin')){
			Func::redirect('/');
			die('Access restricted');
		}
		
		Breadcrumb::setCrumb(array('admin'=> 'Кабинет администратора'),0, false);
	}
}
?>