<!doctype html>
<?php
if(!isset($_POST['submit']))//&& $_POST['submit'] == "submit")
{
	echo '<form method="post" action="info.php">';
	echo '<p>Userid:<input type="text" name="userid"></p>';
	echo '<p>Email:<input type="text" name="email"></p>';
	echo '<p>First Name:<input type="text" name="firstName"></p>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
}
elseif (isset($_POST['submit']) && $_POST['submit'] == "submit")
{
	$userid=$_POST['userid'];
	$email=$_POST['email'];
	$firstName=$_POST['firstName'];
	echo '<h4>Results from previous page: </h4>';
	echo '<p>user id: '.$userid.'</p>';
	echo "<p>email: $email</p>";
	echo "<p>first name: $firstName</p>";
}
else
	echo '<p>Error in flow</p>';



?>