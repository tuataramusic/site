<center>
	<div>
		<select id="scalc" onchange="refreshCurse();">
			<option selected>-- Выберите валюту --</option>
			<?foreach($currencies as $currency):?>
				<option value="<?=$currency->VchCode?>"><?=Func::utf2win($currency->Vname)?></option>
			<?endforeach;?>
		</select>
		
		<span id="course">
			<input type="text" size="6" id="curCount" value="1" onkeyup="refreshCurse()" /> = <span id="cur"></span>
		</span>
	</div>
</center>

<script type="text/javascript" >
	var currencies	= <?= json_encode($currencies)?>;
	
	function refreshCurse(){
		var sval		= document.getElementById('scalc').value;
		var curCount	= document.getElementById('curCount');
		var curOut		= document.getElementById('cur');
		var curOne		= currencies[sval].Vcurs/currencies[sval].Vnom;
		curOut.innerHTML= curOne*curCount.value;
	};
</script>