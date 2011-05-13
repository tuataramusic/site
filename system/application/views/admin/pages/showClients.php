<div class='content'>
	<h2>Аккаунт администратора</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
	</ul>
	<h3>Клиенты (всего: <?=$clients_count?>)</h3>
	<form class='admin-sorting' id="filterForm" action="<?=$selfurl?>filterClients" method="POST">
		<div class='sorting'>
	
			<span class='first-title'>Отфильтровать по стране:</span>
			<select name="client_country" class='select first-input'>
				<option value="">выбрать...</option>
				<?if ($countries) : foreach($countries as $country) : ?>
					<option value="<?=$country->country_id?>" <? if ($country->country_id == $filter->client_country) : ?>selected="selected"<? endif; ?>><?=$country->country_name?></option>
				<?endforeach; endif;?>
			</select>

			<span class='first-title'>партнеру:</span>
			<select name="manager_user" class='select first-input'>
				<option value="">выбрать...</option>
				<?if ($managers) : foreach($managers as $manager) : ?>
					<option value="<?=$manager->manager_user?>" <? if ($manager->manager_user == $filter->manager_user) : ?>selected="selected"<? endif; ?>><?=$manager->user_login?></option>
				<?endforeach; endif;?>
			</select>
			<span>за:</span>
			<select name="period" class='select'>
				<option value="">все</option>
				<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>день</option>
				<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>неделю</option>
				<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>месяц</option>
			</select>
		</div>
		<br />
		<br />
		<div class='sorting'>
			<span class='first-title'>Поиск:</span>
			<div class='text-field first-input'><div><input type="text" maxlength="11" name="search_client" value="<?=$filter->search_client?>"/></div></div>
			<span>по:</span>

			<select name="id_type" class='select'>
				<option value="">выбрать...</option>
				<option value="login" <? if ('login' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Логину</option>
				<option value="client_number" <? if ('client_number' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру</option>
			</select>	
		</div>
	</form>
		
	<form class='admin-inside' id="clientsForm" action="<?=$selfurl?>moveClients" method="POST">
		<ul class='tabs'>
			<li><div><a href='<?=$selfurl?>showNewPackages'>Новые</a></div></li>
			<li><div><a href='<?=$selfurl?>showPayedPackages'>Оплаченные</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
			<li><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
			<li  class='active'><div><a href='<?=$selfurl?>showClients'>Клиенты</a></div></li>
			<li><div><a href='<?=$selfurl?>showPartners'>Партнеры</a></div></li>
		</ul>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<tr>
					<th>Номер клиента</th>
					<th>Логин</th>
					<th>Ф.И.О.</th>
					<th>Адрес доставки</th>
					<th>Кол-во сделанных заказов</th>
					<th>Партнер / Страна</th>
					<th>Баланс</th>
					<th>Изменить / удалить</th>
				</tr>
				<?if ($clients): foreach ($clients as $client):?>
				<tr>
					<td><b>№ <?=$client->client_user?></b></td>
					<td><?=$client->user_login?> / <?=$country_list[$client->client_country]?></td>
					<td><?=$client->client_surname?> <?=$client->client_name?> <?=$client->client_otc?></td>
					<td><?=$client->client_address?></td>
					<td>Пересылка: <?=$client->package_count == '' ? 0 : $client->package_count?><br />Помощь в заказе: <?=$client->order_count == '' ? 0 : $client->order_count?></td>
					<td>
						<?if ($client->managers) : foreach ($client->managers as $manager) : ?>
							<?=$manager->user_login?> / <?=$country_list[$manager->manager_country]?><br />
						<? endforeach; endif; ?>	
						<input type="checkbox" name="move<?=$client->client_user?>"/>
					</td>
					<td>
						<?=$client->user_coints?><br />
						<a href='<?=$selfurl?>editClientBalance/<?=$client->client_user?>'>Изменить</a>
					</td>
					<td align="center">
						<a href='<?=$selfurl?>editClient/<?=$client->client_user?>'>Изменить</a><br/>
						<hr />
						<a href='<?=$selfurl?>deleteClient/<?=$client->client_user?>'><img title="Удалить" border="0" src="/static/images/delete.png"></a>
						<br/>
					</td>
				</tr>
				<?endforeach;endif;?>	

				<tr class='last-row'>
					<td colspan='9'>
						<div class='float'>	
							<div class='submit'>
								Переместить к:
								<select name="newPartnerId" id="newPartnerId">
									<option value="-1">выбрать...</option>
									<?if ($managers && $countries) : foreach($managers as $manager) : ?>
										<option value="<?=$manager->manager_user?>"><?=$manager->user_login?> (<?=$country_list[$manager->manager_country]?>)</option>
									<?endforeach; endif;?>
								</select>
						</div></div>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
	</form>
	<?php if (isset($pager)) echo $pager ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});

		$('#newPartnerId').change(function() 
		{
			var selectedPartner = $('#newPartnerId option:selected');
			
			if (selectedPartner.val() == '-1')
			{
				return false;
			}
			
			if ($('#clientsForm input:checkbox:checked').size() == 0)
			{
				alert('Выберите клиентов для перемещения.');
				return false;
			}
			
			if (confirm('Вы уверены, что хотите переместить выбранных клиентов к новому партнеру?'))
			{
				document.getElementById('clientsForm').submit();
			}
		});
	});
