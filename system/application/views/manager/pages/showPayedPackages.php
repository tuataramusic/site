	
		<div class='content'>
			<h2>������� ��������</h2>

			<h3>���������� �������</h3>
			<form class='admin-inside' action="<?=$selfurl?>updatePackagesTrackingNo" method="POST">
			
				<ul class='tabs'>
					<li><div><a href='<?=$selfurl?>showAddPackage'>�������� �������</a></div></li>
					<li><div><a href='<?=$selfurl?>showNewPackages'>�����</a></div></li>
					<li class='active'><div><a href='<?=$selfurl?>showPayedPackages'>����������</a></div></li>
					<li><div><a href='<?=$selfurl?>showSentPackages'>������������</a></div></li>
					<li><div><a href='<?=$selfurl?>showOpenOrders'>������ ������� � �������</a></div></li>
					<li><div><a href='<?=$selfurl?>showSentOrders'>�������� ������</a></div></li>
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
						<tr>
							<th>����� �������</th>
							<th>����� �������</th>
							<th>��� / ����� ��������</th>
							<th>���� ��������</th>
							<th>�����������</th>
							<th>������</th>
							<th>���������� Tracking � (���������)</th>
						</tr>
						<?if ($packages) : foreach($packages as $package) : ?>
						<tr>
							<td><?=$package->package_client?></td>
							<td nowrap>
								<b>� <?=$package->package_id?></b><br /><?=$package->package_join_ids?'(���������� �������: '.$package->package_join_ids.')<br />':''?><?=$package->package_date?><br /><?=Func::round2half($package->package_weight)?>�� <?=Func::round2half($package->package_weight) != $package->package_weight ? '('.$package->package_weight.'��)' : '';?><br />
								������:<br /><?=$package->package_day == 0 ? "" : $package->package_day.' '.humanForm((int)$package->package_day, "����", "���", "����")?> <?=$package->package_hour == 0 ? "" : $package->package_hour.' '.humanForm((int)$package->package_hour, "���", "����", "�����")?>
							</td>
							<td><?=$package->package_address?></td>
							<td><?=$package->package_cost?>�</td>
							<td>
								<? if ($package->comment_for_manager) : ?>
									�������� ����� �����������<br />
								<? endif; ?>
								<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">���������� / ��������</a>
							</td>
							<td>��������</td>
							<td nowrap>
								<input type="text" name="package_trackingno<?=$package->package_id?>" /> 
								<input type="checkbox" id="package<?=$package->package_id?>" name="package<?=$package->package_id?>">
							</td>
						</tr>
						<?endforeach; endif;?>
						<tr class='last-row'>
							<td colspan='9'>
								<br />
								<div class='float'>	
									<div class='submit'><div><input type='submit' value='���������' /></div></div>
								</div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</form>
			<div class='pages'><div class='block'><div class='inner-block'>
				<a href='#' class='endpoints'>1</a><a href='#'>2</a><a href='#'>3</a><span>...</span><a href='#'>17</a><span>18</span><a href='#'>19</a><span>...</span><a href='#'>83</a><a href='#'>84</a><a href='#' class='endpoints'>85</a>
			</div></div></div>
		</div>
<?php /*?>
���������� �������
<form id="packagesForm" action="<?=$selfurl?>updatePackagesTrackingNo" method="POST">
	<div id="Deliveries" align="center">
		<table>
			<tr>
				<th>� �������</th>
				<th>� �������</th>
				<th>��� / ����� ��������</th>
				<th>���� ��������</th>
				<th>�����������</th>
				<th>������</th>
				<th>���������� Tracking � (���������)</th>
			</tr>
			<?if ($packages) : foreach($packages as $package) : ?>
			<tr>
				<td><?=$package->package_client?></td>
				<td><?=$package->package_id?> <?=$package->package_date?> <?=$package->package_weight?>��<br />
					������ <?=$package->package_age ?> �����</td>
				<td><?=$package->package_address?></td>
				<td><?=$package->package_cost?>$</td>
				<td><? if ($package->comment_for_manager) : ?>
					�������� ����� �����������<br />
				<? endif; ?>
				<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">���������� / ��������</a>
				</td>
				<td>��������</td>
				<td>
					<input type="text" name="package_trackingno<?=$package->package_id?>" /><br />
					<input type="checkbox" id="package<?=$package->package_id?>" name="package<?=$package->package_id?>">
				</td>
			</tr>
			<?endforeach; endif;?>
		</table>
	</div>

	<input type="submit" value="���������"/>
</form>
*/?>
<script type="text/javascript">
	$('#packagesForm').submit(function() {
		if ($('#packagesForm input:checkbox:checked').size() == 0)
		{
			alert('�������� ������� ��� ��������.');
			return false;
		}
		
		if (!confirm('�� �������, ��� ������ ��������� ��������� �������?'))
		{
			return false;
		}
	});
</script>