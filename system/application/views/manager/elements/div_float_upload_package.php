<div class='table' id="lay_block" style="width:400px; position:fixed; z-index: 1000; display:none; top:300; left:400px;">
	
	<form enctype="multipart/form-data" class='admin-inside' action="<?=$selfurl?>addPackageFoto/" method="POST">			
		<table>
			<tr>
				<td>
					<input type="file" name="userfile1" size="40">
					<input type="hidden" name="package_id" id="package_id">
				</td>
			</tr>
			<tr>
				<td>
					<input type="file" name="userfile2" size="40">
				</td>
			</tr>
			<tr>
				<td>
					<input type="file" name="userfile3" size="40">
				</td>
			</tr>
			<tr>
				<td>
					<input type="file" name="userfile4" size="40">
				</td>
			</tr>
			<tr>
				<td>
					<input type="file" name="userfile5" size="40">
				</td>
			</tr>
			<tr class='last-row'>
				<td>
					<div class='float'>	
						<div class='submit'><div><input type='submit' name="add" value='Добавить' /></div></div>
					</div>
				</td>
				<td>
				</td>
			</tr>
		</table>
	</form>
</div>
	
<script type="text/javascript">

	var fclick = 0;
	function lay(){
		$('#lay').css({
			'width': document.body.clientWidth,
			'height': document.body.clientHeight
		});
		
		$('#lay').fadeIn("slow");
		$('#lay_block').fadeIn("slow");
		
		if (!fclick){
			fclick = 1;
			$('#lay').click(function(){
				$('#lay').fadeOut("slow");
				$('#lay_block').fadeOut("slow");
			})
		}
	}
	
	function uploadPackFoto(pac_id){
		document.getElementById('package_id').value = pac_id;
		lay();
	}

</script>