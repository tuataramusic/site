<div class='content'>
	<h2>Закрытые заказы</h2>
	<form class='admin-inside' action='#'>
		
		<ul class='tabs'>
			<li><div><a href='<?=$selfurl?>showOpenPackages'>Ожидающие отправки</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
			<li><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
			<li class='active'><div><a href="<?=$selfurl?>showSentOrders">Закрытые заказы</a></div></li>
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
					<th>№ заказа</th>
					<th>Название магазина</th>
					<th>Страна / Дата / Вес</th>
					<th>Комментарии</th>
					<th>Общая<br />стоимость<br />с местной<br />доставкой</th>
					<th>Примерная<br />стоимость<br />международной<br />доставки *</th>
					<th>Статус</th>
					<th>Посмотреть</th>
				</tr>

				<?if ($orders) : foreach($orders as $order) : ?>
				<tr>
					<td><b>№ <?=$order->order_id?></b></td>
					<td><?=$order->order_shop_name?></td>
					<td><?=$order->order_manager_country?> <?=$order->order_date?> <?=Func::round2half($order->order_weight)?>кг <?=Func::round2half($order->order_weight) != $order->order_weight ? '('.$order->order_weight.'кг)' : '';?></td>
					<td><? if ($order->comment_for_client) : ?>
						Добавлен новый комментарий<br />
					<? endif; ?><a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>#comments">Посмотреть</a>
					</td>
					<td><?=$order->order_cost?>$</td>
					<td></td>
					<td>Отправлен</td>
					<td>
						<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">Посмотреть</a>
					</td>
				</tr>
				<?endforeach; endif;?>
				<tr class='last-row'>
					<td colspan='9'>
						<div id="tableComments" style="text-align:left;float:left;">
							* данная стоимость рассчитывается приблизительно и может не совпадать с реальной<br />
							стоимостью доставки. Точную стоимость международной доставки Вы можете узнать<br />
							в Вашем Личном Кабинете, в разделе "Посылки, ожидающие отправки" после того,<br />
							как мы получим посылку
						</div>
						<div class='float'>	
							<div class='submit'><div></div></div>
						</div>
					</td>
					<td>
					</td>
				</tr>
			</table>
		</div>
	</form>

	<?php if (isset($pager)) echo $pager ?>
</div>

<script type="text/javascript">
	function deleteItem(id) {
		if (confirm("Вы уверены, что хотите удалить заказ №" + id + "?")){
			window.location.href = '<?=$selfurl?>deleteOrder/' + id;
		}
	}

	function payItem(id) {
		if (confirm("Оплатить заказ №" + id + "?")){
			window.location.href = '<?=$selfurl?>payOrder/' + id;
		}
	}
</script>