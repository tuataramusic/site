<?

interface IModel 
{
	public function getList();
	public function getPK();
	public function getTable();
	public function getPropertyList();
}

interface IFileModel
{
	/**
	 * Заргузка файла на сервер
	 *
	 * @return (object)	$resource	- объект ресурса создаваемого при загрузке файла, содержит информацию о файле,
	 * а так же объект-дескриптор данного ресурса, что бы иметь доступ к данному ресурсу.
	 * 
	 */	
	public function upload();
	
	
	/**
	 * Линковка файла
	 *
	 * @param (int)		$resId		- указатель на ресурс
	 * @param (string)	$table		- таблица с которой будет связан объект
	 * @param (string)	$key		- имя ключа по которому производить связку
	 * @param (int)		$id			- ИД записи для производится связка
	 * @param (int)		$file_id	- ИД файла, если он уже существует
	 */	
	public function commit($resId, $table, $key, $id, $file_id = null, $comment = null);
	
	
	/**
	 * Get file list (for object)
	 * 	Если параметр $id не задан то метод вернет полный список файлов которые ссылаются на наш объект.
	 * те. что бы получить список всех файлов для таблицы Companies - getFileListForObject('companies', 'company_id'),
	 * а что бы получить все файлы для определенного продукта(например картинки для продукта с ИД=27) - getFileListForObject('products', 'product_id', 27)
	 *
	 *  Если $simpleList == true, то функци вернет результат в виде обычного массива объектов, если $simpleList == false,
	 * то результат вернется в виде индексированного массива формата: 
	 * array(
	 * 			table name => array(
	 * 								file_db_id_1 => customObject1,
	 * 								file_db_id_2 => customObject2,
	 * 								file_db_id_3 => customObject3,
	 * 								)
	 * 		);
	 *  Если $only == true,  то customObject = array(object3, object4, object5), те массив объектов(файлов), тк иногда
	 * объект может иметь несколько прикрипленных файлов.
	 * 
	 * @param string	$table		- сущность (имя таблицы) с которой связан файл
	 * @param string	$key		- имя свойства сущности (ключа таблицы), через которое связан файл
	 * @param int		$id			- значение свойства сущности (ключа таблицы)
	 * @param boolean	$simpleList	- определяет в каком виде функция вернет результат
	 * @param boolean	$only		- устанавливает количество объектов для каждой записи (если true, то только один объект)
	 */	
	public function getFileListForObject($table, $key, $id = null, $comment = null, $simpleList = true, $only = false);
	
	
	/**
	 * Delete files for object
	 *
	 *  Функция работает аналогично функции getFileListForObject(), если дополнительные параметры не указанны,
	 * то будут удалены все файлы для данного объекта.
	 * 
	 * @param string	$table			- сущность (имя таблицы) с которой связан файл
	 * @param string	$key			- имя свойства сущности (ключа таблицы), через которое связан файл
	 * @param int 		$id				- значение свойства сущности (ключа таблицы)
	 * @param string	$with_comment	- коментарий (значение поля file_comment)
	 * @return boolean
	 */	
	public function deleteFilesForObject($table, $key, $id = null, $comment = null);
		
}

interface IBaseModel
{
	public function _get($property=null);
	public function _set($property, $value);
	public function _load($selfobj);
	public function _clear($selfobj);
	
	public function select($case = null);
	public function update();
	public function insert();
	public function save();
	public function delete();
	
	public function getInfo();
	public function getFiltredData(array $case_arr = null, array $search_arr = null, array $order_arr = null);
}