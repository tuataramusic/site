<div class='content'>

	<h2>Аккаунт администратора</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
	</ul>

	<h3>Заявки на вывод</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div>

	<form class='card' id="pricelistForm" action='<?=$selfurl?>savePricelist/<?=$filter->pricelist_country_from?>/<?=$filter->pricelist_country_to?>/<?=$filter->pricelist_delivery?>' method='POST'>
		<table>
			<tr>
				<th>Поиск заявки:</th>
				<td>
					<div class='text-field name-field'><div><input type='text' name='svalue' /></div></div>
				</td>
				<td>
					<div class='field number-field'>
						<span>по:</span> 
						<select class="select" name='sfield'>
							<option value='order2out_id'>Номеру заявки</option>
							<option value='user_login'>Логину клиента</option>
							<option value='order2out_user'>Номеру клиента</option>
						</select>
					</div
				</td>
				<td>
					<div class='text-field price-field'><div><input type='submit' name='search' value='Искать'/></div></div>
				</td>
			</tr>
		</table>
	</form>
	
	<form class='admin-inside' action='<?=$selfurl?>saveOrders2out'>
		<ul class='tabs'>
		<?if ($status == 'processing'):?>
			<li class='active'><div><a href='javascript:void(0);'>Новые</a></div></li>
			<li><div><a href='<?=$selfurl?>showOrderToOut/payed'>Выплаченные</a></div></li>
		<?else:?>
			<li><div><a href='<?=$selfurl?>showOrderToOut'>Новые</a></div></li>
			<?if ($status == 'none'):?>
				<li class='active'><div><a href='<?=$selfurl?>showOrderToOut/payed'>Выплаченные</a>
			<?else:?>
				<li class='active'><div><a href='javascript:void(0);'>Выплаченные</a></div></li>
			<?endif;?>
		<?endif;?>					
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
				<col width='10' />
				
				<?if ($Orders):?>
				
					<tr>
						<th>Номер заявки</th>
						<th>Клиент</th>
						<th>Способ вывода</th>
						<th>Сумма</th>
						<th>Статус</th>
						<th>Комментарий</th>
						<?if ($status == 'processing' || $status == 'none'):?><th class='last-child'></th><?endif;?>
					</tr>
					
					<?foreach ($Orders as $Order):?>
					<tr>
						<td><b>№ <?=$Order->order2out_id?></b><br/><?=$Order->order2out_time?></td>
						<td>Логин: <?=$Order->user_login?><br/>Номер: <?=$Order->order2out_user?></td>
						<td></td>
						<td><?=$Order->order2out_ammount?> руб.</td>
						<td>
							<select name="status_<?=$Order->order2out_id?>">
								<?foreach ($statuses as $key=>$val):?>
								<option value='<?=$key?>' <?if ($key==$Order->order2out_status):?>selected="selected"<?endif;?>><?=$val?></option>
								<?endforeach;?>	
							</select>
						</td>
						<td><? if ($Order->comment_for_admin) : ?>
							Добавлен новый комментарий<br />
							<? endif; ?><a href="<?=$selfurl?>showO2oComments/<?=$Order->order2out_id?>">Посмотреть</a>
						</td>
						<td><?if ($Order->order2out_status == 'processing'):?><a class="delete" href='<?=$selfurl?>deleteOrder2out/<?=$Order->order2out_id?>'><img border="0" src="/static/images/delete.png" title="Удалить"></a><?endif;?></td>
					</tr>
					<?endforeach;?>	

					<tr class='last-row'>
						<td colspan='9'>
							<div class='float'>	
								<div class='submit'><div><input type='submit' name="save" value='Сохранить' /></div></div>
							</div>
						</td>
					</tr>
				<?else:?>
					<tr>
						<td  colspan='7'>Заявок нет</td>
					</tr>
				<?endif;?>
			</table>
		</div>
	</form>
	<?php if (isset($pager)) echo $pager ?>
</div>


<?/*
<b>Заявки на вывод</b><br/>

<form action='<?=$selfurl?>searchOrders2out' method='POST'>
<b>Поиск заявки:</b> <input type='text' name='svalue'></input> по 
<select name='sfield'>
	<option value='order2out_id'>Номеру заявки</option>
	<option value='user_login'>Логину клиента</option>
	<option value='order2out_user'>Номеру клиента</option>
</select> <input type='submit' name='search' value='Ok'/><br/><br/>
</form>


<?if ($status == 'processing'):?>
	Новые | <a href='<?=$selfurl?>showOrderToOut/payed'>Выплаченные</a>
<?else:?>
	<a href='<?=$selfurl?>showOrderToOut'>Новые</a> | 
	<?if ($status == 'none'):?>
		<a href='<?=$selfurl?>showOrderToOut/payed'>Выплаченные</a>
	<?else:?>
	Выплаченные
	<?endif;?>
<?endif;?>
<br/>



<?if ($Orders):?>
<form action='<?=$selfurl?>saveOrders2out' method='POST'>
<table>
	<tr>
		<td>№ заявки</td>
		<td>Клиент</td>
		<td>Способ вывода</td>
		<td>Сумма</td>
		<td>Статус</td>
		<td>Комментарий</td>
		<?if ($status == 'processing' || $status == 'none'):?><td>Удалить</td><?endif;?>
	</tr>
	
	<?foreach ($Orders as $Order):?>
	<tr>
		<td><?=$Order->order2out_id?><br/><?=$Order->order2out_time?></td>
		<td>Логин: <?=$Order->user_login?><br/>Номер: <?=$Order->order2out_user?></td>
		<td></td>
		<td><?=$Order->order2out_ammount?> руб.</td>
		<td>
			<select name="status_<?=$Order->order2out_id?>">
				<?foreach ($statuses as $key=>$val):?>
				<option value='<?=$key?>' <?if ($key==$Order->order2out_status):?>selected="selected"<?endif;?>><?=$val?></option>
				<?endforeach;?>	
			</select>
		</td>
		<td><? if ($Order->comment_for_admin) : ?>
			Добавлен новый комментарий<br />
			<? endif; ?><a href="<?=$selfurl?>showO2oComments/<?=$Order->order2out_id?>">Посмотреть</a>
		</td>
		<td><?if ($Order->order2out_status == 'processing'):?><a href='<?=$selfurl?>deleteOrder2out/<?=$Order->order2out_id?>'>Удалить</a><?endif;?></td>
	</tr>
	<?endforeach;?>	
	
</table>
<br/>
<div style='float: right; width: 60%;'><input type='submit' value='Сохранить' name='save'/></div>
</form>

<br/><br/>
<?else:?>
Заявок нет
<?endif;?>
*/?>