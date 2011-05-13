<div class='content'>
	<?if (isset($news) && count($news)>0):?>
		<h2>Новости и объявления</h2>
		<div class='forward'><a href='<?=$selfurl?>showNewsList'><span>Все новости</span></a></div>
	
		<div class='news'>
		<?foreach($news as $item):?>
			<div class='this-news'>
				<span class='date'><?=date('d/m/Y', strtotime($item->news_addtime))?></span>
				<a href='<?=$selfurl.'showNewsList/1/0/'.$item->news_id;?>' class='title'><?=preg_replace("/^(.{1,200}).+?$/s","$1...",$item->news_body)?></a>
			</div>
		<?endforeach;?>
		</div>
	<?endif;?>
		
	<?if ($just_registered):?>
		<h2>Добро пожаловать, <?=$user->user_login;?></h2>
		<span>
		Спасибо за регистрацию на Countrypost.ru<br/>
		Как сделать заказ Вы можете посмотреть <a href='/main/showHowItWork'>тут</a>.<br/>
		Чтобы сделать заказ самостоятельно на наш адрес посмотрите все доступные адреса <a href='/client/showAddresses'>тут</a>.<br/>
		Перед тем как сделать заказ рекомендуем Вам пополнить счет заранее. Способы пополнения счета можно посмотреть <a href='#'>тут</a>.<br/> 
		Желаем приятного шопинга.
		</span>
	<?else:?>
		<h2>Статус посылок</h2>
			
		<div class='status-packet'>
			<a href='<?=$selfurl?>showOpenPackages' class='status-left-block'>
				<span>Посылки,<br />ожидающие отправки</span>
				<em><?=$package_open?> посылок(и)</em>
			</a>
			<a href='<?=$selfurl?>showSentPackages'>
				<span>Отправленные<br />посылки</span>
				<em><?=$package_sent?> посылок(и)</em>
			</a>
			<a href='<?=$selfurl?>showOpenOrders' class='status-right-block'>
				<span>Помощь<br />в покупке</span>
				<em>Перейти</em>
			</a>
		</div>
	<?endif;?>
</div>