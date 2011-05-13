<a href='javascript:history.back();'>Назад</a>
<center>
<b>Заявка №<?=$o2o->order2out_id?></b><br/>
<table>
	<tr><td style="color: #aaa;">Сумма</td><td><?=$o2o->order2out_ammount?>р</td></tr>
	<tr><td style="color: #aaa;">Статус</td><td><?=$o2o->order2out_status == 'processing' ? 'В обработке' : 'Выплачено'?></td></tr>
</table>
</center>
<br/>Комментарии<br/><br/>
<?if (!$comments):?>
Пока нет комментариев<br/>
<?else:?>
	<? foreach ($comments as $comment):?>
	<i><b><?if ($comment->o2comment_user == $user->user_id):?>Вы:<?else:?>Клиент:<?endif;?></b>&nbsp;<?=$comment->o2comment_comment?></i><br/><br/>
	<? endforeach; ?>
<?endif;?>

<br/><b>Добавить комментарий</b><br/>
<form action='<?=$selfurl?>addO2oComment/<?=$o2o->order2out_id?>' method='POST'>
<textarea name='comment' cols="40" rows="5"></textarea><br/>
<input type="submit" name="add" value="Добавить"/>
</form>
