	
		<div class='content'>
			<h2>Аккаунт Партнера</h2>

			<h3>Отправленные посылки</h3>
			<form class='admin-inside'>
			
				<ul class='tabs'>
					<li><div><a href='<?=$selfurl?>showAddPackage'>Добавить посылку</a></div></li>
					<li><div><a href='<?=$selfurl?>showNewPackages'>Новые</a></div></li>
					<li><div><a href='<?=$selfurl?>showPayedPackages'>Оплаченные</a></div></li>
					<li class='active'><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
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
						<tr>
							<th>Номер клиента</th>
							<th>Номер посылки</th>
							<th>ФИО / Адрес доставки</th>
							<th>Цена доставки</th>
							<th>Комментарии</th>
							<th>Tracking №</th>
						</tr>
						<?if ($packages) : foreach($packages as $package) : ?>
						<tr>
							<td><b>№ <?=$package->package_client?></b></td>
							<td nowrap><b>№ <?=$package->package_id?></b><br /><?=$package->package_join_ids?'(объединены посылки: '.$package->package_join_ids.')<br />':''?><?=$package->package_date?><br /><?=Func::round2half($package->package_weight)?>кг <?=Func::round2half($package->package_weight) != $package->package_weight ? '('.$package->package_weight.'кг)' : '';?></td>
							<td><?=$package->package_address?></td>
							<td><?=$package->package_cost?>$</td>
							<td><? if ($package->comment_for_manager) : ?>
								Добавлен новый комментарий<br />
							<? endif; ?>
							<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">Посмотреть / добавить</a>
							</td>
							<td><b><?=$package->package_trackingno?></b></td>
						</tr>
						<?endforeach; endif;?>
					</table>
				</div>
			</form>
			<div class='pages'><div class='block'><div class='inner-block'>
				<a href='#' class='endpoints'>1</a><a href='#'>2</a><a href='#'>3</a><span>...</span><a href='#'>17</a><span>18</span><a href='#'>19</a><span>...</span><a href='#'>83</a><a href='#'>84</a><a href='#' class='endpoints'>85</a>
			</div></div></div>
		</div>