<div id="header">
	<div style="float:left; width: 1000px;">&nbsp;
		
		<a href="<?=BASEURL?>">На главную</a>
	
	</div>
	<? if (isset($user) && $user):?>
	
		<div id="welcome">
			Здравствуйте, <?=$user->user_login;?>
			<a href="<?=BASEURL?>user/logout" >Выход</a>
		</div>
	
	<? else:?>
	
		<div id="loginForm">
			<form id="loginForm" name="loginForm" method="POST" action="<?=BASEURL?>user/login">
				<table>
					<tr>
						<td>Login: </td>
						<td><input type="text" name="login" /></td>
					</tr>	
					<tr>
						<td>Password: </td>			
						<td><input type="password" name="password" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>			
						<td><input type="submit" value="login"/></td>
					</tr>		
				</table>
			</form>
			
		</div>
	
	<? endif;?>
</div>