</script>
<?php /*
Клиенты (всего: <?=$clients_count?>)
<br /><br />

<form id="filterForm" action="<?=$selfurl?>filterClients" method="POST">
	<div id="clientFilter" align="center">
		Отфильтровать по стране: <select name="client_country">
			<option value="">выбрать...</option>
			<?if ($countries) : foreach($countries as $country) : ?>
			<option value="<?=$country->country_id?>" <? if ($country->country_id == $filter->client_country) : ?>selected="selected"<? endif; ?>><?=$country->country_name?></option>
			<?endforeach; endif;?></select> партнеру: <select name="manager_user">
			<option value="">выбрать...</option>
			<?if ($managers) : foreach($managers as $manager) : ?>
			<option value="<?=$manager->manager_user?>" <? if ($manager->manager_user == $filter->manager_user) : ?>selected="selected"<? endif; ?>><?=$manager->user_login?></option>
			<?endforeach; endif;?></select> за <select name="period">
			<option value="">все</option>
			<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>день</option>
			<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>неделю</option>
			<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>месяц</option>
			</select> Поиск: <input type="text" maxlength="11" name="search_client" value="<?=$filter->search_client?>"/> по <select name="id_type">
			<option value="">выбрать...</option>
			<option value="login" <? if ('login' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Логину</option>
			<option value="client_number" <? if ('client_number' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру</option>
		</select>
	</div>
</form>
	
<?if ($clients):?>
<br />
<form id="clientsForm" action="<?=$selfurl?>moveClients" method="POST">
	<table>
		<tr>
			<td>№</td>
			<td>Логин</td>
			<td>ФИО</td>
			<td>Адрес доставки</td>
			<td>Кол-во сделанных заказов</td>
			<td>Партнер / Страна</td>
			<td>Баланс</td>
			<td>Изменить / Удалить</td>
		</tr>
		<?foreach ($clients as $client):?>
		<tr>
			<td><?=$client->client_user?></td>
			<td><?=$client->user_login?> / <?=$country_list[$client->client_country]?></td>
			<td><?=$client->client_surname?> <?=$client->client_name?> <?=$client->client_otc?></td>
			<td><?=$client->client_address?></td>
			<td>Пересылка: <?=$client->package_count == '' ? 0 : $client->package_count?><br />Помощь в заказе: <?=$client->order_count == '' ? 0 : $client->order_count?></td>
			<td><?if ($client->managers) : foreach ($client->managers as $manager) : ?>
				<?=$manager->user_login?> / <?=$country_list[$manager->manager_country]?><br />
				<? endforeach; endif; ?>	
				<input type="checkbox" name="move<?=$client->client_user?>" ></td>
			<td><?=$client->user_coints?><br />
				<a href='<?=$selfurl?>editClientBalance/<?=$client->client_user?>'>Изменить</a></td>
			<td><a href='<?=$selfurl?>editClient/<?=$client->client_user?>'>Изменить</a> / <a href='<?=$selfurl?>deleteClient/<?=$client->client_user?>'>Удалить</a></td>
		</tr>
		<?endforeach;?>	
	</table>

	<div id="clientMoveFilter" align="center">
	Переместить к: <select name="newPartnerId" id="newPartnerId">
		<option value="-1">выбрать...</option>
		<?if ($managers && $countries) : foreach($managers as $manager) : ?>
		<option value="<?=$manager->manager_user?>"><?=$manager->user_login?> (<?=$country_list[$manager->manager_country]?>)</option>
		<?endforeach; endif;?></select>
	</div>
</form>
<?endif;?>

*/?>