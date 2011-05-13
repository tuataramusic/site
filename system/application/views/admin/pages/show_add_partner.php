<?if (isset($update) && $update==1):?><h1>Обновление информации о партнере</h1><?else:?><h1>Добавление нового партнера</h1><?endif;?>

<form action='<?=$selfurl?><?if (isset($update) && $update==1):?>updatePartner/<?=$user->user_id?><?else:?>addPartner<?endif;?>' method='POST'>
<table>
	<tr><td>Логин:</td><td><?if (isset($update) && $update==1):?><?=$user->user_login?><?else:?><input type='text' name='user_login' size='30' value='<?=$user ? $user->user_login :'';?>'/><?endif;?></td></tr>
	<tr><td>Пароль:</td><td><input type='text' name='user_pass' size='30' value=''/></td></tr>
	<tr><td>Email:</td><td><input type='text' name='user_email' size='30' value='<?=$user ? $user->user_email :'';?>'/></td></tr>
	
	<tr><td>Имя:</td><td><input type='text' name='manager_name' size='30' value='<?=isset($manager) ? $manager->manager_name :'';?>'/></td></tr>
	<tr><td>Фамилия:</td><td><input type='text' name='manager_surname' size='30' value='<?=isset($manager) ? $manager->manager_surname :'';?>'/></td></tr>
	<tr><td>Отчество:</td><td><input type='text' name='manager_otc' size='30' value='<?=isset($manager) ? $manager->manager_otc :'';?>'/></td></tr>
	<tr><td>Адрес:</td><td><textarea name='manager_addres' rows='3' cols='30'><?=isset($manager) ? $manager->manager_addres :'';?></textarea></td></tr>
	<tr><td>Телефон:</td><td><input type='text' name='manager_phone' size='30' value='<?=isset($manager) ? $manager->manager_phone :'';?>'/></td></tr>
	<tr><td>Страна:</td>
		<td>
		<select name="manager_country" style="width: 230px;">
			<option value="0">выберите...</option>
			<?if (count($countries)>0): foreach ($countries as $country):?>
				<option value="<?=$country->country_id;?>" <?= (isset($manager) && $manager->manager_country==$country->country_id) ? 'selected' :'';?>><?=$country->country_name?></option>
			<?endforeach; endif;?>	
		</select>
		</td></tr>
	<tr><td>Способы доставки:</td><td><?if (count($deliveries) > 0): 
		foreach ($deliveries as $delivery) : ?>
		<input type="checkbox" name="delivery<?=$delivery->delivery_id?>" id="delivery<?=$delivery->delivery_id?>" <?=$delivery->checked?> />
		<label for="delivery<?=$delivery->delivery_id?>"><?=$delivery->delivery_name?></label><br />
				<?endforeach; endif;?></td></tr>
	<tr><td style="width: 150px;">Максимальное кол-во пользователей:</td><td><input type='text' name='manager_max_clients' size='30' value='<?=isset($manager) ? $manager->manager_max_clients :'50';?>'/></td></tr>
	<tr><td>Статус:</td>
		<td>
			<select name='manager_status' style="width: 230px;">
				<option value="0">выберите...</option>
				<?if (count($statuses)>0): foreach ($statuses as $key=>$status):?>
					<option value="<?=$key;?>" <?= (isset($manager) && $manager->manager_status==$key) ? 'selected' :'';?>><?=$status?></option>
				<?endforeach; endif;?>
			</select>
		</td></tr>
	<tr><td></td><td style="text-align: center;"><input type='submit' name='add' value='Сохранить'/></td></tr>
</table>
</form>