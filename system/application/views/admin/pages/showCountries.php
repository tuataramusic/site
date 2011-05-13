������
<br /><br />

<?if ($countries):?>
<br />
<table>
	<tr>
		<td>��������</td>
		<td>�������� ��</td>
		<td>�������� �</td>
		<td>�������� / �������</td>
	</tr>
	<?foreach ($countries as $country):?>
	<tr>
		<td><?=$country->country_name?></td>
		<td><?=isset($country->is_from) ? '��' : ''?></td>
		<td><?=isset($country->is_to) ? '��' : ''?></td>
		<td><a href='<?=$selfurl?>editCountry/<?=$country->country_id?>'>��������</a> / <a href='<?=$selfurl?>deleteCountry/<?=$country->country_id?>'>�������</a></td>
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
				alert('�������� �������� ��� �����������.');
				return false;
			}
			
			if (confirm('�� �������, ��� ������ ����������� ��������� �������� � ������ ��������?'))
			{
				document.getElementById('clientsForm').submit();
			}
		});
	});
</script>