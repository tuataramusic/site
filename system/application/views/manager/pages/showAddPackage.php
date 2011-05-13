<div class='content'>
	<h2>Аккаунт Партнера</h2>

	<h3>Добавление посылки</h3>
	<form class='admin-inside'  action="<?=$selfurl?>addPackage" method="POST">
	
		<ul class='tabs'>
			<li class='active'><div><a href='<?=$selfurl?>showAddPackage'>Добавить посылку</a></div></li>
			<li><div><a href='<?=$selfurl?>showNewPackages'>Новые</a></div></li>
			<li><div><a href='<?=$selfurl?>showPayedPackages'>Оплаченные</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentPackages'>Отправленные</a></div></li>
			<li><div><a href='<?=$selfurl?>showOpenOrders'>Заказы “Помощь в покупке”</a></div></li>
			<li><div><a href='<?=$selfurl?>showSentOrders'>Закрытые заказы</a></div></li>
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
					<th>Клиент</th>
					<th>Вес, кг</th>
				</tr>
				<tr>
					<td>
						<select id="package_client" name="package_client" style="width:150px;">
							<option value="">выберите клиента...</option>
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
							<div class='submit'><div><input type='submit' value='Добавить' /></div></div>
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