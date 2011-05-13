
<div class='content'>
	<h2>������� ��������������</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
	</ul>

	<h3>������� ��������</h3>

	<br />
	<div class="back">
		<a href="javascript:history.back();" class="back"><span>�����</span></a>
	</div><br />
	
	<form class='admin-inside' action='<?=$selfurl?>searchPayments' method='POST'>
		<b>����� �������:</b> <input type='text' name='svalue' value="<?=isset($postback['svalue'])? $postback['svalue'] : ''?>"></input> �� 
		<select name='sfield'>
			<option value='payment' <?=isset($postback['sfield']) && $postback['sfield']=='payment' ? 'selected' : ''?>>������</option>
			<option value='user' <?=isset($postback['sfield']) && $postback['sfield']=='user' ? 'selected' : ''?>>������</option>	
		</select>
		<select name='stype'>
			<option value='from' <?=isset($postback['stype']) && $postback['stype']=='from' ? 'selected' : ''?>>������������</option>
			<option value='to' <?=isset($postback['stype']) && $postback['stype']=='to' ? 'selected' : ''?>>����������</option>	
		</select>
		 ��
		<select name='sdate'>
			<option value='all' <?=isset($postback['sdate']) && $postback['sdate']=='all' ? 'selected' : ''?>>���� ������</option>
			<option value='day' <?=isset($postback['sdate']) && $postback['sdate']=='day' ? 'selected' : ''?>>����</option>	
			<option value='week' <?=isset($postback['sdate']) && $postback['sdate']=='week' ? 'selected' : ''?>>������</option>
			<option value='month' <?=isset($postback['sdate']) && $postback['sdate']=='month' ? 'selected' : ''?>>�����</option>
		</select>
		<?if ($result->e<0):?>
			<em style="color:red;"><?=$result->m;?></em>
		<?endif;?>
		<div class='submit' style="width:60px; float:right;"><div><input type='submit' value='������' /></div></div>
	</form>
	<br />

	<?if(isset($from_search) && $from_search):?><a href='<?=$selfurl?>showPaymentHistory'>��� �������</a><br/><?endif;?>
	
	
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
				<th>� ������� / ����</th>
				<th>�����������</th>
				<th>����������</th>
				<th>������ ����������</th>
				<th>���������� �������</th>
				<th>�����������</th>
				<th>����� ��������</th>
				<th>+ ��������</th>
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
					<td><?=$Payment->payment_amount_to?>�</td>
					<td><?=$Payment->payment_amount_tax?>�</td>
				</tr>
				<?endforeach;?>	
			<?else:?>
				<tr>
					<td colspan="8">�������� ���</td>
				</tr>
			<?endif;?>
		</table>
	</div>
</div>
<?/*
<h3>������� ��������</h3>

<form action='<?=$selfurl?>searchPayments' method='POST'>
<b>����� �������:</b> <input type='text' name='svalue'></input> �� 
<select name='sfield'>
	<option value='payment_from'>������ �������</option>
	<option value='user_login'>������ �������</option>	
</select> ��
<select name='sdate'>
	<option value='all'>���</option>
	<option value='day'>����</option>	
	<option value='week'>������</option>
	<option value='month'>�����</option>
</select>
 <input type='submit' name='search' value='������'/><br/><br/>
</form>

<?if(isset($from_search) && $from_search):?><a href='<?=$selfurl?>showPaymentHistory'>��� �������</a><br/><?endif;?>

<?if ($Payments):?>
<table>
	<tr>
		<td>� ������� / ����</td>
		<td>����� �����������</td>
		<td>������ ����������</td>
		<td>��� �������</td>
		<td>�����������</td>
		<td>�����</td>
	</tr>
	
	<?foreach ($Payments as $Payment):?>
	<tr>
		<td><?=$Payment->payment_from?><br/><?=$Payment->payment_time?></td>
		<td><?=$Payment->user_login?></td>
		<td></td>
		<td><?=$Payment->payment_purpose?></td>
		<td><?=$Payment->payment_comment?></td>
		<td><?=$Payment->payment_amount_from?>�</td>		
	</tr>
	<?endforeach;?>	
	
</table>

<?else:?>
�������� ���
<?endif;?>

*/?>