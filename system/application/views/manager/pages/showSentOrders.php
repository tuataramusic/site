<div class='content'>
	<h2>������� ��������</h2>

	<h3>�������� ������</h3>
	<form class='admin-inside' action="<?=$selfurl?>closeOrders" method="POST">
	
		<ul class='tabs'>
			<li><div><a href='<?=$selfurl?>showAddPackage'>�������� �������</a></div></li>
			<li><div><a href='<?=$selfurl?>showNewPackages'>�����</a></div></li>
			<li><div><a href='<?=$selfurl?>showPayedPackages'>����������</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>������������</a></div></li>
			<li><div><a href='<?=$selfurl?>showOpenOrders'>������ ������� � �������</a></div></li>
			<li class='active'><div><a href='<?=$selfurl?>showSentOrders'>�������� ������</a></div></li>
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
					<th>����� ������</th>
					<th>�������� ��������</th>
					<th>����� �������</th>
					<th>���� ������������ ������</th>
					<th>���� ��������</th>
					<th>����������� � ������</th>
					<th>������</th>
					<th>�������� ������� ������</th>
				</tr>
				<?if ($orders) : foreach($orders as $order) : ?>
				<tr>
					<td><b>� <?=$order->order_id?></b></td>
					<td><? echo($order->order_shop_name); 
						if ($order->order_status == 'proccessing') : ?><br />NEW<? endif; ?></td>
					<td><b>� <?=$order->order_client?></b></td>
					<td><?=$order->order_date?></td>
					<td><?=$order->order_cost?>$</td>
					<td><? if ($order->comment_for_manager) : ?>
						�������� ����� �����������<br />
					<? endif; ?>
					<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>#comments">���������� / ��������</a>
					</td>
					<td>���������</td>
					<td><a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">����������</a></td>
				</tr>
				<?endforeach; endif;?>
			</table>
		</div>
	</form>
	<div class='pages'><div class='block'><div class='inner-block'>
		<a href='#' class='endpoints'>1</a><a href='#'>2</a><a href='#'>3</a><span>...</span><a href='#'>17</a><span>18</span><a href='#'>19</a><span>...</span><a href='#'>83</a><a href='#'>84</a><a href='#' class='endpoints'>85</a>
	</div></div></div>
</div>
