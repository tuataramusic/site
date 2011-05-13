<div class='content'>
	<h2>������� ��������</h2>

	<h3>���������� �������</h3>
	<form class='admin-inside'  action="<?=$selfurl?>addPackage" method="POST">
	
		<ul class='tabs'>
			<li class='active'><div><a href='<?=$selfurl?>showAddPackage'>�������� �������</a></div></li>
			<li><div><a href='<?=$selfurl?>showNewPackages'>�����</a></div></li>
			<li><div><a href='<?=$selfurl?>showPayedPackages'>����������</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>������������</a></div></li>
			<li><div><a href='<?=$selfurl?>showOpenOrders'>������ ������� � �������</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentOrders'>�������� ������</a></div></li>
		</ul>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<col width='auto' />
				<col width='auto' />
				<tr>
					<th>������</th>
					<th>���, ��</th>
				</tr>
				<tr>
					<td>
						<select id="package_client" name="package_client" style="width:150px;">
							<option value="">�������� �������...</option>
							<?if ($clients) : foreach ($clients as $client):?>
						    <option value="<?=$client->client_user?>"><?=$client->client_user?></option>
							<?endforeach; endif;?>
						</select>
					</td>
					<td><input id="package_weight" name="package_weight" type="text" maxlength="5" style="width:100px;" onkeypress="javascript:validate_number(event);" ></td>
				</tr>
				<tr class='last-row'>
					<td colspan='9'>
						<br />
						<div class='float'>	
							<div class='submit'><div><input type='submit' value='��������' /></div></div>
						</div>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
	</form>
</div>

<script>
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