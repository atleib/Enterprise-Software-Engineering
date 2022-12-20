<?php
include("functions.php");
$username="mmj931";
$password="HjQCRTG#6fgzBb";
$data="username=$username&password=$password";
do{
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
	uploadLog($cinfo);
	if($cinfo[0] == "Status: OK" && $cinfo[1] == "MSG: Session Created"){//Session was created
		$sid = $cinfo[2];
		$data= "sid=$sid&uid=$username";
		do{
			$ch=curl_init('https://cs4743.professorvaladez.com/api/query_files');
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
			uploadLog($cinfo);
			if($cinfo[0] == "Status: OK"){//Query files worked
				if($cinfo[2] == "Action: None"){
					//Do nothing
				}
				else{
					$tmp = explode(":", $cinfo[1]);
					$files = explode(",", $tmp[1]);
					foreach($files as $key=>$value){
						$tmp=explode("/", $value);
						$file=$tmp[4];
						$data= "sid=$sid&uid=$username&fid=$file";
						do{
							$ch=curl_init('https://cs4743.professorvaladez.com/api/request_file');
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
							$content=$result;
							if(empty($content)){
								$uploadDate=date("Y-m-d H:i:s");
								$dblink=db_connect("docstorage");
								$sql="Insert into `API_log` (`status`, `message`, `action`,`dateCalled`) values ('Status: ERROR','MSG: File $value was unable to request data', 'Action: Recall request', '$uploadDate')";
								$dblink->query($sql) or
									die("Something went wrong with $sql<br>".$dblink->error);
							}
							else{
								$fp=fopen("/var/www/html/receive/$file", "wb");
								fwrite($fp,$content);
								fclose($fp);
								$uploadDate=date("Y-m-d H:i:s");
								$dblink=db_connect("docstorage");
								$sql="Insert into `API_log` (`status`, `message`, `action`,`dateCalled`) values ('Status: OK','MSG: File $value has been created', 'Action: Continue', '$uploadDate')";
								$dblink->query($sql) or
									die("Something went wrong with $sql<br>".$dblink->error);
							}
						}while(empty($content));//While loop until file content is not empty for Request File
					}
				}
			}
			else{//Query files failed
				$uploadDate=date("Y-m-d H:i:s");
				$dblink=db_connect("docstorage");
				$sql="Insert into `API_log` (`status`, `message`, `action`,`dateCalled`) values ('$cinfo[0]','$cinfo[1]', '$cinfo[2]', '$uploadDate')";
				$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);
			}
		}while($cinfo[0] != "Status: OK");//While loop until Status is OK for Query Files
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
		uploadLog($cinfo);
		if($cinfo[0]=="Status: OK"){
			break;
		}else{//Unable to close session
			$username="mmj931";
			$password="HjQCRTG#6fgzBb";
			$data="username=$username&password=$password";
			$ch=curl_init('https://cs4743.professorvaladez.com/api/clear_session');
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
			$uploadDate=date("Y-m-d H:i:s");
			$dblink=db_connect("docstorage");
			$sql="Insert into `API_log` (`status`, `message`, `action`,`dateCalled`) values ('Status: OK','MSG: Forced clear session', 'Action: Done', '$uploadDate')";
			$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
			break;
		}
	}
	else{//Unable to create session
		$username="mmj931";
		$password="HjQCRTG#6fgzBb";
		$data="username=$username&password=$password";
		$ch=curl_init('https://cs4743.professorvaladez.com/api/clear_session');
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
		$uploadDate=date("Y-m-d H:i:s");
		$dblink=db_connect("docstorage");
		$sql="Insert into `API_log` (`status`, `message`, `action`,`dateCalled`) values ('Status: OK','MSG: Forced clear session', 'Action: Continue', '$uploadDate')";
		$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	}
}while(true);

?>