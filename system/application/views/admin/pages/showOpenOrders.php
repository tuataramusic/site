	
		<div class='content'>
			<h2>������� ��������������</h2>
			<ul class='admin-buttons'>
				<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
				<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
				<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
			</ul>
			<h3>������ ������� � �������</h3>
			<form class='admin-sorting' id="filterForm" action="<?=$selfurl?>filterOpenOrders" method="POST">
				<div class='sorting'>
					<span class='first-title'>����������� �� ��������:</span>
					<select name="manager_user" class='select first-input'>
						<option value="">�������...</option>
						<?if ($managers) : foreach($managers as $manager) : ?>
						<option value="<?=$manager->manager_user?>" <? if ($manager->manager_user == $filter->manager_user) : ?>selected="selected"<? endif; ?>><?=$manager->user_login?></option>
						<?endforeach; endif;?>
					</select>

					<span>��:</span>
					<select name="period" class='select'>
						<option value="">���</option>
						<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>����</option>
						<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>������</option>
						<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>�����</option>
					</select> 
				</div>
				<div class='sorting'>
					<span class='first-title'>����� ������:</span>
					<div class='text-field first-input'><div><input type='text' maxlength="11" name="search_id" value="<?=$filter->search_id?>" value='������� ����� ������' /></div></div>
					<span>��:</span>
					<select name="id_type" class='select'>
						<option value="">�������...</option>
						<option value="package" <? if ('package' == $filter->id_type) : ?>selected="selected"<? endif; ?>>������ �������</option>
						<option value="client" <? if ('client' == $filter->id_type) : ?>selected="selected"<? endif; ?>>������ ������������</option>
					</select>	
				</div>
			</form>

			
			<form class='admin-inside' id="ordersForm" action="<?=$selfurl?>updateOpenOrdersStatus" method="POST">
				<ul class='tabs'>
					<li><div><a href='<?=$selfurl?>showNewPackages'>�����</a></div></li>
					<li><div><a href='<?=$selfurl?>showPayedPackages'>����������</a></div></li>
					<li><div><a href='<?=$selfurl?>showSentPackages'>������������</a></div></li>
					<li class='active'><div><a href='<?=$selfurl?>showOpenOrders'>������ ������� � �������</a></div></li>
					<li><div><a href='<?=$selfurl?>showClients'>�������</a></div></li>
					<li><div><a href='<?=$selfurl?>showPartners'>��������</a></div></li>
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
						<col width='200' />
						<col width='auto' />
						<col width='80' />
						<col width='120' />
                        <tr>
							<th>����� �����</th>
							<th>�������, ������</th>
							<th>����� �������</th>
							<th>�.�.�., ����� ��������</th>
							<th>���� ��������</th>
							<th>�����������</th>
							<th>������</th>
							<th>���������� / �������</th>
						</tr>
						<?if ($orders) : foreach($orders as $order) : ?>
						<tr>
							<td nowrap>
								<b>� <?=$order->order_id?></b><br /><?=$order->order_date?><br /><?=$order->order_weight?>��<br />
								������:<br /><?=$order->package_day == 0 ? "" : $order->package_day.' '.humanForm((int)$order->package_day, "����", "���", "����")?> <?=$order->package_hour == 0 ? "" : $order->package_hour.' '.humanForm((int)$order->package_hour, "���", "����", "�����")?>
							</td>
							<td><?=$order->order_manager_login?> / <?=$order->order_manager_country?></td>
							<td><b>� <?=$order->order_client?></b></td>
							<td><?=$order->order_address?></td>
							<td>
								<?=$order->order_cost?>$
								<a href="javascript:void(0)" onclick="$('#pre_<?=$order->order_id?>').toggle()">���������</a>
								<pre class="pre-href" id="pre_<?=$order->order_id?>">
									<?= $order->order_delivery_cost ?>$
									<? if ($order->order_products_cost) : ?>
									+
									*<?= $order->order_products_cost ?>$
									<? endif;
									 if ($order->order_comission) : ?>
									+
									**<?= $order->order_comission ?>%
									<? endif; ?>
								</pre>
							</td>
							<td>
								<? if ($order->comment_for_manager || $order->comment_for_client) : ?>
									�������� ����� �����������<br />
								<? endif; ?>
								<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>#comments">����������</a>
							</td>
							<td>
								<select name="order_status<?=$order->order_id?>">
									<option value="proccessing" <? if ($order->order_status == 'proccessing') : ?>selected="selected"<?endif;?>>��������������</option>
									<option value="not_available" <? if ($order->order_status == 'not_available') : ?>selected="selected"<?endif;?>>��� � �������</option>
									<option value="not_available_color" <? if ($order->order_status == 'not_available_color') : ?>selected="selected"<?endif;?>>��� ������� �����</option>
									<option value="not_available_size" <? if ($order->order_status == 'not_available_size') : ?>selected="selected"<?endif;?>>��� ������� �������</option>
									<option value="not_available_count" <? if ($order->order_status == 'not_available_count') : ?>selected="selected"<?endif;?>>��� ���������� ���-��</option>
									<option value="not_payed" <? if ($order->order_status == 'not_payed') : ?>selected="selected"<?endif;?>>�� �������</option>
									<option value="payed" <? if ($order->order_status == 'payed') : ?>selected="selected"<?endif;?>>�������</option>
									<option value="sended">���������</option>
								</select>
							</td>
							<td align="center">
								<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">����������</a><br/>
								<hr />
								<a href="javascript:deleteItem('<?=$order->order_id?>');"><img title="�������" border="0" src="/static/images/delete.png"></a>
								<br/>
							</td>
						</tr>
						<?endforeach; endif;?>
						<tr class='last-row'>
							<td colspan='9'>
								<div class='float'>	
									<div class='submit'><div><input type='submit' value='���������' /></div></div>
								</div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</form>

			<?php if (isset($pager)) echo $pager ?>
		</div>
<?php /*
������ "������ � �������"
<br /><br />
<form id="filterForm" action="<?=$selfurl?>filterOpenOrders" method="POST">
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
	
<form id="ordersForm" action="<?=$selfurl?>updateOpenOrdersStatus" method="POST">
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
				<td><?=$order->order_cost?>�
					<hr />
					<?= $order->order_delivery_cost ?>�
					<? if ($order->order_products_cost) : ?>
					<br />+<br />
					*<?= $order->order_products_cost ?>�
					<? endif; if ($order->order_comission) : ?>
					<br />+<br />
					**<?= $order->order_comission ?>%
					<? endif; ?></td>
				<td><? if ($order->comment_for_manager || $order->comment_for_client) : ?>
					�������� ����� �����������<br />
				<? endif; ?>
				<a href="<?=$selfurl?>showOrderComments/<?=$order->order_id?>">����������</a>
				</td>
				<td><select name="order_status<?=$order->order_id?>">
						<option value="proccessing" <? if ($order->order_status == 'proccessing') : ?>selected="selected"<?endif;?>>��������������</option>
						<option value="not_available" <? if ($order->order_status == 'not_available') : ?>selected="selected"<?endif;?>>��� � �������</option>
						<option value="not_payed" <? if ($order->order_status == 'not_payed') : ?>selected="selected"<?endif;?>>�� �������</option>
						<option value="payed" <? if ($order->order_status == 'payed') : ?>selected="selected"<?endif;?>>�������</option>
						<option value="sended">���������</option>
					</select></td>
				<td>
					<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">����������</a> /
					<a href="javascript:deleteItem('<?=$order->order_id?>');">�������</a>
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

*/?>