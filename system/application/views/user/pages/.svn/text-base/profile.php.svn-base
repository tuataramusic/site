		<form name='registration' class='registration' action='<?=BASEURL?>user/showProfile' method="POST">
			<h1 class='logo'><a href='/'>ContryPost - Лучший сервис покупок за рубежом</a></h1>
			<h2>личные данные</h2>
			<p>Все поля заполняются только латинскими буквами</p>
			
			<? if ($result->e):?>
				<em style="color:<?= $result->e<0 ? 'red' : 'green'; ?> !important"><?=$result->m?></em>
				<br />
			<?endif;?>
			<div class='field <?=$user && $user->user_login && $result->e != -17  ? 'done' :'';?>'>
				<span>Логин:</span>
				<div class='text-field'><div><input type='text' name="login" value="<?=$user ? $user->user_login :'';?>" /></div></div>
			</div>
			<div class='field <?=$user && $user->user_password ? 'done' :'';?>'>
				<span>Пароль:</span>
				<div class='text-field'><div><input type='password' name="password" value="" /></div></div>
			</div>
			<div class='field <?=$user && $user->repassword ? 'done' :'';?>'>
				<span>Повторите пароль:</span>
				<div class='text-field'><div><input type='password' name="repassword" value="" /></div></div>
			</div>
			<div class='field <?=$user && $user->user_email && $result->e != -13 ? 'done' :'';?>' >
				<span>E-mail:</span>
				<div class='text-field'><div><input type='text' name="email" value="<?=$user ? $user->user_email :'';?>" /></div></div>
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
			<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
			<input type="hidden" name='action' value='change' />
		</form>