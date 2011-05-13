Загрузить изображение
<br /><br />
<div id="Image" align="center">
	<form action="#" method="post">
	<input type="hidden"  id="img" name="file_id" value="0" />
	<input type="text" id="imginfo" style="width:350px;" value="" readonly="readonly" />
	<iframe id="buttonfile" src="/main/getFile/screenshots/0"  scrolling="no" frameborder="0"   width="75" height="22" style="width:75px;"></iframe>
	<br /><br />
	<input type="submit" value="Сохранить" />
	</form>
</div>

<script type="text/javascript" language="javascript">
	function upload(err, filename, id, width, height)
	{
		$('a#imgshow').removeClass('imgshow1');
		$('a#imgshow').removeClass('imgload');

		if (err != 'NULL')
		{
			text = err;
		}
		else if (filename == '')
		{
			text = 'Максимальный размер изображения: 1024x769, 1 МБ';
		}
		else
		{
			text = 'Загруженный файл: ' + filename + '; размер: ' + width + 'x' + height;
			$('#img').attr('value', id);
		}
		
		$('#imginfo').attr('value',text);
	}

	function imgprogressload()
	{
		$('a#imgshow').addClass('imgload');

	}

</script>