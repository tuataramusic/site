Добавить посылку
<form action="<?=$selfurl?>showNewPackages" method="POST">
<table>
	<tr>
		<td>Клиент</td>
		<td>Вес (кг)</td>
	</tr>
	<tr>
		<td>
			<select id="user_id" name="user_id" style="width:150px;">
				<option value="-1">выберите клиента...</option>
				<?foreach ($users as $user):?>
			    <option value="<?=$user->client_user?>"><?=$user->client_user?></option>
				<?endforeach;?>
			</select>
		</td>
		<td><input id="weight" name="weight" type="number" maxlength="5" style="width:100px;" onkeypress="javascript:validate_number(event);" ></td>
	</tr>
</table>
<input type="button" value="Назад" onclick="javascript:history.back();">
<input type="submit" value="Добавить">
</form>
<script>
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
</script>