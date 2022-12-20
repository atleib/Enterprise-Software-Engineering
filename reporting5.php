<?php
/*
			A complete loan is one that has at least one of the following documents: credit, closing, title, financial, personal, internal, legal, other
				-A list of all loan numbers that have all documents (100 pts)
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
foreach($loanUnique as $key=>$value){
	$documentTypeArray=array();
	$sql="Select `name` from `documents` where `name` like '%$value%' and `upload_date` between '2022-11-14 00:00:00' and '2022-11-30 23:59:00' order by `upload_date`";
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	while($data= $result->fetch_array(MYSQLI_ASSOC)){
		$nameArray= explode("-",$data['name']);
		$documentTypeArray[]=$nameArray[1];
	}
	if(in_array("Credit", $documentTypeArray) && in_array("Closing", $documentTypeArray) && in_array("Title", $documentTypeArray) && in_array("Financial", $documentTypeArray) && in_array("Personal", $documentTypeArray) && in_array("Internal", $documentTypeArray) && in_array("Legal", $documentTypeArray) && in_array("Other", $documentTypeArray)){
		echo '<div>Loan Number '.$value.' have all document types.</div>';
	}
}
?>