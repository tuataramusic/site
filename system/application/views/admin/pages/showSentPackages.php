	
		<div class='content'>
			<h2>������� ��������������</h2>
			<ul class='admin-buttons'>
				<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
				<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
				<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
			</ul>
			<h3>������������</h3>

			<form class='admin-sorting' id="filterForm" action="<?=$selfurl?>filterSentPackages" method="POST">
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

			
			<form class='admin-inside' id="packagesForm" action="<?=$selfurl?>updateSentPackagesStatus" method="POST">
				<ul class='tabs'>
					<li><div><a href='<?=$selfurl?>showNewPackages'>�����</a></div></li>
					<li><div><a href='<?=$selfurl?>showPayedPackages'>����������</a></div></li>
					<li class='active'><div><a href='<?=$selfurl?>showSentPackages'>������������</a></div></li>
					<li><div><a href='<?=$selfurl?>showOpenOrders'>������ ������� � �������</a></div></li>
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
						<col width='auto' />
						<tr>
							<th>����� �������, �����</th>
							<th>�������, ������</th>
							<th>����� �������</th>
							<th>�.�.�., ����� ��������</th>
							<th>���� ��������</th>
							<th>�����������</th>
							<th>������</th>
							<th>Tracking �����</th>
						</tr>
						<?if ($packages) : foreach($packages as $package) : ?>
						<tr>
							<td nowrap>
								<b>� <?=$package->package_id?></b><br /><?=$package->package_date?><br /><?=$package->package_weight?>��<br />
								������:<br /><?=$package->package_day == 0 ? "" : $package->package_day.' '.humanForm((int)$package->package_day, "����", "���", "����")?> <?=$package->package_hour == 0 ? "" : $package->package_hour.' '.humanForm((int)$package->package_hour, "���", "����", "�����")?>
							</td>
							<td><?=$package->package_manager_login?> / <?=$package->package_manager_country?></td>
							<td><b>� <?=$package->package_client?></b></td>
							<td><?=$package->package_address?></td>
							<td><? if (!$package->package_delivery_cost) : ?>�������� ������ ��������<? else : ?>
									<?=$package->package_cost?>$
									<a href="javascript:void(0)" onclick="$('#pre_<?=$package->package_id?>').toggle()">���������</a>
									<pre class="pre-href" id="pre_<?=$package->package_id?>">
										<?= $package->package_delivery_cost ?>$
										+
										*<?= $package->package_comission ?>$
										<? if ($package->package_declaration_cost) : ?>
										+
										**<?= $package->package_declaration_cost ?>$
										<? endif; ?>
										<? if ($package->package_join_cost) : ?>
										+
										***<?= $package->package_join_cost ?>$
										<? endif;?>
									</pre>
								<? endif; ?>
							</td>
							<td><? if ($package->comment_for_manager || $package->comment_for_client) : ?>
								�������� ����� �����������<br />
							<? endif; ?>
							<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">����������</a>
							</td>
							<td><select name="package_status<?=$package->package_id?>">
									<option value="not_payed">�� �������</option>
									<option value="payed">�������</option>
									<option value="sent" selected="selected">���������</option>
								</select></td>
							<td><b><?=$package->package_trackingno?></b></td>
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
������������ �������
<br /><br />
<form id="filterForm" action="<?=$selfurl?>filterSentPackages" method="POST">
	<div id="packageFilter" align="center">
		������������� �� �������� <select name="manager_user">
			<option value="">�������...</option>
			<?if ($managers) : foreach($managers as $manager) : ?>
			<option value="<?=$manager->manager_user?>" <? if ($manager->manager_user == $filter->manager_user) : ?>selected="selected"<? endif; ?>><?=$manager->user_login?></option>
			<?endforeach; endif;?></select> �� <select name="period">
			<option value="">���</option>
			<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>����</option>
			<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>������</option>
			<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>�����</option>
		</select> ����� ������� <input type="text" maxlength="11" name="search_id" value="<?=$filter->search_id?>"/> �� <select name="id_type">
			<option value="">�������...</option>
			<option value="package" <? if ('package' == $filter->id_type) : ?>selected="selected"<? endif; ?>>������ �������</option>
			<option value="client" <? if ('client' == $filter->id_type) : ?>selected="selected"<? endif; ?>>������ ������������</option>
		</select>
	</div>
</form>
	
<br />
	
<form id="packagesForm" action="<?=$selfurl?>updateSentPackagesStatus" method="POST">
	<div id="Deliveries" align="center">
		<table>
			<tr>
				<th>������� / ������</th>
				<th>� �������</th>
				<th>� �������</th>
				<th>��� / ����� ��������</th>
				<th>���� ��������</th>
				<th>�����������</th>
				<th>������</th>
				<th>Tracking �</th>
			</tr>
			<?if ($packages) : foreach($packages as $package) : ?>
			<tr>
				<td><?=$package->package_manager_login?> / <?=$package->package_manager_country?></td>
				<td><?=$package->package_client?></td>
				<td><?=$package->package_id?> <?=$package->package_date?> <?=$package->package_weight?>��<br />
					������ <?=$package->package_age ?> �����</td>
				<td><?=$package->package_address?></td>
				<td><? if (!$package->package_delivery_cost) : ?>�������� ������ ��������<? else : ?>
					<?=$package->package_cost?>�
					<hr />
					<?= $package->package_delivery_cost ?>�
					<br />+<br />
					*<?= $package->package_comission ?>�
					<? if ($package->package_declaration_cost) : ?>
					<br />+<br />
					**<?= $package->package_declaration_cost ?>�
					<? endif; ?>
					<? if ($package->package_join_cost) : ?>
					<br />+<br />
					***<?= $package->package_join_cost ?>�
					<? endif; endif; ?></td>
				<td><? if ($package->comment_for_manager || $package->comment_for_client) : ?>
					�������� ����� �����������<br />
				<? endif; ?>
				<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">����������</a>
				</td>
				<td><select name="package_status<?=$package->package_id?>">
						<option value="not_payed">�� �������</option>
						<option value="payed">�������</option>
						<option value="sent" selected="selected">���������</option>
					</select></td>
				<td><?=$package->package_trackingno?></td>
			</tr>
			<?endforeach; endif;?>
		</table>

		<input type="submit" value="���������" />
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});
		
		$('#filterForm input:text').keypress(function(event){validate_number(event);});
	})
</script>
*/?>