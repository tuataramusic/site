<div class='content'>

	<h2>������� ��������������</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
	</ul>

	<h3>�������� ������ ��������</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>�����</span></a>
	</div>

	<center>
	<form class='card' action='<?=$selfurl?>addDelivery' method='POST'>

		<table>
			<tr>
				<th>�������� ��������:</th>
				<td>
					<div class='text-field name-field'><div><input type="text" name="delivery_name" maxlength="32" /></div></div>
				</td>
				<td>
					<span>���� ��������:</span>
					<div class='text-field number-field'><div><input type="text" name="delivery_time" maxlength="32" /></div></div>
				</td>
			</tr>

			<tr>
				<td class='total-price' colspan='4'>
					<div class='submit'><div><input type='submit' value='��������' /></div></div>
				</td>
			</tr>
		</table>
	</form>
	</center>
</div>