	
		<div class='content'>
			<h2>Аккаунт администратора</h2>
			<ul class='admin-buttons'>
				<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
				<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
				<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
			</ul>
			<h3>Заказы “Помощь в покупке”</h3>
			<form class='admin-sorting' id="filterForm" action="<?=$selfurl?>filterOpenOrders" method="POST">
				<div class='sorting'>
					<span class='first-title'>Сортировать по партнеру:</span>
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
				<div class='sorting'>
					<span class='first-title'>Поиск заказа:</span>
					<div class='text-field first-input'><div><input type='text' maxlength="11" name="search_id" value="<?=$filter->search_id?>" value='Введите текст поиска' /></div></div>
					<span>по:</span>
					<select name="id_type" class='select'>
						<option value="">выбрать...</option>
						<option value="package" <? if ('package' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру посылки</option>
						<option value="client" <? if ('client' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру пользователя</option>
					</select>	
				</div>
			</form>

			
			<form class='admin-inside' id="ordersForm" action="<?=$selfurl?>updateOpenOrdersStatus" method="POST">
				<ul class='tabs'>
					<li><div><a href='<?=$selfurl?>showNewPackages'>Новые</a></div></li>
					<li><div><a href='<?=$selfurl?>showPayedPackages'>Оплаченные</a></div></li>
					<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
					<li class='active'><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
					<li><div><a href='<?=$selfurl?>showClients'>Клиенты</a></div></li>
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
						<col width='200' />
						<col width='auto' />
						<col width='80' />
						<col width='120' />
                        <tr>
							<th>Номер заказ</th>
							<th>Партнер, страна</th>
							<th>Номер клиента</th>
							<th>Ф.И.О., адрес доставки</th>
							<th>Цена доставки</th>
							<th>Комментарии</th>
							<th>Статус</th>
							<th>Посмотреть / удалить</th>
						</tr>
						<?if ($orders) : foreach($orders as $order) : ?>
						<tr>
							<td nowrap>
								<b>№ <?=$order->order_id?></b><br /><?=$order->order_date?><br /><?=$order->order_weight?>кг<br />
								Прошло:<br /><?=$order->package_day == 0 ? "" : $order->package_day.' '.humanForm((int)$order->package_day, "день", "дня", "дней")?> <?=$order->package_hour == 0 ? "" : $order->package_hour.' '.humanForm((int)$order->package_hour, "час", "часа", "часов")?>
							</td>
							<td><?=$order->order_manager_login?> / <?=$order->order_manager_country?></td>
							<td><b>№ <?=$order->order_client?></b></td>
							<td><?=$order->order_address?></td>
							<td>
								<?=$order->order_cost?>$
								<a href="javascript:void(0)" onclick="$('#pre_<?=$order->order_id?>').toggle()">Подробнее</a>
								<pre class="pre-href" id="pre_<?=$order->order_id?>">
									<?= $order->order_delivery_cost ?>$
									<? if ($order->order_products_cost) : ?>
									+
									*<?= $order->order_products_cost ?>$
									<? endif;
									 if ($order->order_comission) : ?>
									+
									**<?= $order->order_comission ?>%
									<? endif; ?>
								</pre>
							</td>
							<td>
								<? if ($order->comment_for_manager || $order->comment_for_client) : ?>
									Добавлен новый комментарий<br />
								<? endif; ?>
								<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>#comments">Посмотреть</a>
							</td>
							<td>
								<select name="order_status<?=$order->order_id?>">
									<option value="proccessing" <? if ($order->order_status == 'proccessing') : ?>selected="selected"<?endif;?>>Обрабатывается</option>
									<option value="not_available" <? if ($order->order_status == 'not_available') : ?>selected="selected"<?endif;?>>Нет в наличии</option>
									<option value="not_available_color" <? if ($order->order_status == 'not_available_color') : ?>selected="selected"<?endif;?>>Нет данного цвета</option>
									<option value="not_available_size" <? if ($order->order_status == 'not_available_size') : ?>selected="selected"<?endif;?>>Нет данного размера</option>
									<option value="not_available_count" <? if ($order->order_status == 'not_available_count') : ?>selected="selected"<?endif;?>>Нет указанного кол-ва</option>
									<option value="not_payed" <? if ($order->order_status == 'not_payed') : ?>selected="selected"<?endif;?>>Не оплачен</option>
									<option value="payed" <? if ($order->order_status == 'payed') : ?>selected="selected"<?endif;?>>Оплачен</option>
									<option value="sended">Отправлен</option>
								</select>
							</td>
							<td align="center">
								<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">Посмотреть</a><br/>
								<hr />
								<a href="javascript:deleteItem('<?=$order->order_id?>');"><img title="Удалить" border="0" src="/static/images/delete.png"></a>
								<br/>
							</td>
						</tr>
						<?endforeach; endif;?>
						<tr class='last-row'>
							<td colspan='9'>
								<div class='float'>	
									<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
								</div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</form>

			<?php if (isset($pager)) echo $pager ?>
		</div>
<?php /*
Заказы "Помощь в покупке"
<br /><br />
<form id="filterForm" action="<?=$selfurl?>filterOpenOrders" method="POST">
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
	
<form id="ordersForm" action="<?=$selfurl?>updateOpenOrdersStatus" method="POST">
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
				<td><?=$order->order_cost?>р
					<hr />
					<?= $order->order_delivery_cost ?>р
					<? if ($order->order_products_cost) : ?>
					<br />+<br />
					*<?= $order->order_products_cost ?>р
					<? endif; if ($order->order_comission) : ?>
					<br />+<br />
					**<?= $order->order_comission ?>%
					<? endif; ?></td>
				<td><? if ($order->comment_for_manager || $order->comment_for_client) : ?>
					Добавлен новый комментарий<br />
				<? endif; ?>
				<a href="<?=$selfurl?>showOrderComments/<?=$order->order_id?>">Посмотреть</a>
				</td>
				<td><select name="order_status<?=$order->order_id?>">
						<option value="proccessing" <? if ($order->order_status == 'proccessing') : ?>selected="selected"<?endif;?>>Обрабатывается</option>
						<option value="not_available" <? if ($order->order_status == 'not_available') : ?>selected="selected"<?endif;?>>Нет в наличии</option>
						<option value="not_payed" <? if ($order->order_status == 'not_payed') : ?>selected="selected"<?endif;?>>Не оплачен</option>
						<option value="payed" <? if ($order->order_status == 'payed') : ?>selected="selected"<?endif;?>>Оплачен</option>
						<option value="sended">Отправлен</option>
					</select></td>
				<td>
					<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">Посмотреть</a> /
					<a href="javascript:deleteItem('<?=$order->order_id?>');">Удалить</a>
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

*/?>