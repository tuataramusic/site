<div class='content'>
	<h3>���������� �����</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>�����</span></a>
	</div><br />
	
</div>



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

<a href='javascript:void(null);' onclick='$("#order2out").show();$.get("<?=$selfurl?>createOrder2out", function(data){$("#order_id").text(data);$("#order2out_id").val(data);});'>������ �� ����� �����</a>
<div id='order2out' style='display:none;'>
����� ������: <b id='order_id'></b><br/>
<form action='<?=$selfurl?>order2out' method='POST'>
<input type='hidden' name='order2out_id' id='order2out_id'/>
����� ������: <input type='text' name='ammount'/><br/>
<input type='submit' name='send' value='��������� ������'/>
</form>
</div>