		<form name='registration' class='registration' action='<?=BASEURL?>user/registration' method="POST">
			<h1 class='logo'><a href='/'>ContryPost - Лучший сервис покупок за рубежом</a></h1>
			<h2>регистрация</h2>
			<p>Все поля заполняются только латинскими буквами</p>
			
			<? if ($result->e <0):?>
				<em style="color:red !important"><?=$result->m?></em>
				<br />
			<?endif;?>
			<div class='field <?=$result->d && $result->d->user_login && $result->e != -17  ? 'done' :'';?>'>
				<span>Логин:</span>
				<div class='text-field'><div><input type='text' name="login" value="<?=$result->d ? $result->d->user_login :'';?>" /></div></div>
			</div>
			<div class='field <?=$result->d && $result->d->user_password ? 'done' :'';?>'>
				<span>Пароль:</span>
				<div class='text-field'><div><input type='password' name="password" value="<?=$result->d ? $result->d->user_password :'';?>" /></div></div>
			</div>
			<div class='field <?=$result->d && $result->d->repassword ? 'done' :'';?>'>
				<span>Повторите пароль:</span>
				<div class='text-field'><div><input type='password' name="repassword" value="<?=$result->d ? $result->d->repassword :'';?>" /></div></div>
			</div>
			<div class='field <?=$result->d && $result->d->user_email && $result->e != -13 ? 'done' :'';?>' >
				<span>E-mail:</span>
				<div class='text-field'><div><input type='text' name="email" value="<?=$result->d ? $result->d->user_email :'';?>" /></div></div>
			</div>
			<div class='hr'></div>
			<div class='field <?=isset($client) && $client->client_name ?'done' :'';?>'>
				<span>Имя:</span>
				<div class='text-field'><div><input type='text' name="name" value="<?=isset($client) ? $client->client_name :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_surname ?'done' :'';?>'>
				<span>Фамилия:</span>
				<div class='text-field'><div><input type='text' name="surname" value="<?=isset($client) ? $client->client_surname :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_otc ?'done' :'';?>'>
				<span>Отчество:</span>
				<div class='text-field'><div><input type='text' name="otc" value="<?=isset($client) ? $client->client_otc :'';?>" /></div></div>
			</div>
			<div class='field done' id='country'>
				<span>Страна:</span>
				<select class="select" name="country">
					<?if ($Countries):foreach($Countries as $country):?>
						<option value="<?=$country->country_id?>" <?=isset($client)&&$client->client_country==$country->country_id?'selected':''?>><?=$country->country_name?></option>
					<?endforeach;endif;?>
				</select>
			</div>
			<div class='field <?=isset($client) && $client->client_town ?'done' :'';?>'>
				<span>Город:</span>
				<div class='text-field'><div><input type='text' name="town" value="<?=isset($client) ? $client->client_town :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_address ?'done' :'';?>'>
				<span>Адрес:</span>
				<div class='text-field'><div><input type='text' name="address" value="<?=isset($client) ? $client->client_address :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_index ?'done' :'';?>'>
				<span>Индекс:</span>
				<div class='text-field'><div><input type='text' name="index" value="<?=isset($client) ? $client->client_index :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_phone ?'done' :'';?>'>
				<span>Телефон:</span>
				<div class='text-field'><div><input type='text' name="phone" value="<?=isset($client) ? $client->client_phone :'';?>" /></div></div>
			</div>
			<div class='hr'></div>
			<div class='captcha'><img src='<?=BASEURL.'user/showCaptchaImage/'.rand(0,255)?>' /></div>
			<div class='field'>
				<span>Введите текст на картинке:</span>
				<div class='text-field'><div><input type='text' name='captchacode' value='' /></div></div>
			</div>
			<div class='submit'><div><input type='submit' value='Регистрация' /></div></div>
		</form>

<?/*if ($result->e !=1):?>
<!--	показываем форму регистрации-->
	<div align="center">
		<form method="POST" action="<?=BASEURL?>user/registration">
			<table>
				<tr><td colspan="2">Все данные должны быть введены латинскими буквами!</td></tr>
			
				<tr>
					<td>Логин</td>
					<td><input type="text" name="login" value="<?=$result->d ? $result->d->user_login :'';?>"></td>
				</tr>
				<tr>
					<td>Пароль</td>
					<td><input type="password" name="password" value="<?=$result->d ? $result->d->user_password :'';?>"></td>
				</tr>
				<tr>
					<td>Повторите пароль</td>
					<td><input type="password" name="repassword" value="<?=$result->d ? $result->d->repassword :'';?>"></td>
				</tr>				
				<tr>
					<td>E-mail</td>
					<td><input type="text" name="email" value="<?=$result->d ? $result->d->user_email :'';?>"></td>
				</tr>

				<tr><td colspan="2"><hr></td></tr>
				
				<tr>
					<td>Имя</td>
					<td><input type="text" name="name" value="<?=isset($client) ? $client->client_name :'';?>"></td>
				</tr>
				<tr>
					<td>Отчество</td>
					<td><input type="text" name="otc" value="<?=isset($client) ? $client->client_otc :'';?>"></td>
				</tr>
				<tr>
					<td>Фамилия</td>
					<td><input type="text" name="surname" value="<?=isset($client) ? $client->client_surname :'';?>"></td>
				</tr>
				<tr>
					<td>Страна</td>
					<td>
						<select name="country">
							<option>выберите...</option>
							<?if (count($countries)>0): foreach ($countries as $country):?>
								<option value="<?=$country->country_id;?>" <?= (isset($client) && $client->client_country==$country->country_id) ? 'selected' :'';?>><?=$country->country_name?></option>
							<?endforeach; endif;?>							
						</select>
					</td>
				</tr>
				<tr>
					<td>Город</td>
					<td><input type="text" name="town" value="<?=isset($client) ? $client->client_town :'';?>"></td>
				</tr>
				<tr>
					<td>Индекс</td>
					<td><input type="text" name="index" value="<?=isset($client) ? $client->client_index :'';?>"></td>
				</tr>
				<tr>
					<td>Адрес</td>
					<td><input type="text" name="address" value="<?=isset($client) ? $client->client_address :'';?>"><br/>
					* пример: Tverskaya 5, 24
					</td>
				<tr>
					<td>Телефон</td>
					<td><input type="text" name="phone" value="<?=isset($client) ? $client->client_phone :'';?>"><br/>
					* указывайте только Ваш номер в международном формате
					</td>
				</tr>
				<tr>
					<td>Проверочный код</td>
					<td>
						<img src="<?=BASEURL.'user/showCaptchaImage/'.rand(0,255)?>">
						<input type="text" name="captchacode" value=""><br/>
					* введите изображение указанное на картинке
					</td>
				</tr>
				<tr><td colspan="2"><hr></td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Регистрация"></td>
				</tr>
			</table>	
		</form>
	</div>
<?endif;*/?>
