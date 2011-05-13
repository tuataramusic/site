<?
if (!isset($result)){
	$result=new stdClass();
	$result->e='';
	$result->m='';
	$result->d='';
}?>

<style>
	.registration h2 {
		margin: 41px 0 0 225px !important;
	}
	.registration p {
		margin: -3px 0 47px 270px !important;
	}
</style>

	<form name='registration' class='registration' method="post" action='<?=BASEURL?>user/passwordRecovery'>
		<input type='hidden' name='country' value='' id='country' />
		<h1 class='logo'><a href='/'>ContryPost - Лучший сервис покупок за рубежом</a></h1>
		<h2>восстановление пароля</h2>
		<p>Пароль будет выслан на указаный вами адрес электронной почты</p>
		<div class='field done'>
			<span>E-mail:</span>
			<div class='text-field'><div><input type='text' name="email" value="<?=$result->d ? $result->d->user_email :'';?>" /></div></div>
		</div>
		<?if ($result->m):?>
			<p><?=$result->m?></p>
		<?endif;?>
		<div class='submit'><div><input type='submit' value='Восстановить' /></div></div>
	</form>

<?/*if ($result->e !=1):?>
<!--	показываем форму регистрации-->
	<div align="center">
		Пароль будет выслан на указаный вами адрес электронной почты
		<form method="POST" action="<?=BASEURL?>user/passwordRecovery">
			<table>
				<tr>
					<td>E-mail</td>
					<td><input type="text" name="email" value="" <?=$result->d ? $result->d->user_email :'';?>></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Восстановить"></td>
				</tr>			
			</table>	
		</form>
	</div>
<?endif;*/?>