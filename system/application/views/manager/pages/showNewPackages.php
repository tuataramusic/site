<div class='content'>
	<h2>Аккаунт Партнера</h2>
	<?if(isset($result->m) && $result->m):?><em style="color:red;"><?=$result->m?></em><br/><?endif;?>
	<?View::show($viewpath.'elements/div_float_preview_package');?>
	<?View::show($viewpath.'elements/div_float_upload_package');?>
	
	
	<h3>Новые посылки</h3>
	<form class='admin-inside' action='<?=$selfurl?>updateNewPackagesStatus' method="post">
	
		<ul class='tabs'>
			<li><div><a href='<?=$selfurl?>showAddPackage'>Добавить посылку</a></div></li>
			<li class='active'><div><a href='<?=$selfurl?>showNewPackages'>Новые</a></div></li>
			<li><div><a href='<?=$selfurl?>showPayedPackages'>Оплаченные</a></div></li>
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
				<col width='auto' />
				<tr>
					<th>Номер клиента</th>
					<th>Номер посылки, заказ</th>
					<th>Ф.И.О., адрес доставки</th>
					<th>Цена доставки</th>
					<th>Комментарии</th>
					<th>Статус</th>
					<th>Декларация</th>
					<th>Фото</th>
					<th class='last-child'></th>
				</tr>
				<?if ($packages) : foreach($packages as $package) : ?>
				<tr>
					<td><b>№ <?=$package->package_client?></b></td>
					<td nowrap>
						<b>№ <?=$package->package_id?></b><br /><?=$package->package_join_ids?'(объединены посылки: '.$package->package_join_ids.')<br />':''?><?=$package->package_date?><br /><?=Func::round2half($package->package_weight)?>кг <?=Func::round2half($package->package_weight) != $package->package_weight ? '('.$package->package_weight.'кг)' : '';?><br />
						<?=$package->package_day == 0 ? "" : 'Прошло:<br />'.$package->package_day.' '.humanForm((int)$package->package_day, "день", "дня", "дней")?> <?=$package->package_hour == 0 ? "" : $package->package_hour.' '.humanForm((int)$package->package_hour, "час", "часа", "часов")?>
					</td>
					<td><?=nl2br($package->package_address)?>
						<br />
						<a href="<?=$selfurl?>editPackageAddress/<?=$package->package_id?>">Изменить</a>
					</td>
					<td><?=$package->package_cost?>$</td>
					<td><? if ($package->comment_for_manager) : ?>
						Добавлен новый комментарий<br />
					<? endif; ?>
					<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">Посмотреть / добавить</a>
					</td>
					<td>Не оплачен</td>
					<td><? if ($package->declaration_status == 'not_completed') : ?>
						Не заполнена
					<? elseif ($package->declaration_status == 'completed') : ?>
						Заполнена <input type="checkbox" id="help<?=$package->package_id?>" name="help<?=$package->package_id?>"><br />
						<a href="<?=$selfurl?>previewDeclaration/<?=$package->package_id?>">Посмотреть</a>
					<? else : ?>
						<a href="<?=$selfurl?>showDeclaration/<?=$package->package_id?>">Заполнить самостоятельно</a><br />
						<a href="<?=$selfurl?>previewDeclaration/<?=$package->package_id?>">Посмотреть</a>
					<? endif; ?></td>
					<td>
						<a href="javascript:uploadPackFoto(<?=$package->package_id?>);">Добавить</a>
						</br></br>
						<? if (isset($packFotos[$package->package_id])): ?>
							<a href="javascript:void(0)" onclick="setRel(<?=$package->package_id?>)" >Посмотреть (<?=count($packFotos[$package->package_id]);?> фото)
								<?foreach ($packFotos[$package->package_id] as $packFoto):?>
									<a rel="lightbox_<?=$package->package_id?>" href="/manager/showPackageFoto/<?=$package->package_id?>/<?=$packFoto?>" style="display:none">Посмотреть</a>
								<?endforeach;?>
							</a>
						<? endif; ?>
					</td>
					<td>
						<a  class='delete' href="javascript:deleteItem('<?=$package->package_id?>');"><img title="Удалить" border="0" src="/static/images/delete.png"></a>
					</td>
				</tr>
				<?endforeach; endif;?>
				<tr class='last-row'>
					<td colspan='9'>
						<br />
						<div class='float'>	
							<label for="declaration_status">Выбрать статус декларации:</label>
							<select id="declaration_status" name="declaration_status" onchange="javascript:updateStatus();">
								<option value="-1">выбрать...</option>
								<option value="not_completed">Не заполнена</option>
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
Новые посылки
<form id="packagesForm" action="<?=$selfurl?>updateNewPackagesStatus" method="POST">
	<div id="Deliveries" align="center">
		<table>
			<tr>
				<th>№ клиента</th>
				<th>№ посылки</th>
				<th>ФИО / Адрес доставки</th>
				<th>Цена доставки</th>
				<th>Комментарии</th>
				<th>Статус</th>
				<th>Декларация</th>
				<th>Удалить посылку</th>
			</tr>
			<?if ($packages) : foreach($packages as $package) : ?>
			<tr>
				<td><?=$package->package_client?></td>
				<td><?=$package->package_id?> <?=$package->package_date?> <?=$package->package_weight?>кг<br />
					Прошло <?=$package->package_age ?> часов</td>
				<td><?=$package->package_address?>
					<br />
					<a href="<?=$selfurl?>editPackageAddress/<?=$package->package_id?>">Изменить</a>
				</td>
				<td><?=$package->package_cost?>р</td>
				<td><? if ($package->comment_for_manager) : ?>
					Добавлен новый комментарий<br />
				<? endif; ?>
				<a href="<?=$selfurl?>showPackageComments/<?=$package->package_id?>">Посмотреть / добавить</a>
				</td>
				<td>Не оплачен</td>
				<td><? if ($package->declaration_status == 'not_completed') : ?>
					Не заполнена
				<? elseif ($package->declaration_status == 'completed') : ?>
					Заполнена <input type="checkbox" id="help<?=$package->package_id?>" name="help<?=$package->package_id?>">
				<? else : ?>
					<a href="<?=$selfurl?>showDeclaration/<?=$package->package_id?>">Заполнить самостоятельно</a>
				<? endif; ?></td>
				<td>
					<a href="javascript:deleteItem('<?=$package->package_id?>');"></a>
				</td>
			</tr>
			<?endforeach; endif;?>
		</table>

		<label for="declaration_status">Выбрать статус декларации:</label>
		<select id="declaration_status" name="declaration_status" onchange="javascript:updateStatus();">
			<option value="-1">выбрать...</option>
			<option value="not_completed">Не заполнена</option>
		</select>
	</div>
</form>
*/?>
<script type="text/javascript">
	function deleteItem(id){
		if (confirm("Вы уверены, что хотите удалить посылку №" + id + "?")){
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
				alert('Выберите посылки со незаполненными декларациями.');
				return;
			}
			
			if (confirm('Вы уверены, что хотите изменить статус деклараций выбранных посылок на "' 
				+ $(selectedStatus).text() + '"?'))
			{
				document.getElementById('packagesForm').submit();
			}
		}
	}
</script>