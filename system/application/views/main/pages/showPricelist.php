<?
	
	if (isset($pricelist) && $pricelist) 
	{
		foreach($pricelist as $delivery) 
		{
			$deliveries[$delivery->delivery_id]['name']=$delivery->delivery_name;
			$deliveries[$delivery->delivery_id]['time']=$delivery->delivery_time;
			$deliveries[$delivery->delivery_id]['items'][]=array('weight'=>$delivery->pricelist_weight,'price'=>$delivery->pricelist_price);
		}
	}
?>

<div class='content' style='text-align:center'>
	<h2>Тарифы на доставку</h2>
		<form   id="filterForm" action="<?=$selfurl?>filterPricelist" method="POST">
			<div id="orderFilter" style="margin:0 auto; width:225px;">
				Доставка из: 
				<select class="select" name="pricelist_country_from" style="width: 225px;">
					<option value="">выбрать...</option>
					<?if ($from_countries) : foreach($from_countries as $country) : ?>
						<option value="<?=$country->country_id?>" <? if ($country->country_id == $filter->pricelist_country_from) : ?>selected="selected"<? endif; ?>><?=$country->country_name?></option>
					<?endforeach; endif;?></select> 
				Доставка в: 
				<select class="select" name="pricelist_country_to" style="width: 225px;">
					<option value="">выбрать...</option>
					<?if ($to_countries) : foreach($to_countries as $country) : ?>
						<option value="<?=$country->country_id?>" <? if ($country->country_id == $filter->pricelist_country_to) : ?>selected="selected"<? endif; ?>><?=$country->country_name?></option>
					<?endforeach; endif;?>
				</select>
			</div>
		</form>
		<br />
		<br />
		
		<form class='admin-inside' action='#'>
		<?
								
		if (isset($deliveries)) 
		{
			foreach($deliveries as $id=>$delivery)
			{
		?>			
			<div class='table' style="width:250px;float:left; margin:5px;">
				<div class='angle angle-lt'></div>
				<div class='angle angle-rt'></div>
				<div class='angle angle-lb'></div>
				<div class='angle angle-rb'></div>
				<div style="text-align:center;">
					<span style="font-size:1.6em;font-weight:bold;"><?=$delivery['name']?></span><br>  <i>Срок доставки: <?=$delivery['time']?></i>               
				</div>
				<table>
				<tr><td>Вес (кг)</td><td>Цена ($)</td></tr>
					<?
		 			foreach($delivery['items'] as $val) 
					{
				?>
						<tr>
							<td><?=$val['weight']?></td>
							<td><?=$val['price']?></td>
						</tr>
				<? 
					}				
				?>				
				</table>
			</div>
			<? 
			}
		}
			?>	
		</form>
</div>
<div style="clear:both;"></div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});
	});
</script>
<? 
				?>			