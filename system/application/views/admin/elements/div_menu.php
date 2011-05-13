<style>
.menu li {
	float:	left;
	border:	0.5px solid;
	float:	left;
	list-style: none outside none;
	padding: 23px;
}

.adminMenu a{
	margin: 100px;
	float: clear;
}
</style>

<div id="menu">
	<ul class="menu">
		
			<li><a href="<?=BASEURL?>main/showHowItWork">Как это работает</a></li>
			<li><a href="<?=$selfurl?>">Личный кабинет</a></li>
<!--			<li><a href="< ?=BASEURL?>main/showPays">Способы оплаты</a></li>-->
			<li><a href="<?=BASEURL?>main/showPricelist">Тарифы на доставку</a></li>
			<li><a href="<?=BASEURL?>main/showCollaboration">Сотрудничество</a></li>
			<li><a href="<?=BASEURL?>main/showShopCatalog">Каталог интернет магазинов</a></li>
			<li><a href="<?=BASEURL?>main/showContacts">Контакты</a></li>
		
	</ul>
</div>

<div>&nbsp;<br /></div>

<div class="adminMenu">
	<a href="<?=$selfurl?>showNewPackages">Новые</a>
	<br />
	<a href="<?=$selfurl?>showPayedPackages">Оплаченные</a>
	<br />
	<a href="<?=$selfurl?>showSentPackages">Отправленные</a>
	<br />
	<a href="<?=$selfurl?>showOpenOrders">Заказы "Помощь в покупке"</a>
	<br />
	<a href="<?=$selfurl?>showSentOrders">Закрытые заказы</a>
	<br />
	<a href="<?=$selfurl?>showAddPackage">Добавить посылку</a>
	<br />
	<a href="<?=$selfurl?>editPricelist">Изменить тарифы за доставку</a>
	<br />
	<a href="<?=$selfurl?>showAddDelivery">Добавить способ доставки</a>
	<br />
	<a href="<?=$selfurl?>showEditServicesPrice">Изменить цены за услуги</a>
	<br />
	<a href="<?=$selfurl?>showEditNews">Редактирование новостей</a>
	<br />
	<a href="<?=$selfurl?>showEditFAQ">Редактирование FAQа</a>
	<br />
	<a href="<?=$selfurl?>showPartners">Партнеры</a>
	<br />
	<a href="<?=$selfurl?>showClients">Клиенты</a>
	<br />
	<a href="<?=$selfurl?>showPaymentHistory">История платежей</a>
	<br />
	<a href="<?=$selfurl?>showOrderToOut">Заявки на вывод</a>
	<br />
	<a href="<?=$selfurl?>showCountries">Страны</a>
</div>