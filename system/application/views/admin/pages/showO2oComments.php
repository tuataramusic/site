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
	<i><b><?if ($comment->o2comment_user == $user->user_id):?>��:<?else:?>������:<?endif;?></b>&nbsp;<?=$comment->o2comment_comment?></i><br/><br/>
	<? endforeach; ?>
<?endif;?>

<br/><b>�������� �����������</b><br/>
<form action='<?=$selfurl?>addO2oComment/<?=$o2o->order2out_id?>' method='POST'>
<textarea name='comment' cols="40" rows="5"></textarea><br/>
<input type="submit" name="add" value="��������"/>
</form>
