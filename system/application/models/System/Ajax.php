<?php
/**
 *	����� ��� ������ � ajax
 */
class Ajax
{
	/**
	 * ������ (����� ������), ������������ ����� ajax
	 *
	 * @var unknown_type
	 */
	static $data;
	
	/**
	 * �������� ���������� ajax-������� � ���� HTML
	 *
	 * @param string $html
	 */
	static public function returnHTML($html)
	{
		header('Content-Type: text/javascript; charset=windows-1251');
		echo $html;
	}
	
	/**
	 * �������� ���������� ajax-������� � ���� JSON
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
	 * �������� ���������� � ������ ajax-������� � ���� JSON
	 *
	 */
	static public function returnAll($data = null)
	{
		$return_data = (is_null($data)) ? self::$data : $data;
		self::returnJSON(
							array(
									//AJAX_ERRORS => Error::getErrors4Ajax(), - ���� ����� � ��� � ������ ������ �����������
									AJAX_DATA => $return_data
							)
		);
		self::$data = null;
	}
	
	/**
	 * �������� ���������� ��� ������ ajax-������� � ���� JSON
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
	 * �������� ������ ajax-������� � ���� JSON
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
	 * �������� null ���������� ajax-������� � ���� JSON
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