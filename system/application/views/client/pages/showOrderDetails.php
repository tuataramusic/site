<script type="text/javascript" src="/static/js/easyTooltip.js"></script>
<script type="text/javascript" src="/static/js/jquery.numeric.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){
		$("img.tooltip").easyTooltip();
		$("img.tooltip_rbk").easyTooltip({
			tooltipId: "tooltip_id",
			content: '\
				<div class="box">\
					<p>Если в течении 7 дней этот товар не был доставлен и не появился в разделе "Посылки ожидающие отправки" поставьте тут галочку и нажмите сохранить.</p>\
				</div>\
			'
		});
	});
	
	function setstatusundelivered(o)
	{
		if(o.checked)
		{
			$.get("/client/setStatusUndelivered/" + o.value, { odetail_id: o.value}, function(data){
			  alert("Данные загружены: ");
			});
		}
	}

</script>
<div class='content'>
	<h2>Заказ № <?=$order->order_id?></h2>
    <? View::show($viewpath.'elements/div_float_manual'); ?>	
	<form class='admin-inside'>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<th>Общая цена заказа с учетом местной доставки</th>
					<th>Статус</th>
				</tr>
				<tr>
					<td><?=$order->order_cost?>$<br />
						<hr />
						Общая стоимость указанных товаров: <?=$order->order_products_cost?>$<br />
						Цена местной доставки: <?=$order->order_delivery_cost?>$<br />
						Примерный вес посылки: <?=$order->order_weight?>кг
					</td>
                    
					<td><?=$order->order_status_desc; ?></td>
				</tr>
			</table>
		</div>
	</form>
	
	<br /><hr />

	<h3>Товары для покупки в заказе:</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div><br />	
    
    <div style="height: 50px;" class="admin-inside">
		<div class="submit">
			<div>
				<input type="button" le="width:125px !important;" sty="" value="Добавить товар" name="add" onclick="lay2()">
			</div>
		</div>
	</div>
    
	<form class='admin-inside'>
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<th>№</th>
					<th>Название магазина</th>
					<th>Наименование</th>
					<th>Цвет / Размер / Кол-во</th>
					<th>Скриншот</th>
					<th>Ссылка на товар</th>
					<th>Статус</th>
                    <th>Цена</th>
                    <th>Местная доставка</th>
					<th width="1">Удалить</th>
				</tr>
				<?if ($odetails) : foreach($odetails as $odetail) : ?>
				<tr>
					<td><?=$odetail->odetail_id?></td>
					<td><?=$odetail->odetail_shop_name?></td>
					<td><?=$odetail->odetail_product_name?></td>
					<td><?=$odetail->odetail_product_color?> / <?=$odetail->odetail_product_size?> / <?=$odetail->odetail_product_amount?></td>
					<td>
						<a href="javascript:void(0)" onclick="setRel(<?=$odetail->odetail_id?>);">
	                        Просмотреть скриншот <a rel="lightbox_<?=$odetail->odetail_id?>" href="/client/showScreen/<?=$odetail->odetail_id?>" style="display:none;">Посмотреть</a>
						</a>
					</td>
					<td><a href="#" onclick="window.open('<?=$odetail->odetail_link?>');return false;"><?=(strlen($odetail->odetail_link)>20?substr($odetail->odetail_link,0,20).'...':$odetail->odetail_link)?></a></td>
					<td><?=$odetail->odetail_status_desc?>
                        <br />
                        <input type="checkbox" value="<?=$odetail->odetail_id?>" name="not_delivered" onclick="setstatusundelivered(this);"  />Не доставлен <img class="tooltip tooltip_rbk" src="/static/images/mini_help.gif">
					</td>
                    <td><?=$odetail->odetail_price?></td>
                    <td><?=$odetail->odetail_pricedelivery?></td>
					<td align="center">
						<a href="javascript:<?=($order->order_status == 'sended' ? "alert('Вы не можете удалить товар. Заказ уже отправлен.')" : "deleteItem(".$odetail->odetail_id.")")?>"><img border="0" src="/static/images/delete.png" title="Удалить"></a>
					</td>
				</tr>
				<?endforeach; endif;?>
                <tr><td colspan="7">&nbsp;</td>
                    <td><?=$order->order_products_cost?></td>
                    <td><?=$order->order_delivery_cost?></td>
					<td align="center">&nbsp;</td></tr>
				<tr class='last-row'>
					<td colspan='10'>
					<? /*if ($order->order_status != 'sended' && $order->order_status != 'payed'){?>
						<div class='float'>	
							<div class='submit'><div><input type="button" value='Изменить заказ' /></div></div>
						</div>
					<?}*/ ?>
					</td>
				</tr>
			</table>
		</div>
	</form>
    <div style="height: 50px;" class="admin-inside">
		<div class="submit">
			<div>
				<input type="button" le="width:125px !important;" sty="" value="Добавить товар" name="add" onclick="lay2()">
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function deleteItem(id) {
			if (confirm("Вы уверены, что хотите удалить товар № " + id + " ?")){
				window.location.href = '<?=$selfurl?>deleteProduct/' + id;
			}
		}
	</script>
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
						<span class="name">Вы:</span>
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
<script>
	function setRel(id){
		$("a[rel*='lightbox_"+id+"']").lightBox();
		var aa = $("a[rel*='lightbox_"+id+"']");
		$(aa[0]).click();
	}
</script>