<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><body <? if ($pageinfo['mname'] != 'index'):?>class="inner"<? endif;?>> <!--<![endif]-->

	<div class='layout'>
	
		<?View::show('main/elements/div_header');?>
		
		<?//View::show('main/elements/div_menu');?>
		
		<?//View::show('main/elements/div_debug');?>
		
		<?View::show('main/elements/div_content');?>
	</div>
	
	<?View::show('elements/div_bottom');?>
	
	<?View::show('elements/div_footer');?>
</body>