<?php
include("functions.php");

$username="mmj931";
$password="HjQCRTG#6fgzBb";
$data="username=$username&password=$password";
$ch=curl_init('https://cs4743.professorvaladez.com/api/create_session');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded',
	'content-length: ' . strlen($data))
);

$time_start = microtime(true);
$result = curl_exec($ch);
$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
curl_close($ch);
$cinfo=json_decode($result, true);
$arrayAll=array();
$arrayLoans=array();
$arrayLoanFiles=array();

if($cinfo[0] == "Status: OK" && $cinfo[1] == "MSG: Session Created"){
	$sid= $cinfo[2];
	$data= "sid=$sid&uid=$username";
	$ch=curl_init('https://cs4743.professorvaladez.com/api/request_loans');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'content-type: application/x-www-form-urlencoded',
		'content-length: ' . strlen($data))
	);
	$time_start = microtime(true);
	$result = curl_exec($ch);
	$time_end = microtime(true);
	$execution_time = ($time_end - $time_start)/60;
	curl_close($ch);
	$cinfo=json_decode($result, true);
	if($cinfo[0] == "Status: OK"){
		$tmp=explode(":",$cinfo[1]);
		//$tmp=explode(",",$tmp[1]);
		$tmp=explode("[",$tmp[1]);
		$tmp=explode("]",$tmp[1]);
		$tmp=explode("\"",$tmp[0]);
		$arrayLoans=array_filter($tmp, "filtering");
		//$tmp=explode("\"",$tmp[0]);
		//$tmp=explode("\"",$tmp[0]);
		//$arrayLoans=array_filter($tmp, "filtering");
		//print_r($tmp);
		foreach($arrayLoans as $key=>$value){
			$data= "sid=$sid&uid=$username&lid=$value";
			$ch=curl_init('https://cs4743.professorvaladez.com/api/request_file_by_loan');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'content-type: application/x-www-form-urlencoded',
				'content-length: ' . strlen($data))
			);
			$time_start = microtime(true);
			$result = curl_exec($ch);
			$time_end = microtime(true);
			$execution_time = ($time_end - $time_start)/60;
			curl_close($ch);
			$cinfo = json_decode($result, true);
			if($cinfo[0] == "Status: OK"){
				$tmp=explode(":",$cinfo[1]);
				$tmp=explode("[",$tmp[1]);
				$tmp=explode("]",$tmp[1]);
				$tmp=explode("\"",$tmp[0]);
				$arrayLoanFiles=array_filter($tmp, "filtering");
				foreach($arrayLoanFiles as $key=>$value){
					$arrayAll[] = $value;
				}
			}
		}
	}
	$data= "sid=$sid&uid=$username";
		$ch=curl_init('https://cs4743.professorvaladez.com/api/close_session');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'content-type: application/x-www-form-urlencoded',
			'content-length: ' . strlen($data))
		);
		$time_start = microtime(true);
		$result = curl_exec($ch);
		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start)/60;
		curl_close($ch);
		$cinfo = json_decode($result, true);
}
//print_r($arrayAll);
echo "\r\n";
echo count($arrayAll);
echo "\r\n";
$dblink=db_connect("docstorage");
$countInDatabase=0;
$countNotDatabase=0;
foreach($arrayAll as $key=>$value){
	$sql="Select `name` from `documents` where `name` like '%$value%'";
	$result=$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	if(mysqli_num_rows($result) > 0){
		$countInDatabase++;
	}else{
		echo "\r\nThis file is not in the database ------> $value\r\n";
		$countNotDatabase++;
	}
}
echo "\r\n$countInDatabase\r\n";
echo "\r\n$countNotDatabase\r\n";

function filtering($var){
	return ($var !== NULL && $var !== FALSE && $var !== "" && $var !== ",");
}

?>