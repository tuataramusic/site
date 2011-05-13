<div class='main-block'>
	<div class='adittional-block'>
		<div class='headlines'>
			<h2>Курсы валют</h2>
			<dl>
				<dt>Доллар</dt><dd><?=number_format($currency->USD,2)?></dd>
				<dt>Евро</dt><dd><?=number_format($currency->EUR,2)?></dd>
				<dt>Юань</dt><dd><?=number_format($currency->CNY,2)?></dd>
				<dt>Корейская вона</dt><dd><?=number_format($currency->KRW,2)?></dd>
			</dl>
			<a href='javascript:void(0)' onclick='window.open("/main/showCurrencyCalc", "Curency calc", "width=420,height=230,resizable=yes,scrollbars=yes,status=yes")'>Другие валюты</a>
		</div>
		
		<div class='map-world'>
			<a href='javascript:void(0);' class='china globus' style="cursor: default;">Китай</a>
			<a href='javascript:void(0);' class='korea globus' style="cursor: default;">Корея</a>
			
			<h2>Покупки в Китае</h2>
			<p>Покупайте любые товары на самой известной торговой площадке в мире taobao.com. Низкие цены, дешевая доставка по стране.</p>
			<h2>Покупки в Корее</h2>
			<p>Покупайте товары на торговой площадке gmarket.co.kr. Высокое качество товаров, дешевая доставка по стране и международная доставка.</p>
			<br />
			</div>
	</div>
	
	<div class='main-content'>
		<h2 class='float-left'>Добро пожаловать</h2>
		<div class='adittional-links'>
			<a href='/main/showHowItWork' class='how-it-works'>Как это работает?</a>
			<a href='/main/showFAQ' class='faq'>F.A.Q.</a>
		</div>
		<p>
			<b>Countrypost</b> – это сервис, через который вы сможете купить товары в любом <a href="<?=BASEURL?>main/showShopCatalog">интернет магазине</a> Китая и Кореи. Большинство продавцов на торговых площадках таких как <b>taobao.com</b> и <b>gmarket.co.kr</b> и др. не отправляют товары за пределы страны. Мы поможем Вам купить, оплатить и доставить ваш заказ по всему миру.<br/>Также если у вас есть возможность оплатить товары самостоятельно и вы хотите сэкономить, то вы можете воспользоваться услугой «Виртуальный адрес» (Mail Forwarding). На этот адрес вы сможете заказывать и получать товары заказанные в интернет магазинах. Сделайте заказ <a href="<?=$user ? BASEURL.$user->user_group : BASEURL.'user/registration';?>">прямо сейчас</a>.
		</p>
		<h2>Наши услуги</h2>
		<div class='services'>
			<ul>
				<li class='service1'>Использование программы снайпер<br />(бесплатно)</li>
				<li class='service2'>Предоставление почтового адреса<br />в Европе и Азии (от 14$)</li>
				<li class='service3'>Покупка в <nobr>интернет-магазинах</nobr><br />Европы и Азии</li>
				<li class='service4'>Участие в торгах, покупка и оплата лотов<br />на интернет аукционах ebay.com и др.</li>
				<li class='service5'>Система отслеживания<br />состояния заказа</li>
				<li class='service6'>Объединение покупок из разных<br />магазинов в одну посылку</li>
			</ul>
			<ul>
				<li class='service7'>Обработка заказа<br />в течении 24 часов</li>
				<li class='service8'>Индивидуальный подход,<br />оперативность, ответственность</li>
				<li class='service9'>Большой выбор удобных<br />способов оплаты</li>
				<li class='service10'>Оплата заказа только после проверки<br />товара в наличии</li>
				<li class='service11'>Простота. Вы только формируете заказ<br />на сайте и оплачиваете</li>
			</ul>
		</div>
	</div>
</div>