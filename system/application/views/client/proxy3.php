<html>
	<head>
		<link rel="stylesheet" type="text/css" href="<?="http://$server_host/proxy/css/image-crop.css"?>" />
		<script type="text/javascript" src="<?=JS_PATH?>jquery-1.4.3.min.js"></script>
	</head>
	<body>
		<?if(isset($result->m) && $result->m):?><em style="color:red;"><?=$result->m?></em><br/><?endif;?>
		<div id="imageContainer" style="margin:0px !important;">
			<img id="imageContainerSource" src='/client/getScreenshot/<?=$fname;?>' />
		</div>
		<?php 
				if (count($Odetails)) {
					$country = false;
					$countries_str	= '<input type="hidden" name="omanager" value="'.$Odetails->odetail_manager.'" />';
				}
				else {
					$country = true;
					$countries_str = '<select name="ocountry">';
					foreach ($Countries as $Country)
						$countries_str .= ('<option value="'.$Country->country_id.'">'.$Country->country_name.'</option>');
					$countries_str .= '</select>';
				}		
		?>
		<script type="text/javascript">
			var cropToolBorderWidth = 1;
			var smallSquareWidth = 7;
			var crop_imageWidth,  crop_imageHeight, crop_originalImageWidth, crop_originalImageHeight;
			var crop_minimumWidthHeight = 15;
			var updateFormValuesAsYouDrag = true;
			
			var has_sel = false;
			function validate_send(){
				if (!has_sel) {
					document.getElementById('send_error').style.display = 'none';
					if (!document.getElementById('oname').value) {
						document.getElementById('send_error').style.display = '';
						document.getElementById('send_error').innerText = 'Пожалуйста, введите название товара';
						document.getElementById('send_error').textContent  = 'Пожалуйста, введите название товара';
						return false;
					}
					
					if (isNaN(document.getElementById('oamount').value)) {
						document.getElementById('send_error').style.display = '';
						document.getElementById('send_error').innerText = 'Пожалуйста, введите количество товара';
						document.getElementById('send_error').textContent  = 'Пожалуйста, введите количество товара';
						return false;
					}
					
					document.getElementById('buy_btn').value = 'Купить';
					crop_imageWidth = document.getElementById('imageContainer').clientWidth;
					crop_imageHeight = document.getElementById('imageContainer').clientHeight;
					init_imageCrop();			
					has_sel = true;
					return false;
				}
				else {
					return true;
				}
			}
				
			var send_form_div = document.createElement("div");
			var send_error = document.createElement('div');
			var send_form = document.createElement('form');
			
			$().ready(function(){
				$('#imageContainerSource').load(function(){
					crop_imageWidth = document.getElementById('imageContainer').clientWidth;
					crop_imageHeight = document.getElementById('imageContainer').clientHeight;
					crop_originalImageWidth = crop_imageWidth;
					crop_originalImageHeight = crop_imageHeight;
					
					if(!document.all)updateFormValuesAsYouDrag = false;
					
					send_form_div.setAttribute('id', 'send_form');
					send_form_div.setAttribute('style', 'height: 50px;');
					
					send_error.setAttribute('id', 'send_error');
					send_error.setAttribute('style', 'display: none; color: red;');
					send_form_div.appendChild(send_error);
					
					send_form.setAttribute('id', 'send_form_id');
					send_form.setAttribute('onsubmit', 'return validate_send();');
					send_form.setAttribute('action', '<?=BASEURL?>client/addProduct');
					send_form.setAttribute('method', 'POST');
					send_form.innerHTML = '<?=(($country) ? "Страна: ".$countries_str : '' )?> Товар: <input type="text" name="oname" id="oname"/> Цвет: <input type="text" name="ocolor" size="15"/> Размер: <input type="text" name="osize" size="10"/> Количество: <input type="text" name="oamount" id="oamount" size="10"/><input type="hidden" name="olink" value="<?=$url?>"/><input type="hidden" name="x1" id="input_crop_x"/><input type="hidden" name="y1" id="input_crop_y"/><input type="hidden" name="x2" id="input_crop_width"/><input type="hidden" name="y2" id="input_crop_height"/><input type="hidden" name="sh_width" id="sh_width"/><input type="hidden" name="fname" value="<?=$fname;?>" /><input type="submit" id="buy_btn" name="buy" value="Выделить" />';
					send_form_div.appendChild(send_form);
					
			        document.body.insertBefore(send_form_div, document.getElementById('imageContainer'));		
			        document.getElementById('sh_width').value = document.body.scrollWidth;
					
					var Script = document.createElement('SCRIPT');
					Script.type = 'text/javascript';
					Script.charset = 'windows-1251';
					Script.src = '<?="http://$server_host/proxy/image-crop.js"?>';
					document.body.appendChild(Script);
				});
			});
		
		</script>
	</body>
</html>





