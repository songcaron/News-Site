<?php
	if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}

    session_start();
    session_destroy();
    header("Location: news_login.php");
?>