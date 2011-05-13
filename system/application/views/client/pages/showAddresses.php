<div class='content'>
	<h2>������, �� ������� �� ������ ������ ������.</h2>
	<p>���� ����������� ������, �� ������� �� ������ ���������� � ����� �����. �� ������� ������� � �������� �� ���.</p>

	<?if ($partners) : ?>
	
	<div class='table' id="Addresses">
		<div class='angle angle-lt'></div>
		<div class='angle angle-rt'></div>
		<div class='angle angle-lb'></div>
		<div class='angle angle-rb'></div>
		
		<select id="country" class="select">
			<option value="">----- �������� ������ -----</option>
		<? foreach($partners as $address) : 
			if (isset($partner_id) && $address->manager_user == $partner_id):?>
				<option value="<?=$address->manager_user?>" selected><?=$address->country_name?></option>
			<?else:?>
				<option value="<?=$address->manager_user?>"><?=$address->country_name?></option>
			<?endif;?>
		<? endforeach; ?>
		</select><br />
		
		<table>
			<? foreach($partners as $manager) : ?>
			<tr style="display:none;" id="tr<?=$manager->manager_user?>">
				<td>�����:</td>
				<td><?=$manager->manager_addres?></td>
			</tr>
			<tr style="display:none;" id="tr<?=$manager->manager_user?>p">
				<td>�������:</td>
				<td><?=$manager->manager_phone?></td>
			</tr>
			<? endforeach; ?>
			
			<?if ($client) : ?>
			<tr id="client_name">
				<td>��� (������):</td>
				<td><?=$client->client_surname?> <?=$client->client_name?> <?=$client->client_otc?></td>
			</tr>
			<? endif; ?>
			
		</table>
	</div>
	<?endif;?>
	
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$('#country').change(function() {
			var selectedId = $('#country').val();
			$('#Addresses tr:nth-child(n+2)').hide();
			$('#client_name').show();

			if (selectedId == '') 
			{
				return;
			}
			
			$('#tr' + selectedId).show();
			$('#tr' + selectedId + 'p').show();
		});
		
		$('#country').change();
	});
</script>

<?/*
<h1>������, �� ������� �� ������ ��������...</h1>
<span style="font-weight:normal;">���� ����������� ������, �� ������� �� ������ ���������� � ����� �����. �� ������� ������� � �������� �� ���.</span>
<br /><br />
<?if ($partners) : ?>
<div id="Addresses" align="center">
	<table>
		<tr>
			<td>������:</td>
			<td>
				<select id="country">
					<option value="">��������...</option>
				<? foreach($partners as $address) : 
					if (isset($partner_id) && $address->manager_user == $partner_id):?>
						<option value="<?=$address->manager_user?>" selected><?=$address->country_name?></option>
					<?else:?>
						<option value="<?=$address->manager_user?>"><?=$address->country_name?></option>
					<?endif;?>
				<? endforeach; ?>
				</select>
			</td>
		</tr>
		<? foreach($partners as $manager) : ?>
		<tr style="display:none;" id="tr<?=$manager->manager_user?>">
			<td>�����:</td>
			<td><?=$manager->manager_addres?></td>
		</tr>
		<tr style="display:none;" id="tr<?=$manager->manager_user?>p">
			<td>�������:</td>
			<td><?=$manager->manager_addres?></td>
		</tr>
		<? endforeach; ?>
		<?if ($client) : ?>
		<tr id="client_name">
			<td>���:</td>
			<td><?=$client->client_surname?> <?=$client->client_name?> <?=$client->client_otc?></td>
		</tr>
		<? endif; ?>
	</table>
		<i>���� �� ������ ������� ����� �� ������ ���, ��������� ��� ����������� � ����� ������ ������.<br />
		����� ����, ��� �� ������� �������, �� ������ �������� �������.</i>
</div>
<? endif; ?>
*/?>