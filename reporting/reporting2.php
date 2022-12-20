<?php
/*
			The total size of all documents recieved from the API and the average size of all documents across all loans (100 pts)
*/
include("functions.php");
$dblink=db_connect("docstorage");
$sql="Select octet_length(`content`) from `documents` where `upload_date` between '2022-11-14 00:00:00' and '2022-11-30 23:59:00' order by `upload_date`";
$result = $dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);
$totalFiles=0;
$fileCount=0;
while($data= $result->fetch_array(MYSQLI_NUM)){
	$fileCount++;
	$totalFiles+=$data[0];
}
echo '<div>Total size of '.$fileCount.' files: '.$totalFiles.' </div>';
echo '<div>Average size of files: '.$totalFiles/$fileCount.'</div>';
?>