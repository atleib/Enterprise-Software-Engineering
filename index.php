<?php
echo "<p>Hello world, I am php!</p>";
echo "<p>This is my second line.</p>";
$hostname="localhost";
$username="webuser";
$password="eHc5KMSPoz!CIqTs";
$db="temp";
$mysqli=new mysqli($hostname,$username,$password,$db);
if (mysqli_connect_errno())
{
	die("Error connecting to database: ".mysqli_connect_error());
}
/*
$sql="Select * from `user_input` where 1";
$result=$mysqli->query($sql) or
	die("Something went wrong with $sql".$mysqli->error);
while ($data=$result->fetch_array(MYSQLI_ASSOC))
{
	echo "<p>Entry $data[auto_id]: $data[input] - $data[user_id]</p>";
}
*/
$sql="Insert into `user_input` (`input`,`user_id`) values ('input from web','webuser@mail.com')";
$mysqli->query($sql) or
	die("Something went wrong with $sql ".$mysqli->error);
echo "<p>Executed $sql</p>";
?>
