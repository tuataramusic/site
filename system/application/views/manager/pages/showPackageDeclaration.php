<div class='content'>
	<h2>Декларация</h2>
	<div class='back'><a href='javascript:history.back();'><span>Назад</span></a></div>
	
	<form class='card' id="declarationForm" action='<?=$selfurl?>saveDeclaration/<?=$package->package_id?>' method='POST'>
	
		<table>
			<? if ($package->package_status == 'not_payed'): ?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td align="right">
					<a href="javascript:addDeclaration();" >добавить</a>
				</td>
			</tr>
			<? endif;?>
			<?$index = 0; if (!$declarations): $index++;?>
			<tr>
				<th>Вещь №1</th>
				<td>
					<div class='text-field name-field'><div><input name="new_item1" type='text' value='' /></div></div>
				</td>
				<td>
					<span>Количество:</span>
					<div class='text-field number-field'><div><input class="count" name="new_amount1" type='text' value='' /></div></div>
				</td>
				<td>
					<span>Стоимость:</span>
					<div class='text-field price-field'><div><input class="price" name="new_cost1" type='text' value='' /></div></div>
					<span>$</span>
				</td>
			</tr>
			<?else : foreach ($declarations as $declaration): $index++; ?>
			<tr>
				<th>Вещь №<?=$index?></th>
				<td>
					<div class='text-field name-field'><div><input type='text' name="declaration_item<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_item?>" /></div></div>
				</td>
				<td>
					<span>Количество:</span>
					<div class='text-field number-field'><div><input class="count" type='text' name="declaration_amount<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_amount?>" /></div></div>
				</td>
				<td>
					<span>Стоимость:</span>
					<div class='text-field price-field'><div><input class="price" type='text'  name="declaration_cost<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_cost?>" /></div></div>
					<span>$</span>
				</td>
			</tr>
			<? endforeach; endif;?>

			<tr>
				<td class='total-price' colspan='4'>
					<span>Общая сумма: <strong class='big-text' id="total">0</strong></span>
					<span class='pink-color'>Лимит для России 10000$</span>
					<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
				</td>
			</tr>
		</table>
		<input type="hidden" id="declaration_count" value="<?=$index?>" />
	</form>
</div>



<script type="text/javascript">
	$(document).ready(function() {
		addValidation();
	});
	
	function addValidation()
	{
		var inputs = $('.count, .price');
		
		inputs.keypress(function(event){validate_number(event);});
		inputs.change(function(){updateTotal();});
		updateTotal();
	}
	
	function validate_number(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		var regex = /[0-9]|\./;
		if( !regex.test(key) ) {
			theEvent.returnValue = false;
			theEvent.preventDefault();
		}
	}

	function addDeclaration(){
		var declaration_count = $('#declaration_count').val();
		var tag = $('#declarationForm tr:last');
		declaration_count++;
		var declaration_html = '<tr><th>Вещь №' + declaration_count + '</th><td><div class="text-field name-field"><div><input type="text" name="new_item' + declaration_count + '" value="" /></div></div></td>	<td><span>Количество:</span><div class="text-field number-field"><div><input class="count" type="text" name="new_amount' + declaration_count + '" value="" /></div></div></td>		<td><span>Стоимость:</span><div class="text-field price-field"><div><input class="price" type="text" name="new_cost' + declaration_count + '" value="" /></div></div><span>$</span></td></tr>';
		tag.before(declaration_html);
		$('#declaration_count').val(declaration_count);
		addValidation();
	}

	function updateTotal(){
		var amounts = $('.count');
		var costs = $('.price');
		var total = 0;
		
		for (var i = 0; i < amounts.length; i++)
		{
			amount = parseInt(amounts[i].value);
			cost = parseFloat(costs[i].value);
			if (isNaN(amount)) amount = 0;
			if (isNaN(cost)) cost = 0;
			total += amount * cost;
		}

		$('#total').text(parseInt(total)+' $');
	}
</script>



<?/*
<a href='javascript:history.back();'>Назад</a>
<center>
<b>Посылка №<?=$package->package_id?></b><br/>
<table>
	<tr><td style="color: #aaa;">Клиент №</td><td><?=$package->package_client?></td></tr>
	<tr><td style="color: #aaa;">Вес</td><td><?=$package->package_weight?>кг</td></tr>
	<tr><td style="color: #aaa;">Стоимость</td><td><?=$package->package_cost?>р</td></tr>
</table>
</center>
<br/>Декларация<br/><br/>

<form id="declarationForm" action='<?=$selfurl?>saveDeclaration/<?=$package->package_id?>' method='POST'>
	<?$index = 0; if (!$declarations): $index++;?>
	Вещь № 1: <input name="new_item1" type="text"/>	Количество:	<input name="new_amount1" type="text"/> Стоимость: <input name="new_cost1" type="text" />$<br/>
	<?else : foreach ($declarations as $declaration): $index++; ?>
	Вещь № <?=$index?>: <input type="text" name="declaration_item<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_item?>" /> Количество: <input type="text" name="declaration_amount<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_amount?>" /> Стоимость: <input type="text" name="declaration_cost<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_cost?>" />$<br/>
	<? endforeach; endif;?>
	Общая сумма: <label id="total" for="declaration_count">0</label>р
	<? if ($package->package_status != 'sent'): ?>
	<a href="javascript:addDeclaration();" >добавить</a>
	<input type="hidden" id="declaration_count" value="<?=$index?>" />
	<input type="submit" value="Сохранить"/>
	<? endif;?>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		addValidation();
	});
	
	function addValidation()
	{
		var inputs = $('#declarationForm input:text:nth-child(4n+2),#declarationForm input:text:nth-child(4n+3)')
		
		inputs.keypress(function(event){validate_number(event);});
		inputs.change(function(){updateTotal();});
		updateTotal();
	}
	
	function validate_number(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		var regex = /[0-9]|\./;
		if( !regex.test(key) ) {
			theEvent.returnValue = false;
			theEvent.preventDefault();
		}
	}

	function addDeclaration(){
		var tag = $('#declarationForm br:last');
		var declaration_count = $('#declaration_count').val();
		declaration_count++;
		var declaration_html = 'Вещь № ' + declaration_count + ': <input name="new_item' + declaration_count + '" type="text"/>	Количество:	<input name="new_amount' + declaration_count + '" type="text"/> Стоимость: <input name="new_cost' + declaration_count + '" type="text" />$<br/>';
		tag.after(declaration_html);
		$('#declaration_count').val(declaration_count);
		
		addValidation();
	}

	function updateTotal(){
		var amounts = $('#declarationForm input:text:nth-child(4n+2)');
		var costs = $('#declarationForm input:text:nth-child(4n+3)');
		var total = 0;
		
		for (var i = 0; i < amounts.length; i++)
		{
			amount = parseInt(amounts[i].value);
			cost = parseFloat(costs[i].value);
			if (isNaN(amount)) amount = 0;
			if (isNaN(cost)) cost = 0;
			total += amount * cost;
		}
		
		$('#total').text(parseInt(total));
	}
</script>
*/?>

