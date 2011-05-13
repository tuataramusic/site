<div class='content'>
	<h2>Каталог магазинов</h2>
	<div class="back">
		<a href="javascript:history.back();" class="back"><span>Назад</span></a>
	</div><br />
	<center>
		<h3><?=$shop->shop_name?></h3>
	</center>
	
	<form class='admin-inside' action='#'>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<col width='auto' />
				<tr>
					<th>Страна</th>
					<th>Описание</th>
				</tr>
				<tr>
					<td><?=$country?></td>
					<td><?=$shop->shop_desc?></td>
				</tr>
			</table>
		</div>
	</form>
	
	<h3>Комментарии</h3>
	<form class='comments' action='<?=BASEURL?>main/showShop/<?=$shop->shop_id?>' method='POST'>
		<?if (!$comments):?>
			<div class='comment'>
				Пока нет комментариев<br/>
			</div>
		<?else:?>
			<? foreach ($comments as $comment):?>
				<div class='comment'>
					<div class='question'>
						<span class="name"><?=$susers[$comment->scomment_user]->user_login?>:</span>
						<p><?=$comment->scomment_comment?></p>
					</div>
				</div>
			<? endforeach; ?>
		<?endif;?>
	
		<?if ($user):?>
		<div class='add-comment'>
			<div class='textarea'><textarea name='comment'></textarea></div>
			<div class='submit'><div><input type='submit' name="add" value="Добавить" /></div></div>
		</div>
		<?endif;?>
	</form>
	
</div>

<?/*
<a href='<?=BASEURL?>main/showShopCatalog'>Назад</a>
<center>
<b><?=$shop->shop_name?></b><br/>
<table>
	<tr><td width="150px;" style="color: #aaa;">Страна</td><td width="300px;"><?=$country?></td></tr>
	<tr><td style="color: #aaa;">Описание</td><td><?=$shop->shop_desc?></td></tr>
</table>
</center>
<br/>Комментарии<br/><br/>
<?if (!$comments):?>
Пока нет комментариев<br/>
<?else:?>
	<? foreach ($comments as $comment):?>
	<i><?=$comment->scomment_comment?></i><br/><br/>
	<? endforeach; ?>
<?endif;?>

<?if ($user):?>
<br/><b>Добавить коммент</b><br/>
<form action='<?=BASEURL?>main/showShop/<?=$shop->shop_id?>' method='POST'>
<textarea name='comment' cols="40" rows="5"></textarea><br/>
<input type="submit" name="add" value="Добавить"/>
</form>
<?endif;?>
*/?>