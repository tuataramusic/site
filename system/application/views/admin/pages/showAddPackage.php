<div class='content'>
	<h2>Аккаунт администратора</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
	</ul>

	<h3>Добавление посылки</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div><br />
	
	<form class='admin-inside'  action="<?=$selfurl?>addPackage" method="POST">
	
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<tr>
					<th>Клиент</th>
					<th>Добавить от имени партнера</th>
					<th>Вес (кг)</th>
				</tr>
				<tr>
					<td>
						<select id="package_client" name="package_client" style="width:150px;" class="select">
							<option value="">выберите...</option>
							<?if ($clients) : foreach ($clients as $client):?>
						    <option value="<?=$client->client_user?>"><?=$client->client_user?></option>
							<?endforeach; endif;?>
						</select>
					</td>
					<td>
						<select id="package_manager" name="package_manager" style="width:150px;" class="select">
							<option value="">выберите...</option>
							<?if ($managers) : foreach ($managers as $manager):?>
						    <option value="<?=$manager->manager_user?>"><?=$manager->user_login?></option>
							<?endforeach; endif;?>
						</select>
					</td>
					<td><input id="package_weight" name="package_weight" type="text" maxlength="5" style="width:100px;" onkeypress="javascript:validate_number(event);" ></td>
				</tr>
				<tr class='last-row'>
					<td colspan='9'>
						<br />
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='Добавить' /></div></div>
						</div>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
	</form>
</div>

<?php /*?>

Добавить посылку
<form action="<?=$selfurl?>addPackage" method="POST">
<table>
	<tr>
		<td>Клиент</td>
		<td>Добавить от имени партнера</td>
		<td>Вес (кг)</td>
	</tr>
	<tr>
		<td>
			<select id="package_client" name="package_client" style="width:150px;">
				<option value="">выберите...</option>
				<?if ($clients) : foreach ($clients as $client):?>
			    <option value="<?=$client->client_user?>"><?=$client->client_user?></option>
				<?endforeach; endif;?>
			</select>
		</td>
		<td>
			<select id="package_manager" name="package_manager" style="width:150px;">
				<option value="">выберите...</option>
				<?if ($managers) : foreach ($managers as $manager):?>
			    <option value="<?=$manager->manager_user?>"><?=$manager->user_login?></option>
				<?endforeach; endif;?>
			</select>
		</td>
		<td><input id="package_weight" name="package_weight" type="text" maxlength="5" style="width:100px;" onkeypress="javascript:validate_number(event);" ></td>
	</tr>
</table>
<input type="button" value="Назад" onclick="javascript:history.back();">
<input type="submit" value="Добавить">
</form>
*/?>
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