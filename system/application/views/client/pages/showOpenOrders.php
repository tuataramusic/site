
<div class='content'>
	<h2>Заказы "Помощь в покупке"</h2>

	<?if(isset($result->m) && $result->m):?><em class="order_result"><?=$result->m?></em><br/><?endif;?>
	
	<?View::show($viewpath.'elements/div_float_help');?>
	
	<?View::show($viewpath.'elements/div_float_manual');?>
	
<!--	<div align="left"><a href="javascript:lay();">Помощь в формировании заказа</a></div>-->
	<div class="admin-inside" style="height:50px">
		<div class="submit">
			<div>
				<input type="button" onclick="lay2()" name="add" value="Добавить заказ" sty le="width:125px !important;">
			</div>
		</div>
	</div>
	
	<form class='admin-inside' action='#'>
		
		<ul class='tabs'>
			<li><div><a href='<?=$selfurl?>showOpenPackages'>Ожидающие отправки</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
			<li class='active'><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
			<li><div><a href="<?=$selfurl?>showSentOrders">Закрытые заказы</a></div></li>
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
					<th>Страна / Дата / Вес</th>
					<th>Комментарии</th>
					<th>Общая стоимость с местной доставкой</th>
					<th>Примерная стоимость международной доставки ***</th>
					<th>Статус</th>
					<th>Оплатить</th>
					<th>Посмотреть / Удалить</th>
				</tr>

				<?if ($orders) : foreach($orders as $order) : ?>
				<tr>
					<td><b>№ <?=$order->order_id?></b></td>
					<td><?=$order->order_shop_name?></td>
					<td><?=$order->order_manager_country?> <?=$order->order_date?> <?=Func::round2half($order->order_weight)?>кг <?=Func::round2half($order->order_weight) != $order->order_weight ? '('.$order->order_weight.'кг)' : '';?></td>
					<td><? if ($order->comment_for_client) : ?>
						Добавлен новый комментарий<br />
					<? endif; ?><a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>#comments">Посмотреть</a></td>
					<td><?=$order->order_cost?>$
						<a href="javascript:void(0)" onclick="$('#pre_<?=$order->order_id?>').toggle()">Подробнее</a>
						<pre class="pre-href" id="pre_<?=$order->order_id?>">
							<?= $order->order_delivery_cost ?>$
							<? if ($order->order_products_cost) : ?>
							+
							*<?= $order->order_products_cost ?>$
							<? endif; if ($order->order_comission) : ?>
							+
							**<?= $order->order_comission ?>%
							<? endif; ?>
						</pre>
					</td>
					<td><?= $order->package_delivery_cost ?></td>
					<td>
						<?	  if ($order->order_status == 'proccessing') : ?>Обрабатывается
						<?elseif ($order->order_status == 'not_available') : ?>Нет в наличии<br /><i>Удалите из заказа товары, которых нет в наличии</i>
						<?elseif ($order->order_status == 'not_available_color'):?>Нет данного цвета
						<?elseif ($order->order_status == 'not_available_size'):?>Нет данного размера
						<?elseif ($order->order_status == 'not_available_count'):?>Нет указанного кол-ва
						<?elseif ($order->order_status == 'not_payed') : ?>Не оплачен
                        <?elseif ($order->order_status == 'not_delivered') : ?>Не получен
						<?elseif ($order->order_status == 'payed') : ?>Оплачен<? endif; ?>
					</td>
					<td>
						<? if ($order->order_status == 'not_payed') : ?><a href="javascript:payItem(<?=$order->order_id?>);">Оплатить</a><? endif; ?>
					</td>
					<td>
						<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">Посмотреть</a><br />
						<hr>
						<center><a href="javascript:deleteItem('<?=$order->order_id?>');"><img border="0" src="/static/images/delete.png" title="Удалить"></a></center>
						<br />
					</td>
				</tr>
				<?endforeach; endif;?>
				<tr class='last-row'>
					<td colspan='9'>
					<div id="tableComments" style="text-align:left;float:left;">
							* стоимость местной доставки<br />
							** комиссия за помощь в покупке<br />
							*** данная стоимость рассчитывается приблизительно и может не совпадать с реальной<br />
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