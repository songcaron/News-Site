<?php
require 'database.php';

if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}

$link = mysqli_connect("localhost", "module3_user", "module", "news");

//check connection
if($link == false)
{
	echo "Failed to connect to database. " . mysqli_connect_error();
}

$username = $_SESSION['username'];
$stmt = "DELETE FROM users WHERE username = $username";

if(mysqli_query($link, $stmt))
{
	echo "Your account was deleted successfuly.";
}
else
{
	echo "Error: Your account was not deleted successfully." . mysqli_error($link);
}
mysqli_close($link)
?>