<?php
$username="mmj931";
$sid = "7f9da8ceff33f367785dd243fd458a4257b423de";
//71ac395c7cd68ee991f9616543aa700a324436c6
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
if($cinfo[0]=="Status: OK"){
	echo "Session successfully closed!\r\n";
	echo "SID: $sid\r\n";
	echo "Close Session execution time: $execution_time\r\n";
}else{
	echo "Unable to close session";
	echo "\r\n";
	echo $cinfo[0];
	echo "\r\n";
	echo $cinfo[1];
	echo "\r\n";
	echo $cinfo[2];
	echo "\r\n";
}



?>