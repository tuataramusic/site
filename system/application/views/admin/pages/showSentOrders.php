Закрытые заказы
<br /><br />
<form id="filterForm" action="<?=$selfurl?>filterSentOrders" method="POST">
	<div id="orderFilter" align="center">
		Отфильтровать по партнеру <select name="manager_user">
			<option value="">выбрать...</option>
			<?if ($managers) : foreach($managers as $manager) : ?>
			<option value="<?=$manager->manager_user?>" <? if ($manager->manager_user == $filter->manager_user) : ?>selected="selected"<? endif; ?>><?=$manager->user_login?></option>
			<?endforeach; endif;?></select> за <select name="period">
			<option value="">все</option>
			<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>день</option>
			<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>неделю</option>
			<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>месяц</option>
		</select> Поиск заказа <input type="text" maxlength="11" name="search_id" value="<?=$filter->search_id?>"/> по <select name="id_type">
			<option value="">выбрать...</option>
			<option value="order" <? if ('order' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру заказа</option>
			<option value="client" <? if ('client' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру пользователя</option>
		</select>
	</div>
</form>
	
<br />
	
<form id="ordersForm" action="<?=$selfurl?>updateSentOrdersStatus" method="POST">
	<div id="Deliveries" align="center">
		<table>
			<tr>
				<th>Партнер / Страна</th>
				<th>№ клиента</th>
				<th>№ заказа</th>
				<th>ФИО / Адрес доставки</th>
				<th>Цена доставки</th>
				<th>Комментарии</th>
				<th>Статус</th>
				<th>Посмотреть / Удалить</th>
			</tr>
			<?if ($orders) : foreach($orders as $order) : ?>
			<tr>
				<td><?=$order->order_manager_login?> / <?=$order->order_manager_country?></td>
				<td><?=$order->order_client?></td>
				<td><?=$order->order_id?> <?=$order->order_date?> <?=$order->order_weight?>кг<br />
					Прошло <?=$order->order_age ?> часов</td>
				<td><?=$order->order_address?></td>
				<td><?=$order->order_cost?>р</td>
				<td><? if ($order->comment_for_manager || $order->comment_for_client) : ?>
					Добавлен новый комментарий<br />
				<? endif; ?>
				<a href="<?=$selfurl?>showOrderComments/<?=$order->order_id?>">Посмотреть</a>
				</td>
				<td><select name="order_status<?=$order->order_id?>">
						<option value="proccessing">Обрабатывается</option>
						<option value="not_available">Нет в наличии</option>
						<option value="not_payed">Не оплачен</option>
						<option value="payed">Оплачен</option>
						<option value="sended" selected="selected">Отправлен</option>
					</select></td>
				<td>
					<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">Посмотреть</a> /
					<a href="javascript:deleteItem(<?=$order->order_id?>);">Удалить</a>
				</td>
			</tr>
			<?endforeach; endif;?>
		</table>
	</div>

	<input type="submit" value="Сохранить"/>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});
		
		$('#filterForm input:text').keypress(function(event){validate_number(event);});
	})
	
	function deleteItem(id){
		if (confirm("Вы уверены, что хотите удалить заказ №" + id + "?")){
			window.location.href = '<?=$selfurl?>deleteOrder/' + id;
		}
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
</script>