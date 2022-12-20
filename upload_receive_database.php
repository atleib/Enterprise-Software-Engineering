<?php
include('functions.php');

$dblink=db_connect("docstorage");
$directory = '/var/www/html/receive';
$scanned_directory = array_diff(scandir($directory), array('..', '.'));
foreach($scanned_directory as $key=>$value){
	$sql="Select `name` from `documents` where `name` like '%$value%'";
	$result=$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	if(mysqli_num_rows($result) > 0){
		//Do nothing
	}else{
		$path="/var/www/html/receive/";
		$fp=fopen($path.$value, 'r');
		$content=fread($fp, filesize($path.$value));
		fclose($fp);
		$contentsClean=addslashes($content);
		uploadFile($value, $contentsClean, $dblink);
	}
}
?>
