<div class='content'>
	<h2>������� ��������</h2>
	<?if(isset($result->m) && $result->m):?><em style="color:red;"><?=$result->m?></em><br/><?endif;?>
	<?View::show($viewpath.'elements/div_float_preview_package');?>
	<?View::show($viewpath.'elements/div_float_upload_package');?>
	
	
	<h3>����� �������</h3>
	<form class='admin-inside' action='<?=$selfurl?>updateNewPackagesStatus' method="post">
	
		<ul class='tabs'>
			<li><div><a href='<?=$selfurl?>showAddPackage'>�������� �������</a></div></li>
			<li class='active'><div><a href='<?=$selfurl?>showNewPackages'>�����</a></div></li>
			<li><div><a href='<?=$selfurl?>showPayedPackages'>����������</a></div></li>
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
				<col width='auto' />
				<tr>
					<th>����� �������</th>
					<th>����� �������, �����</th>
					<th>�.�.�., ����� ��������</th>
					<th>���� ��������</th>
					<th>�����������</th>
					<th>������</th>
					<th>����������</th>
					<th>����</th>
					<th class='last-child'></th>
				</tr>
				<?if ($packages) : foreach($packages as $package) : ?>
				<tr>
					<td><b>� <?=$package->package_client?></b></td>
					<td nowrap>
						<b>� <?=$package->package_id?></b><br /><?=$package->package_join_ids?'(���������� �������: '.$package->package_join_ids.')<br />':''?><?=$package->package_date?><br /><?=Func::round2half($package->package_weight)?>�� <?=Func::round2half($package->package_weight) != $package->package_weight ? '('.$package->package_weight.'��)' : '';?><br />
						<?=$package->package_day == 0 ? "" : '������:<br />'.$package->package_day.' '.humanForm((int)$package->package_day, "����", "���", "����")?> <?=$package->package_hour == 0 ? "" : $package->package_hour.' '.humanForm((int)$package->package_hour, "���", "����", "�����")?>
					</td>
					<td><?=nl2br($package->package_address)?>
						<br />
						<a href="<?=$selfurl?>editPackageAddress/<?=$package->package_id?>">��������</a>
					</td>
					<td><?=$package->package_cost?>$</td>
					<td><? if ($package->comment_for_manager) : ?>
						�������� ����� �����������<br />
					<? endif; ?>
					<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">���������� / ��������</a>
					</td>
					<td>�� �������</td>
					<td><? if ($package->declaration_status == 'not_completed') : ?>
						�� ���������
					<? elseif ($package->declaration_status == 'completed') : ?>
						��������� <input type="checkbox" id="help<?=$package->package_id?>" name="help<?=$package->package_id?>"><br />
						<a href="<?=$selfurl?>previewDeclaration/<?=$package->package_id?>">����������</a>
					<? else : ?>
						<a href="<?=$selfurl?>showDeclaration/<?=$package->package_id?>">��������� ��������������</a><br />
						<a href="<?=$selfurl?>previewDeclaration/<?=$package->package_id?>">����������</a>
					<? endif; ?></td>
					<td>
						<a href="javascript:uploadPackFoto(<?=$package->package_id?>);">��������</a>
						</br></br>
						<? if (isset($packFotos[$package->package_id])): ?>
							<a href="javascript:void(0)" onclick="setRel(<?=$package->package_id?>)" >���������� (<?=count($packFotos[$package->package_id]);?> ����)
								<?foreach ($packFotos[$package->package_id] as $packFoto):?>
									<a rel="lightbox_<?=$package->package_id?>" href="/manager/showPackageFoto/<?=$package->package_id?>/<?=$packFoto?>" style="display:none">����������</a>
								<?endforeach;?>
							</a>
						<? endif; ?>
					</td>
					<td>
						<a  class='delete' href="javascript:deleteItem('<?=$package->package_id?>');"><img title="�������" border="0" src="/static/images/delete.png"></a>
					</td>
				</tr>
				<?endforeach; endif;?>
				<tr class='last-row'>
					<td colspan='9'>
						<br />
						<div class='float'>	
							<label for="declaration_status">������� ������ ����������:</label>
							<select id="declaration_status" name="declaration_status" onchange="javascript:updateStatus();">
								<option value="-1">�������...</option>
								<option value="not_completed">�� ���������</option>
							</select>
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
����� �������
<form id="packagesForm" action="<?=$selfurl?>updateNewPackagesStatus" method="POST">
	<div id="Deliveries" align="center">
		<table>
			<tr>
				<th>� �������</th>
				<th>� �������</th>
				<th>��� / ����� ��������</th>
				<th>���� ��������</th>
				<th>�����������</th>
				<th>������</th>
				<th>����������</th>
				<th>������� �������</th>
			</tr>
			<?if ($packages) : foreach($packages as $package) : ?>
			<tr>
				<td><?=$package->package_client?></td>
				<td><?=$package->package_id?> <?=$package->package_date?> <?=$package->package_weight?>��<br />
					������ <?=$package->package_age ?> �����</td>
				<td><?=$package->package_address?>
					<br />
					<a href="<?=$selfurl?>editPackageAddress/<?=$package->package_id?>">��������</a>
				</td>
				<td><?=$package->package_cost?>�</td>
				<td><? if ($package->comment_for_manager) : ?>
					�������� ����� �����������<br />
				<? endif; ?>
				<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">���������� / ��������</a>
				</td>
				<td>�� �������</td>
				<td><? if ($package->declaration_status == 'not_completed') : ?>
					�� ���������
				<? elseif ($package->declaration_status == 'completed') : ?>
					��������� <input type="checkbox" id="help<?=$package->package_id?>" name="help<?=$package->package_id?>">
				<? else : ?>
					<a href="<?=$selfurl?>showDeclaration/<?=$package->package_id?>">��������� ��������������</a>
				<? endif; ?></td>
				<td>
					<a href="javascript:deleteItem('<?=$package->package_id?>');"></a>
				</td>
			</tr>
			<?endforeach; endif;?>
		</table>

		<label for="declaration_status">������� ������ ����������:</label>
		<select id="declaration_status" name="declaration_status" onchange="javascript:updateStatus();">
			<option value="-1">�������...</option>
			<option value="not_completed">�� ���������</option>
		</select>
	</div>
</form>
*/?>
<script type="text/javascript">
	function deleteItem(id){
		if (confirm("�� �������, ��� ������ ������� ������� �" + id + "?")){
			window.location.href = '<?=$selfurl?>deletePackage/' + id;
		}
	}
	
	function setRel(id){
		$("a[rel*='lightbox_"+id+"']").lightBox();
		var aa = $("a[rel*='lightbox_"+id+"']");
		$(aa[0]).click();
	}
	
	function updateStatus(id){
		var selectedStatus = $('#declaration_status option:selected');
		if (selectedStatus.val() != '-1'){
			if ($('#packagesForm input:checkbox:checked').size() == 0){
				alert('�������� ������� �� �������������� ������������.');
				return;
			}
			
			if (confirm('�� �������, ��� ������ �������� ������ ���������� ��������� ������� �� "' 
				+ $(selectedStatus).text() + '"?'))
			{
				document.getElementById('packagesForm').submit();
			}
		}
	}
</script>