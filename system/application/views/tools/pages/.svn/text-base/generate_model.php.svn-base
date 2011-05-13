<center><b>Automatic model generator</b></center>
<br>
<br>
<?if ($error->m):?>
	<div align="center">
		<?=$error->m?>
	</div>
<?endif;?>
<br>
<br>
<form action="<?=BASEURL?>tools/generateModel" method="POST">
	<table>
		<tr>
			<td>Model name:</td>
			<td><input type="text" name="mname" style="text-align:right;">Model</td>
		</tr>
		<tr>
			<td>Table name:</td>
			<td><input type="text" name="tname"></td>
		</tr>
		<tr>
			<td>Table primary key:</td>
			<td><input type="text" name="pname"></td>
		</tr>		
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="generate"></td>
		</tr>		
	</table>
</form>

<?if (isset($model['name'])):?>
	<center><b><span style="color:green;">Model successful generated!</span></b></center>
	<table>
		<tr>
			<td>Model name:</td>
			<td><?=$model['name']?></td>
		</tr>
		<tr>
			<td>Model file:</td>
			<td><?=$model['file']?></td>
		</tr>
	</table>
<?endif;?>