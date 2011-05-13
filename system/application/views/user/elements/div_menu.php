<style>
.menu li {
	float:	left;
	border:	0.5px solid;
	float:	left;
	list-style: none outside none;
	padding: 23px;
}
</style>

<div id="menu">
	<ul class="menu">
		<li><a href="<?=BASEURL?>main/showHowItWork">Как это работает</a></li>
		<li><a href="<?=$user ? BASEURL.$user->user_group : BASEURL.'user/registration';?>">Личный кабинет</a></li>
<!--		<li><a href="< ?=BASEURL?>main/showPays">Способы оплаты</a></li>-->
		<li><a href="<?=BASEURL?>main/showTariffs">Тарифы на доставку</a></li>
		<li><a href="<?=BASEURL?>main/showCollaboration">Сотрудничество</a></li>
		<li><a href="<?=BASEURL?>main/showShopCatalog">Каталог интернет магазинов</a></li>
		<li><a href="<?=BASEURL?>main/showContacts">Контакты</a></li>
	</ul>
</div>