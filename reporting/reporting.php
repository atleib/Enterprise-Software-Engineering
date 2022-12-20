<?php
$page="reporting.php";
include("functions.php");
$dblink=db_connect("docstorage");
$sql="Select `name` from `documents` where `upload_date` like '%2022-11%'";
$result = $dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);
$loanArray=array();
while($data= $result->fetch_array(MYSQLI_ASSOC)){
	//echo '<div>'.$data['name'].'</div>';
	$tmp=explode("-", $data['name']);
	$loanArray[]=$tmp[0];
}
$loanArray = array_unique($loanArray);
foreach($loanArray as $key=>$value){
	$sql="Select count(`name`) from `documents` where `name` like '%$value%' and `upload_date` like '%2022-11%'";
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	//echo '<div>Loan Number: '.$value.'</div>';
	$tmp=$result->fetch_array(MYSQLI_NUM);
	echo '<div>Loan Number: '.$value.' has '.$tmp[0].' number of documents</div>';
}
?>