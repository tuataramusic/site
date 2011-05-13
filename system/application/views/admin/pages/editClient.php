<div class='content'>
	<h2>������� ��������������</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
	</ul>
	<br />
	<div class="back">
		<a href="javascript:history.back();" class="back"><span>�����</span></a>
	</div>
	
	<form name='registration' class='registration' action="<?=$selfurl?>updateClient/<?=isset($client_user) ? $client_user->user_id :'';?>" method="POST">
	
		<h2>������: <?=isset($client_user) ? $client_user->user_login :'';?></h2>
		<p>��� ���� ����������� ������ ���������� �������</p>
		
		
		<? if ($result->e <0):?>
			<em style="color:red !important"><?=$result->m?></em>
			<br />
		<?endif;?>
		<div class='field <?=isset($client_user) && $client_user->user_login ? 'done' :'';?>'>
			<span>�����:</span>
			<div class='text-field'><div><input type="text" name="login" value="<?=isset($client_user) ? $client_user->user_login :'';?>"></div></div>
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
					<th>� ������� / ������</th>
					<th>����</th>
					<th>Tracking �</th>
					<th>�������� �������</th>
				</tr>
				<? foreach ($packages as $package) : ?>
				<tr>
					<? if (isset($package->package_id)) : ?>
						<td>�������</td>
						<td>
							<?=$package->package_id?> <?=$package->package_date?> <?=$package->package_weight?>��<br />
							������ <?=$package->package_age ?> �����
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
						<td><?=$order->order_id?> <?=$order->order_date?> <?=$order->order_weight?>��<br />
							������ <?=$order->order_age ?> �����</td>
						<td><?=$order->order_cost?>�
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

<?/*
������: <?=isset($client_user) ? $client_user->user_login :'';?>
<br /><br />

<div align="center">
	<form method="POST" action="<?=$selfurl?>updateClient/<?=isset($client_user) ? $client_user->user_id :'';?>">
		<table>
			<tr><td colspan="2">��� ������ ������ ���� ������� ���������� �������!</td></tr>
		
			<tr>
				<td>�����</td>
				<td><input type="text" name="login" value="<?=isset($client_user) ? $client_user->user_login :'';?>"></td>
			</tr>
			<tr>
				<td>������</td>
				<td><input type="password" name="password" value=""></td>
			</tr>
			<tr>
				<td>E-mail</td>
				<td><input type="text" name="email" value="<?=isset($client_user) ? $client_user->user_email :'';?>"></td>
			</tr>

			<tr><td colspan="2"><hr></td></tr>
			
			<tr>
				<td>���</td>
				<td><input type="text" name="name" value="<?=isset($client) ? $client->client_name :'';?>"></td>
			</tr>
			<tr>
				<td>��������</td>
				<td><input type="text" name="otc" value="<?=isset($client) ? $client->client_otc :'';?>"></td>
			</tr>
			<tr>
				<td>�������</td>
				<td><input type="text" name="surname" value="<?=isset($client) ? $client->client_surname :'';?>"></td>
			</tr>
			<tr>
				<td>������</td>
				<td>
					<select name="country">
						<option>��������...</option>
						<?if (count($countries)>0): foreach ($countries as $country):?>
							<option value="<?=$country->country_id;?>" <?= (isset($client) && $client->client_country==$country->country_id) ? 'selected' :'';?>><?=$country->country_name?></option>
						<?endforeach; endif;?>							
					</select>
				</td>
			</tr>
			<tr>
				<td>�����</td>
				<td><input type="text" name="town" value="<?=isset($client) ? $client->client_town :'';?>"></td>
			</tr>
			<tr>
				<td>������</td>
				<td><input type="text" name="index" value="<?=isset($client) ? $client->client_index :'';?>"></td>
			</tr>
			<tr>
				<td>�����</td>
				<td><input type="text" name="address" value="<?=isset($client) ? $client->client_address :'';?>"><br/>
				* ������: Tverskaya 5, 24
				</td>
			<tr>
				<td>�������</td>
				<td><input type="text" name="phone" value="<?=isset($client) ? $client->client_phone :'';?>"><br/>
				* ���������� ������ ��� ����� � ������������� �������
				</td>
			</tr>

			<tr><td colspan="2"><hr></td></tr>
			
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="���������"></td>
			</tr>
		</table>	
	</form>
</div>


<h1>����� � ��������� �������</h1>
<form id="filterForm" action="<?=$selfurl?>filterClientReport/<?=isset($client_user) ? $client_user->user_id :'';?>" method="POST">
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
				<th>��� ������</th>
				<th>� ������� / ������</th>
				<th>����</th>
				<th>Tracking �</th>
				<th>�������� �������</th>
			</tr>
			<? foreach ($packages as $package) : ?>
			<tr>
				<? if (isset($package->package_id)) : ?>
				<td>�������</td>
				<td><?=$package->package_id?> <?=$package->package_date?> <?=$package->package_weight?>��<br />
					������ <?=$package->package_age ?> �����</td>
				<td><? if (!$package->package_delivery_cost) : ?>������ �������� �� ������<? else : ?>
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
				<td><?= $package->package_trackingno ?></td>
				<td></td>
				<? elseif (isset($package->order_id)) : $order = $package; ?>
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
				<td></td>
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