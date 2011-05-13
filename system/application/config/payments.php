<?
//Общее: адреса скриптов
define('TESTMODE', 1); //В большинстве случаев при 1 будут проходить тестовые платежи
//Для работы нужно установить в 0

define('SUCCESS_URL',	"http://countrypost.ru/syspay/showSuccess");
define('FAIL_URL',		"http://countrypost.ru/syspay/showFail");
define('RESULT_URL',	"http://countrypost.ru/syspay/showResult");
define('ADMIN_EMAIL',	"info@countrypost.ru");

//$success_url="http://countrypost.ru/success.php";
//$fail_url="http://countrypost.ru/fail.php";
//$admin_email="info@countrypost.ru";
###########################################################################################


//WebMoney
//define('WM_PURSE',			"R335456041886");
//define('WM_SUCCESS_URL',	       "http://countrypost.ru/syspay/showSuccessWM");
//define('WM_FAIL_URL',		"http://countrypost.ru/syspay/showFail");
//define('WM_RESULT_URL',		"http://countrypost.ru/syspay/showResult");
//define('WM_SECRET_KEY',		"key");

define('WM_PURSE',			"R165356359023");
define('WM_SUCCESS_URL',	       "http://countrypost.ru/syspay/showSuccessWM");
define('WM_FAIL_URL',		"http://countrypost.ru/syspay/showFail");
define('WM_RESULT_URL',		"http://countrypost.ru/syspay/showResultWM");
define('WM_SECRET_KEY',		"key");
define('WM_IN_TAX',0.8);

//$wm_purse="R335456041886";
//$wm_result_url="http://countrypost.ru/wm.php";
//$wm_secret_key="secretkey";
###########################################################################################

//RoboKassa
//define('RK_LOGIN', 'Craftsman1');
define('RK_LOGIN', 'Craftsman1');
//define('RK_PASS1', '658236a5');
define('RK_PASS1', '658236a5');
define('RK_PASS2', '490743r5');
//define('RK_PASS2', '490743r5');

#$rk_login="Craftsman1";
#$rk_pass1="658236a5";
#$rk_pass2="490743r5";
define('RK_IN_TAX',3);
###########################################################################################

//W1
//define('W1_WALLET', '103853778255');
//define('W1_KEY', 'jugla4khn2');
define('W1_WALLET', '135670173257');
define('W1_PASS', 'AyDcbD');
define('W1_KEY', 'bU9RVUpYbU00aEJwT0VCX2NxOHhue1NgWG9a');

#$w1_wallet="103853778255";
#$w1_key="jugla4khn2"; //Лучше не использовать кнопку "Сгенерировать" в админке, т.к. слишком длинный код иногда вызывает проблемы с ЭЦП
define('W1_IN_TAX',4);
###########################################################################################


//LiqPay
//define('LP_MERCHANT_ID', 'i0327037845');
//define('LP_MERCHANT_PASSWORD', 'YB1zi3hLHCJeXEo9ZeIfcLMT56Ydw');
define('LP_MERCHANT_ID', 'i2498933264');
define('LP_MERCHANT_SIG1', 'x1XA6xyodERIWefQAR3sSbpdOo1Af0bmoY5Um');
define('LP_MERCHANT_SIG2', 'OPy4OGrEhcbUa1uaiWNlzh970lUfBv93seO8wVLj');
define('LP_RESULT_URL', 'http://omni.kio.teralabs.ru/syspay/showResultLP');
define('LP_SERVER_URL', 'http://omni.kio.teralabs.ru/syspay/showServerLP');

#$lp_merchant_id="i0327037845";
#$lp_merchant_password="YB1zi3hLHCJeXEo9ZeIfcLMT56Ydw";
#$lp_result_url="http://countrypost.ru/lp_result.php"; // success/fail
#$lp_server_url="http://countrypost.ru/lp.php";
define('LP_IN_TAX',3);
?>