<div class='content'>
	<h2>����� �����</h2>
	<form class='admin-inside' action="<?=$selfurl?>order2out" method='POST' style="width:300px;">
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<td>����� ������:</td>
					<td><input class="input" size="30" type='text' name='ammount'/></td>
				</tr>
				<tr class='last-row'>
					<td colspan='9'>
						<div class='float'>	
							<div class='submit'><div>
								<input type='submit' name='send' value='��������� ������' style="width:115px;"/>
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
	<h3>���� ������ �� �����</h3>
	
	<form class='admin-inside' action="<?=$selfurl?>order2out" method='POST'>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<th>� ������</th>
					<th>������ ������</th>
					<th>�����</th>
					<th>������</th>
					<th>�����������</th>
					<th>�������</th>
				</tr>
				<?foreach ($Orders as $Order):?>
				<tr>
					<td>#<?=$Order->order2out_id?>&nbsp;&nbsp;(<?=date('H:i d-m-Y',strtotime($Order->order2out_time))?>)</td>
					<td></td>
					<td><?=$Order->order2out_ammount?> ���.</td>
					<td><?=$statuses[$Order->order2out_status]?></td>
					<td><? if ($Order->comment_for_client) : ?>
							�������� ����� �����������<br />
						<? endif; ?><a href="<?=$selfurl?>showO2oComments/<?=$Order->order2out_id?>">����������</a></td>
					<td><?if ($Order->order2out_status == 'processing'):?><a href='<?=$selfurl?>deleteOrder2out/<?=$Order->order2out_id?>'>�������</a><?endif;?></td>
				</tr>
				<?endforeach;?>
			</table>
		</div>
	</form>
	
	
</div>

<?/*

���������� �����
<br/><br/>
<?if ($Orders):?>
���� ������ �� �����
<br/><br/>
<div id="Requests" align="center">
	<table>
		<tr>
			<td>� ������</td>
			<td>������ ������</td>
			<td>�����</td>
			<td>������</td>
			<td>�����������</td>
			<td>�������</td>
		</tr>
		
		<?foreach ($Orders as $Order):?>
		<tr>
			<td><?=$Order->order2out_id?><br/><?=$Order->order2out_time?></td>
			<td></td>
			<td><?=$Order->order2out_ammount?> ���.</td>
			<td><?=$statuses[$Order->order2out_status]?></td>
			<td><? if ($Order->comment_for_client) : ?>
					�������� ����� �����������<br />
				<? endif; ?><a href="<?=$selfurl?>showO2oComments/<?=$Order->order2out_id?>">����������</a></td>
			<td><?if ($Order->order2out_status == 'processing'):?><a href='<?=$selfurl?>deleteOrder2out/<?=$Order->order2out_id?>'>�������</a><?endif;?></td>
		</tr>
		<?endforeach;?>	
	</table>
</div>
<br/>
<?endif;?>

<?/*
<a href='javascript:void(null);' onclick='$("#order2out").show();$.get("<?=$selfurl?>createOrder2out", function(data){$("#order_id").text(data);$("#order2out_id").val(data);});'>������ �� ����� �����</a>
<div id='order2out' style='display:none;'>
����� ������: <b id='order_id'></b><br/>
<form action='<?=$selfurl?>order2out' method='POST'>
<input type='hidden' name='order2out_id' id='order2out_id'/>
����� ������: <input type='text' name='ammount'/><br/>
<input type='submit' name='send' value='��������� ������'/>
</form>
</div>
*/?>