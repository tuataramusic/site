<div class='content'>
	<h3>Заказ № <?=$order->order_id?></h3>

	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div><br />
	<form class='admin-inside' id="orderForm" action="<?=$selfurl?>updateOrderDetails" method="POST">
		
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
			
				<tr>
					<th>№ клиента /<br />Логин</th>
					<th>ФИО / Адрес<br />доставки / телефон<br />/ Email / Доп. контакты</th>
					<th>Общая цена заказа с<br />учетом местной доставки</th>
					<th>Статус</th>
				</tr>
				<tr>
					<td><?=$order->order_client?> / <?=$order->order_login?></td>
					<td><?=$order->order_address?></td>
					<td align="right"><?=$order->order_cost?>$<br />
						<hr />
						<span>Общая стоимость указанных товаров: </span>
						<input name="order_products_cost" type="text" value="<?=$order->order_products_cost?>"/><br /><br />
						<span>Цена местной доставки: <span>
						<input name="order_delivery_cost" type="text" value="<?=$order->order_delivery_cost?>"/><br /><br />
						<span>Примерный вес посылки: <span>
						<input name="order_weight" type="text" value="<?=$order->order_weight?>"/><br /><br />
						<input name="order_id" type="hidden" value="<?=$order->order_id?>"/>
					</td>
					<td><?=$order->order_status_desc?></td>
				</tr>
				<tr class='last-row'>
					<td colspan='4'>
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</form>
	
	<br /><hr />
	
	<h3>Товары для покупки в заказе:</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div><br />	
	
	<form class='admin-inside' id="detailsForm" action="<?=$selfurl?>updateOdetailStatuses" method="POST">
		<input name="order_id" type="hidden" value="<?=$order->order_id?>"/>
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
					<th>№</th>
					<th>Название магазина</th>
					<th>Наименование</th>
					<th>Цвет / Размер / Кол-во</th>
					<th>Скриншот</th>
					<th>Ссылка на товар</th>
                    <th>Цена</th>
                    <th>Местная доставка</th>
					<th>Статус</th>
				</tr>
				<?if ($odetails) : foreach($odetails as $odetail) : ?>
				<tr>
					<td><?=$odetail->odetail_id?></td>
					<td><?=$odetail->odetail_shop_name?></td>
					<td><?=$odetail->odetail_product_name?></td>
					<td><?=$odetail->odetail_product_color?> / <?=$odetail->odetail_product_size?> / <?=$odetail->odetail_product_amount?></td>
					<td><a href="javascript:void(0)" onclick="setRel(<?=$odetail->odetail_id?>);">
	                        Просмотреть скриншот <a rel="lightbox_<?=$odetail->odetail_id?>" href="/client/showScreen/<?=$odetail->odetail_id?>" style="display:none;">Посмотреть</a>
						</a></td>
					<td><a href="#" onclick="window.open('<?=$odetail->odetail_link?>');return false;"><?=(strlen($odetail->odetail_link)>20?substr($odetail->odetail_link,0,20).'...':$odetail->odetail_link)?></a></td>
                    <td><input size="3" type="text" name="odetail_price<?=$odetail->odetail_id?>" value="<?=$odetail->odetail_price?>"></td>
                    <td><input size="3" type="text" name="odetail_pricedelivery<?=$odetail->odetail_id?>" value="<?=$odetail->odetail_pricedelivery?>"></td>
					<td>
						<select class="select" name="odetail_status<?=$odetail->odetail_id?>">
                        <?
                        foreach ($odetails_statuses as $key => $val)
						{
								?><option value="<?=$key?>" <? if ($odetail->odetail_status == $key) : ?>selected="selected"<? endif; ?>><?=$val?></option><?
						}
						?>
						</select>
					</td>
				</tr>
				<?endforeach; endif;?>
				<tr class='last-row'>
					<td colspan='9'>
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</form>
	<a name="comments"></a>
	<h3>Комментарии к заказу</h3>
	<form class='comments' action='<?=$selfurl?>addOrderComment/<?=$order->order_id?>' method='POST'>
		<?if (!$comments):?>
			<div class='comment'>
				Пока нет комментариев<br/>
			</div>
		<?else:?>
			<? foreach ($comments as $comment):?>
				<div class='comment'>
					<div class='question'>
					<?if ($comment->ocomment_user == $order->order_client):?>
						<span class="name">Клиент:</span>
					<?elseif ($comment->ocomment_user == $order->order_manager):?>
						<span class="name">Партнер:</span>
					<?else:?>
						<span class="name">Администрация:</span>
					<?endif;?>
						<p><?=$comment->ocomment_comment?></p>
					</div>
				</div>
			<? endforeach; ?>
		<?endif;?>
	
		<div class='add-comment'>
			<div class='textarea'><textarea name='comment'></textarea></div>
			<div class='submit'><div><input type='submit' name="add" value="Добавить" /></div></div>
		</div>
	</form>
