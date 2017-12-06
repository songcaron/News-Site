<?php 
    session_start();
    require 'database.php';
	
	$comment_id = (int)$_POST['comment'];
	
	$stmt = $mysqli->prepare("delete from comments where id=?");
	
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('i', $comment_id);
	 
	$stmt->execute();
	
	$stmt->close();
	
	header("Location: news_listing.php");
?>