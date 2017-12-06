
<?php
 
$mysqli = new mysqli('localhost', 'module3_user', 'module', 'news');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

?>