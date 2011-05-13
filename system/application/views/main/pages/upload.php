<!doctype html>
<html lang="ru">
<head>
	<meta http-equiv="content-type" content="text/html;charset=windows-1251" />
	<title></title>
	<style type="text/css" title="text/css">
	body,form {margin:0;padding:0;}
	</style>
</head>
<body>
<div style="position:relative;width:75px;overflow:hidden;height:22px;cursor:pointer;">
<form action="/main/uploadFile/<?=$data->dir?>/<?=$data->id?>" method="post" enctype="multipart/form-data">
		<div style="text-align:right;position:width:75px;height:22px;absolute;right:0px;">
			<input type="button" value="Обзор..." style="position:absolute;left:0;width:75px;height:22px;cursor:pointer;" />
			<input type="file" name="userfile" class="file" id="file" value="" onchange="fileset(this)" style="visibility:visible;position:absolute;z-index:1000;opacity:0;filter:alpha(opacity=0);cursor:pointer;direction:ltr;right:0;cursor:pointer;" />
        </div>
        <input type="hidden" name="id" value="<?=$data->id?>"/>
        <input type="hidden" name="dir" value="<?=$data->dir?>"/>
</form>
</div>
<script type="text/javascript" language="javascript">
	window.parent.upload('<?=$data->err?>','<?=$data->name?>','<?=$data->id?>','<?=$data->width?>','<?=$data->height?>');

	function fileset(f)
	{
		window.parent.imgprogressload(); 
		f.form.submit();
	}
</script>
</body>
</html>
