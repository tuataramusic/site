	
		<div class='content'>
			<h2>������� ��������������</h2>
			<ul class='admin-buttons'>
				<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
				<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
				<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
			</ul>
			<h3>��������</h3>
			<form class='admin-inside' action='#'>
				<br />
				<div align="right"><a href='<?=$selfurl?>showAddPartner'>�������� ������ ��������</a></div>
				<br />
				
				<ul class='tabs'>
					<li><div><a href='<?=$selfurl?>showNewPackages'>�����</a></div></li>
					<li><div><a href='<?=$selfurl?>showPayedPackages'>����������</a></div></li>
					<li><div><a href='<?=$selfurl?>showSentPackages'>������������</a></div></li>
					<li><div><a href='<?=$selfurl?>showOpenOrders'>������ ������� � �������</a></div></li>
					<li><div><a href='<?=$selfurl?>showClients'>�������</a></div></li>
					<li class='active'><div><a href='<?=$selfurl?>showPartners'>��������</a></div></li>
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
						<!--col width='auto' /-->
						<col width='200' />
						<col width='auto' />
						<col width='80' />
						<col width='auto' />
                        <col width='auto' />
						<col width='auto' />
						<tr>
							<th>����� ��������</th>
							<th>�������,<br />������</th>
							<th>���</th>
							<!--th>����� ��������</th-->
							<th>���������� ��������</th>
							<th>����������� �������</th>
							<th>������</th>
							<th>������</th>
                            <th>������</th>
							<th>�������� / �������</th>
						</tr>
						<?if ($managers): foreach ($managers as $manager):?>
							<tr>
								<td><b>� <?=$manager->manager_user?></b></td>
								<td><?=$manager->user_login?> / <?=$countries[$manager->manager_country]?></td>
								<td><?=$manager->manager_surname?> <?=$manager->manager_name?> <?=$manager->manager_otc?></td>
								<!--td><?=$manager->manager_addres?></td-->
								<td><?=$manager->clients_count?></td>
								<td><?=0?></td>
								<td><?=$statuses[$manager->manager_status]?></td>
								<td><?=isset($manager->manager_credit) ? "<b>".$manager->manager_credit."</b>" : 0?></td>
                                <td><?=0?></td>
								<td align="center">
									<a href='<?=$selfurl?>showPartnerInfo/<?=$manager->manager_user?>'>��������</a><br/>
									<hr>
									<center><a href='<?=$selfurl?>deletePartner/<?=$manager->manager_user?>'><img title="�������" border="0" src="/static/images/delete.png"></a></center>
									<br/>
								</td>
							</tr>
							<?endforeach;?>	
						<?else:?>
							<tr>
								<td colspan=9>��������� ���!</td>
							</tr>
						<?endif;?>
						<tr class='last-row'>
							<td colspan='9'>
								<div class='float'>&nbsp;
								</div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</form>
			<?php if (isset($pager)) echo $pager ?>
		</div>