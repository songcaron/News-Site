<?php
	session_start();
    require 'database.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edit Story</title>
	</head>
	<body>
		<?php
		
			if(isset($_POST['story'])) {
				$story_id = (int)$_POST['story'];
	
				$stmt = $mysqli->prepare("select title,story_content,category,link from stories where id=?");
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				 
				$stmt->bind_param('i', $story_id);
			 
				$stmt->execute();
				 
				$stmt->bind_result($title,$content,$category,$link);
				while($stmt->fetch()) {
					echo '<form name="submit" action="" method="post">';
					echo	 '<input type="text" name="title" required=required value="'.htmlentities($title).'">';
					echo	 '<select name = "categories">';
					echo		'<option value = "Technology"> Technology</option>';
					echo		'<option value = "TV"> TV </option>';
					echo		'<option value = "Sports"> Sports </option>';
					echo		'<option value = "Health"> Health </option>';
					echo		'<option value = "Money"> Money </option>';
					echo		'<option value = "Nature"> Nature </option>';
					echo	 '<input type="text" name="link" value="'.$link.'">';
					echo	 '<input type = "text" name="content" required=required value="'.$content.'">';
					echo 	 '<input type="hidden" name="new_story" value='.$story_id.'>';
					echo	 '<input type="submit" name="submit" value="Update Story">';
					echo '</form>';
				}
			}
			else {
				$title = $_POST['title'];
				$categories = $_POST['categories'];
				$link = $_POST['link'];
				$content = $_POST['content'];
				$story_id = (int)$_POST['new_story'];
				
				$stmt = $mysqli->prepare("update stories set title=?, story_content=?, category=?, link=? where id=?");
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				 
				$stmt->bind_param('ssssi', $title, $content, $categories,$link,$story_id);
				 
				$stmt->execute();
				 
				$stmt->close();
				
				header("Location: news_listing.php");
			}

		?>
	</body>
</html>