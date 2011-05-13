<div class='content'>
	<h2>Каталог магазинов</h2>
	<div class="back">
		<a href="javascript:history.back();" class="back"><span>Назад</span></a>
	</div><br />
	<center>
		<h3><?=$category->scategory_name?></h3>
	</center>
		<?if ($shops):?>
		Отсортировать по:&nbsp;
		<a href='<?=BASEURL?>main/showCategory/<?=$category->scategory_id?>/country'>Стране</a> 
		<a href='<?=BASEURL?>main/showCategory/<?=$category->scategory_id?>/comments'>Отзывам</a>
	<?endif;?>
	<?if ($is_authorized):?>
		<div align="right" style="float:right;">
			<a href='<?=BASEURL?>main/showAddShop'>Добавить новый магазин</a>
		</div>
	<?endif;?>
	<div>&nbsp;</div>
	<form class='admin-inside' action='#'>
		
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
					<th>№</th>
					<th>Магазин</th>
					<th>Страна</th>
					<th>Описание</th>
					<th>Отзывы</th>
				</tr>
				<?if ($shops):?>
					<?foreach ($shops as $shop):?>
					<tr>
						<td><?=$shop->shop_id?></td>
						<td><a href='<?=$shop->shop_name?>'><?=$shop->shop_name?></a></td><!--<?=BASEURL?>main/showShop/<?=$shop->shop_id?> -->
						<td><?=$countries[$shop->shop_country]?></td>
						<td><?=$shop->shop_desc?></td>
						<td><a href='<?=BASEURL?>main/showShop/<?=$shop->shop_id?>'><?=$shop->count?></a></td>
					</tr>
					<?endforeach;?>	
				<?else:?>
					<tr>
						<td colspan="5">Магазинов нет!</td>
					</tr>
				<?endif;?>
			</table>
		</div>
	</form>

	<div class='pages'><div class='block'><div class='inner-block'>
		<a href='#' class='endpoints'>1</a><a href='#'>2</a><a href='#'>3</a><span>...</span><a href='#'>17</a><span>18</span><a href='#'>19</a><span>...</span><a href='#'>83</a><a href='#'>84</a><a href='#' class='endpoints'>85</a>
	</div></div></div>
</div>


<?/*
<a href='<?=BASEURL?>main/showShopCatalog'>Назад</a>
<center><b><?=$category->scategory_name?></b>
<?if ($is_authorized):?>
<br/><a href='<?=BASEURL?>main/showAddShop'>Добавить новый магазин</a>
<?endif;?>
<br/>
<?if ($shops):?>
Отсортировать по: <a href='<?=BASEURL?>main/showCategory/<?=$category->scategory_id?>/country'>Стране</a> <a href='<?=BASEURL?>main/showCategory/<?=$category->scategory_id?>/comments'>Отзывам</a>
<table>
	<tr>
		<td>№</td>
		<td>Магазин</td>
		<td>Страна</td>
		<td>Описание</td>
		<td>Отзывы</td>
	</tr>
	<?foreach ($shops as $shop):?>
	<tr>
		<td><?=$shop->shop_id?></td>
		<td><a href='<?=BASEURL?>main/showShop/<?=$shop->shop_id?>'><?=$shop->shop_name?></a></td>
		<td><?=$countries[$shop->shop_country]?></td>
		<td><?=$shop->shop_desc?></td>
		<td><?=$shop->count?></td>
	</tr>
	<?endforeach;?>	
</table>
<?else:?>
Магазинов нет!
<?endif;?>
</center>
*/?>