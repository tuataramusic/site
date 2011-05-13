<div class='content'>

	<h2>Аккаунт администратора</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
	</ul>

	<h3>Изменение баланса пользователя</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div><br />

	<center>
	<form class="admin-inside" action="<?=$selfurl?>updateClientBalance/<?=$client_user->user_id?>" method="POST">

		<div class='table' style="width:40% !important">
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table width="60%">
				<tr>
					<td><span>Клиент №</span></td>
					<td><input type="text" size="35" value="<?=$client_user->user_id?>" readonly></td>
				</tr>	
				<tr>
					<td>Ф.И.О.</td>
					<td><input type="text" name="help" size="35" value="<?=$client->client_surname?> <?=$client->client_name?> <?=$client->client_otc?>" readonly></td>
				</tr>		
				<tr>
					<td>Баланс:</td>
					<td><input type='text' name='user_coints' size="35" id='user_coints' maxlength='11' value="<?=$client_user->user_coints?>"></td>
				</tr>
				<tr class='last-row'>
					<td colspan='2'>
						<br />
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</form>
	</center>
</div>

<?/*
<a href='javascript:history.back();'>Назад</a>
<center>
<b>Клиент №<?=$client_user->user_id?></b><br/>
<table>
	<tr><td style="color: #aaa;">ФИО</td><td><?=$client->client_surname?> <?=$client->client_name?> <?=$client->client_otc?></td></tr>
</table>
</center>

<br/><b>Баланс</b><br/>
<form action='<?=$selfurl?>updateClientBalance/<?=$client_user->user_id?>' method='POST'>
<input type='text' name='user_coints' id='user_coints' maxlength='11' value="<?=$client_user->user_coints?>"><br/>
<input type="submit" value="Сохранить"/>
</form>
*/?>


<script type="text/javascript">
	$(document).ready(function() {
		$('#user_coints').keypress(function(event){validate_number(event);});
	});
	
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