<div class='content'>

	<h2>Аккаунт администратора</h2>
	<ul class='admin-buttons'>
		<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
		<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
		<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
	</ul>

	<h3>Редактирование FAQа</h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>Назад</span></a>
	</div><br />
	
	<div align="right">
		<a href="javascript:showAddForm()" style="text-align:right;">Добавить вопрос</a>
	</div>
	
	<div id="add" align="center" style="display: none;">
	<br />
		<form class='admin-inside' name="addForm" id="addForm" method="POST" action="<?=BASEURL?>admin/saveFaq">
			<div class='table'>
				<div class='angle angle-lt'></div>
				<div class='angle angle-rt'></div>
				<div class='angle angle-lb'></div>
				<div class='angle angle-rb'></div>
				<table>
					<tr>
						<td><span>Вопрос:</span></td>
						<td>
							<textarea name="question" cols="155" rows="2"></textarea>
						</td>
					</tr>
					<tr>
						<td><span>Ответ:</span></td>
						<td>
							<textarea name="answer" cols="155" rows="3"></textarea>
						</td>
					</tr>
					<tr class='last-row'>
						<td colspan='9'>
							<br />
							<div class='float'>	
								<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
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
				<col width='auto' />
				<col width='30' />
				<tr>
					<th>ID</th>
					<th>Q/A</th>
					<th>Action</th>
				</tr>
				<?if (count($faq)):foreach($faq as $item):?>
				<tr>
					<td><?=$item->faq_id?></td>
					<td>
						Q: <span id="q_<?=$item->faq_id?>"><?=$item->faq_question?></span>
						<br />
						A: <span id="a_<?=$item->faq_id?>"><?=$item->faq_answer?></span>
					</td>
					<td>
						<a href="javascript:editFaq(<?=$item->faq_id?>);">Edit</a>
						<a href="javascript:deleteItem(<?=$item->faq_id?>);">Delete</a>
					</td>
				</tr>
				<?endforeach;endif;?>
			</table>
		</div>
	</form>
</div>



<?/*
<style>

#addForm{
	
}

#add{
	display: none;
	position: absolute;
	background: #FFF;
	margin-left: 500px;
}

</style>
Редактирование FAQа<br />

<div align="right">
	<a href="javascript:showAddForm()" style="text-align:right;">Добавить запись</a>
</div>

<div id="add" align="center">
	<form name="addForm" id="addForm" method="POST" action="<?=BASEURL?>admin/saveFaq">
		<table>
			<tr>
				<td>Вопрос:</td>
				<td>
					<textarea name="question"></textarea>
				</td>
			</tr>
			<tr>
				<td>Ответ:</td>
				<td>
					<textarea name="answer"></textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" value="Сохранить">
				</td>
			</tr>	
		</table>
		<input type="hidden" name="id">
	</form>
</div>

<div id="Faq" align="center">
	<table>
		<tr>
			<th>ID</th>
			<th>Q/A</th>
			<th>Action</th>
		</tr>
		<?if (count($faq)):foreach($faq as $item):?>
		<tr>
			<td><?=$item->faq_id?></td>
			<td>
				Q: <span id="q_<?=$item->faq_id?>"><?=$item->faq_question?></span>
				<br />
				A: <span id="a_<?=$item->faq_id?>"><?=$item->faq_answer?></span>
			</td>
			<td>
				<a href="javascript:editFaq(<?=$item->faq_id?>);">Edit</a>
				|
				<a href="javascript:deleteItem(<?=$item->faq_id?>);">Delete</a>
			</td>
		</tr>
		<?endforeach;endif;?>
	</table>
</div>
*/
?>

<script type="text/javascript">

	function showAddForm(){
		$('#add').toggle();
	}
	
	function editFaq(id){
		
		var f = document.forms['addForm'];
		var q = $('#q_'+id).html();
		var a = $('#a_'+id).html();
		f.question.innerHTML	= q;
		f.answer.innerHTML		= a;
		f.id.value				= id;
		$('#add').show();
		
	}
	
	function deleteItem(id){
		if (confirm("Вы уверены что хотите удалить запись?")){
			window.location.href = '<?=$selfurl?>deleteFaq/'+id;
		}
	}
</script>