<div class='content'>
	<h2>��������� ������ ������� �<?=$package->package_id?></h2>
	<form class='comments' action='<?=$selfurl?>updatePackageAddress/<?=$package->package_id?>' method='POST'>
		<select class="select" id="package_country_to" name="package_country_to" style="width:150px;">
			<option value="">�������� ������...</option>
			<?foreach ($countries as $country):?>
			<option value="<?=$country->country_id?>" <? if ($package->package_country_to == $country->country_id) : ?>selected="selected"<? endif; ?>><?=$country->country_name?></option>
			<?endforeach;?>
		</select>
		<h3>������ �����:</h3>
		<div class='add-comment'>
			<div class='textarea'><textarea name='package_address'><?=$package->package_address?></textarea></div>
			<div class='submit'><div><input type='submit' value="���������" /></div></div>
		</div>
	</form>
</div>
<?/*
��������� ������ ������� �<?=$package->package_id?>
<br/><br/>
<form action="<?=$selfurl?>updatePackageAddress/<?=$package->package_id?>" method="POST">
������:<br/>
<select id="package_country_to" name="package_country_to" style="width:150px;">
	<option value="">�������� ������...</option>
	<?foreach ($countries as $country):?>
	<option value="<?=$country->country_id?>" <? if ($package->package_country_to == $country->country_id) : ?>selected="selected"<? endif; ?>><?=$country->country_name?></option>
	<?endforeach;?>
</select>
<br/><br/>������ �����:<br/>
<textarea name="package_address" maxlength="255" cols="40" rows="5"><?=strip_tags($package->package_address)?></textarea>
<br/>
<input type="button" value="�����" onclick="javascript:history.back();">
<input type="submit" value="���������">
</form>
*/?>