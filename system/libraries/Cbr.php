<?
/*
** ����� CBR
** ���������� ������ ������ ����� � ��� ������ ����������� ������
*/
class CBRBase {
	
	const		WSDL = "http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL";	// WSDL ������ �����������
	const       addpercent = 1.02;
	protected	$soap;
	protected	$soapDate;
	public		$currencyCodes = array();
	
	public function __construct()
	{
		$this->soap = new SoapClient(CBR::WSDL);
	}
	
	protected function getSOAPDate($timeStamp, $withTime = false)
	{
		$soapDate = date("Y-m-d", $timeStamp);
		return ($withTime) ? $soapDate .  "T" . date("H:i:s", $timeStamp) :	$soapDate . "T00:00:00";
	}
	
	protected function getXML($date)
	{
		$currentDate = self::getSOAPDate($date);

		if ($currentDate != $this->soapDate){

			$this->soapDate		= $currentDate;
			$params["On_date"]	= $currentDate;
			$response			= $this->soap->GetCursOnDateXML($params);
			
			return $response->GetCursOnDateXMLResult->any;
		}
		return false;
		
	}
	

	public function getRate($currencyCode, $date = 0)
	{
		if (!$date) $date	= time();
		
		$xml				= simplexml_load_string($this->getXML($date));

		$result				= $xml->xpath('/ValuteData/ValuteCursOnDate[VchCode="'.$currencyCode.'"]');
		
		if (count($result) == 0) return 0;
		$v = array_shift($result)->Vcurs;
		return str_replace(",",".",$v) * CBR::addpercent;
	}
	
	
	public function getCurrencyCodes()
	{
		$xml	= simplexml_load_string($this->getXML(time()));
		$xPath	= "/ValuteData/ValuteCursOnDate";
		$allCurrencies = $xml->xpath($xPath);
		foreach ($allCurrencies as $currency){
			$code = trim($currency->VchCode);
			$name = trim($currency->Vname);
			$this->currencyCodes[$code] = $name;
		}
		
		return ($this->currencyCodes);
	}
	
	public function getAllCurrencyInfo()
	{
		$xml	= simplexml_load_string($this->getXML(time()));
		$xPath	= "/ValuteData/ValuteCursOnDate";
		return $xml->xpath($xPath);
	}
}


class cbr extends CBRBase
{
	public $tempFolder = "tmp/cbr";	// ��������� ����� ��� ���������� ������ � ����
	
	protected function getXML($date)
	{
		$cacheFile = md5($this->getSOAPDate($date)) . ".xml";
		
		if ($this->tempFolder)
			$cacheFile = $this->tempFolder . $cacheFile;
			
		if (!file_exists($cacheFile))
		{
			$result = parent::getXML($date);
			file_put_contents($cacheFile, $result);
			return $result;
			
		}else{
			return file_get_contents($cacheFile);
		}
	}
}

?>