<div class='content'>
	<h2>������ �<?=$o2o->order2out_id?></h2>
	<br />
	<div class='table' style="width:300px;">
		<div class='angle angle-lt'></div>
		<div class='angle angle-rt'></div>
		<div class='angle angle-lb'></div>
		<div class='angle angle-rb'></div>
		
		<table>
			<tr>
				<td>�����:</td>
				<td><?=$o2o->order2out_ammount?>�</td>
			</tr>
			<tr>
				<td>������:</td>
				<td><?=$o2o->order2out_status == 'processing' ? '� ���������' : '���������'?></td>
			</tr>
		</table>
	</div>

	
	<h3>�����������</h3>
	<form class='comments' action='<?=$selfurl?>addO2oComment/<?=$o2o->order2out_id?>' method='POST'>
		<?if (!$comments):?>
			<div class='comment'>
				���� ��� ������������<br/>
			</div>
		<?else:?>
			<? foreach ($comments as $comment):?>
				<div class='comment'>
					<div class='question'>
						<span class="name">
							<?if ($comment->o2comment_user == $o2o->order2out_user):?>
								��:
							<?else:?>
								�������������:
							<?endif;?>
						</span>
						<p><?=$comment->o2comment_comment?></p>
					</div>
				</div>
			<? endforeach; ?>
		<?endif;?>
	
		<?if ($user):?>
		<div class='add-comment'>
			<div class='textarea'><textarea name='comment'></textarea></div>
			<div class='submit'><div><input type='submit' name="add" value="��������" /></div></div>
		</div>
		<?endif;?>
	</form>
	
</div>




<?/*
<a href='javascript:history.back();'>�����</a>
<center>
<b>������ �<?=$o2o->order2out_id?></b><br/>
<table>
	<tr><td style="color: #aaa;">�����</td><td><?=$o2o->order2out_ammount?>�</td></tr>
	<tr><td style="color: #aaa;">������</td><td><?=$o2o->order2out_status == 'processing' ? '� ���������' : '���������'?></td></tr>
</table>
</center>
<br/>�����������<br/><br/>
<?if (!$comments):?>
���� ��� ������������<br/>
<?else:?>
	<? foreach ($comments as $comment):?>
	<i><b><?if ($comment->o2comment_user == $o2o->order2out_user):?>��:<?else:?>�������������:<?endif;?></b>&nbsp;<?=$comment->o2comment_comment?></i><br/><br/>
	<? endforeach; ?>
<?endif;?>

<br/><b>�������� �����������</b><br/>
<form action='<?=$selfurl?>addO2oComment/<?=$o2o->order2out_id?>' method='POST'>
<textarea name='comment' cols="40" rows="5"></textarea><br/>
<input type="submit" name="add" value="��������"/>
</form>
*/?>