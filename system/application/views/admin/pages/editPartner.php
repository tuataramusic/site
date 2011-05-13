<div class='content'>
	<h2>������� ��������������</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
	</ul>
	<br />
	
	<?if (isset($update) && $update==1):?>
		<h1>���������� ���������� � ��������</h1>
	<?else:?>
		<h2>���������� ������ ��������</h2>
	<?endif;?>
	<br />
	<div class="back">
		<a href="javascript:history.back();" class="back"><span>�����</span></a>
	</div><br />
	
	
	<form name='registration' class='registration' action="<?=$selfurl?>updateClient/<?=isset($client_user) ? $client_user->user_id :'';?>" method="POST">

		<p>��� ���� ����������� ������ ���������� �������</p>
		
		<? if ($result->e <0):?>
			<em style="color:red !important"><?=$result->m?></em>
			<br />
		<?endif;?>
		<div class='field <?=isset($client_user) && $client_user->user_login ? 'done' :'';?>'>
			<span>�����:</span>
			<div class='text-field'><div><?if (isset($update) && $update==1):?><input type='text' disabled size='30' value='<?=$manager_user->user_login?>'/><?else:?><input type='text' name='user_login' size='30' value='<?=isset($manager_user) ? $manager_user->user_login :'';?>'/><?endif;?></div></div>
		</div>		
		<div class='field <?=isset($client_user) && $client_user->user_login ? 'done' :'';?>'>
			<span>������:</span>
			<div class='text-field'><div><input type='password' name='password' size='30' value=''/></div></div>
		</div>
		<div class='field <?=isset($client_user) && $client_user->user_email ? 'done' :'';?>' >
			<span>E-mail:</span>
			<div class='text-field'><div><input type="text" name="email" value="<?=isset($client_user) ? $client_user->user_email :'';?>"></div></div>
		</div>
		<div class='hr'></div>
		<div class='field <?=isset($client) && $client->client_name ?'done' :'';?>'>
			<span>���:</span>
			<div class='text-field'><div><input type="text" name="name" value="<?=isset($client) ? $client->client_name :'';?>"></div></div>
		</div>
		<div class='field <?=isset($client) && $client->client_surname ?'done' :'';?>'>
			<span>�������:</span>
			<div class='text-field'><div><input type="text" name="surname" value="<?=isset($client) ? $client->client_surname :'';?>"></div></div>
		</div>
		<div class='field <?=isset($client) && $client->client_otc ?'done' :'';?>'>
			<span>��������:</span>
			<div class='text-field'><div><input type="text" name="otc" value="<?=isset($client) ? $client->client_otc :'';?>"></div></div>
		</div>
		<div class='field done' id='country'>
			<span>������:</span>
			<select name="country" class="select">
				<option>��������...</option>
				<?if (count($countries)>0): foreach ($countries as $country):?>
					<option value="<?=$country->country_id;?>" <?= (isset($client) && $client->client_country==$country->country_id) ? 'selected' :'';?>><?=$country->country_name?></option>
				<?endforeach; endif;?>							
			</select>
		</div>
		<div class='field <?=isset($client) && $client->client_town ?'done' :'';?>'>
			<span>�����:</span>
			<div class='text-field'><div><input type="text" name="town" value="<?=isset($client) ? $client->client_town :'';?>"></div></div>
		</div>
		<div class='field <?=isset($client) && $client->client_address ?'done' :'';?>'>
			<span>�����:</span>
			<div class='text-field'><div><input type='text' name="address" value="<?=isset($client) ? $client->client_address :'';?>" /></div></div>
		</div>
		<div class='field <?=isset($client) && $client->client_index ?'done' :'';?>'>
			<span>������:</span>
			<div class='text-field'><div><input type="text" name="index" value="<?=isset($client) ? $client->client_index :'';?>"></div></div>
		</div>
		<div class='field <?=isset($client) && $client->client_phone ?'done' :'';?>'>
			<span>�������:</span>
			<div class='text-field'><div><input type='text' name="phone" value="<?=isset($client) ? $client->client_phone :'';?>" /></div></div>
		</div>
		<div class='hr'></div>
		<div class='submit'><div><input type='submit' value='���������' /></div></div>
	</form>


	
	<h3>����� � ��������� �������</h3>
	<div class="back">
		<a href="javascript:history.back();" class="back"><span>�����</span></a>
	</div>
	<form class='admin-sorting' id="filterForm" action="<?=$selfurl?>filterClientReport/<?=isset($client_user) ? $client_user->user_id :'';?>" method="POST">
		<div class='sorting'>
			<span class='first-title'>������������� ��:</span>
			<select class="select" name="period">
				<option value="">���</option>
				<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>����</option>
				<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>������</option>
				<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>�����</option>
			</select>
		</div>
	</form>

	<? if (isset($packages) && $packages) : ?>
	<form class='admin-inside' id="packagesForm" action="<?=$selfurl?>updateNewPackagesStatus" method="POST">
		
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
				<tr>
					<th>��� ������</th>
					<th>����� ������� / ������</th>
					<th>����</th>
					<th>Tracking �</th>
					<th>�������� �������</th>
				</tr>
				<? foreach ($packages as $package) : ?>
				<tr>
					<? if (isset($package->package_id)) : ?>
						<td>�������</td>
						<td>
							<b>� <?=$package->package_id?></b><br /><?=$package->package_date?><br /><?=$package->package_weight?>��<br />
							������:<br /><?=$package->package_day == 0 ? "" : $package->package_day.' '.humanForm((int)$package->package_day, "����", "���", "����")?> <?=$package->package_hour == 0 ? "" : $package->package_hour.' '.humanForm((int)$package->package_hour, "���", "����", "�����")?>
						</td>
						<td><? if (!$package->package_delivery_cost) : ?>������ �������� �� ������<? else : ?>
							<?=$package->package_cost?>�
							
							<a href="javascript:void(0)" onclick="$('#pre_<?=$package->package_id?>').toggle()">���������</a>
							<pre class="pre-href" id="pre_<?=$package->package_id?>">
								<?= $package->package_delivery_cost ?>�+
								*<?= $package->package_comission ?>�
								<? if ($package->package_declaration_cost) : ?>+
									**<?= $package->package_declaration_cost ?>�
								<? endif; ?>
								<? if ($package->package_join_cost) : ?>+
									***<?= $package->package_join_cost ?>�
								<? endif;?>
							</pre>
							<? endif; ?>
						</td>
						<td><?= $package->package_trackingno ?></td>
						<td></td>
					<? elseif (isset($package->order_id)) : $order = $package; ?>
						<td>������ � �������</td>
						<td>
							<b>� <?=$order->order_id?></b><br /><?=$order->order_date?><br /><?=$order->order_weight?>��<br />
							������:<br /><?=$order->package_day == 0 ? "" : $order->package_day.' '.humanForm((int)$order->package_day, "����", "���", "����")?> <?=$order->package_hour == 0 ? "" : $order->package_hour.' '.humanForm((int)$order->package_hour, "���", "����", "�����")?>
						</td>
						<td>
							<?=$order->order_cost?>�
							<a href="javascript:void(0)" onclick="$('#pre_<?=$package->order_id?>').toggle()">���������</a>
							<pre class="pre-href" id="pre_<?=$package->order_id?>">
								<?= $order->order_delivery_cost ?>�
								<? if ($order->order_products_cost) : ?>+
									*<?= $order->order_products_cost ?>�
								<? endif; if ($order->order_comission) : ?>+
									**<?= $order->order_comission ?>%
								<? endif; ?>
							</pre>
						</td>
						<td></td>
						<td>
							<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">����������</a>
						</td>
					<? endif; ?>
				</tr>
				<? endforeach; ?>
			</table>
		</div>
	</form>
	<?php if (isset($pager)) echo $pager ?>
	<?endif;?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});
	});
