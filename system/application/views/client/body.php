<!--[if IE 7 ]>    <body class="ie7 inner"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8 inner"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9 inner"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body class='inner'> <!--<![endif]-->

	<div id="lay" style="position:absolute; z-index: 999; background: #787878; width:100%; height:100%; display:none; opacity:0.3;"></div>
	
	<div class='layout'>
		<?View::show($viewpath.'elements/div_top');?>
	
		<?View::show($viewpath.'elements/div_header');?>
		
		<?View::show($viewpath.'elements/div_content');?>
		
	</div>
		
	<?View::show('elements/div_bottom');?>
	
	<?View::show('elements/div_footer');?>
</body>