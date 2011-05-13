<div class='top-block'>
	<div class='block-user'>
		<div class='left-block'>
		<h2>Авторизация</h2>
		<p>Здравствуйте, <span class='big-text'><?=$user->user_login;?></span></p>
		<div class='submit'><div><input type='submit' value='Выйти' onclick="javascript:window.location='<?=BASEURL?>user/logout';" /></div></div>
		</div>
		<div class='center-block'>
			<h3>ВАШ НОМЕР НА САЙТЕ: <?=$user->user_id;?></h3>
			<p>Общий балланс: <span class='big-text'><?=$user->user_coints;?> $</span></p>
			<p><a href='#'>Пополнить</a></p>
			<p>(<a href='#' class='anthracite-color'>Как пополнить?</a>)</p>
			<p><a href='<?=$selfurl?>showPaymentHistory'>Статистика платежей</a></p>
			<p><a href='<?=$selfurl?>showOutMoney'>Заявка на вывод денег</a></p>
		</div>
	</div>
</div>