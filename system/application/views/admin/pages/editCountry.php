Страна
<br/><br/>

<form action='<?=$selfurl?>updateCountry/<?=$country->country_id?>' method='POST'>
<input type='text' name='country_name' id='country_name' maxlength='32' value="<?=$country->country_name?>"><br/>
<input type="submit" value="Сохранить"/>
</form>