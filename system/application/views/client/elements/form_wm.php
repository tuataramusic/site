<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" name="postform">
	LMI_PAYMENT_AMOUNT:<input type="text" name="LMI_PAYMENT_AMOUNT" value="<?=$amount?>"><br />
	LMI_PAYMENT_DESC:<input type="text" name="LMI_PAYMENT_DESC" value="<?=$comment?>"><br />
	LMI_PAYMENT_NO:<input type="text" name="LMI_PAYMENT_NO" value="<?=$number?>"><br />
	LMI_PAYEE_PURSE:<input type="text" name="LMI_PAYEE_PURSE" value="<?=WM_PURSE?>"><br />
	LMI_SIM_MODE:<input type="text" name="LMI_SIM_MODE" value="<?= (TESTMODE==1)?2:0?>"><br />
	LMI_RESULT_URL:<input type="text" name="LMI_RESULT_URL" value="<?=RESULT_URL?>"><br />
	LMI_SUCCESS_URL:<input type="text" name="LMI_SUCCESS_URL" value="<?=SUCCESS_URL?>"><br />
	LMI_SUCCESS_METHOD:<input type="text" name="LMI_SUCCESS_METHOD" value="2"><br />
	LMI_FAIL_URL:<input type="text" name="LMI_FAIL_URL" value="<?=FAIL_URL?>"><br />
	LMI_FAIL_METHOD:<input type="text" name="LMI_FAIL_METHOD" value="2"><br />
	User_id:<input type="text" name="User_id" value="<?=$user->user_id;?>"><br />
</form>

<!--<form id=pay name=pay method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp"> 
	<input type="text" name="LMI_PAYMENT_AMOUNT" value="1.0">
	<input type="text" name="LMI_PAYMENT_DESC" value="тестовый платеж">
	<input type="text" name="LMI_PAYMENT_NO" value="1">
	<input type="text" name="LMI_PAYEE_PURSE" value="Z145179295679">
	<input type="text" name="LMI_SIM_MODE" value="0"> 
	<input type="submit" value="submit">
</form>-->