</div>









<?/*

Заказ № <?=$order->order_id?>
<form id="orderForm" action="<?=$selfurl?>updateOrderDetails" method="POST">
	<a href='javascript:history.back();'>Назад</a>
	<input type="submit" value="Сохранить"/>

	<div id="Order" align="center">
		<table>
			<tr>
				<th>№ клиента /<br />Логин</th>
				<th>ФИО / Адрес<br />доставки / телефон<br />/ Email / Доп. контакты</th>
				<th>Общая цена заказа с<br />учетом местной доставки</th>
				<th>Статус</th>
			</tr>
			<tr>
				<td><?=$order->order_client?><br /><?=$order->order_login?></td>
				<td><?=$order->order_address?></td>
				<td><input name="order_cost" type="text" value="<?=$order->order_cost?>"/><br />
					<hr />
					Общая стоимость указанных товаров:<br />
					<input name="order_products_cost" type="text" value="<?=$order->order_products_cost?>"/><br />
					Цена местной доставки:<br />
					<input name="order_delivery_cost" type="text" value="<?=$order->order_delivery_cost?>"/><br />
					Примерный вес посылки:<br />
					<input name="order_weight" type="text" value="<?=$order->order_weight?>"/><br />
					<input name="order_id" type="hidden" value="<?=$order->order_id?>"/><br />
				</td>
				<td><? if ($order->order_status == 'proccessing') : ?>
					Обрабатывается
				<? elseif ($order->order_status == 'not_available') : ?>
					Нет в наличии
				<? elseif ($order->order_status == 'not_payed') : ?>
					Не оплачен
				<? elseif ($order->order_status == 'payed') : ?>
					Оплачен
				<? elseif ($order->order_status == 'sended') : ?>
					Отправлен
				<? endif; ?></td>
			</tr>
		</table>
	</div>
</form>

Товары для покупки в заказе:
<form id="detailsForm" action="<?=$selfurl?>updateOdetailStatuses" method="POST">
	<div id="OrderDetails" align="center">
		<table>
			<tr>
				<th>№</th>
				<th>Название магазина</th>
				<th>Наименование</th>
				<th>Цвет</th>
				<th>Размер</th>
				<th>Кол-во</th>
				<th>Скриншот</th>
				<th>Ссылка на товар</th>
				<th>Статус</th>
			</tr>
			<?if ($odetails) : foreach($odetails as $odetail) : ?>
			<tr>
				<td><?=$odetail->odetail_id?></td>
				<td><?=$odetail->odetail_shop_name?></td>
				<td><?=$odetail->odetail_product_name?></td>
				<td><?=$odetail->odetail_product_color?></td>
				<td><?=$odetail->odetail_product_size?></td>
				<td><?=$odetail->odetail_product_amount?></td>
				<td></td>
				<td><?=$odetail->odetail_link?></td>
				<td>
					<select name="odetail_status<?=$odetail->odetail_id?>">
						<option value="not_available" <? if ($odetail->odetail_status == 'not_available') : ?>selected="selected"<? endif; ?>>Нет в наличии</option>
						<option value="available" <? if ($odetail->odetail_status == 'available') : ?>selected="selected"<? endif; ?>>Есть в наличии</option>
					</select>
				</td>
			</tr>
			<?endforeach; endif;?>
		</table>
	</div>

	<input name="order_id" type="hidden" value="<?=$order->order_id?>"/><br />
	<a href='javascript:history.back();'>Назад</a>
	<input type="submit" value="Сохранить"/>
</form>

*/?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#orderForm input:text').keypress(function(event){validate_number(event);});
	});
	
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