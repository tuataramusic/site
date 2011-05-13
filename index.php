<?php
$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (stripos($user_agent, 'MSIE 6.0') !== false && stripos($user_agent, 'MSIE 8.0') === false && stripos($user_agent, 'MSIE 7.0') === false) {
   if (!isset($HTTP_COOKIE_VARS["ie"])) {setcookie("ie", "yes", time()+60*60*24*360);header ("Location: static/ie6/ie6.html");}
}
/*
|---------------------------------------------------------------
| PHP ERROR REPORTING LEVEL
|---------------------------------------------------------------
|
| By default CI runs with error reporting set to ALL.  For security
| reasons you are encouraged to change this when your site goes live.
| For more info visit:  http://www.php.net/error_reporting
|
*/
	error_reporting(E_ALL);

/*
|---------------------------------------------------------------
| SYSTEM FOLDER NAME
|---------------------------------------------------------------
|
| This variable must contain the name of your "system" folder.
| Include the path if the folder is not in the same  directory
| as this file.
|
| NO TRAILING SLASH!
|
*/
	$system_folder = "system";

/*
|---------------------------------------------------------------
| APPLICATION FOLDER NAME
|---------------------------------------------------------------
|
| If you want this front controller to use a different "application"
| folder then the default one you can set its name here. The folder 
| can also be renamed or relocated anywhere on your server.
| For more info please see the user guide:
| http://codeigniter.com/user_guide/general/managing_apps.html
|
|
| NO TRAILING SLASH!
|
*/
	$application_folder = "application";

/*
|===============================================================
| END OF USER CONFIGURABLE SETTINGS
|===============================================================
*/


/*
|---------------------------------------------------------------
| SET THE SERVER PATH
|---------------------------------------------------------------
|
| Let's attempt to determine the full-server path to the "system"
| folder in order to reduce the possibility of path problems.
| Note: We only attempt this if the user hasn't specified a 
| full server path.
|
*/
if (strpos($system_folder, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
	{
		$system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
	}
}
else
{
	// Swap directory separators to Unix style for consistency
	$system_folder = str_replace("\\", "/", $system_folder); 
}

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| SELF		- The name of THIS file (typically "index.php")
| FCPATH	- The full server path to THIS file
| BASEPATH	- The full server path to the "system" folder
| APPPATH	- The full server path to the "application" folder
|
*/
define('EXT', '.php');
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', str_replace(SELF, '', __FILE__));
define('BASEPATH', $system_folder.'/');

define('BASEURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
define('IMG_PATH', '/static/images/');
define('CSS_PATH', '/static/css/');
define('JS_PATH','/static/js/');
define('UPLOAD_DIR', (getcwd()).'/upload/');

if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ($application_folder == '')
	{
		$application_folder = 'application';
	}

	define('APPPATH', BASEPATH.$application_folder.'/');
}

define('MODELS_PATH', APPPATH.'models/');
define('SYS_MODELS_PATH', APPPATH.'models/System/');
define('VIEWS_PATH', APPPATH.'views/');
define('CONTROLERS_PATH', APPPATH.'controllers/');
define('BASE_CONTROLLERS_PATH', APPPATH.'controllers/Base/');


/*
|---------------------------------------------------------------
| LOAD THE FRONT CONTROLLER
|---------------------------------------------------------------
|
| And away we go...
|
*/
// не так сразу...
require_once APPPATH.'config/sys'.EXT;
require_once APPPATH.'config/interfaces'.EXT;
require_once APPPATH.'config/errors'.EXT;
require_once APPPATH.'config/payments'.EXT;
require_once SYS_MODELS_PATH.'View'.EXT;
require_once SYS_MODELS_PATH.'Check'.EXT;
require_once SYS_MODELS_PATH.'Ajax'.EXT;
require_once SYS_MODELS_PATH.'Func'.EXT;
require_once SYS_MODELS_PATH.'Stack'.EXT;
require_once SYS_MODELS_PATH.'qDb'.EXT;
require_once SYS_MODELS_PATH.'Log'.EXT;
require_once SYS_MODELS_PATH.'PayLog'.EXT;
require_once SYS_MODELS_PATH.'Breadcrumb'.EXT;
// а вот теперь можно и ГО...
require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;



