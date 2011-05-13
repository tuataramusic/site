<div class='content'>
	<h3>����� � <?=$order->order_id?></h3>

	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>�����</span></a>
	</div><br />
	<form class='admin-inside' id="orderForm" action="<?=$selfurl?>updateOrderDetails" method="POST">
		
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
			
				<tr>
					<th>� ������� /<br />�����</th>
					<th>��� / �����<br />�������� / �������<br />/ Email / ���. ��������</th>
					<th>����� ���� ������ �<br />������ ������� ��������</th>
					<th>������</th>
				</tr>
				<tr>
					<td><?=$order->order_client?> / <?=$order->order_login?></td>
					<td><?=$order->order_address?></td>
					<td align="right"><?=$order->order_cost?>$<br />
						<hr />
						<span>����� ��������� ��������� �������: </span>
						<input name="order_products_cost" type="text" value="<?=$order->order_products_cost?>"/><br /><br />
						<span>���� ������� ��������: <span>
						<input name="order_delivery_cost" type="text" value="<?=$order->order_delivery_cost?>"/><br /><br />
						<span>��������� ��� �������: <span>
						<input name="order_weight" type="text" value="<?=$order->order_weight?>"/><br /><br />
						<input name="order_id" type="hidden" value="<?=$order->order_id?>"/>
					</td>
					<td><?=$order->order_status_desc?></td>
				</tr>
				<tr class='last-row'>
					<td colspan='4'>
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='���������' /></div></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</form>
	
	<br /><hr />
	
	<h3>������ ��� ������� � ������:</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>�����</span></a>
	</div><br />	
	
	<form class='admin-inside' id="detailsForm" action="<?=$selfurl?>updateOdetailStatuses" method="POST">
		<input name="order_id" type="hidden" value="<?=$order->order_id?>"/>
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
				<col width='auto' />
				<tr>
					<th>�</th>
					<th>�������� ��������</th>
					<th>������������</th>
					<th>���� / ������ / ���-��</th>
					<th>��������</th>
					<th>������ �� �����</th>
                    <th>����</th>
                    <th>������� ��������</th>
					<th>������</th>
				</tr>
				<?if ($odetails) : foreach($odetails as $odetail) : ?>
				<tr>
					<td><?=$odetail->odetail_id?></td>
					<td><?=$odetail->odetail_shop_name?></td>
					<td><?=$odetail->odetail_product_name?></td>
					<td><?=$odetail->odetail_product_color?> / <?=$odetail->odetail_product_size?> / <?=$odetail->odetail_product_amount?></td>
					<td><a href="javascript:void(0)" onclick="setRel(<?=$odetail->odetail_id?>);">
	                        ����������� �������� <a rel="lightbox_<?=$odetail->odetail_id?>" href="/client/showScreen/<?=$odetail->odetail_id?>" style="display:none;">����������</a>
						</a></td>
					<td><a href="#" onclick="window.open('<?=$odetail->odetail_link?>');return false;"><?=(strlen($odetail->odetail_link)>20?substr($odetail->odetail_link,0,20).'...':$odetail->odetail_link)?></a></td>
                    <td><input size="3" type="text" name="odetail_price<?=$odetail->odetail_id?>" value="<?=$odetail->odetail_price?>"></td>
                    <td><input size="3" type="text" name="odetail_pricedelivery<?=$odetail->odetail_id?>" value="<?=$odetail->odetail_pricedelivery?>"></td>
					<td>
						<select class="select" name="odetail_status<?=$odetail->odetail_id?>">
                        <?
                        foreach ($odetails_statuses as $key => $val)
						{
								?><option value="<?=$key?>" <? if ($odetail->odetail_status == $key) : ?>selected="selected"<? endif; ?>><?=$val?></option><?
						}
						?>
						</select>
					</td>
				</tr>
				<?endforeach; endif;?>
				<tr class='last-row'>
					<td colspan='9'>
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='���������' /></div></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</form>
	<a name="comments"></a>
	<h3>����������� � ������</h3>
	<form class='comments' action='<?=$selfurl?>addOrderComment/<?=$order->order_id?>' method='POST'>
		<?if (!$comments):?>
			<div class='comment'>
				���� ��� ������������<br/>
			</div>
		<?else:?>
			<? foreach ($comments as $comment):?>
				<div class='comment'>
					<div class='question'>
					<?if ($comment->ocomment_user == $order->order_client):?>
						<span class="name">������:</span>
					<?elseif ($comment->ocomment_user == $order->order_manager):?>
						<span class="name">�������:</span>
					<?else:?>
						<span class="name">�������������:</span>
					<?endif;?>
						<p><?=$comment->ocomment_comment?></p>
					</div>
				</div>
			<? endforeach; ?>
		<?endif;?>
	
		<div class='add-comment'>
			<div class='textarea'><textarea name='comment'></textarea></div>
			<div class='submit'><div><input type='submit' name="add" value="��������" /></div></div>
		</div>
	</form>
