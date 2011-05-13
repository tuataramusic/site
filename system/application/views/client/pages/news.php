<div class='content'>
	<?if (isset($news) && count($news)>0):?>
		<h2>Новости и объявления</h2>
		<div class='news'>
		<?foreach($news as $item):?>
			<div>
				<span class='date'><?=date('d/m/Y', strtotime($item->news_addtime))?></span>
				<pre>
					<?=$item->news_body?>
				</pre>
			</div>
			<br />
		<?endforeach;?>
		</div>
	<?endif;?>
</div>