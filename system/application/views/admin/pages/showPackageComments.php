<?// var_dump($comments, $package);?>

<div class='content'>
	<h2>Аккаунт Администратора</h2>
	<h3>Комментарии к посылке №<?=$package->package_id?></h3>
	<div class='back'>
<!--		<a class='back' href='javascript:history.back();'><span>Назад</span></a>-->
	</div><br />
	<?Breadcrumb::showCrumbs();?>
	<br />
	
	<form class='partner-inside-1' action='#'>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<col width='auto' />
				<col width='auto' />
				<col width='250' />
				<col width='auto' />
				<tr>
					<th>Информация о партнере</th>
					<th>Информация о клиенте</th>
					<th>Ф.И.О., адрес доставки</th>
					<th>Общая цена заказа <br />с учетом местной доставки</th>
					<th>Статус</th>
				</tr>
				<tr>
					<td><?= '#'.$package->Managers->manager_user.' '.$package->Managers->manager_name.' '.$package->Managers->manager_surname?></td>
					<td><?= '#'.$package->Clients->client_user.' '.$package->Clients->client_name.' '.$package->Clients->client_surname?></td>
					<td><?=$package->package_address?></td>
					<td>
						Общая стомость заказанных товаров: <?=$package->package_cost?> $<br />
						Цена доставки: <?=$package->package_delivery_cost?> $<br />
						Общий вес посылки: <?=$package->package_weight?> кг
					</td>
					<td>
						<?if (	$package->package_status == 'not_available'):?>
							Нет в наличии
						<?elseif ($package->package_status == 'payed'):?>
							Оплачено
						<?elseif ($package->package_status == 'not_payed'):?>
							Не оплачено
						<?elseif ($package->package_status == 'sended' || $package->package_status == 'sent'):?>
							Отправлена
						<?elseif ($package->package_status == 'proccessing'):?>
							Обрабатывается
						<?elseif ($package->package_status == 'deleted'):?>
							Удалена
						<?endif;?>
					</td>
				</tr>
				
			</table>
		</div>
	</form>
	
	<h3>Комментарии к посылке</h3>
	<form id="commentForm" class='comments' action='<?=$selfurl?>addPackageComment/<?=$package->package_id?>' method='POST'>
		<?if (!$comments):?>
			<div class='comment'>
				Пока нет комментариев<br/>
			</div>
		<?else:?>
			<? foreach ($comments as $comment):?>
				<div class='comment'>
					<div class='question'>
						<?if ($comment->pcomment_user == $package->package_manager):?>
							<span class="name">Менеджер:</span>
						<?elseif ($comment->pcomment_user == $package->package_client):?>
							<span class="name">Клиент:</span>
						<?else:?>
							<span class="name">Администрация:</span>
						<?endif;?>
						<p><?=$comment->pcomment_comment?></p>

						<a href="/admin/delPackageComment/<?=$package->package_id.'/'.$comment->pcomment_id?>" >Удалить</a>
						<p onclick="$('#editComment_<?=$comment->pcomment_id?>').show();"  style="text-decoration:underline; cursor:pointer; color:#BF0090;">Редактировать</p>
							<div class='add-comment' id="editComment_<?=$comment->pcomment_id?>" style="display:none;">
								<div class='textarea'><textarea name='ecomment_<?=$comment->pcomment_id?>'><?=$comment->pcomment_comment?></textarea></div>
								<div style="clear:both">
									<div style="margin:0;clear: none;" class='submit'><div><input type='button' name="add" value="Сохранить"  onclick="editComment(<?=$package->package_id?>,<?=$comment->pcomment_id?>)"/></div></div>
									<div style="margin:0;clear: none;" class='submit'><div><input type='button' name="add" value="Отмена"  onclick="$('#editComment_<?=$comment->pcomment_id?>').hide();" /></div></div>
								</div>
							</div>
					</div>
				</div>
			<? endforeach; ?>
		<?endif;?>
	
		<div class='add-comment'>
			<div class='textarea'><textarea name='comment'></textarea></div>
			<div class='submit'><div><input type='submit' name="add" value="Добавить" /></div></div>
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
<a href='javascript:history.back();'>Назад</a>
<center>
<b>Посылка №<?=$package->package_id?></b><br/>
<table>
	<tr><td style="color: #aaa;">Клиент №</td><td><?=$package->package_client?></td></tr>
	<tr><td style="color: #aaa;">Вес</td><td><?=$package->package_weight?>кг</td></tr>
	<tr><td style="color: #aaa;">Стоимость</td><td><?=$package->package_cost?>р</td></tr>
</table>
</center>
<br/>Комментарии<br/><br/>
<?if (!$comments):?>
Пока нет комментариев<br/>
<?else:?>
	<? foreach ($comments as $comment):?>
	<i><b><?if ($comment->pcomment_user == $package->package_manager):?><?=$comment->package_manager_login?>:<?else:?>Клиент:<?endif;?></b>&nbsp;<?=$comment->pcomment_comment?></i><br/><br/>
	<? endforeach; ?>
<?endif;?>
*/?>