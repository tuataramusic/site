<div class='content'>
	<h2>Аккаунт Партнера</h2>
	<h3>Комментарии к заказу №<?=$package->package_id?></h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div><br />
	
	<form class='partner-inside-1' action='#'>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<th>Клиент №</th>
					<th>Ф.И.О., адрес доставки</th>
					<th>Общая цена заказа <br />с учетом местной доставки</th>
					<th>Статус</th>
				</tr>
				<tr>
					<td><?=$package->package_client?></td>
					<td><?=$package->package_address?></td>
					<td>
						Общая стомость заказанных товаров: <?=$package->package_cost?> $<br />
						Цена доставки: <?=$package->package_delivery_cost?> $<br />
						Общий вес посылки: <?=$package->package_weight?> кг
					</td>
					<td>
						<?    if ($package->package_status == 'not_available'):?>Нет в наличии
						<?elseif ($package->package_status == 'not_available_color') : ?>Нет данного цвета
						<?elseif ($package->package_status == 'not_available_size') : ?>Нет данного размера
						<?elseif ($package->package_status == 'not_available_count') : ?>Нет указанного кол-ва
						<?elseif ($package->package_status == 'payed'):?>Оплачено
						<?elseif ($package->package_status == 'not_payed'):?>Не оплачено
						<?elseif ($package->package_status == 'sended' || $package->package_status == 'sent'):?>Отправлена
						<?elseif ($package->package_status == 'proccessing'):?>Обрабатывается
						<?elseif ($package->package_status == 'deleted'):?>Удалена
						<?endif;?>
					</td>
				</tr>
				
			</table>
		</div>
	</form>
	
	<h3>Комментарии к заказу</h3>
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
						<span class="name">Вы:</span>
					<?elseif ($comment->pcomment_user == $package->package_client):?>
						<span class="name">Клиент:</span>
					<?else:?>
						<span class="name">Администрация:</span>
					<?endif;?>
						<p><?=$comment->pcomment_comment?></p>
						
						<? if ($comment->pcomment_user == $package->package_manager):?>
							<a href="<?=$selfurl?>delPackageComment/<?=$package->package_id.'/'.$comment->pcomment_id?>" >Удалить</a>
							<p onclick="$('#editComment_<?=$comment->pcomment_id?>').show();"  style="text-decoration:underline; cursor:pointer; color:#BF0090;">Редактировать</p>
									<div class='add-comment' id="editComment_<?=$comment->pcomment_id?>" style="display:none;">
									<div class='textarea'><textarea name='ecomment_<?=$comment->pcomment_id?>'><?=$comment->pcomment_comment?></textarea></div>
									<div><a href="javascript:editComment(<?=$package->package_id?>,<?=$comment->pcomment_id?>)" >Сохранить</a></div>
								</div>
						<? endif;?>
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