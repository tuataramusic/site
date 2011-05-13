<?
function clearOld($oldthen, $dir){
	$files = scandir($dir);

	foreach ($files as $file){
		
		echo $dir.'/'.$file."\n";

		if (strpos($file,".") === 0) continue;
		
		if (is_dir($dir.'/'.$file)){
			clearOld($oldthen,$dir.'/'.$file);
			continue;
		}
		
		if (is_file($dir.'/'.$file) && (time() - filectime($dir.'/'.$file)) > $oldthen){
			unlink($dir.'/'.$file);
		}
	}
}


// ������ ��� ����� �����
$cbr_xml	= getcwd().'/tmp';

clearOld(172800, $cbr_xml);

// ������ ������ �������� (������� ������ 4� �������)
$pics	= getcwd().'/upload';
clearOld(10368000, $pics);


?>