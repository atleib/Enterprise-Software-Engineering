<?php
function db_connect($db){
	$hostname="localhost";
   	$username="webuser";
    $password="eHc5KMSPoz!CIqTs";
    //$db="docstorage";
    $dblink=new mysqli($hostname,$username,$password,$db);
    if (mysqli_connect_errno())
    {
        die("Error connecting to database: ".mysqli_connect_error());   
    }
	return $dblink;
}

function redirect($uri){
	?>
		<script type="text/javascript">
		<!--
			document.location.href="<?php echo $uri; ?>";
		-->
		</script>
	<?php die;
}

function uploadLog($cinfo){
	$uploadDate=date("Y-m-d H:i:s");
	$dblink=db_connect("docstorage");
	$sql="Insert into `API_log` (`status`, `message`, `action`,`dateCalled`) values ('$cinfo[0]','$cinfo[1]', '$cinfo[2]', '$uploadDate')";
	$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
}

function uploadFile($value, $contentsClean, $dblink){
	$path="/var/www/html/receive/";
	//$uploadDate=date("Y-m-d H:i:s");
	$tmp= explode("-", $value);
	$date= explode("_",$tmp[2]);
	$stringDate = strtotime(substr($date[0], 0, 4)."-".substr($date[0], 4, 2)."-".substr($date[0], 6, 2)." ".$date[1].":30:".substr($date[3], 0, 2));
	
	$uploadDate= date("Y-m-d H:i:s",$stringDate);
	if($uploadDate < "2022-12-01"){
		$sql="Insert into `documents` (`name`, `path`, `upload_by`,`upload_date`, `status`, `file_type`, `content`) values ('$value','$path', 'webuser', '$uploadDate', 'active', 'pdf', '$contentsClean')";
		$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	}
}

?>