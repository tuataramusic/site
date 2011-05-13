	
		<div class='content'>
			<h2>Аккаунт Партнера</h2>

			<h3>Оплаченные посылки</h3>
			<form class='admin-inside' action="<?=$selfurl?>updatePackagesTrackingNo" method="POST">
			
				<ul class='tabs'>
					<li><div><a href='<?=$selfurl?>showAddPackage'>Добавить посылку</a></div></li>
					<li><div><a href='<?=$selfurl?>showNewPackages'>Новые</a></div></li>
					<li class='active'><div><a href='<?=$selfurl?>showPayedPackages'>Оплаченные</a></div></li>
					<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
					<li><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
					<li><div><a href='<?=$selfurl?>showSentOrders'>Закрытые заказы</a></div></li>
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
							<th>Номер клиента</th>
							<th>Номер посылки</th>
							<th>ФИО / Адрес доставки</th>
							<th>Цена доставки</th>
							<th>Комментарии</th>
							<th>Статус</th>
							<th>Добавление Tracking № (Отправлен)</th>
						</tr>
						<?if ($packages) : foreach($packages as $package) : ?>
						<tr>
							<td><?=$package->package_client?></td>
							<td nowrap>
								<b>№ <?=$package->package_id?></b><br /><?=$package->package_join_ids?'(объединены посылки: '.$package->package_join_ids.')<br />':''?><?=$package->package_date?><br /><?=Func::round2half($package->package_weight)?>кг <?=Func::round2half($package->package_weight) != $package->package_weight ? '('.$package->package_weight.'кг)' : '';?><br />
								Прошло:<br /><?=$package->package_day == 0 ? "" : $package->package_day.' '.humanForm((int)$package->package_day, "день", "дня", "дней")?> <?=$package->package_hour == 0 ? "" : $package->package_hour.' '.humanForm((int)$package->package_hour, "час", "часа", "часов")?>
							</td>
							<td><?=$package->package_address?></td>
							<td><?=$package->package_cost?>р</td>
							<td>
								<? if ($package->comment_for_manager) : ?>
									Добавлен новый комментарий<br />
								<? endif; ?>
								<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">Посмотреть / добавить</a>
							</td>
							<td>Оплачено</td>
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
									<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
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
Оплаченные посылки
<form id="packagesForm" action="<?=$selfurl?>updatePackagesTrackingNo" method="POST">
	<div id="Deliveries" align="center">
		<table>
			<tr>
				<th>№ клиента</th>
				<th>№ посылки</th>
				<th>ФИО / Адрес доставки</th>
				<th>Цена доставки</th>
				<th>Комментарии</th>
				<th>Статус</th>
				<th>Добавление Tracking № (Отправлен)</th>
			</tr>
			<?if ($packages) : foreach($packages as $package) : ?>
			<tr>
				<td><?=$package->package_client?></td>
				<td><?=$package->package_id?> <?=$package->package_date?> <?=$package->package_weight?>кг<br />
					Прошло <?=$package->package_age ?> часов</td>
				<td><?=$package->package_address?></td>
				<td><?=$package->package_cost?>$</td>
				<td><? if ($package->comment_for_manager) : ?>
					Добавлен новый комментарий<br />
				<? endif; ?>
				<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">Посмотреть / добавить</a>
				</td>
				<td>Оплачено</td>
				<td>
					<input type="text" name="package_trackingno<?=$package->package_id?>" /><br />
					<input type="checkbox" id="package<?=$package->package_id?>" name="package<?=$package->package_id?>">
				</td>
			</tr>
			<?endforeach; endif;?>
		</table>
	</div>

	<input type="submit" value="Сохранить"/>
</form>
*/?>
<script type="text/javascript">
	$('#packagesForm').submit(function() {
		if ($('#packagesForm input:checkbox:checked').size() == 0)
		{
			alert('Выберите посылки для отправки.');
			return false;
		}
		
		if (!confirm('Вы уверены, что хотите отправить выбранные посылки?'))
		{
			return false;
		}
	});
</script>