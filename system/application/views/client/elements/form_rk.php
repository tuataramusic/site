<?
$comment=Func::win2utf($comment);
$crc  = md5(RK_LOGIN.":$amount:$number:".RK_PASS1.":ShpUser=".$user->user_id);

if (TESTMODE==1) {
	$psform="<form action='http://test.robokassa.ru/Index.aspx' method=POST name='postform'>";}
else {
	$psform="<form action='https://merchant.roboxchange.com/Index.aspx' method=POST name='postform'>";
}
$psform	=	$psform.
		      "MrchLogin:<input type=text name=MrchLogin value=".RK_LOGIN.">".
		      "OutSum:<input type=text name=OutSum value=$amount>".
		      "InvId:<input type=text name=InvId value=$number>".
		      "Desc:<input type=text name=Desc value='$comment'>".
		      "SignatureValue:<input type=text name=SignatureValue value=$crc>".
		      "IncCurrLabel:<input type=text name=IncCurrLabel value=PCR>".
		      "Culture:<input type=text name=Culture value=ru>".
		      "ShpUser:<input type=text name=ShpUser value='".$user->user_id."'>".
		      "</form>";
echo $psform;
?>