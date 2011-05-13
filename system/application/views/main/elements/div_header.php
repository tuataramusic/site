
		<div class='header'>
			<h1 class='logo'><a href='/'>CountryPost - ������ ������ ������� �� �������</a></h1>
			<ul class='menu'>
				<li><a href='<?=BASEURL?>main/showHowItWork'>��� ��� ��������</a></li>
				<li><a href='<?=$user ? BASEURL.$user->user_group : BASEURL.'user/showRegistration';?>'>������ �������</a></li>
<!--				<li><a href='< ?=BASEURL?>main/showPays'>������� ������</a></li>-->
				<li><a href='<?=BASEURL?>main/showPricelist'>������ �� ��������</a></li>
				<li><a href='<?=BASEURL?>main/showCollaboration'>��������������</a></li>
				<li><a href='<?=BASEURL?>main/showShopCatalog'>������� ���������</a></li>
				<li><a href='<?=BASEURL?>main/showContacts'>��������</a></li>
			</ul>
		</div>
		
		
		<? if (isset($user) && $user && $pageinfo['mname'] != 'index'):?>
		<div class='top-block'>
			<div class='block-user'>
				<div class='left-block'>
				<h2>�����������</h2>
				<p>������������, <span class='big-text'><?=$user->user_login;?></span></p>
				<?if ($user->user_group == 'client'):?>
					<p><a href='<?=BASEURL?>user/showProfile'>�������� ������ ������</a></p>
				<?endif;?>
				<div class='submit'><div><input type='submit' value='�����' onclick="javascript:window.location='<?=BASEURL?>user/logout';" /></div></div>
				</div>
				<?if (!empty($partners)): $partner = array_shift($partners)?>
				<div class='right-block'>
					<p><strong>��� <?=Func::CorrectCountryAdjective($partner->country_name)?> �����:</strong> <?=$partner->manager_addres;?> <a href='<?="/client/showAddresses/".$partner->manager_user;?>'>���������</a></p>
					<?if (count($partners)>1): $partner = array_shift($partners)?>
						<p><strong>��� <?=strtolower(Func::CorrectCountryAdjective($partner->country_name));?> �����:</strong> <?=$partner->manager_addres;?> <a href='<?="/client/showAddresses/".$partner->manager_user;?>'>���������</a></p>
					<?endif;?>
					<p><a href='/client/showAddresses'>������ ������</a></p>
				</div>
				<?endif;?>
				<div class='center-block'>
					<h3>��� ����� �� �����: <?=$user->user_id;?></h3>
					<p>����� ������: <span class='big-text'><?=$user->user_coints;?> $</span></p>
					<p><a href='<?= $selfurl?>showAddBalance'>���������</a></p>
					<p>(<a href='#' class='anthracite-color'>��� ���������?</a>)</p>
					<p><a href='<?= $user->user_group?>/showPaymentHistory'>���������� ��������</a></p>
					<p><a href='/<?= $user->user_group?>/showOutMoney'>������ �� ����� �����</a></p>
				</div>
			</div>
		</div>

		<? elseif (isset($user) && $user && $pageinfo['mname'] == 'index'):?>

		<div class='top-block'>
			<div class='step-by-step'>
				<div class='step-one'>�������� �����, <span>������� ������ ������</span></div>
				<div class='step-two'>����������� ����� <span>�� ����� �����</span></div>
				<div class='step-three'>�������� �������</div>
			</div>
		<div class='autorization autorization-ok'>
				<h2>�����������</h2>
				<p><b>������������:</b><br /><span class='big-text'><?=$user->user_login;?></span></p>
				<p><b>��� ����� �� �����:</b> <?=$user->user_id;?></p>
				<p><b>����� �������:</b><br /><span class='big-text'><?=$user->user_coints;?> $</span></p>
				<?if ($user->user_group == 'client'):?>
					<p><a href='<?=BASEURL?>user/showProfile'>�������� ������ ������</a></p><br />
				<?endif;?>
				<div class='submit'><div><input type='submit' value='�����'  onclick="javascript:window.location='<?=BASEURL?>user/logout';" /></div></div>
			</div>
		</div>
		
		<? elseif ($pageinfo['mname'] == 'index'):?>
		<div class='top-block'>
			<div class='step-by-step'>
				<div class='step-one'>�������� �����, <span>������� ������ ������</span></div>
				<div class='step-two'>����������� ����� <span>�� ����� �����</span></div>
				<div class='step-three'>�������� �������</div>
			</div>
			<form class='autorization' method="post" action='<?=BASEURL?>user/login'>
				<h2>�����������</h2>
				<div class='text-field'><div><input type='text' name="login" value='�����' onfocus='javascript: if (this.value == "�����") this.value = "";' onblur='javascript: if (this.value == "") this.value = "�����";' /></div></div>
				<div class='text-field'><div><div class='password'><input type='password' name="password" id="password" value='������' onfocus='javascript: if (this.value == "������") this.value = "";' onblur='javascript: if (this.value == "") this.value = "������";' /></div></div></div>
				<div class='submit'><div><input type='submit' value='�����' /></div></div>
				<a href='<?=BASEURL?>user/showPasswordRecovery' class='remember-password'>���������</a>
			</form>
			<a href='<?=BASEURL?>user/showRegistration' class='registration'>�����������</a>
			
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