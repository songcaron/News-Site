<?php 
    session_start();
    require 'database.php';
	
	$story_id = (int)$_POST['story'];
	
	//delete comments first
	$stmt = $mysqli->prepare("delete from comments where story_id=?");
	
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('i', $story_id);
	 
	$stmt->execute();
	
	$stmt->close();
	
	//delete story itself
	$stmt = $mysqli->prepare("delete from stories where id=?");
	
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('i', $story_id);
	 
	$stmt->execute();
	
	$stmt->close();
	
	header("Location: news_listing.php");
?>