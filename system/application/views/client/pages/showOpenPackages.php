<div class='content'>
	<h2>Посылки, ожидающие отправки</h2>
	<?if($result->e<0):?>
		<em style="color:red;"><?=$result->m?></em>
	<?elseif ($result->e>0):?>
		<em style="color:green;"><?=$result->m?></em>
	<?endif;?>
	<form class='admin-inside' id="packagesForm" action="<?=$selfurl?>joinPackages" method="POST">
	
		<?View::show($viewpath.'elements/div_float_preview_package');?>
		
		<ul class='tabs'>
			<li class='active'><div><a href='<?=$selfurl?>showOpenPackages'>Ожидающие отправки</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
			<li><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
			<li><div><a href="<?=$selfurl?>showSentOrders">Закрытые заказы</a></div></li>
		</ul>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<col width='200' />
				<col width='auto' />
				<col width='300' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<tr>
					<th>№ посылки / Страна</th>
					<th>Выберите способ<br />доставки</th>
					<th>ФИО / Адрес доставки</th>
					<th>Цена доставки</th>
					<th>Статус</th>
					<th>Оплата</th>
					<th>Декларация&nbsp;/<br />Помощь в заполнении</th>
					<th>Комментарии</th>
					<th>Фото</th>
					<th>Объединить в одну посылку ***</th>
				</tr>

				<?if ($packages) : foreach($packages as $package) : ?>
				<tr>
					<td nowrap><b>№ <?=$package->package_id?></b><br/><?=$package->package_date?><br/><?=Func::round2half($package->package_weight)?>кг <?=Func::round2half($package->package_weight) != $package->package_weight ? '('.$package->package_weight.'кг)' : '';?><br />
						<?=$package->package_manager_country?></td>
					<td><? if ($package->package_status == 'payed') : echo($package->package_delivery_name); else : ?>
						<select id="delivery<?=$package->package_id?>" onchange="javascript:updateDelivery('<?=$package->package_id?>');" >
							<option value="0">выбрать...</option>
							<? if ($package->delivery_list) : foreach($package->delivery_list as $delivery) : ?>
							<option value="<?=$delivery->delivery_id?>" <? if ($package->package_delivery == $delivery->delivery_id) : ?>selected="selected"<? endif; ?>><?=$delivery->delivery_name?></option>
							<? endforeach; endif;?>
						</select>
						<? endif; ?></td>
					<td><?=nl2br($package->package_address)?>
						<? if ($package->package_status != 'sent' && $package->package_status != 'payed') : ?>
						<br />
						<a href="<?=$selfurl?>editPackageAddress/<?=$package->package_id?>">Изменить</a>
						<? endif; ?>
					</td>
					<td><? if (!$package->package_delivery_cost) : ?>Выберите способ доставки
						<? elseif ($package->declaration_status == 'not_completed') : ?>Заполните декларацию<? else : ?>
						<?=$package->package_cost?>$
							<a href="javascript:void(0)" onclick="$('#pre_<?=$package->package_id?>').toggle()">Подробнее</a>
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
					<td><? if ($package->package_status == 'processing') : ?>Обрабатывается
						<? elseif ($package->package_status == 'not_available') : ?>Нет в наличии
						<? elseif ($package->package_status == 'not_available_color') : ?>Нет данного цвета
						<? elseif ($package->package_status == 'not_available_size') : ?>Нет данного размера
						<? elseif ($package->package_status == 'not_available_count') : ?>Нет указанного кол-ва
						<? elseif ($package->package_status == 'not_payed') : ?>Не оплачен
						<? elseif ($package->package_status == 'payed') : ?>Оплачен<? endif; ?></td>
					<td>
						<? if ($package->package_status == 'not_payed' && 
								$package->declaration_status != 'not_completed' &&
								$package->package_delivery_cost) : ?><a href="javascript:payItem('<?=$package->package_id?>');">Оплатить</a><? endif; ?>
					</td>
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
					<td>
						<? if ($package->package_status != 'sent' && $package->package_status != 'payed') : ?>
							<input type="checkbox" id="join<?=$package->package_id?>" name="join<?=$package->package_id?>" />
						<? endif; ?>
					</td>
				</tr>
				<?endforeach; endif;?>
				<tr class='last-row'>
					<td colspan='10'>
					<div id="tableComments" style="text-align:left;float:left;">
							* оплата услуг за пересылку<br />
							** помощь в заполнении декларации<br />
							*** за каждое нажатие кнопки Объединить с вашего счета снимается 3$,<br />
							поэтому выбирайте все посылки сразу, которые хотите объединить
						</div>
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='Объединить' /></div></div>
						</div>
					</td>
					<td>
					</td>
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
		$('#packagesForm input:checkbox:nth-child(2n)').change(function() {
			if ($(this).attr('checked') &&
				confirm('Запросить помощь партнера в заполнении декларации выбранной посылки?'))
			{
				window.location = '<?=$selfurl?>addDeclarationHelp/' + $(this).attr('id').replace('help', '');	
			}
		});
	});
	
	function setRel(id){
		$("a[rel*='lightbox_"+id+"']").lightBox();
		var aa = $("a[rel*='lightbox_"+id+"']");
		$(aa[0]).click();
	}

	function payItem(id) {
		if (confirm("Оплатить посылку №" + id + "?")){
			window.location.href = '<?=$selfurl?>payPackage/' + id;
		}
	}
	
	function updateDelivery(id) {
		var selectedDelivery = $('#delivery' + id + ' option:selected').val();
		
		if (selectedDelivery != '0' &&
			confirm("Изменить способ доставки посылки №" + id + "?"))
		{			
			window.location.href = '<?=$selfurl?>updatePackageDelivery/' + id + '/' + selectedDelivery;
		}
	}
</script>