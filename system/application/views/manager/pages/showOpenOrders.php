<div class='content'>
	<h2>Аккаунт Партнера</h2>

	<h3>Заказы "Помощь в покупке"</h3>
	<form class='admin-inside' action="<?=$selfurl?>closeOrders" method="POST">
	
		<ul class='tabs'>
			<li><div><a href='<?=$selfurl?>showAddPackage'>Добавить посылку</a></div></li>
			<li><div><a href='<?=$selfurl?>showNewPackages'>Новые</a></div></li>
			<li><div><a href='<?=$selfurl?>showPayedPackages'>Оплаченные</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
			<li class='active'><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentOrders'>Закрытые заказы</a></div></li>
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
				<col width='auto' />
				<tr>
					<th>Номер заказа</th>
					<th>Название магазина</th>
					<th>Номер клиента</th>
					<th>Дата формирования заказа</th>
					<th>Цена доставки</th>
					<th>Комментарии к заказу</th>
					<th>Статус</th>
					<th>Заказ отправлен<br />(закрыть заказ)</th>
					<th>Просмотр деталей заказа</th>
				</tr>
				<?if ($orders) : foreach($orders as $order) : ?>
				<tr>
					<td><b>№ <?=$order->order_id?></b></td>
					<td><? echo($order->order_shop_name); 
						if ($order->order_status == 'proccessing') : ?><br />NEW<? endif; ?></td>
					<td><b>№ <?=$order->order_client?></b></td>
					<td><?=$order->order_date?></td>
					<td><?=$order->order_cost?>$</td>
					<td><? if ($order->comment_for_manager) : ?>
						Добавлен новый комментарий<br />
					<? endif; ?>
					<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>#comments">Посмотреть / добавить</a>
					</td>
					<td>
					<? switch ($order->order_status) {
						case 'proccessing': ?>Обрабатывается<? break;
						case 'not_available': ?>Нет в наличии<? break;
						case 'not_available_color': ?>Нет данного цвета<? break;
						case 'not_available_size': ?>Нет данного размера<? break;
						case 'not_available_count': ?>Нет указанного кол-ва<? break;
						case 'not_payed': ?>Не оплачен<? break;
						case 'payed': ?>Оплачен<? break; } ?>
					</td>
					<td>
						<input type="checkbox" id="order<?=$order->order_id?>" name="order<?=$order->order_id?>">
					</td>
					<td><a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">Посмотреть</a></td>
				</tr>
				<?endforeach;?>
				<tr class='last-row'>
					<td colspan='9'>
						<br />
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
						</div>
					</td>
					<td></td>
				</tr>
				<?endif;?>
			</table>
		</div>
	</form>
	<div class='pages'><div class='block'><div class='inner-block'>
		<a href='#' class='endpoints'>1</a><a href='#'>2</a><a href='#'>3</a><span>...</span><a href='#'>17</a><span>18</span><a href='#'>19</a><span>...</span><a href='#'>83</a><a href='#'>84</a><a href='#' class='endpoints'>85</a>
	</div></div></div>
</div>

<script type="text/javascript">
	$('#ordersForm').submit(function() {
		if ($('#ordersForm input:checkbox:checked').size() == 0)
		{
			alert('Выберите заказы для отправки.');
			return false;
		}
		
		if (!confirm('Вы уверены, что хотите отправить выбранные заказы?'))
		{
			return false;
		}
	});
</script>