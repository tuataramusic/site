<div class='content'>

	<h2>Аккаунт администратора</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
	</ul>

	<h3>Редактирование новостей</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div><br />
	
	<div align="right">
		<a href="javascript:showAddForm()" style="text-align:right;">Добавить новости</a>
	</div>
	
	<div id="add" align="center" style="display: none;">
	<br />
		<form class='admin-inside' name="addForm" id="addForm" method="POST" action="<?=BASEURL?>admin/saveNews">
			<div class='table'>
				<div class='angle angle-lt'></div>
				<div class='angle angle-rt'></div>
				<div class='angle angle-lb'></div>
				<div class='angle angle-rb'></div>
				<table>
					<tr>
						<td><span>Заголовок:</span></td>
						<td>
							<textarea name="title" cols="155" rows="2"></textarea>
						</td>
					</tr>
					<tr>
						<td><span>Текст:</span></td>
						<td>
							<textarea name="body" cols="155" rows="3"></textarea>
						</td>
					</tr>
					<tr class='last-row'>
						<td colspan='9'>
							<br />
							<div class='float'>	
								<div class='submit'><div><input type='submit' value='Добавить' /></div></div>
							</div>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
			<input type="hidden" name="id" />
		</form>
	</div>
	
	<br />
	
	<form class='admin-inside' method="POST">
	
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<col width='30' />
				<col width='200' />
				<col width='350' />
				<col width='100' />
				<col width='100' />
				<tr>
					<th>ID</th>
					<th>Заголовок</th>
					<th>Текст</th>
					<th>Время создания</th>
					<th>Действие</th>
				</tr>
				<?if (count($news)):foreach($news as $item):?>
				<tr>
					<td><?=$item->news_id?></td>
					<td id="t_<?=$item->news_id?>"><?=$item->news_title?></td>
					<td id="b_<?=$item->news_id?>"><?=$item->news_body?></td>
					<td><?=date('d-m-Y H:i:s', strtotime($item->news_addtime))?></td>
					<td>
						<a href="javascript:editNews(<?=$item->news_id?>);">Редактировать</a>
						<a href="javascript:deleteItem(<?=$item->news_id?>);">Удалить</a>
					</td>
				</tr>
				<?endforeach;endif;?>
			</table>
		</div>
	</form>
</div>



<?/*

Редактирование новостей<br />

<div align="right">
	<a href="javascript:showAddForm()" style="text-align:right;">Добавить новости</a>
</div>

<div id="add" align="center">
	<form name="addForm" id="addForm" method="POST" action="<?=BASEURL?>admin/saveNews">
		<table>
			<tr>
				<td>Заголовок:</td>
				<td>
					<textarea name="title"></textarea>
				</td>
			</tr>
			<tr>
				<td>Текст:</td>
				<td>
					<textarea name="body"></textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" value="Сохранить">
				</td>
			</tr>	
		</table>
		<input type="hidden" name="id" />
	</form>
</div>

<div id="News" align="center">
	<table>
		<tr>
			<th>ID</th>
			<th>Title</th>
			<th>Body</th>
			<th>Addtime</th>
			<th>Action</th>
		</tr>
		<?if (count($news)):foreach($news as $item):?>
		<tr>
			<td><?=$item->news_id?></td>
			<td id="t_<?=$item->news_id?>"><?=$item->news_title?></td>
			<td id="b_<?=$item->news_id?>"><?=$item->news_body?></td>
			<td><?=$item->news_addtime?></td>
			<td>
				<a href="javascript:editNews(<?=$item->news_id?>);">Edit</a>
				|
				<a href="javascript:deleteItem(<?=$item->news_id?>);">Delete</a>
			</td>
		</tr>
		<?endforeach;endif;?>
	</table>
</div>

*/?>

<script type="text/javascript">

	function showAddForm(){
		$('#add').toggle();
	}
	
	function editNews(id){
		
		var f = document.forms['addForm'];
		var t = $('#t_'+id).html();
		var b = $('#b_'+id).html();
		f.title.innerHTML		= t;
		f.body.innerHTML		= b;
		f.id.value				= id;
		$('#add').show();
		
	}	
	
	function deleteItem(id){
		if (confirm("Вы уверены что хотите удалить новость?")){
			window.location.href = '<?=$selfurl?>deleteNews/'+id;
		}
	}

</script>