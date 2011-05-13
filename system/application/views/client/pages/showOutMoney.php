<div class='content'>
	<h2>Вывод денег</h2>
	<form class='admin-inside' action="<?=$selfurl?>order2out" method='POST' style="width:300px;">
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<td>Сумма вывода:</td>
					<td><input class="input" size="30" type='text' name='ammount'/></td>
				</tr>
				<tr class='last-row'>
					<td colspan='9'>
						<div class='float'>	
							<div class='submit'><div>
								<input type='submit' name='send' value='Отправить заявку' style="width:115px;"/>
							</div></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</form>
	
	<br />
	<br />
	<hr />
	<h3>Ваши заявки на вывод</h3>
	
	<form class='admin-inside' action="<?=$selfurl?>order2out" method='POST'>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<th>№ заявки</th>
					<th>Способ вывода</th>
					<th>Сумма</th>
					<th>Статус</th>
					<th>Комментарий</th>
					<th>Удалить</th>
				</tr>
				<?foreach ($Orders as $Order):?>
				<tr>
					<td>#<?=$Order->order2out_id?>&nbsp;&nbsp;(<?=date('H:i d-m-Y',strtotime($Order->order2out_time))?>)</td>
					<td></td>
					<td><?=$Order->order2out_ammount?> руб.</td>
					<td><?=$statuses[$Order->order2out_status]?></td>
					<td><? if ($Order->comment_for_client) : ?>
							Добавлен новый комментарий<br />
						<? endif; ?><a href="<?=$selfurl?>showO2oComments/<?=$Order->order2out_id?>">Посмотреть</a></td>
					<td><?if ($Order->order2out_status == 'processing'):?><a href='<?=$selfurl?>deleteOrder2out/<?=$Order->order2out_id?>'>удалить</a><?endif;?></td>
				</tr>
				<?endforeach;?>
			</table>
		</div>
	</form>
	
	
</div>

<?/*

Пополнение счета
<br/><br/>
<?if ($Orders):?>
Ваши заявки на вывод
<br/><br/>
<div id="Requests" align="center">
	<table>
		<tr>
			<td>№ заявки</td>
			<td>Способ вывода</td>
			<td>Сумма</td>
			<td>Статус</td>
			<td>Комментарий</td>
			<td>Удалить</td>
		</tr>
		
		<?foreach ($Orders as $Order):?>
		<tr>
			<td><?=$Order->order2out_id?><br/><?=$Order->order2out_time?></td>
			<td></td>
			<td><?=$Order->order2out_ammount?> руб.</td>
			<td><?=$statuses[$Order->order2out_status]?></td>
			<td><? if ($Order->comment_for_client) : ?>
					Добавлен новый комментарий<br />
				<? endif; ?><a href="<?=$selfurl?>showO2oComments/<?=$Order->order2out_id?>">Посмотреть</a></td>
			<td><?if ($Order->order2out_status == 'processing'):?><a href='<?=$selfurl?>deleteOrder2out/<?=$Order->order2out_id?>'>удалить</a><?endif;?></td>
		</tr>
		<?endforeach;?>	
	</table>
</div>
<br/>
<?endif;?>

<?/*
<a href='javascript:void(null);' onclick='$("#order2out").show();$.get("<?=$selfurl?>createOrder2out", function(data){$("#order_id").text(data);$("#order2out_id").val(data);});'>Заявка на вывод денег</a>
<div id='order2out' style='display:none;'>
Номер заявки: <b id='order_id'></b><br/>
<form action='<?=$selfurl?>order2out' method='POST'>
<input type='hidden' name='order2out_id' id='order2out_id'/>
Сумма вывода: <input type='text' name='ammount'/><br/>
<input type='submit' name='send' value='Отправить заявку'/>
</form>
</div>
*/?>