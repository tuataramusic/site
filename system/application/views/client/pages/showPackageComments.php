<div class='content'>
	<h2>����������� � ������ �<?=$package->package_id?></h2>
	<form class='partner-inside-1' action='#'>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<th>������� �</th>
					<th>����� ��������</th>
					<th>����� ���� ������ <br />� ������ ������� ��������</th>
					<th>������</th>
				</tr>
				<tr>
					<td><?=$package->package_manager?></td>
					<td><?=$package->Managers->manager_name?> (<?=$package->Managers->manager_addres?>)</td>
					<td>
						����� �������� ���������� �������: <?=$package->package_cost?> $<br />
						���� ��������: <?=$package->package_delivery_cost?> $<br />
						����� ��� �������: <?=$package->package_weight?> ��
					</td>
					<td>
						<?if (	$package->package_status == 'not_available'):?>
							��� � �������
						<?elseif ($package->package_status == 'payed'):?>
							��������
						<?elseif ($package->package_status == 'not_payed'):?>
							�� ��������
						<?elseif ($package->package_status == 'sended' || $package->package_status == 'sent'):?>
							����������
						<?elseif ($package->package_status == 'proccessing'):?>
							��������������
						<?elseif ($package->package_status == 'deleted'):?>
							�������
						<?endif;?>
					</td>
				</tr>
				
			</table>
		</div>
	</form>
	
	<h3>����������� � ������</h3>
	<form  id="commentForm"  class='comments' action='<?=$selfurl?>addPackageComment/<?=$package->package_id?>' method='POST'>
		<?if (!$comments):?>
			<div class='comment'>
				���� ��� ������������<br/>
			</div>
		<?else:?>
			<? foreach ($comments as $comment):?>
				<div class='comment'>
					<div class='question'>
					<?if ($comment->pcomment_user == $package->package_client):?>
						<span class="name">��:</span>
					<?elseif ($comment->pcomment_user == $package->package_manager):?>
						<span class="name">�������:</span>
					<?else:?>
						<span class="name">�������������:</span>
					<?endif;?>
						<p><?=$comment->pcomment_comment?></p>
						
					<?if ($comment->pcomment_user == $package->package_client):?>
						<a href="<?=$selfurl?>delPackageComment/<?=$package->package_id.'/'.$comment->pcomment_id?>" >�������</a>
						<p onclick="$('#editComment_<?=$comment->pcomment_id?>').show();"   style="text-decoration:underline; cursor:pointer; color:#BF0090;" >�������������</p>
							<div class='add-comment' id="editComment_<?=$comment->pcomment_id?>" style="display:none;">
								<div class='textarea'><textarea name='ecomment_<?=$comment->pcomment_id?>'><?=$comment->pcomment_comment?></textarea></div>
								<div><a href="javascript:editComment(<?=$package->package_id?>,<?=$comment->pcomment_id?>)" >���������</a></div>
							</div>
					<?endif;?>
						
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


<script type="text/javascript">

	function editComment($pid, $cid){
		var $f = document.getElementById('commentForm');
		$f.action = '<?=$selfurl?>addPackageComment/'+$pid+'/'+$cid;
		$f.comment.value = $f['ecomment_'+$cid].value;
		$f.submit();
	}

</script>



<?/*
<a href='javascript:history.back();'>�����</a>
<center>
<b>������� �<?=$package->package_id?></b><br/>
<table>
	<tr><td style="color: #aaa;">������� �</td><td><?=$package->package_manager?></td></tr>
	<tr><td style="color: #aaa;">���</td><td><?=$package->package_weight?>��</td></tr>
	<tr><td style="color: #aaa;">���������</td><td><?=$package->package_cost?>�</td></tr>
</table>
</center>
<br/>�����������<br/><br/>
<?if (!$comments):?>
���� ��� ������������<br/>
<?else:?>
	<? foreach ($comments as $comment):?>
	<i><b><?if ($comment->pcomment_user == $package->package_client):?>��:<?else:?>�������:<?endif;?></b>&nbsp;<?=$comment->pcomment_comment?></i><br/><br/>
	<? endforeach; ?>
<?endif;?>

<br/><b>�������� �����������</b><br/>
<form action='<?=$selfurl?>addPackageComment/<?=$package->package_id?>' method='POST'>
<textarea name='comment' cols="40" rows="5"></textarea><br/>
<input type="submit" name="add" value="��������"/>
</form> */
			?>