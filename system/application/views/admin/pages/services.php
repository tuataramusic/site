<div class='content'>

	<h2>������� ��������������</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>�������� ����� �������</a><br /><a href='<?=$selfurl?>editPricelist'>��������� ������� �� ��������</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>�������� ���� �� ������</a><br /><a href='<?=$selfurl?>showEditNews'>������������� �������</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>������������� F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>������ �� �����</a></li>
	</ul>

	<h3>��������� ��� �� ������</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>�����</span></a>
	</div><br />

	<center>
	<form class="admin-inside" action="<?=$selfurl?>saveServicesPrice" method="POST">

		<div class='table' style="width:40% !important">
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table width="60%">
				<tr>
					<td><span>���� �� ���������:</span></td>
					<td><input type="text" name="transmission" size="5" value="<?=$config['price_for_trasmission']->config_value?>"></td>
				</tr>	
				<tr>
					<td>���� �� ������ � ������:</td>
					<td><input type="text" name="help" size="5" value="<?=$config['price_for_help']->config_value?>">%</td>
				</tr>		
				<tr>
					<td>���� �� ���������� ����������:</td>
					<td><input type="text" name="declaration" size="5" value="<?=$config['price_for_declaration']->config_value?>"></td>
				</tr>
				<tr>
					<td>���� �� ����������� �������:</td>
					<td><input type="text" name="merge" size="5" value="<?=$config['price_for_marge']->config_value?>"></td>
				</tr>
				<tr>
					<td>���� �� ���������:</td>
					<td><input type="text" name="insurance" size="5" value="<?=$config['price_for_insurance']->config_value?>">%</td>
				</tr>
				<tr>
					<td>������������ ����� ���������:</td>
					<td><input type="text" name="max_insurance" size="5" value="<?=$config['max_insurance']->config_value?>">�</td>
				</tr>

				<tr class='last-row'>
					<td colspan='2'>
						<br />
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='���������' /></div></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</form>
	</center>
</div>