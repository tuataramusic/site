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
		
			<li><a href="<?=BASEURL?>main/showHowItWork">��� ��� ��������</a></li>
			<li><a href="<?=$selfurl?>">������ �������</a></li>
			<li><a href="<?=BASEURL?>main/showPays">������� ������</a></li>
			<li><a href="<?=BASEURL?>main/showPricelist">������ �� ��������</a></li>
			<li><a href="<?=BASEURL?>main/showCollaboration">��������������</a></li>
			<li><a href="<?=BASEURL?>main/showShopCatalog">������� �������� ���������</a></li>
			<li><a href="<?=BASEURL?>main/showContacts">��������</a></li>
		
	</ul>
</div>

<div>&nbsp;<br /></div>

<div class="clientMenu">
	<a href="<?=$selfurl?>showNewPackages">�����</a>
	<br />
	<a href="<?=$selfurl?>showPayedPackages">����������</a>
	<br />
	<a href="<?=$selfurl?>showSentPackages">������������</a>
	<br />
	<a href="<?=$selfurl?>showOpenOrders">������ "������ � �������"</a>
	<br />
	<a href="<?=$selfurl?>showSentOrders">�������� ������</a>
	<br />
	<a href="<?=$selfurl?>showAddPackage">�������� �������</a>
</div>