</div>









<?/*

����� � <?=$order->order_id?>
<form id="orderForm" action="<?=$selfurl?>updateOrderDetails" method="POST">
	<a href='javascript:history.back();'>�����</a>
	<input type="submit" value="���������"/>

	<div id="Order" align="center">
		<table>
			<tr>
				<th>� ������� /<br />�����</th>
				<th>��� / �����<br />�������� / �������<br />/ Email / ���. ��������</th>
				<th>����� ���� ������ �<br />������ ������� ��������</th>
				<th>������</th>
			</tr>
			<tr>
				<td><?=$order->order_client?><br /><?=$order->order_login?></td>
				<td><?=$order->order_address?></td>
				<td><input name="order_cost" type="text" value="<?=$order->order_cost?>"/><br />
					<hr />
					����� ��������� ��������� �������:<br />
					<input name="order_products_cost" type="text" value="<?=$order->order_products_cost?>"/><br />
					���� ������� ��������:<br />
					<input name="order_delivery_cost" type="text" value="<?=$order->order_delivery_cost?>"/><br />
					��������� ��� �������:<br />
					<input name="order_weight" type="text" value="<?=$order->order_weight?>"/><br />
					<input name="order_id" type="hidden" value="<?=$order->order_id?>"/><br />
				</td>
				<td><? if ($order->order_status == 'proccessing') : ?>
					��������������
				<? elseif ($order->order_status == 'not_available') : ?>
					��� � �������
				<? elseif ($order->order_status == 'not_payed') : ?>
					�� �������
				<? elseif ($order->order_status == 'payed') : ?>
					�������
				<? elseif ($order->order_status == 'sended') : ?>
					���������
				<? endif; ?></td>
			</tr>
		</table>
	</div>
</form>

������ ��� ������� � ������:
<form id="detailsForm" action="<?=$selfurl?>updateOdetailStatuses" method="POST">
	<div id="OrderDetails" align="center">
		<table>
			<tr>
				<th>�</th>
				<th>�������� ��������</th>
				<th>������������</th>
				<th>����</th>
				<th>������</th>
				<th>���-��</th>
				<th>��������</th>
				<th>������ �� �����</th>
				<th>������</th>
			</tr>
			<?if ($odetails) : foreach($odetails as $odetail) : ?>
			<tr>
				<td><?=$odetail->odetail_id?></td>
				<td><?=$odetail->odetail_shop_name?></td>
				<td><?=$odetail->odetail_product_name?></td>
				<td><?=$odetail->odetail_product_color?></td>
				<td><?=$odetail->odetail_product_size?></td>
				<td><?=$odetail->odetail_product_amount?></td>
				<td></td>
				<td><?=$odetail->odetail_link?></td>
				<td>
					<select name="odetail_status<?=$odetail->odetail_id?>">
						<option value="not_available" <? if ($odetail->odetail_status == 'not_available') : ?>selected="selected"<? endif; ?>>��� � �������</option>
						<option value="available" <? if ($odetail->odetail_status == 'available') : ?>selected="selected"<? endif; ?>>���� � �������</option>
					</select>
				</td>
			</tr>
			<?endforeach; endif;?>
		</table>
	</div>

	<input name="order_id" type="hidden" value="<?=$order->order_id?>"/><br />
	<a href='javascript:history.back();'>�����</a>
	<input type="submit" value="���������"/>
</form>

*/?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#orderForm input:text').keypress(function(event){validate_number(event);});
	});
	
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