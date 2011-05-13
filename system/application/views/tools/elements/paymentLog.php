########### <?= date('d-m-Y H:i', time())?> ###########
<?foreach ($_POST as $key => $val){
	
	echo "$key		=> $val\n";
}
if ($msg) echo $msg;?>
########################################


