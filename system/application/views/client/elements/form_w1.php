<?php

//��������� ���� ��������-��������
$key = W1_KEY;

$fields = array(); 
$comment	= Func::win2utf($comment);

// ���������� ����� ����� � ������������� ������
$fields["WMI_MERCHANT_ID"]		= W1_WALLET;
$fields["WMI_PAYMENT_AMOUNT"]	= $amount;
$fields["WMI_CURRENCY_ID"]		= 643;
$fields["WMI_PAYMENT_NO"]		= $number;
$fields["WMI_DESCRIPTION"]		= "BASE64:".base64_encode($comment);
$fields["WMI_SUCCESS_URL"]		= SUCCESS_URL;
$fields["WMI_FAIL_URL"]			= FAIL_URL;
$fields["WMI_AUTO_ACCEPT"]		= "0";
$fields["User_id"]				= $user->user_id;
//$fields["MyShopParam1"]       = "Value333"; // �������������� ���������
//$fields["MyShopParam2"]       = "Value2"; // ��������-�������� ���� ���������
//$fields["MyShopParam3"]       = "Value3"; // ��� ������������ �������!
//���� ��������� ������ ������ ������������ ������� ������, ��������������� ������ ������ � ����������� ��������� ������� ������.
//$fields["WMI_PTENABLED"]      = array("ContactRUB", "UnistreamRUB", "SberbankRUB", "RussianPostRUB");

//���������� �������� ������ �����
foreach($fields as $name => $val) 
{
  if (is_array($val))
  {
     usort($val, "strcasecmp");
     $fields[$name] = $val;
  }
}


// ������������ ���������, ����� ����������� �������� �����, 
// ��������������� �� ������ ������ � ������� �����������.
uksort($fields, "strcasecmp");
$fieldValues = "";

foreach($fields as $value) 
{
    if (is_array($value))
       foreach($value as $v)
       {
		  //����������� �� ������� ��������� (UTF-8)
	      //���������� ������ ���� ��������� �������� ������� �� Windows-1251
//          $v = iconv("utf-8", "windows-1251", $v);
          $fieldValues .= $v;
       }
   else
  {
     //����������� �� ������� ��������� (UTF-8)
     //���������� ������ ���� ��������� �������� ������� �� Windows-1251
//     $value = iconv("utf-8", "windows-1251", $value);
     $fieldValues .= $value;
  }
}

// ������������ �������� ��������� WMI_SIGNATURE, ����� 
// ���������� ���������, ��������������� ���� ���������, 
// �� ��������� MD5 � ������������� ��� � Base64

$signature = base64_encode(pack("H*", sha1($fieldValues . $key)));

//���������� ��������� WMI_SIGNATURE � ������� ���������� �����

$fields{"WMI_SIGNATURE"} = $signature;

// ������������ HTML-���� ��������� �����

print "<form action=\"https://merchant.w1.ru/checkout/default.aspx\" method=\"POST\">";

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
