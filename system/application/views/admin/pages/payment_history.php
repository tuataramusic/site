
<div class='content'>
	<h2>Аккаунт администратора</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
	</ul>

	<h3>История платежей</h3>

	<br />
	<div class="back">
		<a href="javascript:history.back();" class="back"><span>Назад</span></a>
	</div><br />
	
	<form class='admin-inside' action='<?=$selfurl?>searchPayments' method='POST'>
		<b>Поиск платежа:</b> <input type='text' name='svalue' value="<?=isset($postback['svalue'])? $postback['svalue'] : ''?>"></input> по 
		<select name='sfield'>
			<option value='payment' <?=isset($postback['sfield']) && $postback['sfield']=='payment' ? 'selected' : ''?>>Номеру</option>
			<option value='user' <?=isset($postback['sfield']) && $postback['sfield']=='user' ? 'selected' : ''?>>Логину</option>	
		</select>
		<select name='stype'>
			<option value='from' <?=isset($postback['stype']) && $postback['stype']=='from' ? 'selected' : ''?>>Отправителяа</option>
			<option value='to' <?=isset($postback['stype']) && $postback['stype']=='to' ? 'selected' : ''?>>Получателя</option>	
		</select>
		 за
		<select name='sdate'>
			<option value='all' <?=isset($postback['sdate']) && $postback['sdate']=='all' ? 'selected' : ''?>>Весь период</option>
			<option value='day' <?=isset($postback['sdate']) && $postback['sdate']=='day' ? 'selected' : ''?>>День</option>	
			<option value='week' <?=isset($postback['sdate']) && $postback['sdate']=='week' ? 'selected' : ''?>>Неделю</option>
			<option value='month' <?=isset($postback['sdate']) && $postback['sdate']=='month' ? 'selected' : ''?>>Месяц</option>
		</select>
		<?if ($result->e<0):?>
			<em style="color:red;"><?=$result->m;?></em>
		<?endif;?>
		<div class='submit' style="width:60px; float:right;"><div><input type='submit' value='Искать' /></div></div>
	</form>
	<br />

	<?if(isset($from_search) && $from_search):?><a href='<?=$selfurl?>showPaymentHistory'>Все платежи</a><br/><?endif;?>
	
	
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
				<th>№ Клиента / Дата</th>
				<th>Отправитель</th>
				<th>Получатель</th>
				<th>Способ пополнения</th>
				<th>Назначение платежа</th>
				<th>Комментарий</th>
				<th>Сумма перевода</th>
				<th>+ Комиссия</th>
			</tr>
	
			<?if ($Payments):?>
				<?foreach ($Payments as $Payment):?>
				<tr>
					<td><?=date('d-m-Y H:i', strtotime($Payment->payment_time))?></td>
					<td>[<?=$Payment->payment_from?>] <?=$Payment->user_from?></td>
					<td>[<?=$Payment->payment_to?>] <?=$Payment->user_to?></td>
					<td></td>
					<td><?=$Payment->payment_purpose?></td>
					<td><?=$Payment->payment_comment?></td>
					<td><?=$Payment->payment_amount_to?>р</td>
					<td><?=$Payment->payment_amount_tax?>р</td>
				</tr>
				<?endforeach;?>	
			<?else:?>
				<tr>
					<td colspan="8">Платежей нет</td>
				</tr>
			<?endif;?>
		</table>
	</div>
</div>
<?/*
<h3>История платежей</h3>

<form action='<?=$selfurl?>searchPayments' method='POST'>
<b>Поиск платежа:</b> <input type='text' name='svalue'></input> по 
<select name='sfield'>
	<option value='payment_from'>Номеру клиента</option>
	<option value='user_login'>Логину клиента</option>	
</select> за
<select name='sdate'>
	<option value='all'>Все</option>
	<option value='day'>День</option>	
	<option value='week'>Неделю</option>
	<option value='month'>Месяц</option>
</select>
 <input type='submit' name='search' value='Искать'/><br/><br/>
</form>

<?if(isset($from_search) && $from_search):?><a href='<?=$selfurl?>showPaymentHistory'>Все платежи</a><br/><?endif;?>

<?if ($Payments):?>
<table>
	<tr>
		<td>№ Клиента / Дата</td>
		<td>Логин отправителя</td>
		<td>Способ пополнения</td>
		<td>Вид платежа</td>
		<td>Комментарий</td>
		<td>Сумма</td>
	</tr>
	
	<?foreach ($Payments as $Payment):?>
	<tr>
		<td><?=$Payment->payment_from?><br/><?=$Payment->payment_time?></td>
		<td><?=$Payment->user_login?></td>
		<td></td>
		<td><?=$Payment->payment_purpose?></td>
		<td><?=$Payment->payment_comment?></td>
		<td><?=$Payment->payment_amount_from?>р</td>		
	</tr>
	<?endforeach;?>	
	
</table>

<?else:?>
Платежей нет
<?endif;?>

*/?>