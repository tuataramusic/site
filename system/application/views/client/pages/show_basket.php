<div class='content'>
	<h3>Формирование заказа</h3>

	<?if(isset($result->m) && $result->m):?><em class="order_result"><?=$result->m?></em><br/><?endif;?>
	
	<?View::show($viewpath.'elements/div_float_help');?>
	
	<?View::show($viewpath.'elements/div_float_manual');?>
	
	<fieldset class='admin-inside'>
<!--		<legend>Продолжить шопинг</legend>-->
<!--		<div align="left"><a href="javascript:lay();">Помощь в формировании заказа</a></div>
		<div align="left"><a href="javascript:lay2();">Формировать заказ вручную</a></div>-->
		<div class="submit">
			<div>
				<input type="button" onclick="lay2()" name="add" value="Добавить товар" sty le="width:125px !important;">
			</div>
		</div>
	</fieldset>
	<br />

	
	<form class='admin-inside' action="<?=$selfurl?>checkout" method="POST">
	
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			
			<?if($Odetails):?>
			<table>
				<tr>
					<td>Товар</td>
					<td>Название магазина</td>
					<td>Страна</td>
					<td>Наименование товара</td>
					<td>Параметры товара</td>
					<td>Скриншот</td>
					<td >Удалить</td>
				</tr>
				<?foreach ($Odetails as $Odetail):?>
				<tr>
					<td><?=$Odetail->odetail_id?></td>
					<td><?=$Odetail->odetail_shop_name?></td>
					<td><?=$Odetail->country_name?></td>
					<td><div style="width:250px; height:100%; overflow-x:auto;"><?=$Odetail->odetail_product_name?></div></td>
					<td>
						Цвет: <?=$Odetail->odetail_product_color?><br/>
						Кол-во: <?=$Odetail->odetail_product_amount?><br/>
						Размер: <?=$Odetail->odetail_product_size?>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="setRel(<?=$Odetail->odetail_id?>)">
							<img border="0" src="<?=$selfurl?>showScreen/<?=$Odetail->odetail_id?>" width="200px" height="150px"/>
							<a rel="lightbox_<?=$Odetail->odetail_id?>" href="<?=$selfurl?>showScreen/<?=$Odetail->odetail_id?>" style="display:none;">Посмотреть</a>
						</a>
					</td>
					<td align="center"><a href="<?=$selfurl?>deleteDetail/<?=$Odetail->odetail_id?>"><img title="Удалить" border="0" src="/static/images/delete.png"></a></td>
				</tr>
				<?endforeach;?>
				<tr class='last-row'>
					<td colspan='9'>
						<div class='float'>	
							<div class='submit'><div><input type='submit' name="add" value='Сформировать заказ' style="width:125px !important;" /></div></div>
						</div>
					</td>
					<td>
					</td>
				</tr>
			</table>
			<?endif;?>
			<?/*
			<table>
				<tr>
					<td>Название магазина:</td>
					<td><input type="text" name="shop" value="" size=40></td>
				</tr>
				<tr>
					<td>Адрес сайта:</td>
					<td><input type="text" name="url" value="http://" size=40></td>
				</tr>
				<tr class='last-row'>
					<td colspan='9'>
						<div class='float'>	
							<div class='submit'><div><input type='submit' name="add" value='Добавить' /></div></div>
						</div>
					</td>
					<td>
					</td>
				</tr>
			</table>
			*/?>
		</div>
	</form>
</div>
<script>
	function setRel(id){
		$("a[rel*='lightbox_"+id+"']").lightBox();
		var aa = $("a[rel*='lightbox_"+id+"']");
		$(aa[0]).click();
	}
</script>