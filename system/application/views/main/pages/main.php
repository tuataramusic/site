<div class='main-block'>
	<div class='adittional-block'>
		<div class='headlines'>
			<h2>����� �����</h2>
			<dl>
				<dt>������</dt><dd><?=number_format($currency->USD,2)?></dd>
				<dt>����</dt><dd><?=number_format($currency->EUR,2)?></dd>
				<dt>����</dt><dd><?=number_format($currency->CNY,2)?></dd>
				<dt>��������� ����</dt><dd><?=number_format($currency->KRW,2)?></dd>
			</dl>
			<a href='javascript:void(0)' onclick='window.open("/main/showCurrencyCalc", "Curency calc", "width=420,height=230,resizable=yes,scrollbars=yes,status=yes")'>������ ������</a>
		</div>
		
		<div class='map-world'>
			<a href='javascript:void(0);' class='china globus' style="cursor: default;">�����</a>
			<a href='javascript:void(0);' class='korea globus' style="cursor: default;">�����</a>
			
			<h2>������� � �����</h2>
			<p>��������� ����� ������ �� ����� ��������� �������� �������� � ���� taobao.com. ������ ����, ������� �������� �� ������.</p>
			<h2>������� � �����</h2>
			<p>��������� ������ �� �������� �������� gmarket.co.kr. ������� �������� �������, ������� �������� �� ������ � ������������� ��������.</p>
			<br />
			</div>
	</div>
	
	<div class='main-content'>
		<h2 class='float-left'>����� ����������</h2>
		<div class='adittional-links'>
			<a href='/main/showHowItWork' class='how-it-works'>��� ��� ��������?</a>
			<a href='/main/showFAQ' class='faq'>F.A.Q.</a>
		</div>
		<p>
			<b>Countrypost</b> � ��� ������, ����� ������� �� ������� ������ ������ � ����� <a href="<?=BASEURL?>main/showShopCatalog">�������� ��������</a> ����� � �����. ����������� ��������� �� �������� ��������� ����� ��� <b>taobao.com</b> � <b>gmarket.co.kr</b> � ��. �� ���������� ������ �� ������� ������. �� ������� ��� ������, �������� � ��������� ��� ����� �� ����� ����.<br/>����� ���� � ��� ���� ����������� �������� ������ �������������� � �� ������ ����������, �� �� ������ ��������������� ������� ������������ ����� (Mail Forwarding). �� ���� ����� �� ������� ���������� � �������� ������ ���������� � �������� ���������. �������� ����� <a href="<?=$user ? BASEURL.$user->user_group : BASEURL.'user/registration';?>">����� ������</a>.
		</p>
		<h2>���� ������</h2>
		<div class='services'>
			<ul>
				<li class='service1'>������������� ��������� �������<br />(���������)</li>
				<li class='service2'>�������������� ��������� ������<br />� ������ � ���� (�� 14$)</li>
				<li class='service3'>������� � <nobr>��������-���������</nobr><br />������ � ����</li>
				<li class='service4'>������� � ������, ������� � ������ �����<br />�� �������� ��������� ebay.com � ��.</li>
				<li class='service5'>������� ������������<br />��������� ������</li>
				<li class='service6'>����������� ������� �� ������<br />��������� � ���� �������</li>
			</ul>
			<ul>
				<li class='service7'>��������� ������<br />� ������� 24 �����</li>
				<li class='service8'>�������������� ������,<br />�������������, ���������������</li>
				<li class='service9'>������� ����� �������<br />�������� ������</li>
				<li class='service10'>������ ������ ������ ����� ��������<br />������ � �������</li>
				<li class='service11'>��������. �� ������ ���������� �����<br />�� ����� � �����������</li>
			</ul>
		</div>
	</div>
</div>