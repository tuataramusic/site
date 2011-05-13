
		<div class='header'>
			<h1 class='logo'><a href='/'>CountryPost - Лучший сервис покупок за рубежом</a></h1>
			<ul class='menu'>
				<li><a href='<?=BASEURL?>main/showHowItWork'>Как это работает</a></li>
				<li><a href='<?=$user ? BASEURL.$user->user_group : BASEURL.'user/showRegistration';?>'>Личный кабинет</a></li>
<!--				<li><a href='< ?=BASEURL?>main/showPays'>Способы оплаты</a></li>-->
				<li><a href='<?=BASEURL?>main/showPricelist'>Тарифы на доставку</a></li>
				<li><a href='<?=BASEURL?>main/showCollaboration'>Сотрудничество</a></li>
				<li><a href='<?=BASEURL?>main/showShopCatalog'>Каталог магазинов</a></li>
				<li><a href='<?=BASEURL?>main/showContacts'>Контакты</a></li>
			</ul>
		</div>
		
		
		<? if (isset($user) && $user && $pageinfo['mname'] != 'index'):?>
		<div class='top-block'>
			<div class='block-user'>
				<div class='left-block'>
				<h2>Авторизация</h2>
				<p>Здравствуйте, <span class='big-text'><?=$user->user_login;?></span></p>
				<?if ($user->user_group == 'client'):?>
					<p><a href='<?=BASEURL?>user/showProfile'>Изменить личные данные</a></p>
				<?endif;?>
				<div class='submit'><div><input type='submit' value='Выйти' onclick="javascript:window.location='<?=BASEURL?>user/logout';" /></div></div>
				</div>
				<?if (!empty($partners)): $partner = array_shift($partners)?>
				<div class='right-block'>
					<p><strong>Ваш <?=Func::CorrectCountryAdjective($partner->country_name)?> адрес:</strong> <?=$partner->manager_addres;?> <a href='<?="/client/showAddresses/".$partner->manager_user;?>'>Подробнее</a></p>
					<?if (count($partners)>1): $partner = array_shift($partners)?>
						<p><strong>Ваш <?=strtolower(Func::CorrectCountryAdjective($partner->country_name));?> адрес:</strong> <?=$partner->manager_addres;?> <a href='<?="/client/showAddresses/".$partner->manager_user;?>'>Подробнее</a></p>
					<?endif;?>
					<p><a href='/client/showAddresses'>Другие адреса</a></p>
				</div>
				<?endif;?>
				<div class='center-block'>
					<h3>ВАШ НОМЕР НА САЙТЕ: <?=$user->user_id;?></h3>
					<p>Общий баланс: <span class='big-text'><?=$user->user_coints;?> $</span></p>
					<p><a href='<?= $selfurl?>showAddBalance'>Пополнить</a></p>
					<p>(<a href='#' class='anthracite-color'>Как пополнить?</a>)</p>
					<p><a href='<?= $user->user_group?>/showPaymentHistory'>Статистика платежей</a></p>
					<p><a href='/<?= $user->user_group?>/showOutMoney'>Заявка на вывод денег</a></p>
				</div>
			</div>
		</div>

		<? elseif (isset($user) && $user && $pageinfo['mname'] == 'index'):?>

		<div class='top-block'>
			<div class='step-by-step'>
				<div class='step-one'>Выберите товар, <span>который хотите купить</span></div>
				<div class='step-two'>Сформируйте заказ <span>на нашем сайте</span></div>
				<div class='step-three'>Получите посылку</div>
			</div>
		<div class='autorization autorization-ok'>
				<h2>Авторизация</h2>
				<p><b>Здравствуйте:</b><br /><span class='big-text'><?=$user->user_login;?></span></p>
				<p><b>Ваш номер на сайте:</b> <?=$user->user_id;?></p>
				<p><b>Общий балланс:</b><br /><span class='big-text'><?=$user->user_coints;?> $</span></p>
				<?if ($user->user_group == 'client'):?>
					<p><a href='<?=BASEURL?>user/showProfile'>Изменить личные данные</a></p><br />
				<?endif;?>
				<div class='submit'><div><input type='submit' value='Выйти'  onclick="javascript:window.location='<?=BASEURL?>user/logout';" /></div></div>
			</div>
		</div>
		
		<? elseif ($pageinfo['mname'] == 'index'):?>
		<div class='top-block'>
			<div class='step-by-step'>
				<div class='step-one'>Выберите товар, <span>который хотите купить</span></div>
				<div class='step-two'>Сформируйте заказ <span>на нашем сайте</span></div>
				<div class='step-three'>Получите посылку</div>
			</div>
			<form class='autorization' method="post" action='<?=BASEURL?>user/login'>
				<h2>Авторизация</h2>
				<div class='text-field'><div><input type='text' name="login" value='Логин' onfocus='javascript: if (this.value == "Логин") this.value = "";' onblur='javascript: if (this.value == "") this.value = "Логин";' /></div></div>
				<div class='text-field'><div><div class='password'><input type='password' name="password" id="password" value='Пароль' onfocus='javascript: if (this.value == "Пароль") this.value = "";' onblur='javascript: if (this.value == "") this.value = "Пароль";' /></div></div></div>
				<div class='submit'><div><input type='submit' value='Войти' /></div></div>
				<a href='<?=BASEURL?>user/showPasswordRecovery' class='remember-password'>Напомнить</a>
			</form>
			<a href='<?=BASEURL?>user/showRegistration' class='registration'>Регистрация</a>
			
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