<div class='content'>
	<h2>Каталог магазинов</h2>
	<center>
		<h3>Редактирование магазина</h3>
		
		<?if (!isset($result)){
			$result=new stdClass();
			$result->e='';
			$result->m='';
			$result->d='';
		}
		
		if ($result->m):?>
			<div align="center"><em style="color:red;">
				<?=$result->m?>
			</div></em>
		<?endif;?>
	</center>
	
	<form class='admin-inside' action='<?=BASEURL?>main/saveShop/<?=$shop->shop_id?>' method="POST">
    	<input type="hidden" name="shop_id" value="<?=$shop->shop_id?>" />
		
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
					<td>Ссылка на магазин:</td>
					<td><input class="input" type="text" name="shop_name" style="width: 252px;"  value="<?=$shop->shop_name;?>"/></td>
				</tr>
				<tr>
					<td>Страна:</td>
					<td>
						<select name="country" class="select" style="width: 252px;">
							<option value="0">---</option>
							<?if (count($countries)>0): foreach ($countries as $country):?>
								<option <?= ($shop->shop_country==$country->country_id) ? 'selected' :'';?> value="<?=$country->country_id;?>"><?=$country->country_name?></option>
							<?endforeach; endif;?>	
						</select>
					</td>
				</tr>
				<tr>
					<td>Выбор категории:</td>
					<td>
						<select name="scategory" class="select" style="width: 252px;">
							<option value="0">---</option>
							<?if (count($scategories)>0): foreach ($scategories as $category):?>
								<option <?= ($shop->shop_scategory==$category->scategory_id) ? 'selected' :'';?> value="<?=$category->scategory_id;?>"><?=$category->scategory_name?></option>
							<?endforeach; endif;?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Описание:</td>
					<td><textarea name="shop_desc" rows="6" cols="42"><?=$shop->shop_desc;?></textarea></td>
				</tr>
				<tr class='last-row'>
					<td colspan='2'>
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='Записать' /></div></div>
						</div>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
	</form>
