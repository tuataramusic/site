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
		<li><a href="<?=BASEURL?>main/showHowItWork">��� ��� ��������</a></li>
		<li><a href="<?=$user ? BASEURL.$user->user_group : BASEURL.'user/registration';?>">������ �������</a></li>
<!--		<li><a href="< ?=BASEURL?>main/showPays">������� ������</a></li>-->
		<li><a href="<?=BASEURL?>main/showPricelist">������ �� ��������</a></li>
		<li><a href="<?=BASEURL?>main/showCollaboration">��������������</a></li>
		<li><a href="<?=BASEURL?>main/showShopCatalog">������� �������� ���������</a></li>
		<li><a href="<?=BASEURL?>main/showContacts">��������</a></li>
	</ul>
</div>

<div>&nbsp;<br /></div>

<div class="adminMenu">
	<br />
	<br />
</div>