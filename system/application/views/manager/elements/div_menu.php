<style>
.menu li {
	float:	left;
	border:	0.5px solid;
	float:	left;
	list-style: none outside none;
	padding: 23px;
}

.clientMenu a{
	margin: 100px;
	float: clear;
}
</style>

<div id="menu">
	<ul class="menu">
		
			<li><a href="<?=BASEURL?>main/showHowItWork">Как это работает</a></li>
			<li><a href="<?=$selfurl?>">Личный кабинет</a></li>
			<li><a href="<?=BASEURL?>main/showPays">Способы оплаты</a></li>
			<li><a href="<?=BASEURL?>main/showPricelist">Тарифы на доставку</a></li>
			<li><a href="<?=BASEURL?>main/showCollaboration">Сотрудничество</a></li>
			<li><a href="<?=BASEURL?>main/showShopCatalog">Каталог интернет магазинов</a></li>
			<li><a href="<?=BASEURL?>main/showContacts">Контакты</a></li>
		
	</ul>
</div>

<div>&nbsp;<br /></div>

<div class="clientMenu">
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
</div>