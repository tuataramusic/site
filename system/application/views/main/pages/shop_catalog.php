		<div class='content'>
			<h2>Каталог интернет магазинов</h2>
			<?if (isset($is_added)):?>
				<div class='back'><a href='#'><span>Магазин успешно добавлен</span></a></div>
			<?endif;?>
			<div class='table'>
					<div class='angle angle-lt'></div>
					<div class='angle angle-rt'></div>
					<div class='angle angle-lb'></div>
					<div class='angle angle-rb'></div>
			<? $all = count($Categories); if ($all):?>
				<table class="shop_catalog">
				<? $i = 0; foreach ($Categories as $Category):?>
				<? $i++; ?>
				<td st yle="width: 150px;"><b><a href='<?=BASEURL?>main/showCategory/<?=$Category->scategory_id?>'><?=$Category->scategory_name?></a></b> (<?=$Category->count?>)<br/></td>
				<?if ($i != $all && $i%2==0):?>
					</tr>
				<tr>
				<?elseif ($i == $all):?>
					</tr>
						
				<?endif;?>
				<? endforeach; ?>
						<?if ($is_authorized):?>
						<a href='<?=BASEURL?>main/showAddShop'>Добавить новый магазин</a>
						<?endif;?>
					</td>
				</tr>
				</table></div>
			<?endif;?>
		</div>

