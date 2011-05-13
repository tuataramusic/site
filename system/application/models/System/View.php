<?php
/**
 * Класс работы с шаблонами
 */
class View
{
	/**
	 * Данные, передаваемые в шаблоны
	 *
	 * @var array
	 */
	public static $data = array();
	
	/*
	 * Главный шаблон
	 * 
	 * @var string
	 */
	public static $main_view = '/main/index';
	
	/**
	 * Вложенный шаблон
	 *
	 * @var string
	 */
	public static $child_name = 'content';
	
	/**
	 * Показать шаблон
	 *
	 * @param string $view Имя шаблона
	 * @param array $data Массив данных, передаваемых в шаблон
	 * @param boolean $use_common_data Использовать данные из View:$data ?
	 */
	public static function show($view, $data=array(), $use_common_data=true)
	{
		if (!is_array($data))
		{
			$data = array($data);
		}
		if ($use_common_data)
		{
			self::$data = $data + self::$data;
			get_instance()->load->view($view, self::$data);
		}
		else 
		{
			get_instance()->load->view($view, $data);
		}
	}
	
	/**
	 * Показать главный шаблон и внутренний шаблон
	 *
	 * @param string $child_view Имя вложенного шаблона
	 * @param array $data Массив данных, передаваемых в шаблон
	 * @param string $main_view Имя главного шаблона
	 * @param boolean $use_common_data Использовать данные из View:$data ?
	 */
	public static function showChild($child_view, $data=array(), $child_name='', $main_view='', $use_common_data=true)
	{
		if (!is_array($data))
		{
			$data = array($data);
		}
		
		if (empty($main_view))
		{
			$main_view = self::$main_view;
		}
		if (empty($child_name))
		{
			$child_name = self::$child_name;
		}
		$data = array($child_name => $child_view) + $data;
		self::show($main_view, $data, $use_common_data);
	}
	
	/**
	 * Показать шаблон по имени, передаваемому в данных
	 *
	 * @param string $from_data_view Имя ключа массива, содержащего название показываемого шаблона
	 * @param array $data Массив данных, передаваемых в шаблон
	 */
	public static function showFromData($from_data_view, $data=array(), $use_common_data=true)
	{
		if (isset(self::$data[$from_data_view])){
			self::show(self::$data[$from_data_view], $data, $use_common_data);			
		}
	}
	
	/**
	 * Получить html-код заполненного шаблона
	 *
	 * @param string $view Имя шаблона
	 * @param array $data Массив данных, передаваемых в шаблон
	 * @param boolean $use_common_data Использовать данные из View:$data ?
	 */
	public static function get($view_name, $data=array(), $use_common_data=true)
	{
		ob_start();
			self::show($view_name, $data, $use_common_data);
			$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	
}
?>
