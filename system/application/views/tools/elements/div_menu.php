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
			<li><a href="<?=BASEURL?>main/showPays">Способы оплаты</a></li>
			<li><a href="<?=BASEURL?>main/showTariffs">Тарифы на доставку</a></li>
			<li><a href="<?=BASEURL?>main/showCollaboration">Сотрудничество</a></li>
			<li><a href="<?=BASEURL?>main/showShopCatalog">Каталог интернет магазинов</a></li>
			<li><a href="<?=BASEURL?>main/showContacts">Контакты</a></li>
		
	</ul>
</div>

<div>&nbsp;<br /></div>

<div class="adminMenu">
	<a href="<?=$selfurl?>showPaymentHistory">Добавить новую посылку</a>
	<br />
	<a href="<?=$selfurl?>showEditServicesPrice">Изменение тарифов на доставку</a>
	<br />
	<a href="<?=$selfurl?>showPaymentHistory">Изменить цены за услуги</a>
	<br />
	<a href="<?=$selfurl?>showEditNews">Редактирование новостей</a>
	<br />
	<a href="<?=$selfurl?>showEditFAQ">Редактирование FAQа</a>
	<br />
	<a href="<?=$selfurl?>showEditFAQ">Партнеры</a>
	<br />
	<a href="<?=$selfurl?>showOrderToOut">Заявки на вывод</a>
</div>