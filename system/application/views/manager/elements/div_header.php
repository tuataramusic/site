<div class='top-block'>
	<div class='block-user'>
		<div class='left-block'>
		<h2>�����������</h2>
		<p>������������, <span class='big-text'><?=$user->user_login;?></span></p>
		<div class='submit'><div><input type='submit' value='�����' onclick="javascript:window.location='<?=BASEURL?>user/logout';" /></div></div>
		</div>
		<div class='center-block'>
			<h3>��� ����� �� �����: <?=$user->user_id;?></h3>
			<p>����� �������: <span class='big-text'><?=$user->user_coints;?> $</span></p>
			<p><a href='#'>���������</a></p>
			<p>(<a href='#' class='anthracite-color'>��� ���������?</a>)</p>
			<p><a href='<?=$selfurl?>showPaymentHistory'>���������� ��������</a></p>
			<p><a href='<?=$selfurl?>showOutMoney'>������ �� ����� �����</a></p>
		</div>
	</div>
</div>