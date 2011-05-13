		<div class='content'>
			<h2>Отправленные посылки</h2>
			<form class='admin-inside' action='#'>
			
				<?View::show($viewpath.'elements/div_float_preview_package');?>
				
				<ul class='tabs'>
					<li><div><a href='<?=$selfurl?>showOpenPackages'>Ожидающие отправки</a></div></li>
					<li class='active'><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
					<li><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
					<li><div><a href="<?=$selfurl?>showSentOrders">Закрытые заказы</a></div></li>
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
							<th>Номер посылки</th>
							<th>ФИО / Адрес доставки</th>
							<th>Способ доставки</th>
                           <th>Декларация&nbsp;/<br />Помощь в заполнении</th>
					<th>Комментарии</th>
					<th>Фото</th>
							<th>Оплачено</th>
							<th>Отслеживание (Tracking №)</th>
						</tr>

						<?if ($packages) : foreach($packages as $package) : ?>
						<tr>
							<td nowrap><b>№ <?=$package->package_id?></b><br/><?=$package->package_date?><br /><?=Func::round2half($package->package_weight)?>кг <?=Func::round2half($package->package_weight) != $package->package_weight ? '('.$package->package_weight.'кг)' : '';?><br />
						<?=$package->package_manager_country?></td>
							<td><?=$package->package_address?></td>
							<td><?=$package->package_delivery_name ?></td>
                            <td><? if ($package->declaration_status == 'not_completed') : ?>
						Не заполнена
						<a href="<?=$selfurl?>showDeclaration/<?=$package->package_id?>">Заполнить</a>
					<? elseif ($package->declaration_status == 'completed' ||
								($package->declaration_status == 'help') && $package->package_declaration_cost) : ?>
						Заполнена<br />
						<a href="<?=$selfurl?>showDeclaration/<?=$package->package_id?>">Посмотреть</a>
					<? else : ?>
						Помощь в заполнении<br />
						<a href="<?=$selfurl?>showDeclaration/<?=$package->package_id?>">Посмотреть</a>
					<? endif; ?></td>
					<td><? if ($package->comment_for_client) : ?>
						Добавлен новый комментарий<br />
					<? endif; ?>
					<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">Посмотреть</a>
					</td>
					<td>
						<? if (isset($packFotos[$package->package_id])): ?>
							<a href="javascript:void(0)" onclick="setRel(<?=$package->package_id?>)">
								Посмотреть <?=count($packFotos[$package->package_id]);?> фото
								<?foreach ($packFotos[$package->package_id] as $packFoto):?>
									<a rel="lightbox_<?=$package->package_id?>" href="/client/showPackageFoto/<?=$package->package_id?>/<?=$packFoto?>" style="display:none;">Посмотреть</a>
								<?endforeach;?>
							</a>
						<? endif; ?>
					</td>
							<td><?=$package->package_cost?>$</td>
							<td>
								<b>
								<?=$package->package_trackingno?>
								<?if (file_exists($_SERVER['DOCUMENT_ROOT'].'/upload/packages/'.$package->package_manager.'/'.$package->package_id.'.jpg')):?>
									<div align="left"><a href="javascript:previewPack('/client/showPackageFoto/<?=$package->package_id?>');">Посмотреть</a></div>
								<?endif;?>
								</b>
							</td>
						</tr>
						<?endforeach; endif;?>
						<tr class='last-row'>
							<td colspan='9'>
								<div class='float'>	
									<div class='submit'><div>&nbsp;</div></div>
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