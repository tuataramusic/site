<?php
/**
 *	Класс для работы с ajax
 */
class Ajax
{
	/**
	 * Данные (кроме ошибок), передаваемые через ajax
	 *
	 * @var unknown_type
	 */
	static $data;
	
	/**
	 * Передача результата ajax-запроса в виде HTML
	 *
	 * @param string $html
	 */
	static public function returnHTML($html)
	{
		header('Content-Type: text/javascript; charset=windows-1251');
		echo $html;
	}
	
	/**
	 * Передача результата ajax-запроса в виде JSON
	 *
	 * @param unknown_type $var
	 */
	static public function returnJSON($var)
	{
		header('Content-Type: text/javascript; charset=utf-8');
		$var = Func::win2utf($var);
		echo json_encode($var);
	}
	
	/**
	 * Передача результата и ошибок ajax-запроса в виде JSON
	 *
	 */
	static public function returnAll($data = null)
	{
		$return_data = (is_null($data)) ? self::$data : $data;
		self::returnJSON(
							array(
									//AJAX_ERRORS => Error::getErrors4Ajax(), - этот класс у нас в данной версии отсутствует
									AJAX_DATA => $return_data
							)
		);
		self::$data = null;
	}
	
	/**
	 * Передача результата или ошибок ajax-запроса в виде JSON
	 *
	 */
	static public function returnData($data = null)
	{
		$errors = 1;//Error::getErrors4Ajax();
		if ($errors == 1/*AJAX_RETURN_OK*/)
		{
			$return_data = (is_null($data)) ? self::$data : $data;
		}
		else 
		{
			$return_data = '';
		}
		self::returnJSON(
							array(
									'AJAX_ERRORS'	=> $errors,
									'data'		=> $return_data
							)
		);
		self::$data = null;
	}
	
	/**
	 * Передача ошибок ajax-запроса в виде JSON
	 *
	 */
	static public function returnErrors($error_msg = null)
	{
//		$errors = Error::getErrors4Ajax();
		if (!is_null($error_msg))
		{
			$errors[''] = $error_msg;
		}
		else $errors[''] = 0;
		self::returnJSON(array('error' => $errors));
	}
	
	/**
	 * Передача null результата ajax-запроса в виде JSON
	 *
	 * @param unknown_type $var
	 */
	static public function returnNull($var)
	{
		header('Content-Type: text/javascript; charset=utf-8');
//		$var = Func::win2utf($var);
		echo json_encode($var);
	}	
	
}
?>