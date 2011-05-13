<a href='javascript:history.back();'>�����</a>
<center>
<b>������� �<?=$package->package_id?></b><br/>
<table>
	<tr><td style="color: #aaa;">������ �</td><td><?=$package->package_client?></td></tr>
	<tr><td style="color: #aaa;">���</td><td><?=$package->package_weight?>��</td></tr>
	<tr><td style="color: #aaa;">���������</td><td><?=$package->package_cost?>�</td></tr>
</table>
</center>
<br/>����������<br/><br/>

<form id="declarationForm" action='<?=BASEURL?>admin/saveDeclaration/<?=$package->package_id?>' method='POST'>
	<?$index = 0; if (!$declarations): $index++;?>
	���� � 1: <input name="new_item1" type="text"/>	����������:	<input name="new_amount1" type="text"/> ���������: <input name="new_cost1" type="text" />���.<br/>
	<?else : foreach ($declarations as $declaration): $index++; ?>
	���� � <?=$index?>: <input type="text" name="declaration_item<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_item?>" /> ����������: <input type="text" name="declaration_amount<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_amount?>" /> ���������: <input type="text" name="declaration_cost<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_cost?>" />���.<br/>
	<? endforeach; endif;?>
	����� �����: <label id="total" for="declaration_count">0</label>�
	<? if ($package->package_status != 'sent'): ?>
	<a href="javascript:addDeclaration();" >��������</a>
	<input type="hidden" id="declaration_count" value="<?=$index?>" />
	<input type="submit" value="���������"/>
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
		var declaration_html = '���� � ' + declaration_count + ': <input name="new_item' + declaration_count + '" type="text"/>	����������:	<input name="new_amount' + declaration_count + '" type="text"/> ���������: <input name="new_cost' + declaration_count + '" type="text" />���.<br/>';
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

