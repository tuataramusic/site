<?
require_once(MODELS_PATH.'Base/BaseModel.php');
/**
 * @author tua
 * 
 * модель для посылки
 * 1. в модели не делаем проверок на валидность i\o это должно делаться в контролере
 * 2. допустимы только ошибки уровня БД
 * 3. разрешатся передавать списки параметров функции, только в случает отсутствия публичного 
 * атрибута соответствующего объекта
 *
 */
class PackageModel extends BaseModel implements IModel{

	protected 	$properties			= null;				// array of properties
	protected	$table				= 'packages';		// table name
	protected	$PK					= 'package_id';		// primary key name	
	
	private $statuses = array(
		'not_payed'		=> 'Не оплачен',
		'payed'			=> 'Оплачено',
		'sent'			=> 'Отправлено'
	);
	
	/**
	 * конструктор
	 */
	function __construct()
    {
    	$this->properties								= new stdClass();
    	$this->properties->package_id					='';
    	$this->properties->package_client				='';
    	$this->properties->package_manager				='';
    	$this->properties->package_weight				='';
    	$this->properties->package_cost					='';
    	$this->properties->package_delivery_cost		='';
    	$this->properties->package_declaration_cost		='';
    	$this->properties->package_comission			='';
    	$this->properties->package_status				='';
    	$this->properties->package_date					='';
    	$this->properties->package_address				='';
    	$this->properties->declaration_status			='';
		$this->properties->comment_for_manager			='';
		$this->properties->comment_for_client			='';
		$this->properties->package_trackingno			='';
		$this->properties->package_manager_login		='';
		$this->properties->package_manager_country		='';
		$this->properties->package_age					='';
		$this->properties->package_country_from			='';
		$this->properties->package_country_to			='';
		$this->properties->package_delivery				='';
		$this->properties->package_delivery_name		='';
		$this->properties->package_delivery_list		='';
		$this->properties->package_join_count 			='';
		$this->properties->package_join_cost			='';
		$this->properties->package_join_ids				='';
                $this->properties->package_insurance = '';
                $this->properties->package_insurance_cost = '';
		$this->properties->package_manager_cost			='';
		$this->properties->package_manager_comission	='';
		$this->properties->package_payed_to_manager		='';

        parent::__construct();
    }
    
   /**
     * @see IModel
     * Инкапсуляция
     *
     * @return string
     */
	public function getPK()
	{
		return $this->PK;
	}
	
    /**
     * @see IModel
     * Инкапсуляция
     *
     * @return string
     */	
	public function getTable()
	{
		return $this->table;
	}     
    
    /**
     * Get delivery list
     */
	public function getList()
	{
		$sql = $this->select();
		return ($sql)?($sql):(false);
	}	
	
	/**
	 * Get property list
	 *
	 * @return array
	 */
	public function getPropertyList()
	{
		return array_keys((array) $this->properties);
	}	
	
	/**
	 * Get package by id
	 *
	 * @return array
	 */
	public function getById($id){
		$r = $this->select(array(
			$this->getPK()	=> (int) $id,
		));					
		
		return ((count($r==1) &&  $r) ? array_shift($r) : false);
	}
	
	/**
	 * Get new packages by manager id
	 *
	 * @return array
	 */
	public function getNewPackagesByManagerId($id) {		
		$result = $this->select(array('package_manager' => $id, 'package_status' => 'not_payed'));
		
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}

	
	public function getByManagerId($id){
		if (!is_numeric($id))
			return false;
			
		return $this->select(array('package_manager' => $id));
	}
	

