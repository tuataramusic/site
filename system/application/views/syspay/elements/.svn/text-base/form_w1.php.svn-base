<?php

//Секретный ключ интернет-магазина
$key = W1_KEY;

$fields = array(); 
$comment	= Func::win2utf($comment);
$desc		= Func::win2utf("Пополнение счета пользователем №{$user->user_id} на сумму $green у.е.");

// Добавление полей формы в ассоциативный массив
$fields["WMI_MERCHANT_ID"]		= W1_WALLET;
$fields["WMI_PAYMENT_AMOUNT"]	= $amount;
$fields["WMI_CURRENCY_ID"]		= 643;//руб
$fields["WMI_PAYMENT_NO"]		= $number;
$fields["WMI_DESCRIPTION"]		= "BASE64:".base64_encode($desc);
$fields["WMI_SUCCESS_URL"]		= SUCCESS_URL;
$fields["WMI_FAIL_URL"]			= FAIL_URL;
$fields["WMI_AUTO_ACCEPT"]		= "0";
$fields["User_id"]				= $user->user_id;
$fields["User_comment"]			= "BASE64:".base64_encode($comment);
//$fields["MyShopParam1"]       = "Value333"; // Дополнительные параметры
//$fields["MyShopParam2"]       = "Value2"; // интернет-магазина тоже участвуют
//$fields["MyShopParam3"]       = "Value3"; // при формировании подписи!
//Если требуется задать только определенные способы оплаты, раскоментируйте данную строку и перечислите требуемые способы оплаты.
//$fields["WMI_PTENABLED"]      = array("ContactRUB", "UnistreamRUB", "SberbankRUB", "RussianPostRUB");

//Сортировка значений внутри полей
foreach($fields as $name => $val) 
{
  if (is_array($val))
  {
     usort($val, "strcasecmp");
     $fields[$name] = $val;
  }
}


// Формирование сообщения, путем объединения значений формы, 
// отсортированных по именам ключей в порядке возрастания.
uksort($fields, "strcasecmp");
$fieldValues = "";

foreach($fields as $value) 
{
    if (is_array($value))
       foreach($value as $v)
       {
		  //Конвертация из текущей кодировки (UTF-8)
	      //необходима только если кодировка магазина отлична от Windows-1251
//          $v = iconv("utf-8", "windows-1251", $v);
          $fieldValues .= $v;
       }
   else
  {
     //Конвертация из текущей кодировки (UTF-8)
     //необходима только если кодировка магазина отлична от Windows-1251
//     $value = iconv("utf-8", "windows-1251", $value);
     $fieldValues .= $value;
  }
}

// Формирование значения параметра WMI_SIGNATURE, путем 
// вычисления отпечатка, сформированного выше сообщения, 
// по алгоритму MD5 и представление его в Base64

$signature = base64_encode(pack("H*", sha1($fieldValues . $key)));

//Добавление параметра WMI_SIGNATURE в словарь параметров формы

$fields{"WMI_SIGNATURE"} = $signature;

// Формирование HTML-кода платежной формы

print "<form name='postform' action=\"https://merchant.w1.ru/checkout/default.aspx\" method=\"POST\">";

foreach($fields as $key => $val)
{
    if (is_array($val))
       foreach($val as $value)
       {
	 print "$key: <input type=\"text\" name=\"$key\" value=\"$value\"/><br>";
       }
    else	    
       print "$key: <input type=\"text\" name=\"$key\" value=\"$val\"/><br>";
}

print "<input type=\"submit\"/></<form>";
?>
