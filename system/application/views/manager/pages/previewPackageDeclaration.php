<div class='content'>
	<h2>����������</h2>
	<div class='back'><a href='javascript:history.back();'><span>�����</span></a></div>
	
	<form class='card'>
	
		<table>
			<?$index = 0; $sum = 0; if ($declarations):
			foreach ($declarations as $declaration): 
			$sum += $declaration->declaration_amount * $declaration->declaration_cost;
			$index++; ?>
			<tr>
				<th>���� �<?=$index?></th>
				<td>
					<div class='text-field name-field'><div><input readonly type='text' name="declaration_item<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_item?>" /></div></div>
				</td>
				<td>
					<span>����������:</span>
					<div class='text-field number-field'><div><input readonly class="count" type='text' name="declaration_amount<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_amount?>" /></div></div>
				</td>
				<td>
					<span>���������:</span>
					<div class='text-field price-field'><div><input readonly class="price" type='text'  name="declaration_cost<?=$declaration->declaration_id?>" value="<?=$declaration->declaration_cost?>" /></div></div>
					<span>$</span>
				</td>
			</tr>
			<? endforeach; endif;?>

			<tr>
				<td class='total-price' colspan='4'>
					<span>����� �����: <strong class='big-text' id="total"><?=$sum;?>$</strong></span>
					<span class='pink-color'>����� ��� ������ 10000$</span>
				</td>
			</tr>
		</table>
	</form>
</div>