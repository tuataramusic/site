<?
phpinfo();$headers = 'From: info@countrypost.ru' . "\r\n" .
    'Reply-To: info@countrypost.ru' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	print mail("shevtsov@sibwaypro.ru", '123123','123',$headers);
?>
