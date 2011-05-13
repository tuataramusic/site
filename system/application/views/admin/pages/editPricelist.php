<div class='content'>

	<h2>������� ��������������</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
	</ul>

	<h3>�������� ������ �� ��������</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>�����</span></a>
	</div><br />

	<div align="right">
		<a href="<?=$selfurl?>showAddDelivery">�������� ������ ��������</a><!-- | <a href="< ?=$selfurl?>showAddCountry">�������� ������</a> -->
	</div><br />
	<form class='admin-inside' id="filterForm" action="<?=$selfurl?>filterEditPricelist" method="POST">
	
		<div class='sorting'>
			<span class='first-title'>�������� ��:</span>
			<select name="pricelist_country_from" class="select">
				<option value="">�������...</option>
				<?if ($countries) : foreach($countries as $country) : ?>
				<option value="<?=$country->country_id?>" <? if ($country->country_id == $filter->pricelist_country_from) : ?>selected="selected"<? endif; ?>><?=$country->country_name?></option>
				<?endforeach; endif;?>
			</select>
			
			<span class='first-title'>�������� �</span>
			<select name="pricelist_country_to" class="select">
				<option value="">�������...</option>
				<?if ($countries) : foreach($countries as $country) : ?>
				<option value="<?=$country->country_id?>" <? if ($country->country_id == $filter->pricelist_country_to) : ?>selected="selected"<? endif; ?>><?=$country->country_name?></option>
				<?endforeach; endif;?>
			</select>
			
			<span class='first-title'>������ ��������:</span>
			<select name="pricelist_delivery" class="select">
				<option value="">�������...</option>
				<?if ($deliveries) : foreach($deliveries as $deliv) : ?>
				<option value="<?=$deliv->delivery_id?>" <? if ($deliv->delivery_id == $filter->pricelist_delivery) : ?>selected="selected"<? endif; ?>><?=$deliv->delivery_name?></option>
				<?endforeach; endif;?>
			</select>
		</div>
	</form>
	<br />


	<!--form class='admin-inside' action='#'>
		
		<div class='table' style="width:40% !important">
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			
			<? if (isset($delivery)) : ?>
			<table>
				<col width='auto' />
				<col width='auto' />
				<col width='10' />
				<tr>
					<th>C����� ��������</th>
					<th>���� ��������</th>
				</tr>
				<tr>
					<td><?=$delivery->delivery_name?></td>
					<td><?=$delivery->delivery_time?></td>
					<td><a  class='delete' href="<?=$selfurl?>deletePricelistCountries/<?=$filter->pricelist_country_from?>/<?=$filter->pricelist_country_to?>">�������</a></td>
				</tr>
			</table>
			<?else:?>
			<table>
				<col width='auto' />
				<tr>
					<td>�� ����� ������.</td>
				</tr>
			</table>
			<?endif;?>
		</div>
	</form-->

	<form class='card' id="pricelistForm" action='<?=$selfurl?>savePricelist/<?=$filter->pricelist_country_from?>/<?=$filter->pricelist_country_to?>/<?=$filter->pricelist_delivery?>' method='POST'>

		<table>
            <tr>
	            <td>��� (��)<br /><a href="javascript:addPrice(1);" >�������� ������ ������</a><br /></td><td>���� ($)</td><td></td>
            </tr>
			<? $index = 0; if (!isset($pricelist) || !$pricelist): $index++;?>
			<tr>
				<td>
					<div class='text-field name-field'><div><input name="new_weight1" id="new_weight1" type="text"/></div></div>
				</td>
				<td>
					<div class='text-field number-field'><div><input name="new_price1" type="text"/></div></div>
				</td>
				<td>
					<div class='text-field price-field'><div><input type="button" value="�������" onclick="javascript:removePrice('new_weight1');"/></div></div>
				</td>
			</tr>
			<? else : foreach ($pricelist as $price): $index++; ?>
			<tr>
				<td>
					<div class='text-field name-field'><div>
						<input name="pricelist_weight<?=$price->pricelist_id?>" id="pricelist_weight<?=$price->pricelist_id?>" type="text" value="<?=$price->pricelist_weight?>" />
					</div></div>						
				</td>
				<td>
					<div class='text-field number-field'><div><input name="pricelist_price<?=$price->pricelist_id?>" type="text" value="<?=$price->pricelist_price?>" /></div></div>
				</td>
				<td>
					<div class='text-field price-field'><div><input type="button" value="�������" onclick="javascript:removePrice('pricelist_weight<?=$price->pricelist_id?>');"/></div></div>
				</td>
			</tr>
			<? endforeach; endif;?>
			<tr>
				<td class='total-price' colspan='3'>
	                <a href="javascript:addPrice(2);" >�������� ������ �����</a><br />
					<div class='submit'><div><input type='submit' value='���������' /></div></div>
				</td>
			</tr>
		</table>
           
		<input type="hidden" id="price_count" value="<?=$index?>" />
	</form>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		addValidation();
		
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});
	});
	
	function addValidation()
	{
		$('#pricelistForm input:text').keypress(function(event){validate_number(event);});
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
	
	function addPrice(t) {
		var price_count = $('#price_count').val();
		
		var tag;
		price_count++;
		var price_html = '<tr><td><div class="text-field name-field"><div><input name="new_weight' + price_count + '" id="new_weight' + price_count + '" type="text"/></div></div></td>	<td><div class="text-field number-field"><div><input name="new_price' + price_count + '" type="text"/></div></div></td>		<td><div class="text-field price-field"><div><input type="button" value="�������" onclick="javascript:removePrice(' + "'" + 'new_weight' + price_count + "'" + ');"/></div></div></td></tr>';

		if(t==2)
		{tag  = $('#pricelistForm table tr:last'); tag.before(price_html);}
		else
		{tag  = $('#pricelistForm table tr:first'); tag.after(price_html);}
		
		
		$('#price_count').val(price_count);
		
		addValidation();
	}
	
	function removePrice(id) {//alert('#' + id);
		$('#' + id).parent().parent().parent().parent().fadeOut('fast');
		$('#' + id).val('');
	}
</script>

