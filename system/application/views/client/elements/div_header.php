<? if (isset($user) && $user):?>
	<div class='top-block'>
		<div class='block-user'>
			<div class='left-block'>
			<h2>Авторизация</h2>
			<p>Здравствуйте, <span class='big-text'><?=$user->user_login;?></span></p>
			<p><!--<a href='#'>Сменить пароль</a>  <em>|--></em>  <a href='/user/showProfile'>Изменить личные данные</a></p>
			<div class='submit'><div><input type='submit' value='Выйти' onclick="javascript:window.location='<?=BASEURL?>user/logout';" /></div></div>
			</div>
			<?if (!empty($partners)): $partner = array_shift($partners)?>
			<div class='right-block'>
				<p><strong>Ваш <?=Func::CorrectCountryAdjective($partner->country_name)?> адрес:</strong> <?=$partner->manager_addres;?> <a href='<?=$selfurl."showAddresses/".$partner->manager_user;?>'>Подробнее</a></p>
				<?if (count($partners)>1): $partner = array_shift($partners)?>
					<p><strong>Ваш <?=strtolower(Func::CorrectCountryAdjective($partner->country_name));?> адрес:</strong> <?=$partner->manager_addres;?> <a href='<?=$selfurl."showAddresses/".$partner->manager_user;?>'>Подробнее</a></p>
				<?endif;?>
				<p><a href='<?=$selfurl?>showAddresses'>Другие адреса</a></p>
			</div>
			<?endif;?>
			<div class='center-block'>
				<h3>ВАШ НОМЕР НА САЙТЕ: <?=$user->user_id;?></h3>
				<p>Общий баланс: <span class='big-text'><?=$user->user_coints;?> $</span></p>
				<p><a href='<?=$selfurl?>showAddBalance'>Пополнить</a></p>
				<p>(<a href='/syspay/showPays/' class='anthracite-color'>Как пополнить?</a>)</p>
				<p><a href='<?=$selfurl?>showPaymentHistory'>Статистика платежей</a></p>
				<p><a href='<?=$selfurl?>showOutMoney'>Заявка на вывод денег</a></p>
			</div>
		</div>
	</div>
	
<?else:?>

	<div class='top-block'>
		<form class='block-user autorization-inner' action='<?=BASEURL?>user/login' method="POST">
			<h2>Авторизация</h2>
			<div class='text-field'><div><input name="login" type='text' value='Логин' onfocus='javascript: if (this.value == "Логин") this.value = "";' onblur='javascript: if (this.value == "") this.value = "Логин";' /></div></div>
			<div class='text-field'><div><div class='password'><input name="password" type='password' value='Пароль' onfocus='javascript: if (this.value == "Пароль") this.value = "";' onblur='javascript: if (this.value == "") this.value = "Пароль";' /></div></div></div>
			<div class='submit'><div><input type='submit' value='Войти' /></div></div>
			<a href='<?=BASEURL?>user/showRegistration' class='registration'>Регистрация</a>
			<a href='<?=BASEURL?>user/showPasswordRecovery' class='remember-password'>Напомнить</a>
		</form>
		
	</div>
<? endif;?>

