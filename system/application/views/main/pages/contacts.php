<div class="content">
	<h2>Наши контакты</h2>
	<div class='table'>
					<div class='angle angle-lt'></div>
					<div class='angle angle-rt'></div>
					<div class='angle angle-lb'></div>
					<div class='angle angle-rb'></div>
	<p>Тел. <b>+7 (495) 956-88-50 доб. 293406</b></p>
	<p>Тел. <b>+7 (495) 956-88-50 доб. 293406</b></p>
	<p>Skype: <a href="skype:country_post">country_post</a></p>
	<p>Email:</p>
	<p>По общим вопросам: <a href="mailto:info@countrypost.ru">info@countrypost.ru</a></p>
	<p>По вопросам заказов из Китая: <a href="mailto:china@countrypost.ru">china@countrypost.ru</a></p>
	<p>По вопросам заказов из Кореи: <a href="mailto:korea@countrypost.ru">korea@countrypost.ru</a></p>
	</div>
	<br />
	<br />
  	<b>Также Вы можете задать нам вопрос, воспользовавшись формой, указанной ниже:</b>
	<br /><br />
	<div class="contacts">
	<form name='registration' class='registration' action='<?=BASEURL?>user/registration' method="POST">
		<? if ($result->e <0):?>
			<em style="color:red !important"><?=$result->m?></em>
			<br />
		<?endif;?>
		<?if (!$user){?>
		Фамилия, Имя:
		<div class='field'>
			<div class='text-field'><div><input type='text' name="fio" value="" /></div></div>
		</div>
		Email:
		<div class='field'>
			<div class='text-field'><div><input type='text' name="email" value="" /></div></div>
		</div>
		Телефон:</span>
		<div class='field done'>
			<div class='text-field'><div><input type='text' name="phone" value="" /></div></div>
		</div>
		<?}?>
		<div class='add-comment'>
			<textarea class="textarea" name="message"/></textarea>
		</div>
		<br />
		<div class='captcha'><img src='<?=BASEURL.'user/showCaptchaImage/'.rand(0,255)?>' /></div>
		Введите текст на картинке:
		<div class='field'>
			<div class='text-field'><div><input type='text' name='captchacode' value='' /></div></div>
		</div>
		<div class='submit'><div><input type='submit' value='Отправить' /></div></div>
	</form>

	</div>
</div>