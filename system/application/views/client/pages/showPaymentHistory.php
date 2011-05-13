<div class='content'>
	<h2>История платежей</h2>
<!-- 	
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
 -->	
	
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
<!--			<col width='auto' />-->
<!--			<col width='auto' />
			<col width='auto' />-->
			<tr>
				<th>№ Клиента / Дата</th>
<!--				<th>Отправитель</th>
				<th>Получатель</th>-->
				<th>Способ пополнения</th>
				<th>Назначение платежа</th>
<!--				<th>Комментарий</th>-->
				<th>Сумма перевода</th>
				<th>+ Комиссия</th>
			</tr>
	
			<?if ($Payments):?>
				<?foreach ($Payments as $Payment):?>
				<tr>
					<td><?=date('d-m-Y H:i', strtotime($Payment->payment_time))?></td>
<!--					<td>[<?=$Payment->payment_from?>] <?=$Payment->user_from?></td>
					<td>[<?=$Payment->payment_to?>] <?=$Payment->user_to?></td>-->
					<td></td>
					<td><?=$Payment->payment_purpose?></td>
<!--					<td><?=$Payment->payment_comment?></td>-->
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