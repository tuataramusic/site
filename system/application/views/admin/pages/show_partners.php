<div style="float: right;"><a href='<?=$selfurl?>showAddPartner'>�������� ������ ��������</a></div>
<?if ($result->m):?><center><?=$result->m?></center><?endif;?>
<?if ($managers):?>
<br/>
<table>
	<tr>
		<td>�</td>
		<td>�������/������</td>
		<td>���</td>
		<td>����� ��������</td>
		<td>���-�� �������������</td>
		<td>����������� �������</td>
		<td>������</td>
		<td>��������/�������</td>
	</tr>
	<?foreach ($managers as $manager):?>
	<tr>
		<td><?=$manager->manager_user?></td>
		<td><?=$manager->user_login?> / <?=$countries[$manager->manager_country]?></td>
		<td><?=$manager->manager_surname?> <?=$manager->manager_name?> <?=$manager->manager_otc?></td>
		<td><?=$manager->manager_addres?></td>
		<td><?=$manager->clients_count?></td>
		<td>0</td>
		<td><?=$statuses[$manager->manager_status]?></td>
		<td><a href='<?=$selfurl?>showUpdatePartner/<?=$manager->manager_user?>'>��������</a> / <a href='<?=$selfurl?>deletePartner/<?=$manager->manager_user?>'>�������</a></td>
	</tr>
	<?endforeach;?>	
</table>
<?else:?>
��������� ���!
<?endif;?>