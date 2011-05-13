<div class='table' id="lay3_block" style="width:400px; position:absolute; z-index: 1000; display:none; top:390px; left:290px;">
	<img src="" id="packPreview" width="100%" height="100%" />
</div>
	
<script type="text/javascript">

	var fmclick = 0;
	function lay3(){
		$('#lay').css({
			'width': document.body.clientWidth,
			'height': document.body.clientHeight
		});
		
		$('#lay').fadeIn("slow");
		$('#lay3_block').fadeIn("slow");
		
		if (!fmclick){
			fmclick = 1;
			$('#lay').click(function(){
				$('#lay').fadeOut("slow");
				$('#lay3_block').fadeOut("slow");
				document.getElementById('packPreview').src = '';
			})
		}
	}
	
	function previewPack(picUrl){
		document.getElementById('packPreview').src = picUrl;
		lay3();
	}
</script>