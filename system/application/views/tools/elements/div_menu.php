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
			<li><a href="<?=$selfurl?>">������ �������</a></li>
			<li><a href="<?=BASEURL?>main/showPays">������� ������</a></li>
			<li><a href="<?=BASEURL?>main/showTariffs">������ �� ��������</a></li>
			<li><a href="<?=BASEURL?>main/showCollaboration">��������������</a></li>
			<li><a href="<?=BASEURL?>main/showShopCatalog">������� �������� ���������</a></li>
			<li><a href="<?=BASEURL?>main/showContacts">��������</a></li>
		
	</ul>
</div>

<div>&nbsp;<br /></div>

<div class="adminMenu">
	<a href="<?=$selfurl?>showPaymentHistory">�������� ����� �������</a>
	<br />
	<a href="<?=$selfurl?>showEditServicesPrice">��������� ������� �� ��������</a>
	<br />
	<a href="<?=$selfurl?>showPaymentHistory">�������� ���� �� ������</a>
	<br />
	<a href="<?=$selfurl?>showEditNews">�������������� ��������</a>
	<br />
	<a href="<?=$selfurl?>showEditFAQ">�������������� FAQ�</a>
	<br />
	<a href="<?=$selfurl?>showEditFAQ">��������</a>
	<br />
	<a href="<?=$selfurl?>showOrderToOut">������ �� �����</a>
</div>