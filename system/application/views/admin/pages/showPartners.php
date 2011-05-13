	
		<div class='content'>
			<h2>Аккаунт администратора</h2>
			<ul class='admin-buttons'>
				<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
				<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
				<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
			</ul>
			<h3>Партнеры</h3>
			<form class='admin-inside' action='#'>
				<br />
				<div align="right"><a href='<?=$selfurl?>showAddPartner'>Добавить нового партнера</a></div>
				<br />
				
				<ul class='tabs'>
					<li><div><a href='<?=$selfurl?>showNewPackages'>Новые</a></div></li>
					<li><div><a href='<?=$selfurl?>showPayedPackages'>Оплаченные</a></div></li>
					<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
					<li><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
					<li><div><a href='<?=$selfurl?>showClients'>Клиенты</a></div></li>
					<li class='active'><div><a href='<?=$selfurl?>showPartners'>Партнеры</a></div></li>
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
							<th>Номер партнера</th>
							<th>Партнер,<br />страна</th>
							<th>ФИО</th>
							<!--th>Адрес доставки</th-->
							<th>Количество клиентов</th>
							<th>Выплаченных заказов</th>
							<th>Статус</th>
							<th>Кредит</th>
                            <th>Баланс</th>
							<th>Изменить / удалить</th>
						</tr>
						<?if ($managers): foreach ($managers as $manager):?>
							<tr>
								<td><b>№ <?=$manager->manager_user?></b></td>
								<td><?=$manager->user_login?> / <?=$countries[$manager->manager_country]?></td>
								<td><?=$manager->manager_surname?> <?=$manager->manager_name?> <?=$manager->manager_otc?></td>
								<!--td><?=$manager->manager_addres?></td-->
								<td><?=$manager->clients_count?></td>
								<td><?=0?></td>
								<td><?=$statuses[$manager->manager_status]?></td>
								<td><?=isset($manager->manager_credit) ? "<b>".$manager->manager_credit."</b>" : 0?></td>
                                <td><?=0?></td>
								<td align="center">
									<a href='<?=$selfurl?>showPartnerInfo/<?=$manager->manager_user?>'>Изменить</a><br/>
									<hr>
									<center><a href='<?=$selfurl?>deletePartner/<?=$manager->manager_user?>'><img title="Удалить" border="0" src="/static/images/delete.png"></a></center>
									<br/>
								</td>
							</tr>
							<?endforeach;?>	
						<?else:?>
							<tr>
								<td colspan=9>Партнеров нет!</td>
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