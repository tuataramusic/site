<?
$method		= 'liqpay';//'card';
$comment	= $comment;
$comment	= Func::win2utf($comment);

$xml="<request>      
	<version>1.2</version>
	<result_url>".RESULT_URL.'/'.$user->user_id."</result_url>
	<server_url>".RESULT_URL.'/'.$user->user_id."</server_url>
	<merchant_id>".LP_MERCHANT_ID."</merchant_id>
	<order_id>$number</order_id>
	<amount>$amount</amount>
	<currency>RUR</currency>
	<description>$comment</description>
	<default_phone></default_phone>
	<pay_way>$method</pay_way>
	</request>";
	
	$xml_encoded = base64_encode($xml); 
	$lqsignature = base64_encode(sha1(LP_MERCHANT_SIG1.$xml.LP_MERCHANT_SIG1,1));
	echo $xml;
?>


<form action="https://liqpay.com/?do=clickNbuy" method="POST" name="postform">
    <input type='hidden' name='operation_xml' value='<?=$xml_encoded?>' />
    <input type='hidden' name='signature' value='<?=$lqsignature?>' />
</form>

