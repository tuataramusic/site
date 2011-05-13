<div class='content'>
	<h2>F.A.Q.</h2>
	<form class='admin-inside' method="POST">
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<col width='30' />
				<col width='auto' />
				<tr>
					<th>ID</th>
					<th>Q/A</th>
				</tr>
				<?if (count($faq)):foreach($faq as $item):?>
				<tr>
					<td><?=$item->faq_id?></td>
					<td>
						Q: <span><?=$item->faq_question?></span>
						<br />
						A: <span><?=$item->faq_answer?></span>
					</td>
				</tr>
				<?endforeach;endif;?>
			</table>
		</div>
	</form>
</div>