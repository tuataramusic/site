<div class='content'>
	<h2>�������� ������</h2>
	<form class='admin-inside' action='#'>
		
		<ul class='tabs'>
			<li><div><a href='<?=$selfurl?>showOpenPackages'>��������� ��������</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>������������</a></div></li>
			<li><div><a href='<?=$selfurl?>showOpenOrders'>������ ������� � �������</a></div></li>
			<li class='active'><div><a href="<?=$selfurl?>showSentOrders">�������� ������</a></div></li>
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
				<col width='auto' />
				<col width='auto' />
				<tr>
					<th>� ������</th>
					<th>�������� ��������</th>
					<th>������ / ���� / ���</th>
					<th>�����������</th>
					<th>�����<br />���������<br />� �������<br />���������</th>
					<th>���������<br />���������<br />�������������<br />�������� *</th>
					<th>������</th>
					<th>����������</th>
				</tr>

				<?if ($orders) : foreach($orders as $order) : ?>
				<tr>
					<td><b>� <?=$order->order_id?></b></td>
					<td><?=$order->order_shop_name?></td>
					<td><?=$order->order_manager_country?> <?=$order->order_date?> <?=Func::round2half($order->order_weight)?>�� <?=Func::round2half($order->order_weight) != $order->order_weight ? '('.$order->order_weight.'��)' : '';?></td>
					<td><? if ($order->comment_for_client) : ?>
						�������� ����� �����������<br />
					<? endif; ?><a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>#comments">����������</a>
					</td>
					<td><?=$order->order_cost?>$</td>
					<td></td>
					<td>���������</td>
					<td>
						<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">����������</a>
					</td>
				</tr>
				<?endforeach; endif;?>
				<tr class='last-row'>
					<td colspan='9'>
						<div id="tableComments" style="text-align:left;float:left;">
							* ������ ��������� �������������� �������������� � ����� �� ��������� � ��������<br />
							���������� ��������. ������ ��������� ������������� �������� �� ������ ������<br />
							� ����� ������ ��������, � ������� "�������, ��������� ��������" ����� ����,<br />
							��� �� ������� �������
						</div>
						<div class='float'>	
							<div class='submit'><div></div></div>
						</div>
					</td>
					<td>
					</td>
				</tr>
			</table>
		</div>
	</form>

	<?php if (isset($pager)) echo $pager ?>
</div>

<script type="text/javascript">
	function deleteItem(id) {
		if (confirm("�� �������, ��� ������ ������� ����� �" + id + "?")){
			window.location.href = '<?=$selfurl?>deleteOrder/' + id;
		}
	}

	function payItem(id) {
		if (confirm("�������� ����� �" + id + "?")){
			window.location.href = '<?=$selfurl?>payOrder/' + id;
		}
	}
</script>