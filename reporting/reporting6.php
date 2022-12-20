<?php
/*
			A complete loan is one that has at least one of the following documents: credit, closing, title, financial, personal, internal, legal, other
				-List the total number of each document received across all loan numbers (100 pts)
*/
include("functions.php");
$dblink=db_connect("docstorage");
$sql="Select `name` from `documents` where `upload_date` between '2022-11-14 00:00:00' and '2022-11-30 23:59:00' order by `upload_date`";
$result = $dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);
$documentTypeArrays=array();
$loanArray=array();
while($data= $result->fetch_array(MYSQLI_ASSOC)){
	$tmp=explode("-", $data['name']);
	$loanArray[]=$tmp[0];
	$documentTypeArrays[]=$tmp[1];
}
$loanUnique = array_unique($loanArray);
$documentTypeArrays= array_count_values($documentTypeArrays);
foreach($documentTypeArrays as $key=>$value){
	echo '<div>'.$key.' had '.$value.' documents.</div>';
}
echo '<div><br></div>';
foreach($loanUnique as $key=>$value){
	$documentTypeArray=array();
	$sql="Select `name` from `documents` where `name` like '%$value%' and `upload_date` between '2022-11-14 00:00:00' and '2022-11-30 23:59:00' order by `upload_date`";
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	while($data= $result->fetch_array(MYSQLI_ASSOC)){
		$nameArray= explode("-",$data['name']);
		$documentTypeArray[]=$nameArray[1];
	}
	$documentTypeArray=array_count_values($documentTypeArray);
	echo '<div>Loan Number '.$value.' has the following documents:</div>';
	foreach($documentTypeArray as $key=>$value){
		echo '<div>'.$key.' --->'.$value.'</div>';
	}
	echo '<div><br></div>';
}

?>