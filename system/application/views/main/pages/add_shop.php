<div class='content'>
	<h2>Каталог магазинов</h2>
	<center>
		<h3>Добавление нового магазина</h3>
		
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
	
	<form class='admin-inside' action='<?=BASEURL?>main/addShop' method="POST">
		
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
					<td><input class="input" type="text" name="sname" style="width: 252px;"  value="<?=$result->d ? $result->d->shop_name :'http://';?>"/></td>
				</tr>
				<tr>
					<td>Страна:</td>
					<td>
						<select name="scountry" class="select" style="width: 252px;">
							<option value="0">---</option>
							<?if (count($countries)>0): foreach ($countries as $country):?>
								<option <?= ($result->d && $result->d->shop_country==$country->country_id) ? 'selected' :'';?> value="<?=$country->country_id;?>"><?=$country->country_name?></option>
							<?endforeach; endif;?>	
						</select>
					</td>
				</tr>
				<tr>
					<td>Выбор категории:</td>
					<td>
						<select name="scategory" class="select" style="width: 252px;">
							<option value="0">---</option>
							<?if (count($categories)>0): foreach ($categories as $category):?>
								<option <?= ($result->d && $result->d->shop_scategory==$category->scategory_id) ? 'selected' :'';?> value="<?=$category->scategory_id;?>"><?=$category->scategory_name?></option>
							<?endforeach; endif;?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Описание:</td>
					<td><textarea name="sdescription" rows="6" cols="42"><?=$result->d ? $result->d->shop_desc :'';?></textarea></td>
				</tr>
				<tr class='last-row'>
					<td colspan='2'>
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='Добавить' /></div></div>
						</div>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
	</form>

	
	
<?/*
<center>
	<b>Добавление нового магазина</b><br/>
	
	<?if (!isset($result)){
		$result=new stdClass();
		$result->e='';
		$result->m='';
		$result->d='';
	}
	
	if ($result->m):?>
		<div align="center">
			<?=$result->m?>
		</div>
	<?endif;?>
	
	
	<form action='<?=BASEURL?>main/addShop' method='POST'>
	<table>
		<tr>
			<td>Ссылка на магазин:</td>
			<td><input type="text" name="sname" style="width: 252px;"  value="<?=$result->d ? $result->d->shop_name :'http://';?>"/></td>
		</tr>
		<tr>
			<td>Страна:</td>
			<td>
				<select name="scountry" style="width: 252px;">
					<option value="0">---</option>
					<?if (count($countries)>0): foreach ($countries as $country):?>
						<option <?= ($result->d && $result->d->shop_country==$country->country_id) ? 'selected' :'';?> value="<?=$country->country_id;?>"><?=$country->country_name?></option>
					<?endforeach; endif;?>	
				</select>
			</td>
		</tr>
		<tr>
			<td>Выбор категории:</td>
			<td>
				<select name="scategory" style="width: 252px;">
					<option value="0">---</option>
					<?if (count($categories)>0): foreach ($categories as $category):?>
						<option <?= ($result->d && $result->d->shop_scategory==$category->scategory_id) ? 'selected' :'';?> value="<?=$category->scategory_id;?>"><?=$category->scategory_name?></option>
					<?endforeach; endif;?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Описание:</td>
			<td><textarea name="sdescription" rows="6" cols="33"><?=$result->d ? $result->d->shop_desc :'';?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align: center;"><input type="submit" name="add" value="Добавить"/></td>
		</tr>
	</table>
	</form>
	
</center>
*/?>