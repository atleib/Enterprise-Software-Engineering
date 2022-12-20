<?php
/*
			For each loan number, the total number of documents recieved and the average number of documents across all loan numbers. Compare each loan number to the average and state if it is above or below average (100 pts)
*/
include("functions.php");
$dblink=db_connect("docstorage");
$sql="Select `name` from `documents` where `upload_date` between '2022-11-14 00:00:00' and '2022-11-30 23:59:00' order by `upload_date`";
$result = $dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);
$loanArray=array();
while($data= $result->fetch_array(MYSQLI_ASSOC)){
	$tmp=explode("-", $data['name']);
	$loanArray[]=$tmp[0];
}
$loanUnique = array_unique($loanArray);
$avgFiles= round(count($loanArray)/count($loanUnique));
echo '<div>Average number of documents across all loan numbers: '.$avgFiles.'</div>';

foreach($loanUnique as $key=>$value){
	$sql="Select count(`name`) from `documents` where `name` like '%$value%' and `upload_date` between '2022-11-14 00:00:00' and '2022-11-30 23:59:00' order by `upload_date`";
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	$tmp=$result->fetch_array(MYSQLI_NUM);
	if($tmp[0] == $avgFiles){
		echo '<div>Loan Number '.$value.' has '.$tmp[0].' number of documents and is average.</div>';
	}
	else if($tmp[0] < $avgFiles){
		echo '<div>Loan Number '.$value.' has '.$tmp[0].' number of documents and is not above average.</div>';
	}
	else if($tmp[0] > $avgFiles){
		echo '<div>Loan Number '.$value.' has '.$tmp[0].' number of documents and is above average.</div>';
	}
}
?>