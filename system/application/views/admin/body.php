<!--[if IE 7 ]>    <body class="ie7 inner"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8 inner"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9 inner"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body class='inner'> <!--<![endif]-->

	<div class='layout'>
		<?View::show($viewpath.'elements/div_top');?>
	
		<?View::show($viewpath.'elements/div_header');?>
		
		<?//View::show($viewpath.'elements/div_menu');?>
		
		<?//View::show($viewpath.'elements/div_debug');?>
		
		<?View::show($viewpath.'elements/div_content');?>
		
	</div>
	
	<?//View::show('elements/div_bottom');?>
	
	<?View::show('elements/div_footer');?>
	
<script type="text/javascript" src="/static/build/modulargrid.js"></script>
</body>