	public function getByClientId($id, $status = null){
		if (!is_numeric($id))
			return false;
			
		return $this->select(array('package_client' => $id));
	}
	
	
	/**
	 * Get filtered packages
	 *
	 * @return array
	 */
	public function getPackages($filter=null, $packageStatus='not_payed', $clientId=null, $managerId=null) {
		$managerFilter = '';
		$periodFilter = '';
		$idFilter = '';
		$clientIdAccess = '';
		$managerIdAccess = '';
		$statusFilter = '';
		
		// обработка статуса
		if ($packageStatus != 'open')
		{
			$statusFilter = '`packages`.`package_status` = "'.$packageStatus.'"';
		}
		else
		{
			$statusFilter = '`packages`.`package_status` <> "deleted" AND `packages`.`package_status` <> "sent"';		
		}		
		
		// обработка фильтра
		if (isset($filter))
		{
			if (is_numeric($filter->manager_user))
			{
				$managerFilter = ' AND `managers`.`manager_user` = '.$filter->manager_user;
			}

			if (is_numeric($filter->search_id))
			{
				if ($filter->id_type == 'package')
				{
					$idFilter = ' AND `packages`.`package_id` = '.$filter->search_id;
				}
				else if ($filter->id_type == 'client')
				{
					$idFilter = ' AND `packages`.`package_client` = '.$filter->search_id;
				}
			}

			if ($filter->period == 'day' ||
				$filter->period == 'week' ||
				$filter->period == 'month')
			{
				$periodFilter = ' AND TIMESTAMPDIFF('.strtoupper($filter->period).', `packages`.`package_date`, NOW()) < 1';
			}
		}
		
		// обработка ограничения доступа клиента и менеджера
		if (isset($clientId))
		{
			$clientIdAccess = ' AND `packages`.`package_client` = '.$clientId;		
		}
		else if (isset($managerId))
		{
			$managerIdAccess = ' AND `packages`.`package_manager` = '.$managerId;		
		}		
		
		// выборка
		$result = $this->db->query('
			SELECT `packages`.*, @package_day:=TIMESTAMPDIFF(DAY, `packages`.`package_date`, NOW()) as package_day,
				DATE_FORMAT(`package_date`, "%d.%m.%Y %h:%i") AS `package_date`,
				`users`.`user_login`  as `package_manager_login`, 
				`countries`.`country_name` as `package_manager_country`,
				`deliveries`.`delivery_name` as `package_delivery_name`,
				TIMESTAMPDIFF(HOUR, `packages`.`package_date`, NOW() - INTERVAL @package_day DAY) as `package_hour`
			FROM `packages`
			INNER JOIN `users` on `users`.`user_id` = `packages`.`package_manager`
			INNER JOIN `managers` on `managers`.`manager_user` = `packages`.`package_manager`
			INNER JOIN `countries` on `managers`.`manager_country` = `countries`.`country_id`
			LEFT JOIN `deliveries` on `deliveries`.`delivery_id` = `packages`.`package_delivery`
			WHERE '
			.$statusFilter
			.$managerFilter
			.$periodFilter
			.$idFilter
			.$clientIdAccess
			.$managerIdAccess.'
			ORDER BY `packages`.`package_date` DESC'
		)->result();
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	/**
	 * Updates packages with available deliveries
	 *
	 * @return array
	 */
	public function getAvailableDeliveries($packages, $deliveries) {
		if (!$packages) return false;
		
		foreach ($packages as $package)
		{
			// проверка доступности списка способов доставки
			if (!$package->package_country_from ||
				!$package->package_country_to ||
				!$package->package_status == 'payed')
			{
				$package->delivery_list = false;
				continue;
			}
			
			// выборка способов доставки
			$package->delivery_list = $deliveries->getAvailableDeliveries($package->package_country_from, $package->package_country_to);
		}
		
		return $packages;
	}
	
	/**
	 * Get payed packages by manager id
	 *
	 * @return array
	 */
	public function getPayedPackagesByManagerId($id){
		
		$result = $this->select(array('package_manager' => $id, 'package_status' => 'payed'));
		
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	/**
	 * Get sent packages by manager id
	 *
	 * @return array
	 */
	public function getSentPackagesByManagerId($id)
	{
		$result = $this->select(array('package_manager' => $id, 'package_status' => 'sent'));
		
		return ((count($result) > 0 &&  $result) ? $result : false);		
	}
	
	/**
	 * Возвращает посылку, если она есть у партнера
	 *
	 * @return array
	 */
	public function getManagerPackageById($package_id, $manager_id){
		$package = $this->getById($package_id);
		
		if ($package &&
			$package->package_manager == $manager_id)
		{
			return $package;
		}

		return false;
	}
	
	/**
	 * Возвращает посылку, если она есть у клиента
	 *
	 * @return array
	 */
	public function getClientPackageById($package_id, $client_id){
		$package = $this->getById($package_id);
		
		if ($package &&
			$package->package_client == $client_id)
		{
			return $package;
		}

		return false;
	}
	
	/**
	 * Get all statuses
	 *
	 * @return array
	 */
	public function getStatuses() {
		return $this->statuses;
	}
	
	/**
	 * Добавление/изменение посылки
	 * Выкидывает исключения на некорректные данные
	 * 
	 * @param (object) 	- $package
	 * @return (mixed)	- объект package или false в случае ошибки записи в базу
	 */
	public function savePackage($package){
		$props = $this->getPropertyList();
		
		foreach ($props as $prop){
			if (isset($package->$prop)){
				$this->_set($prop, $package->$prop);
			}
		}
		
		$new_id = $this->save(true);
		
		if (!$new_id) return false;
		
		return $this->getInfo(array($new_id));
	}
	
	/**
	 * Рассчитывает стоимость посылки
	 *
	 * @return array
	 */
	public function calculateCost($package, $config, $pricelist=null)
	{
		$config = $config->getConfig();
			
		if (!$config)
		{
			throw new Exception('Невозможно рассчитать стоимость посылки. Данные для расчета недоступны.',-1);
		}
		
		// стоимость комиссии
		$package->package_comission = $config['price_for_trasmission']->config_value;
		$package->package_manager_comission = $config['price_for_trasmission']->config_value / 2;
		
		// стоимость заполнения декларации
		if ($package->declaration_status == 'help')
		{
			$package->package_declaration_cost = $config['price_for_declaration']->config_value;
		}
		
		// стоимость объединения посылок
		if ($package->package_join_count)
		{
			$package->package_join_cost = $config['price_for_marge']->config_value * $package->package_join_count;
		}

                // стоимость страховки
                if ($package->package_insurance !== null)
                {
                  if ($package->package_insurance == 0)
                  {
                    $package->package_insurance_cost = 0;
                  }
                  else
                  {
                    $package->package_insurance_cost = 0.01 * $config['price_for_insurance']->config_value * $package->package_insurance;
                  }
                }
		
		// стоимость международной доставки
		if (isset($package->package_delivery) &&
			$package->package_delivery &&
			isset($pricelist))
		{
			$package->package_delivery_cost = $pricelist->getPriceByWeight($package->package_weight, $package->package_country_from, $package->package_country_to, $package->package_delivery);
			if (!$package->package_delivery_cost)
			{
				throw new Exception('Невозможно рассчитать стоимость посылки. Ошибка расчета международной доставки.',-1);
			}			
		}
		
		// общая стоимость
		$package->package_cost = 
			$package->package_delivery_cost +
			$package->package_declaration_cost +
			$package->package_join_cost +
                        $package->package_insurance_cost +
			$package->package_comission;

		$package->package_manager_cost = 
			$package->package_delivery_cost +
			$package->package_declaration_cost +
			$package->package_join_cost +
                        $package->package_insurance_cost +
			$package->package_manager_comission;
			
		return ($package->package_cost && $package->package_manager_cost) ? $package : false;
	}

	
        public function getPackageInsuranceCost()
        {
          $config = $config->getConfig();
          if (!$config)
          {
            throw new Exception('Невозможно рассчитать стоимость посылки. Данные для расчета недоступны.',-1);
          }
          return $package->insurance * $config['price_for_insurance']->config_value;
        }

	/**
	 * Получить список фоток дя каждой посылки
	 *
	 * @param object $arrayOfPackObject
	 */
	public function getPackagesFoto( array $arrayOfPackObject){
		
		$packFotos	= array();
		foreach ($arrayOfPackObject as $package){
			$scandir	= UPLOAD_DIR.'packages/'.$package->package_manager.'/'.$package->package_id.'/';
			if (is_dir($scandir)){
				foreach (scandir($scandir) as $scanFile){
					if ($scanFile != '.' && $scanFile != '..'){
						$packFotos[$package->package_id][]	= $scanFile;
					}
				}
			}
		}
		
		return $packFotos;
	}
}
?>