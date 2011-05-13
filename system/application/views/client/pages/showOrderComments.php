<div class='content'>
	<h2>Комментарии к заказу №<?=$order->order_id?></h2>
	<form class='partner-inside-1' action='#'>
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<th>Партнер (№)</th>
					<th>Общая цена заказа с учетом местной доставки</th>
					<th>Статус</th>
				</tr>
				<tr>
					<td>
						#<?=$Managers->manager_user.' '.$Managers->manager_name .' '.$Managers->manager_name?>
					</td>
					<td>
						Общая стомость заказанных товаров: <?=$order->order_cost?> р.<br />
						Цена доставки: <?=$order->order_delivery_cost?> р.<br />
						Общий вес посылки: <?=$order->order_weight?> кг
					</td>
					<td> <? if ($order->order_status == 'not_available'):?>
							Нет в наличии
						<?elseif ($order->order_status == 'not_available_color'):?>
							Нет данного цвета
						<?elseif ($order->order_status == 'not_available_size'):?>
							Нет данного размера
						<?elseif ($order->order_status == 'not_available_count'):?>
							Нет указанного кол-ва
						<?elseif ($order->order_status == 'payed'):?>
							Оплачено
						<?elseif ($order->order_status == 'not_payed'):?>
							Не оплачено
						<?elseif ($order->order_status == 'sended' || $order->order_status == 'sent'):?>
							Отправлена
						<?elseif ($order->order_status == 'proccessing'):?>
							Обрабатывается
						<?elseif ($order->order_status == 'deleted'):?>
							Удалена
						<?endif;?>
					</td>
				</tr>
				
			</table>
		</div>
	</form>
	
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
					<?else:?>
						<span class="name">Партнер:</span>
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
<a href='javascript:history.back();'>Назад</a>
<center>
<b>Посылка №<?=$order->order_id?></b><br/>
<table>
	<tr><td style="color: #aaa;">Партнер №</td><td><?=$order->order_manager?></td></tr>
	<tr><td style="color: #aaa;">Вес</td><td><?=$order->order_weight?>кг</td></tr>
	<tr><td style="color: #aaa;">Стоимость</td><td><?=$order->order_cost?>р</td></tr>
</table>
</center>
<br/>Комментарии<br/><br/>
<?if (!$comments):?>
Пока нет комментариев<br/>
<?else:?>
	<? foreach ($comments as $comment):?>
	<i><b><?if ($comment->ocomment_user == $order->order_client):?>Вы:<?else:?>Партнер:<?endif;?></b>&nbsp;<?=$comment->ocomment_comment?></i><br/><br/>
	<? endforeach; ?>
<?endif;?>

<br/><b>Добавить комментарий</b><br/>
<form action='<?=$selfurl?>addOrderComment/<?=$order->order_id?>' method='POST'>
<textarea name='comment' cols="40" rows="5"></textarea><br/>
<input type="submit" name="add" value="Добавить"/>
</form>
*/?>