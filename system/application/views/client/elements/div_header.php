<? if (isset($user) && $user):?>
	<div class='top-block'>
		<div class='block-user'>
			<div class='left-block'>
			<h2>�����������</h2>
			<p>������������, <span class='big-text'><?=$user->user_login;?></span></p>
			<p><!--<a href='#'>������� ������</a>  <em>|--></em>  <a href='/user/showProfile'>�������� ������ ������</a></p>
			<div class='submit'><div><input type='submit' value='�����' onclick="javascript:window.location='<?=BASEURL?>user/logout';" /></div></div>
			</div>
			<?if (!empty($partners)): $partner = array_shift($partners)?>
			<div class='right-block'>
				<p><strong>��� <?=Func::CorrectCountryAdjective($partner->country_name)?> �����:</strong> <?=$partner->manager_addres;?> <a href='<?=$selfurl."showAddresses/".$partner->manager_user;?>'>���������</a></p>
				<?if (count($partners)>1): $partner = array_shift($partners)?>
					<p><strong>��� <?=strtolower(Func::CorrectCountryAdjective($partner->country_name));?> �����:</strong> <?=$partner->manager_addres;?> <a href='<?=$selfurl."showAddresses/".$partner->manager_user;?>'>���������</a></p>
				<?endif;?>
				<p><a href='<?=$selfurl?>showAddresses'>������ ������</a></p>
			</div>
			<?endif;?>
			<div class='center-block'>
				<h3>��� ����� �� �����: <?=$user->user_id;?></h3>
				<p>����� ������: <span class='big-text'><?=$user->user_coints;?> $</span></p>
				<p><a href='<?=$selfurl?>showAddBalance'>���������</a></p>
				<p>(<a href='/syspay/showPays/' class='anthracite-color'>��� ���������?</a>)</p>
				<p><a href='<?=$selfurl?>showPaymentHistory'>���������� ��������</a></p>
				<p><a href='<?=$selfurl?>showOutMoney'>������ �� ����� �����</a></p>
			</div>
		</div>
	</div>
	
<?else:?>

	<div class='top-block'>
		<form class='block-user autorization-inner' action='<?=BASEURL?>user/login' method="POST">
			<h2>�����������</h2>
			<div class='text-field'><div><input name="login" type='text' value='�����' onfocus='javascript: if (this.value == "�����") this.value = "";' onblur='javascript: if (this.value == "") this.value = "�����";' /></div></div>
			<div class='text-field'><div><div class='password'><input name="password" type='password' value='������' onfocus='javascript: if (this.value == "������") this.value = "";' onblur='javascript: if (this.value == "") this.value = "������";' /></div></div></div>
			<div class='submit'><div><input type='submit' value='�����' /></div></div>
			<a href='<?=BASEURL?>user/showRegistration' class='registration'>�����������</a>
			<a href='<?=BASEURL?>user/showPasswordRecovery' class='remember-password'>���������</a>
		</form>
		
	</div>
<? endif;?>

