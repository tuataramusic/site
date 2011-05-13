Страны
<br /><br />

<?if ($countries):?>
<br />
<table>
	<tr>
		<td>Название</td>
		<td>Доставка из</td>
		<td>Доставка в</td>
		<td>Изменить / Удалить</td>
	</tr>
	<?foreach ($countries as $country):?>
	<tr>
		<td><?=$country->country_name?></td>
		<td><?=isset($country->is_from) ? 'да' : ''?></td>
		<td><?=isset($country->is_to) ? 'да' : ''?></td>
		<td><a href='<?=$selfurl?>editCountry/<?=$country->country_id?>'>Изменить</a> / <a href='<?=$selfurl?>deleteCountry/<?=$country->country_id?>'>Удалить</a></td>
	</tr>
	<?endforeach;?>	
</table>
<?endif;?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#filterForm select').change(function() {
			document.getElementById('filterForm').submit();	
		});

		$('#newPartnerId').change(function() 
		{
			var selectedPartner = $('#newPartnerId option:selected');
			
			if (selectedPartner.val() == '-1')
			{
				return false;
			}
			
			if ($('#clientsForm input:checkbox:checked').size() == 0)
			{
				alert('Выберите клиентов для перемещения.');
				return false;
			}
			
			if (confirm('Вы уверены, что хотите переместить выбранных клиентов к новому партнеру?'))
			{
				document.getElementById('clientsForm').submit();
			}
		});
	});
</script>