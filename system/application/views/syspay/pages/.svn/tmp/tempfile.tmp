<?
	$wm_percent = 0.8;
	$rk_percent = 3;
	$w1_percent = 4;
	$lp_percent = 3;
	
//	$buks = 30.75;
?>
<script type="text/javascript" src="/static/js/easyTooltip.js"></script>
<script type="text/javascript" src="/static/js/jquery.numeric.js"></script> 
<script type="text/javascript">
	var currentPay;
	function getPercentByPayment(pay){
		var percent = 0;
		switch (pay) {
			case "wm": percent = "<?=WM_IN_TAX?>"; break;
			case "rk": percent = "<?=RK_IN_TAX?>"; break;
			case "w1": percent = "<?=W1_IN_TAX?>"; break;
			case "lp": percent = "<?=LP_IN_TAX?>"; break;
		}
		return parseFloat(percent);
	}
	var loadMount = function(){
		var buk = "<?=$usd?>"; // Курс бакса.
		var ourPercent = getPercentByPayment(window.currentPay); /* наши кровные :) */
		var val = $('#mount_buk_id').val();
		if (val.indexOf('.') > -1) $('#mount_buk_id').val(parseInt(val));
		val = parseInt(val);
		val = (isNaN(val) ? 0 : val)*parseFloat(buk);
		val = Math.ceil(val + ourPercent*val/100);
		$('#rur_mount').text(val);
		$('#amount').val(val);
	}
	$(document).ready(function(){
		$('#mount_buk_id').numeric();
		window.currentPay = $('.syspay input.radio[checked]').val();
		window.loadMount();
		$('#mount_buk_id').bind('keypress keydown mouseup keyup blur', function(){
			loadMount();
		});
		$("img.tooltip").easyTooltip();
		$("img.tooltip_rbk").easyTooltip({
			tooltipId: "tooltip_id",
			content: '\
				<div class="box">\
					<div>Способы оплаты:</div>\
					<p>Наличными в терминалах оплаты: Уникасса, Элекснет,</br>Легко и Удобно (Коми), Мобил Элемент</p>\
					<p>Переводом в системе CONTACT: RUR Contact</p>\
					<p>Электронными деньгами: WMR, WMZ, WMB, WME, WMG, WMU, EasyPay,</br>RUR MoneyMail, RUR RBK Money, RUR Z-Payment, RUR Единый Кошелек, Деньги@Mail.Ru</p>\
				</div>\
			'
		});
	});
</script>
<div class='content syspay'>
	<div>
		<form method="POST" action="/syspay/showGate" name="gateform" onsubmit="return check(this);">
			<div class="radio">
				<input type="hidden" class="inp" name="number" value="<?=$user->user_id;?>">
				<font color="red"><b>*</b></font> Сумма пополнения: <input size="5" name="green"  id="mount_buk_id" type="text" class="inp" value="100"> долларов. (В рублях Ваша сумма составит <span id="rur_mount">0</span> рублей)<br>
				<input id="amount" type="hidden" name="amount" value="" />
			</div>
			<br>
			<br>
			<table border="0" cellpadding="1" cellspacing="0">
				<tr>
					<td><input onchange="currentPay=$(this).val();loadMount();" class="radio" type="radio" name="ps" value="wm" id="fpay_1" checked></td>
					<td><label for="fpay_1">Webmoney (Комиссия 0%)</label></td>
				</tr>
				<tr>
					<td><input onchange="currentPay=$(this).val();loadMount();" class="radio" type="radio" name="ps" value="rk" id="fpay_2"></td>
					<td><label for="fpay_2">Robokassa (Комиссия 4-5%)</label> <img class="tooltip tooltip_rbk" src="/static/images/mini_help.gif"></td>
				</tr>
				<tr>
					<td><input onchange="currentPay=$(this).val();loadMount();" class="radio" type=radio name="ps" value="w1" id="fpay_3"></td>
					<td><label for="fpay_3">Единая касса (Комиссия 0-7%)</label> <a href="window.open('https://merchant.w1.ru/checkout/site/payments/');return false;" target="_blank"><img border="0" src="/static/images/mini_help.gif"></a></td>
				</tr>
				<tr>
					<td><input onchange="currentPay=$(this).val();loadMount();" class="radio" type=radio name="ps" value="lp" id="fpay_4"></td>
					<td><label for="fpay_4">liqpay.com (Комиссия 1%)</label> <a href="window.open('http://liqpay.com/');return false;" target="_blank"><img border="0" src="/static/images/mini_help.gif"></a></td>
				</tr>
			</table>
			<br>
			<div class="main">
				Комментарий:<br>
				<textarea style="width:300px; height:100px;" name="comment" class="comment"></textarea>
				<br>
				<input type="hidden" name="send" value="send">
				<input type="submit" value="Оплатить">
			</div>
		</form>
	</div>
</div>