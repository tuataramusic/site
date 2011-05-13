<div class='content'>

	<h2>������� ��������������</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
	</ul>

	<h3>������ �� �����</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>�����</span></a>
	</div>

	<form class='card' id="pricelistForm" action='<?=$selfurl?>savePricelist/<?=$filter->pricelist_country_from?>/<?=$filter->pricelist_country_to?>/<?=$filter->pricelist_delivery?>' method='POST'>
		<table>
			<tr>
				<th>����� ������:</th>
				<td>
					<div class='text-field name-field'><div><input type='text' name='svalue' /></div></div>
				</td>
				<td>
					<div class='field number-field'>
						<span>��:</span> 
						<select class="select" name='sfield'>
							<option value='order2out_id'>������ ������</option>
							<option value='user_login'>������ �������</option>
							<option value='order2out_user'>������ �������</option>
						</select>
					</div
				</td>
				<td>
					<div class='text-field price-field'><div><input type='submit' name='search' value='������'/></div></div>
				</td>
			</tr>
		</table>
	</form>
	
	<form class='admin-inside' action='<?=$selfurl?>saveOrders2out'>
		<ul class='tabs'>
		<?if ($status == 'processing'):?>
			<li class='active'><div><a href='javascript:void(0);'>�����</a></div></li>
			<li><div><a href='<?=$selfurl?>showOrderToOut/payed'>�����������</a></div></li>
		<?else:?>
			<li><div><a href='<?=$selfurl?>showOrderToOut'>�����</a></div></li>
			<?if ($status == 'none'):?>
				<li class='active'><div><a href='<?=$selfurl?>showOrderToOut/payed'>�����������</a>
			<?else:?>
				<li class='active'><div><a href='javascript:void(0);'>�����������</a></div></li>
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
						<th>����� ������</th>
						<th>������</th>
						<th>������ ������</th>
						<th>�����</th>
						<th>������</th>
						<th>�����������</th>
						<?if ($status == 'processing' || $status == 'none'):?><th class='last-child'></th><?endif;?>
					</tr>
					
					<?foreach ($Orders as $Order):?>
					<tr>
						<td><b>� <?=$Order->order2out_id?></b><br/><?=$Order->order2out_time?></td>
						<td>�����: <?=$Order->user_login?><br/>�����: <?=$Order->order2out_user?></td>
						<td></td>
						<td><?=$Order->order2out_ammount?> ���.</td>
						<td>
							<select name="status_<?=$Order->order2out_id?>">
								<?foreach ($statuses as $key=>$val):?>
								<option value='<?=$key?>' <?if ($key==$Order->order2out_status):?>selected="selected"<?endif;?>><?=$val?></option>
								<?endforeach;?>	
							</select>
						</td>
						<td><? if ($Order->comment_for_admin) : ?>
							�������� ����� �����������<br />
							<? endif; ?><a href="<?=$selfurl?>showO2oComments/<?=$Order->order2out_id?>">����������</a>
						</td>
						<td><?if ($Order->order2out_status == 'processing'):?><a class="delete" href='<?=$selfurl?>deleteOrder2out/<?=$Order->order2out_id?>'><img border="0" src="/static/images/delete.png" title="�������"></a><?endif;?></td>
					</tr>
					<?endforeach;?>	

					<tr class='last-row'>
						<td colspan='9'>
							<div class='float'>	
								<div class='submit'><div><input type='submit' name="save" value='���������' /></div></div>
							</div>
						</td>
					</tr>
				<?else:?>
					<tr>
						<td  colspan='7'>������ ���</td>
					</tr>
				<?endif;?>
			</table>
		</div>
	</form>
	<?php if (isset($pager)) echo $pager ?>
</div>


<?/*
<b>������ �� �����</b><br/>

<form action='<?=$selfurl?>searchOrders2out' method='POST'>
<b>����� ������:</b> <input type='text' name='svalue'></input> �� 
<select name='sfield'>
	<option value='order2out_id'>������ ������</option>
	<option value='user_login'>������ �������</option>
	<option value='order2out_user'>������ �������</option>
</select> <input type='submit' name='search' value='Ok'/><br/><br/>
</form>


<?if ($status == 'processing'):?>
	����� | <a href='<?=$selfurl?>showOrderToOut/payed'>�����������</a>
<?else:?>
	<a href='<?=$selfurl?>showOrderToOut'>�����</a> | 
	<?if ($status == 'none'):?>
		<a href='<?=$selfurl?>showOrderToOut/payed'>�����������</a>
	<?else:?>
	�����������
	<?endif;?>
<?endif;?>
<br/>



<?if ($Orders):?>
<form action='<?=$selfurl?>saveOrders2out' method='POST'>
<table>
	<tr>
		<td>� ������</td>
		<td>������</td>
		<td>������ ������</td>
		<td>�����</td>
		<td>������</td>
		<td>�����������</td>
		<?if ($status == 'processing' || $status == 'none'):?><td>�������</td><?endif;?>
	</tr>
	
	<?foreach ($Orders as $Order):?>
	<tr>
		<td><?=$Order->order2out_id?><br/><?=$Order->order2out_time?></td>
		<td>�����: <?=$Order->user_login?><br/>�����: <?=$Order->order2out_user?></td>
		<td></td>
		<td><?=$Order->order2out_ammount?> ���.</td>
		<td>
			<select name="status_<?=$Order->order2out_id?>">
				<?foreach ($statuses as $key=>$val):?>
				<option value='<?=$key?>' <?if ($key==$Order->order2out_status):?>selected="selected"<?endif;?>><?=$val?></option>
				<?endforeach;?>	
			</select>
		</td>
		<td><? if ($Order->comment_for_admin) : ?>
			�������� ����� �����������<br />
			<? endif; ?><a href="<?=$selfurl?>showO2oComments/<?=$Order->order2out_id?>">����������</a>
		</td>
		<td><?if ($Order->order2out_status == 'processing'):?><a href='<?=$selfurl?>deleteOrder2out/<?=$Order->order2out_id?>'>�������</a><?endif;?></td>
	</tr>
	<?endforeach;?>	
	
</table>
<br/>
<div style='float: right; width: 60%;'><input type='submit' value='���������' name='save'/></div>
</form>

<br/><br/>
<?else:?>
������ ���
<?endif;?>
*/?>