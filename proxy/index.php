<?php 

ini_set('display_errors', 0);

$url = @$_GET['url'];
$url = (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0 ) ? $url : 'http://'.$url;
$parse = parse_url($url);
$host = $parse['host'];
$server_host = $_SERVER['HTTP_HOST'];

session_start();
if (isset($_GET['shop']))
	$_SESSION['shop'] = $_GET['shop']; 

$html = file_get_contents($url);

$dom = new domDocument;
$dom->loadHTML($html);

$links = $dom->getElementsByTagName('a');
foreach ($links as $link) {
	$href = $link->getAttribute('href');	
	if (!(strpos($href, 'http://') === 0 || strpos($href, 'https://') === 0)) {
		$hr = 'http://'.$href;
		$parse = parse_url($hr);
		if (!isset($parse['host']))
			$href = $host.$href;		
	}
    $href = 'http://'.$server_host.'/proxy/?url='.urlencode($href);
    $link->setAttribute('href', $href);	
}

$imgs = $dom->getElementsByTagName('img');
foreach ($imgs as $img) {
	$src = $img->getAttribute('src');	
	if (!(strpos($src, 'http://') === 0 || strpos($src, 'https://') === 0)) {
		$hr = 'http://'.$src;
		$parse = parse_url($hr);
		if (!isset($parse['host']))
			$src = 'http://'.$host.$src;		
	}
    $img->setAttribute('src', $src);	
}

$links = $dom->getElementsByTagName('link');
foreach ($links as $link) {
	$href = $link->getAttribute('href');	
	if (!(strpos($href, 'http://') === 0 || strpos($href, 'https://') === 0)) {
		$hr = 'http://'.$href;
		$parse = parse_url($hr);
		if (!isset($parse['host']))
			$href = 'http://'.$host.$href;		
	}
    $link->setAttribute('href', $href);	
}

$scripts = $dom->getElementsByTagName('script');
foreach ($scripts as $script) {
	$src = $script->getAttribute('src');
	if ($src) {	
		if (!(strpos($src, 'http://') === 0 || strpos($src, 'https://') === 0)) {
			$hr = 'http://'.$src;
			$parse = parse_url($hr);
			if (!isset($parse['host']))
				$src = 'http://'.$host.$src;		
		}
	    $script->setAttribute('src', $src);	
	}
}

$body = $dom->getElementsByTagName('body');
$div = $dom->createElement('div');
$div->setAttribute('id', 'sel_area');
while ($body->item(0)->childNodes->length > 0) {
    $child = $body->item(0)->childNodes->item(0);
    $body->item(0)->removeChild($child);
    $div->appendChild($child);
}
$body->item(0)->appendChild($div);

$head = $dom->getElementsByTagName('head');
$script = $dom->createElement('script');
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
$head->item(0)->appendChild($script);
$script = $dom->createElement('script');
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', 'http://odyniec.net/projects/imgareaselect/jquery.imgareaselect.pack.js');
$head->item(0)->appendChild($script);
$css = $dom->createElement('link');
$css->setAttribute('type', 'text/css');
$css->setAttribute('rel', 'stylesheet');
$css->setAttribute('href', 'http://'.$server_host.'/proxy/css/imgareaselect.css');
$head->item(0)->appendChild($css);

$script = $dom->createElement('script', "
var has_sel = false;
var x1 = 0;
var y1 = 0;
var x2 = 0;
var y2 = 0;
function validate_send(){
	if (!has_sel) {
	
		if (!$(\"input[name='oname']\").val()) {
			$(\"#send_error\").show();
			$(\"#send_error\").text('please enter product name');
			return false;
		}
		
		if (!$(\"input[name='oamount']\").val()) {
			$(\"#send_error\").show();
			$(\"#send_error\").text('please enter product amount');
			return false;
		}
	
		$('#buy_btn').val('Send');
		$('#sel_area').imgAreaSelect({ x1: 200, y1: 200, x2: 400, y2: 400, 
			onSelectEnd: function(img, selection){
				x1 = selection.x1;
				y1 = selection.y1;
				x2 = selection.x2;
				y2 = selection.y2;
			}});
		has_sel = true;
		return false;
	}
	else {
		
		$(\"input[name='x1']\").val(x1);
		$(\"input[name='y1']\").val(y1);
		$(\"input[name='x2']\").val(x2);
		$(\"input[name='y2']\").val(y2);
		return true;
	}
}

$(document).ready(function () {	
	//$('body').contents().wrapAll('<div id=\"sel_area\">');
	$('body').prepend('<div id=\"send_form\" style=\"height: 50px;\" ><div id=\"send_error\" style=\"display: none; color: red;\"></div><form id=\"send_form_id\" onsubmit=\"return validate_send();\" action=\"/client/addProduct\" method=\"POST\">product: <input type=\"text\" name=\"oname\"/>	color: <input type=\"text\" name=\"ocolor\" size=\"15\"/>size: <input type=\"text\" name=\"osize\" size=\"10\"/>amount: <input type=\"text\" name=\"oamount\" size=\"10\"/><input type=\"hidden\" name=\"oshop\" value=\"".@$_SESSION['shop']."\"/><input type=\"hidden\" name=\"olink\" value=\"".$url."\"/><input type=\"hidden\" name=\"x1\"/><input type=\"hidden\" name=\"y1\"/><input type=\"hidden\" name=\"x2\"/><input type=\"hidden\" name=\"y2\"/><input type=\"submit\" id=\"buy_btn\" name=\"buy\" value=\"Select\" /></form></div>');	
});
");
$script->setAttribute('type', 'text/javascript');
$head->item(0)->appendChild($script);

$html = $dom->saveHtml();
echo $html;

?>