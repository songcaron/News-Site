<?php 
	session_start();
    require 'database.php';
	
	$selectOption = $_POST['categories'];
	
	$_SESSION['current_category'] = $selectOption; 
	header("Location: news_listing.php");
?>
