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
	 * �������� ����� �� ������
	 *
	 * @return (object)	$resource	- ������ ������� ������������ ��� �������� �����, �������� ���������� � �����,
	 * � ��� �� ������-���������� ������� �������, ��� �� ����� ������ � ������� �������.
	 * 
	 */	
	public function upload();
	
	
	/**
	 * �������� �����
	 *
	 * @param (int)		$resId		- ��������� �� ������
	 * @param (string)	$table		- ������� � ������� ����� ������ ������
	 * @param (string)	$key		- ��� ����� �� �������� ����������� ������
	 * @param (int)		$id			- �� ������ ��� ������������ ������
	 * @param (int)		$file_id	- �� �����, ���� �� ��� ����������
	 */	
	public function commit($resId, $table, $key, $id, $file_id = null, $comment = null);
	
	
	/**
	 * Get file list (for object)
	 * 	���� �������� $id �� ����� �� ����� ������ ������ ������ ������ ������� ��������� �� ��� ������.
	 * ��. ��� �� �������� ������ ���� ������ ��� ������� Companies - getFileListForObject('companies', 'company_id'),
	 * � ��� �� �������� ��� ����� ��� ������������� ��������(�������� �������� ��� �������� � ��=27) - getFileListForObject('products', 'product_id', 27)
	 *
	 *  ���� $simpleList == true, �� ������ ������ ��������� � ���� �������� ������� ��������, ���� $simpleList == false,
	 * �� ��������� �������� � ���� ���������������� ������� �������: 
	 * array(
	 * 			table name => array(
	 * 								file_db_id_1 => customObject1,
	 * 								file_db_id_2 => customObject2,
	 * 								file_db_id_3 => customObject3,
	 * 								)
	 * 		);
	 *  ���� $only == true,  �� customObject = array(object3, object4, object5), �� ������ ��������(������), �� ������
	 * ������ ����� ����� ��������� ������������� ������.
	 * 
	 * @param string	$table		- �������� (��� �������) � ������� ������ ����
	 * @param string	$key		- ��� �������� �������� (����� �������), ����� ������� ������ ����
	 * @param int		$id			- �������� �������� �������� (����� �������)
	 * @param boolean	$simpleList	- ���������� � ����� ���� ������� ������ ���������
	 * @param boolean	$only		- ������������� ���������� �������� ��� ������ ������ (���� true, �� ������ ���� ������)
	 */	
	public function getFileListForObject($table, $key, $id = null, $comment = null, $simpleList = true, $only = false);
	
	
	/**
	 * Delete files for object
	 *
	 *  ������� �������� ���������� ������� getFileListForObject(), ���� �������������� ��������� �� ��������,
	 * �� ����� ������� ��� ����� ��� ������� �������.
	 * 
	 * @param string	$table			- �������� (��� �������) � ������� ������ ����
	 * @param string	$key			- ��� �������� �������� (����� �������), ����� ������� ������ ����
	 * @param int 		$id				- �������� �������� �������� (����� �������)
	 * @param string	$with_comment	- ���������� (�������� ���� file_comment)
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