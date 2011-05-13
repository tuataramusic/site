<html>
	<head>
		<title></title>
	</head>
<?//	<body onLoad="postform.submit()">?>
	<body>
		<input type="button" onclick="postform.submit()" value="далее>>"/>
		<? View::show($viewpath.'elements/form_'.$ps, $psform)?>
	</body>
</html>
