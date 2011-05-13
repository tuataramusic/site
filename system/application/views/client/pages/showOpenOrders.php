
<div class='content'>
	<h2>������ "������ � �������"</h2>

	<?if(isset($result->m) && $result->m):?><em class="order_result"><?=$result->m?></em><br/><?endif;?>
	
	<?View::show($viewpath.'elements/div_float_help');?>
	
	<?View::show($viewpath.'elements/div_float_manual');?>
	
<!--	<div align="left"><a href="javascript:lay();">������ � ������������ ������</a></div>-->
	<div class="admin-inside" style="height:50px">
		<div class="submit">
			<div>
				<input type="button" onclick="lay2()" name="add" value="�������� �����" sty le="width:125px !important;">
			</div>
		</div>
	</div>
	
	<form class='admin-inside' action='#'>
		
		<ul class='tabs'>
			<li><div><a href='<?=$selfurl?>showOpenPackages'>��������� ��������</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>������������</a></div></li>
			<li class='active'><div><a href='<?=$selfurl?>showOpenOrders'>������ ������� � �������</a></div></li>
			<li><div><a href="<?=$selfurl?>showSentOrders">�������� ������</a></div></li>
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
				<col width='auto' />
				<tr>
					<th>����� ������</th>
					<th>�������� ��������</th>
					<th>������ / ���� / ���</th>
					<th>�����������</th>
					<th>����� ��������� � ������� ���������</th>
					<th>��������� ��������� ������������� �������� ***</th>
					<th>������</th>
					<th>��������</th>
					<th>���������� / �������</th>
				</tr>

				<?if ($orders) : foreach($orders as $order) : ?>
				<tr>
					<td><b>� <?=$order->order_id?></b></td>
					<td><?=$order->order_shop_name?></td>
					<td><?=$order->order_manager_country?> <?=$order->order_date?> <?=Func::round2half($order->order_weight)?>�� <?=Func::round2half($order->order_weight) != $order->order_weight ? '('.$order->order_weight.'��)' : '';?></td>
					<td><? if ($order->comment_for_client) : ?>
						�������� ����� �����������<br />
					<? endif; ?><a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>#comments">����������</a></td>
					<td><?=$order->order_cost?>$
						<a href="javascript:void(0)" onclick="$('#pre_<?=$order->order_id?>').toggle()">���������</a>
						<pre class="pre-href" id="pre_<?=$order->order_id?>">
							<?= $order->order_delivery_cost ?>$
							<? if ($order->order_products_cost) : ?>
							+
							*<?= $order->order_products_cost ?>$
							<? endif; if ($order->order_comission) : ?>
							+
							**<?= $order->order_comission ?>%
							<? endif; ?>
						</pre>
					</td>
					<td><?= $order->package_delivery_cost ?></td>
					<td>
						<?	  if ($order->order_status == 'proccessing') : ?>��������������
						<?elseif ($order->order_status == 'not_available') : ?>��� � �������<br /><i>������� �� ������ ������, ������� ��� � �������</i>
						<?elseif ($order->order_status == 'not_available_color'):?>��� ������� �����
						<?elseif ($order->order_status == 'not_available_size'):?>��� ������� �������
						<?elseif ($order->order_status == 'not_available_count'):?>��� ���������� ���-��
						<?elseif ($order->order_status == 'not_payed') : ?>�� �������
                        <?elseif ($order->order_status == 'not_delivered') : ?>�� �������
						<?elseif ($order->order_status == 'payed') : ?>�������<? endif; ?>
					</td>
					<td>
						<? if ($order->order_status == 'not_payed') : ?><a href="javascript:payItem(<?=$order->order_id?>);">��������</a><? endif; ?>
					</td>
					<td>
						<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">����������</a><br />
						<hr>
						<center><a href="javascript:deleteItem('<?=$order->order_id?>');"><img border="0" src="/static/images/delete.png" title="�������"></a></center>
						<br />
					</td>
				</tr>
				<?endforeach; endif;?>
				<tr class='last-row'>
					<td colspan='9'>
					<div id="tableComments" style="text-align:left;float:left;">
							* ��������� ������� ��������<br />
							** �������� �� ������ � �������<br />
							*** ������ ��������� �������������� �������������� � ����� �� ��������� � ��������<br />
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