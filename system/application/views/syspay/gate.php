<html>
	<head>
		<title></title>
	</head>
	<body onLoad="postform.submit()">
<!--	<body>-->

		<div style="display:none;">
		<input type="button" onclick="postform.submit()" value="�����>>"/>
		<? View::show($viewpath.'elements/form_'.$ps, $psform)?>
		</div>
	</body>
</html>
