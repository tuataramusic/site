<div class='content'>

	<h2>Аккаунт администратора</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
	</ul>

	<h3>Добавить способ доставки</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div>

	<center>
	<form class='card' action='<?=$selfurl?>addDelivery' method='POST'>

		<table>
			<tr>
				<th>Название доставки:</th>
				<td>
					<div class='text-field name-field'><div><input type="text" name="delivery_name" maxlength="32" /></div></div>
				</td>
				<td>
					<span>Срок доставки:</span>
					<div class='text-field number-field'><div><input type="text" name="delivery_time" maxlength="32" /></div></div>
				</td>
			</tr>

			<tr>
				<td class='total-price' colspan='4'>
					<div class='submit'><div><input type='submit' value='Добавить' /></div></div>
				</td>
			</tr>
		</table>
	</form>
	</center>
</div>