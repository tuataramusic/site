<?if ($error->m):?>
	<div align="center">
		<?=$error->m?><br/>
		<form action="<?=BASEURL?>client/showShop" method="POST">
			<table>
				<tr>
					<td>�������� ��������:</td>
					<td><input type="text" name="sname" <?if (isset($shop['name'])):?>value="<?=$shop['name']?>"<?endif;?> ></td>
				</tr>
				<tr>
					<td>����� �����:</td>
					<td><input type="text" name="surl" value="http://"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td style="text-align: center;"><input type="submit" name="add" value="��������"/></td>
				</tr>			
			</table>
		</form>
	</div>
<?else:?>
	<link rel="stylesheet" type="text/css" href="/proxy/imgareaselect.css" />
	<script src="http://odyniec.net/projects/imgareaselect/jquery.imgareaselect.min.js" type="text/javascript"></script>
	<script>	
		$(function() {
			$('#buy_btn').click(function() {
				$('#error').hide();
				$("input[name='olink']").val($('#shop').contents().get(0).location.href);
				<?if ($country):?>
				if ($("#ocountry").val() == 0) {
					$('#error').text('�������� ������!');
					$('#error').show();
					return false;	
				}
				<?endif;?>
				if ($("input[name='oname']").val() == "") {
					$('#error').text('������� �������� ������!');
					$('#error').show();
					return false;	
				}
				if ($("input[name='oamount']").val() == "") {
					$('#error').text('������� ���������� ������!');
					$('#error').show();
					return false;	
				}
				return true;
				/*$("#shop").contents().find('body').contents().wrapAll('<div id="sel_area">');
				$("#shop").contents().find('#sel_area').imgAreaSelect({ x1: 200, y1: 200, x2: 400, y2: 400});
				return false;*/
			});
		});
	</script>
	<div id="error" style="display: none; color: red;"></div>
	<div align="center">
		<div>
			<form action="<?=BASEURL?>client/addProduct" method="POST">
				<?if ($country):?>
				������: 
				<select name="ocountry" id="ocountry">
				<option value="0" selected="selected">---</option>
				<? if($countries): ?>
					<? foreach($countries as $country): ?>
					<option value="<?= $country->country_id?>"><?= $country->country_name?></option>
					<? endforeach;?>
				<? endif; ?>
				</select>
				<?endif;?>
				������������ ������: <input type="text" name="oname"/>  
				����: <input type="text" name="ocolor" size="15"/> 
				������: <input type="text" name="osize" size="10"/> 
				���-��: <input type="text" name="oamount" size="10"/>
				<input type="hidden" name="oshop" value="<?=$shop['name']?>"/>
				<input type="hidden" name="olink"/>
				<input type="submit" id="buy_btn" name="buy" value="������"/>				
			</form>
			<p>
				���� �������� ��� �����, ������� ������ ������, ��� ����� ��� ���� ����� � ������� ������ ������, ������������� ������ ������.
			</p>
		</div>
		<iframe src="<?=BASEURL?>proxy/?url=<?=urlencode($shop['url'])?>" width="90%" height="70%" id="shop"></iframe>
	</div>
<?endif;?>