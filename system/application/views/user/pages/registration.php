		<form name='registration' class='registration' action='<?=BASEURL?>user/registration' method="POST">
			<h1 class='logo'><a href='/'>ContryPost - ������ ������ ������� �� �������</a></h1>
			<h2>�����������</h2>
			<p>��� ���� ����������� ������ ���������� �������</p>
			
			<? if ($result->e <0):?>
				<em style="color:red !important"><?=$result->m?></em>
				<br />
			<?endif;?>
			<div class='field <?=$result->d && $result->d->user_login && $result->e != -17  ? 'done' :'';?>'>
				<span>�����:</span>
				<div class='text-field'><div><input type='text' name="login" value="<?=$result->d ? $result->d->user_login :'';?>" /></div></div>
			</div>
			<div class='field <?=$result->d && $result->d->user_password ? 'done' :'';?>'>
				<span>������:</span>
				<div class='text-field'><div><input type='password' name="password" value="<?=$result->d ? $result->d->user_password :'';?>" /></div></div>
			</div>
			<div class='field <?=$result->d && $result->d->repassword ? 'done' :'';?>'>
				<span>��������� ������:</span>
				<div class='text-field'><div><input type='password' name="repassword" value="<?=$result->d ? $result->d->repassword :'';?>" /></div></div>
			</div>
			<div class='field <?=$result->d && $result->d->user_email && $result->e != -13 ? 'done' :'';?>' >
				<span>E-mail:</span>
				<div class='text-field'><div><input type='text' name="email" value="<?=$result->d ? $result->d->user_email :'';?>" /></div></div>
			</div>
			<div class='hr'></div>
			<div class='field <?=isset($client) && $client->client_name ?'done' :'';?>'>
				<span>���:</span>
				<div class='text-field'><div><input type='text' name="name" value="<?=isset($client) ? $client->client_name :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_surname ?'done' :'';?>'>
				<span>�������:</span>
				<div class='text-field'><div><input type='text' name="surname" value="<?=isset($client) ? $client->client_surname :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_otc ?'done' :'';?>'>
				<span>��������:</span>
				<div class='text-field'><div><input type='text' name="otc" value="<?=isset($client) ? $client->client_otc :'';?>" /></div></div>
			</div>
			<div class='field done' id='country'>
				<span>������:</span>
				<select class="select" name="country">
					<?if ($Countries):foreach($Countries as $country):?>
						<option value="<?=$country->country_id?>" <?=isset($client)&&$client->client_country==$country->country_id?'selected':''?>><?=$country->country_name?></option>
					<?endforeach;endif;?>
				</select>
			</div>
			<div class='field <?=isset($client) && $client->client_town ?'done' :'';?>'>
				<span>�����:</span>
				<div class='text-field'><div><input type='text' name="town" value="<?=isset($client) ? $client->client_town :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_address ?'done' :'';?>'>
				<span>�����:</span>
				<div class='text-field'><div><input type='text' name="address" value="<?=isset($client) ? $client->client_address :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_index ?'done' :'';?>'>
				<span>������:</span>
				<div class='text-field'><div><input type='text' name="index" value="<?=isset($client) ? $client->client_index :'';?>" /></div></div>
			</div>
			<div class='field <?=isset($client) && $client->client_phone ?'done' :'';?>'>
				<span>�������:</span>
				<div class='text-field'><div><input type='text' name="phone" value="<?=isset($client) ? $client->client_phone :'';?>" /></div></div>
			</div>
			<div class='hr'></div>
			<div class='captcha'><img src='<?=BASEURL.'user/showCaptchaImage/'.rand(0,255)?>' /></div>
			<div class='field'>
				<span>������� ����� �� ��������:</span>
				<div class='text-field'><div><input type='text' name='captchacode' value='' /></div></div>
			</div>
			<div class='submit'><div><input type='submit' value='�����������' /></div></div>
		</form>

<?/*if ($result->e !=1):?>
<!--	���������� ����� �����������-->
	<div align="center">
		<form method="POST" action="<?=BASEURL?>user/registration">
			<table>
				<tr><td colspan="2">��� ������ ������ ���� ������� ���������� �������!</td></tr>
			
				<tr>
					<td>�����</td>
					<td><input type="text" name="login" value="<?=$result->d ? $result->d->user_login :'';?>"></td>
				</tr>
				<tr>
					<td>������</td>
					<td><input type="password" name="password" value="<?=$result->d ? $result->d->user_password :'';?>"></td>
				</tr>
				<tr>
					<td>��������� ������</td>
					<td><input type="password" name="repassword" value="<?=$result->d ? $result->d->repassword :'';?>"></td>
				</tr>				
				<tr>
					<td>E-mail</td>
					<td><input type="text" name="email" value="<?=$result->d ? $result->d->user_email :'';?>"></td>
				</tr>

				<tr><td colspan="2"><hr></td></tr>
				
				<tr>
					<td>���</td>
					<td><input type="text" name="name" value="<?=isset($client) ? $client->client_name :'';?>"></td>
				</tr>
				<tr>
					<td>��������</td>
					<td><input type="text" name="otc" value="<?=isset($client) ? $client->client_otc :'';?>"></td>
				</tr>
				<tr>
					<td>�������</td>
					<td><input type="text" name="surname" value="<?=isset($client) ? $client->client_surname :'';?>"></td>
				</tr>
				<tr>
					<td>������</td>
					<td>
						<select name="country">
							<option>��������...</option>
							<?if (count($countries)>0): foreach ($countries as $country):?>
								<option value="<?=$country->country_id;?>" <?= (isset($client) && $client->client_country==$country->country_id) ? 'selected' :'';?>><?=$country->country_name?></option>
							<?endforeach; endif;?>							
						</select>
					</td>
				</tr>
				<tr>
					<td>�����</td>
					<td><input type="text" name="town" value="<?=isset($client) ? $client->client_town :'';?>"></td>
				</tr>
				<tr>
					<td>������</td>
					<td><input type="text" name="index" value="<?=isset($client) ? $client->client_index :'';?>"></td>
				</tr>
				<tr>
					<td>�����</td>
					<td><input type="text" name="address" value="<?=isset($client) ? $client->client_address :'';?>"><br/>
					* ������: Tverskaya 5, 24
					</td>
				<tr>
					<td>�������</td>
					<td><input type="text" name="phone" value="<?=isset($client) ? $client->client_phone :'';?>"><br/>
					* ���������� ������ ��� ����� � ������������� �������
					</td>
				</tr>
				<tr>
					<td>����������� ���</td>
					<td>
						<img src="<?=BASEURL.'user/showCaptchaImage/'.rand(0,255)?>">
						<input type="text" name="captchacode" value=""><br/>
					* ������� ����������� ��������� �� ��������
					</td>
				</tr>
				<tr><td colspan="2"><hr></td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="�����������"></td>
				</tr>
			</table>	
		</form>
	</div>
<?endif;*/?>