</script>


<?/*
<?if (isset($update) && $update==1):?><h1>���������� ���������� � ��������</h1><?else:?><h1>���������� ������ ��������</h1><?endif;?>

<form action='<?=$selfurl?><?if (isset($update) && $update==1):?>updatePartner/<?=$manager_user->user_id?><?else:?>addPartner<?endif;?>' method='POST'>
<table>
	<tr><td>�����:</td><td><?if (isset($update) && $update==1):?><?=$manager_user->user_login?><?else:?><input type='text' name='user_login' size='30' value='<?=$manager_user ? $manager_user->user_login :'';?>'/><?endif;?></td></tr>
	<tr><td>������:</td><td><input type='password' name='password' size='30' value=''/></td></tr>
	<tr><td>Email:</td><td><input type='text' name='email' size='30' value='<?=$manager_user ? $manager_user->user_email :'';?>'/></td></tr>
	
	<tr><td>���:</td><td><input type='text' name='manager_name' size='30' value='<?=isset($manager) ? $manager->manager_name :'';?>'/></td></tr>
	<tr><td>�������:</td><td><input type='text' name='manager_surname' size='30' value='<?=isset($manager) ? $manager->manager_surname :'';?>'/></td></tr>
	<tr><td>��������:</td><td><input type='text' name='manager_otc' size='30' value='<?=isset($manager) ? $manager->manager_otc :'';?>'/></td></tr>
	<tr><td>�����:</td><td><textarea name='manager_addres' rows='3' cols='30'><?=isset($manager) ? $manager->manager_addres :'';?></textarea></td></tr>
	<tr><td>�������:</td><td><input type='text' name='manager_phone' size='30' value='<?=isset($manager) ? $manager->manager_phone :'';?>'/></td></tr>
	<tr><td>������:</td>
		<td>
		<select name="manager_country" style="width: 230px;">
			<option value="0">��������...</option>
			<?if (count($countries)>0): foreach ($countries as $country):?>
				<option value="<?=$country->country_id;?>" <?= (isset($manager) && $manager->manager_country==$country->country_id) ? 'selected' :'';?>><?=$country->country_name?></option>
			<?endforeach; endif;?>	
		</select>
		</td></tr>
	<tr><td>������� ��������:</td><td><?if (count($deliveries) > 0): 
		foreach ($deliveries as $delivery) : ?>
		<input type="checkbox" name="delivery<?=$delivery->delivery_id?>" id="delivery<?=$delivery->delivery_id?>" <?=$delivery->checked?> />
		<label for="delivery<?=$delivery->delivery_id?>"><?=$delivery->delivery_name?></label><br />
				<?endforeach; endif;?></td></tr>
	<tr><td style="width: 150px;">������������ ���-�� �������������:</td><td><input type='text' name='manager_max_clients' size='30' value='<?=isset($manager) ? $manager->manager_max_clients :'50';?>'/></td></tr>
	<tr><td>������:</td>
		<td>
			<select name='manager_status' style="width: 230px;">
				<option value="0">��������...</option>
				<?if (count($statuses)>0): foreach ($statuses as $key=>$status):?>
					<option value="<?=$key;?>" <?= (isset($manager) && $manager->manager_status==$key) ? 'selected' :'';?>><?=$status?></option>
				<?endforeach; endif;?>
			</select>
		</td></tr>
	<tr><td></td><td style="text-align: center;"><input type='submit' name='add' value='���������'/></td></tr>
</table>
</form>
<h1>����� � ����������� �������</h1>
<form id="filterForm" action="<?=$selfurl?>filterPartnerReport/<?=isset($manager_user) ? $manager_user->user_id :'';?>" method="POST">
	<div id="clientFilter" align="center">
		������������� �� <select name="period">
			<option value="">���</option>
			<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>����</option>
			<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>������</option>
			<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>�����</option>
		</select>
	</div>
</form>
<? if (isset($packages) && $packages) : ?>
	<div id="Deliveries" align="center">
		<table>
			<tr>
				<th>� �������</th>
				<th>��� ������</th>
				<th>� ������� / ������</th>
				<th>����</th>
				<th>� �������</th>
				<th>Tracking �</th>
				<th>���������</th>
				<th>�������� �������</th>
			</tr>
			<? foreach ($packages as $package) : ?>
			<tr>
				<? if (isset($package->package_id)) : ?>
				<td><?= $package->package_client ?></td>
				<td>�������</td>
				<td><?=$package->package_id?> <?=$package->package_date?> <?=$package->package_weight?>��<br />
					������ <?=$package->package_age ?> �����</td>
				<td><?=$package->package_cost?>�
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
					<? endif; ?></td>
				<td><?=$package->package_manager_cost?>�
					<hr />
					<?= $package->package_delivery_cost ?>�
					<br />+<br />
					*<?= $package->package_manager_comission ?>�
					<? if ($package->package_declaration_cost) : ?>
					<br />+<br />
					**<?= $package->package_declaration_cost ?>�
					<? endif; ?>
					<? if ($package->package_join_cost) : ?>
					<br />+<br />
					***<?= $package->package_join_cost ?>�
					<? endif; ?></td>
				<td><?= $package->package_trackingno ?></td>
				<td><? if ($package->package_payed_to_manager) : ?>��<? else : ?>
					<a href="<?=$selfurl?>payPackageToManager/<?=$package->package_id?>">���������</a><? endif; ?></td>
				<td></td>
				<? elseif (isset($package->order_id)) : $order = $package; ?>
				<td><?= $order->order_client ?></td>
				<td>������ � �������</td>
				<td><?=$order->order_id?> <?=$order->order_date?> <?=$order->order_weight?>��<br />
					������ <?=$order->order_age ?> �����</td>
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
				<td><?=$order->order_manager_cost?>�
					<hr />
					<?= $order->order_delivery_cost ?>�
					<? if ($order->order_products_cost) : ?>
					<br />+<br />
					*<?= $order->order_products_cost ?>�
					<? endif; if ($order->order_manager_comission) : ?>
					<br />+<br />
					**<?= $order->order_manager_comission ?>%
					<? endif; ?></td>
				<td></td>
				<td><? if ($order->order_payed_to_manager) : ?>��<? else : ?>
					<a href="<?=$selfurl?>payOrderToManager/<?=$order->order_id?>">���������</a><? endif; ?></td>
				<td>
					<a href="<?=$selfurl?>showOrderDetails/<?=$order->order_id?>">����������</a>
				</td>
				<? endif; ?>
			</tr>
			<? endforeach; ?>
		</table>
<? endif;?>
*/?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});
	});
</script>