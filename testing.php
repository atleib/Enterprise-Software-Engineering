<?php
include("functions.php");
$db="docstorage";
$dblink=db_connect($db);
$sql="Insert into `testing` (`testin1`,`testing2`,`testing3`,`testing4`) values ('uh', '2','uhhhhh','ummm ')";
	$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
echo "it worked";
?>