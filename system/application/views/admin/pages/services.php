<div class='content'>

	<h2>Аккаунт администратора</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
	</ul>

	<h3>Изменение цен за услуги</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div><br />

	<center>
	<form class="admin-inside" action="<?=$selfurl?>saveServicesPrice" method="POST">

		<div class='table' style="width:40% !important">
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table width="60%">
				<tr>
					<td><span>Цена за пересылку:</span></td>
					<td><input type="text" name="transmission" size="5" value="<?=$config['price_for_trasmission']->config_value?>"></td>
				</tr>	
				<tr>
					<td>Цена за помощь в заказе:</td>
					<td><input type="text" name="help" size="5" value="<?=$config['price_for_help']->config_value?>">%</td>
				</tr>		
				<tr>
					<td>Цена за заполнение декларации:</td>
					<td><input type="text" name="declaration" size="5" value="<?=$config['price_for_declaration']->config_value?>"></td>
				</tr>
				<tr>
					<td>Цена за объединение посылок:</td>
					<td><input type="text" name="merge" size="5" value="<?=$config['price_for_marge']->config_value?>"></td>
				</tr>
				<tr>
					<td>Цена за страховку:</td>
					<td><input type="text" name="insurance" size="5" value="<?=$config['price_for_insurance']->config_value?>">%</td>
				</tr>
				<tr>
					<td>Максимальная сумма страховки:</td>
					<td><input type="text" name="max_insurance" size="5" value="<?=$config['max_insurance']->config_value?>">р</td>
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