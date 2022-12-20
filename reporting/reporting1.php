<?php
/*
			Total number of unique loan numbers generted with a printout of those loan numbers(100 pts)
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
$loanArray = array_unique($loanArray);
echo '<div>Amount of unique loan numbers: '.count($loanArray).'</div>';
foreach($loanArray as $key=>$value){
	echo '<div>Loan Number '.$value.'</div>';
}
?>