�������� ������
<br /><br />
<form id="filterForm" action="<?=$selfurl?>filterSentOrders" method="POST">
	<div id="orderFilter" align="center">
		������������� �� �������� <select name="manager_user">
			<option value="">�������...</option>
			<?if ($managers) : foreach($managers as $manager) : ?>
			<option value="<?=$manager->manager_user?>" <? if ($manager->manager_user == $filter->manager_user) : ?>selected="selected"<? endif; ?>><?=$manager->user_login?></option>
			<?endforeach; endif;?></select> �� <select name="period">
			<option value="">���</option>
			<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>����</option>
			<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>������</option>
			<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>�����</option>
		</select> ����� ������ <input type="text" maxlength="11" name="search_id" value="<?=$filter->search_id?>"/> �� <select name="id_type">
			<option value="">�������...</option>
			<option value="order" <? if ('order' == $filter->id_type) : ?>selected="selected"<? endif; ?>>������ ������</option>
			<option value="client" <? if ('client' == $filter->id_type) : ?>selected="selected"<? endif; ?>>������ ������������</option>
		</select>
	</div>
</form>
	
<br />
	
<form id="ordersForm" action="<?=$selfurl?>updateSentOrdersStatus" method="POST">
	<div id="Deliveries" align="center">
		<table>
			<tr>
				<th>������� / ������</th>
				<th>� �������</th>
				<th>� ������</th>
				<th>��� / ����� ��������</th>
				<th>���� ��������</th>
				<th>�����������</th>
				<th>������</th>
				<th>���������� / �������</th>
			</tr>
			<?if ($orders) : foreach($orders as $order) : ?>
			<tr>
				<td><?=$order->order_manager_login?> / <?=$order->order_manager_country?></td>
				<td><?=$order->order_client?></td>
				<td><?=$order->order_id?> <?=$order->order_date?> <?=$order->order_weight?>��<br />
					������ <?=$order->order_age ?> �����</td>
				<td><?=$order->order_address?></td>
				<td><?=$order->order_cost?>�</td>
				<td><? if ($order->comment_for_manager || $order->comment_for_client) : ?>
					�������� ����� �����������<br />
				<? endif; ?>
				<a href="<?=$selfurl?>showOrderComments/<?=$order->order_id?>">����������</a>
				</td>
				<td><select name="order_status<?=$order->order_id?>">
						<option value="proccessing">��������������</option>
						<option value="not_available">��� � �������</option>
						<option value="not_payed">�� �������</option>
						<option value="payed">�������</option>
						<option value="sended" selected="selected">���������</option>
					</select></td>
				<td>
					<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">����������</a> /
					<a href="javascript:deleteItem(<?=$order->order_id?>);">�������</a>
				</td>
			</tr>
			<?endforeach; endif;?>
		</table>
	</div>

	<input type="submit" value="���������"/>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});
		
		$('#filterForm input:text').keypress(function(event){validate_number(event);});
	})
	
	function deleteItem(id){
		if (confirm("�� �������, ��� ������ ������� ����� �" + id + "?")){
			window.location.href = '<?=$selfurl?>deleteOrder/' + id;
		}
	}
	
	function validate_number(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		var regex = /[0-9]|\./;
		if( !regex.test(key) ) {
			theEvent.returnValue = false;
			theEvent.preventDefault();
		}
	}
</script>