<?/*
<form id="filterForm" action="<?=$selfurl?>filterNewPackages" method="POST">
	<div id="packageFilter" align="center">
		Отфильтровать по партнеру <select name="manager_user">
			<option value="">выбрать...</option>
			<?if ($managers) : foreach($managers as $manager) : ?>
			<option value="<?=$manager->manager_user?>" <? if ($manager->manager_user == $filter->manager_user) : ?>selected="selected"<? endif; ?>><?=$manager->user_login?></option>
			<?endforeach; endif;?></select> за <select name="period">
			<option value="">все</option>
			<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>день</option>
			<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>неделю</option>
			<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>месяц</option>
		</select> Поиск посылки <input type="text" maxlength="11" name="search_id" value="<?=$filter->search_id?>"/> по <select name="id_type">
			<option value="">выбрать...</option>
			<option value="package" <? if ('package' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру посылки</option>
			<option value="client" <? if ('client' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру пользователя</option>
		</select>
	</div>
</form>

<form id="packagesForm" action="<?=$selfurl?>updateNewPackagesStatus" method="POST">
	<div id="Deliveries" align="center">
		<table>
			<tr>
				<th>Партнер / Страна</th>
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
				<td><?=$package->package_manager_login?> / <?=$package->package_manager_country?></td>
				<td><?=$package->package_client?></td>
				<td><?=$package->package_id?> <?=$package->package_date?> <?=$package->package_weight?>кг<br />
					Прошло <?=$package->package_age ?> часов</td>
				<td><?=$package->package_address?>
					<br />
					<a href="<?=$selfurl?>editPackageAddress/<?=$package->package_id?>">Изменить</a>
				</td>
				<td><? if (!$package->package_delivery_cost) : ?>Способ доставки не выбран<? else : ?>
					<?=$package->package_cost?>$
					<hr />
					<?= $package->package_delivery_cost ?>$
					<br />+<br />
					*<?= $package->package_comission ?>$
					<? if ($package->package_declaration_cost) : ?>
					<br />+<br />
					**<?= $package->package_declaration_cost ?>$
					<? endif; ?>
					<? if ($package->package_join_cost) : ?>
					<br />+<br />
					***<?= $package->package_join_cost ?>$
					<? endif; endif; ?></td>
				<td><? if ($package->comment_for_manager || $package->comment_for_client) : ?>
					Добавлен новый комментарий<br />
				<? endif; ?>
				<a href="<?=BASEURL?>admin/showPackageComments/<?=$package->package_id?>">Посмотреть</a>
				</td>
				<td><select name="package_status<?=$package->package_id?>">
						<option value="not_payed" selected="selected">Не оплачен</option>
						<option value="payed">Оплачен</option>
						<option value="sent">Отправлен</option>
					</select></td>
				<td><? if ($package->declaration_status == 'not_completed') : ?>
					Не заполнена
				<? elseif ($package->declaration_status == 'completed') : ?>
					Заполнена
				<? else : ?>
					Заполнить самостоятельно
				<? endif; ?><br /><input type="checkbox" id="help<?=$package->package_id?>" name="help<?=$package->package_id?>"></td>
				<td>
					<a href="javascript:deleteItem('<?=$package->package_id?>');"></a>
				</td>
			</tr>
			<?endforeach; endif;?>
		</table>

		<input type="submit" value="Сохранить" />
		<label for="declaration_status">Выбрать статус декларации:</label>
		<select id="declaration_status" name="declaration_status" onchange="javascript:updateStatus();">
			<option value="-1">выбрать...</option>
			<option value="completed">Заполнена</option>
			<option value="not_completed">Не заполнена</option>
		</select>
	</div>
</form>
*/?>


	
		<div class='content'>
			<h2>Аккаунт администратора</h2>
			<ul class='admin-buttons'>
				<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
				<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
				<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
			</ul>
			<h3>Новые посылки</h3>
			
			<?Breadcrumb::showCrumbs();?>
			
			<form class='admin-sorting' id="filterForm" action="<?=$selfurl?>filterNewPackages" method="POST">
				<div class='sorting'>
					<span class='first-title'>Сортировать по партнеру:</span>
					<select name="manager_user" class='select first-input'>
						<option value="">выбрать...</option>
						<?if ($managers) : foreach($managers as $manager) : ?>
						<option value="<?=$manager->manager_user?>" <? if ($manager->manager_user == $filter->manager_user) : ?>selected="selected"<? endif; ?>><?=$manager->user_login?></option>
						<?endforeach; endif;?>
					</select>

					<span>за:</span>
					<select name="period" class='select'>
						<option value="">все</option>
						<option value="day" <? if ('day' == $filter->period) : ?>selected="selected"<? endif; ?>>день</option>
						<option value="week" <? if ('week' == $filter->period) : ?>selected="selected"<? endif; ?>>неделю</option>
						<option value="month" <? if ('month' == $filter->period) : ?>selected="selected"<? endif; ?>>месяц</option>
					</select> 
				</div>
				<div class='sorting'>
					<span class='first-title'>Поиск заказа:</span>
					<div class='text-field first-input'><div><input type='text' maxlength="11" name="search_id" value="<?=$filter->search_id?>" value='Введите текст поиска' /></div></div>
					<span>по:</span>
					<select name="id_type" class='select'>
						<option value="">выбрать...</option>
						<option value="package" <? if ('package' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру посылки</option>
						<option value="client" <? if ('client' == $filter->id_type) : ?>selected="selected"<? endif; ?>>Номеру пользователя</option>
					</select>	
				</div>
			</form>


			<form class='admin-inside' id="packagesForm" action="<?=$selfurl?>updateNewPackagesStatus" method="POST">
				
				<ul class='tabs'>
					<li class='active'><div><a href='<?=$selfurl?>showNewPackages'>Новые</a></div></li>
					<li><div><a href='<?=$selfurl?>showPayedPackages'>Оплаченные</a></div></li>
					<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
					<li><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
					<li><div><a href='<?=$selfurl?>showClients'>Клиенты</a></div></li>
					<li><div><a href='<?=$selfurl?>showPartners'>Партнеры</a></div></li>
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
						<col width='auto' />
						<tr>
							<th>Номер посылки</th>
							<th>Партнер, страна</th>
							<th>Номер клиента</th>
							<th>Номер посылки, заказ</th>
							<th>Ф.И.О., адрес доставки</th>
							<th>Цена доставки</th>
							<th>Статус</th>
							<th>Комментарии</th>
							<th>Декларация</th>
							<th class='last-child'></th>
						</tr>
						<?if ($packages) : foreach($packages as $package) : ?>
						<tr>
							<td><b>№ <?=$package->package_manager?></b></td>
							<td><?=$package->package_manager_login?>, <?=$package->package_manager_country?></td>
							<td><b>№ <?=$package->package_client?></b></td>
							<td nowrap>
								<b>№ <?=$package->package_id?></b><br /><?=$package->package_date?><br /><?=$package->package_weight?>кг<br />
								Прошло:<br /><?=$package->package_day == 0 ? "" : $package->package_day.' '.humanForm((int)$package->package_day, "день", "дня", "дней")?> <?=$package->package_hour == 0 ? "" : $package->package_hour.' '.humanForm((int)$package->package_hour, "час", "часа", "часов")?>
							</td>
							<td><?=$package->package_address?> <a href='<?=$selfurl?>editPackageAddress/<?=$package->package_id?>'>Подробнее</a></td>
							<td>
								<? if (!$package->package_delivery_cost) : ?>Способ доставки не выбран<? else : ?>
								<?=$package->package_cost?>$
								<a href="javascript:void(0)" onclick="$('#pre_<?=$package->package_id?>').toggle()">Подробнее</a>
								<pre class="pre-href" id="pre_<?=$package->package_id?>">
									<?= $package->package_delivery_cost ?>$ <p>+
									*<?= $package->package_comission ?>$
									<? if ($package->package_declaration_cost) : ?>	+
									**<?= $package->package_declaration_cost ?>$
									<? endif; ?>
									<? if ($package->package_join_cost) : ?>+
									***<?= $package->package_join_cost ?>$
									<? endif;?>
								</pre>
								<?endif; ?>
							</td>
							<td>
								<select name="package_status<?=$package->package_id?>">
									<option value="not_payed" selected="selected">Не оплачен</option>
									<option value="payed">Оплачен</option>
									<option value="sent">Отправлен</option>
								</select></td>
							<td><? if ($package->comment_for_manager || $package->comment_for_client) : ?>
								Добавлен новый комментарий<br />
								<? endif; ?>
								<a href="<?=BASEURL?>admin/showPackageComments/<?=$package->package_id?>">Посмотреть</a></td>
							<td><? if ($package->declaration_status == 'not_completed') : ?>
										Не заполнена
									<? elseif ($package->declaration_status == 'completed') : ?>
										Заполнена
									<? else : ?>
										Заполнить самостоятельно
									<? endif; ?><br /><input type="checkbox" id="help<?=$package->package_id?>" name="help<?=$package->package_id?>">
							</td>
							<td><a href="javascript:deleteItem('<?=$package->package_id?>');" class='delete'><img title="Удалить" border="0" src="/static/images/delete.png"></a></td>
						</tr>
						<?endforeach; endif;?>
						<tr class='last-row'>
							<td colspan='9'>
								<div class='float'>	
									<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
									<span>Выбрать статус декларации:</span>
									<select class='select' id="declaration_status" name="declaration_status" onchange="javascript:updateStatus();">
										<option value="-1">выбрать...</option>
										<option value="completed">Заполнена</option>
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


<script type="text/javascript">
	$(document).ready(function() {
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});
		
		$('#filterForm input:text').keypress(function(event){validate_number(event);});
	});
	
	function deleteItem(id){
		if (confirm("Вы уверены, что хотите удалить посылку №" + id + "?")){
			window.location.href = '<?=$selfurl?>deletePackage/' + id;
		}
	}
	
	function updateStatus(id){
		var selectedStatus = $('#declaration_status option:selected');
		if (selectedStatus.val() != '-1'){
			if ($('#packagesForm input:checkbox:checked').size() == 0){
				alert('Выберите посылки со статусом декларации "Заполнить самостоятельно".');
				return;
			}
			
			if (confirm('Вы уверены, что хотите изменить статус деклараций выбранных посылок на "' 
				+ $(selectedStatus).text() + '"?'))
			{
				document.getElementById('packagesForm').submit();
			}
		}
	}
	
	function validate_number(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		var regex = /[0-9]|\./;
		if( !regex.test(key) ) {
			theEvent.returnValue = false;
			theEvent.preventDefault();
		}
	}
</script>