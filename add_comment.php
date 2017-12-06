<?php
    session_start();
    require 'database.php';
?>
<!DOCTYPE html> 
<html>
	<head>
        <title>Add Comment</title>
    </head>
	<body>
		<?php
			
			if(isset($_POST['story_title'])) {
				$title = $_POST['story_title'];
				$story_id = (int)$_POST['story'];
				
				echo "<h4>What comment did you want to add to \"".$title."\"?</h4>";
				
				echo "<form method='POST'>";
				echo "<input type=text name='new_comment' placeholder='Enter comment'>";
				echo "<input type='submit' value='Add Comment'>";
				echo "<input type='hidden' name='story' value=".$story_id.">";
				
				echo "</form>";
			}
			else {
				$story_id = (int)$_POST['story'];
				$comment = $_POST['new_comment'];
				$user_id = (int)$_SESSION['user_id'];
				
				$stmt = $mysqli->prepare("insert into comments (comment_content, user_id, story_id) values (?, ?, ?)");
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				 
				$stmt->bind_param('sii', $comment,$user_id,$story_id);
				 
				$stmt->execute();
				 
				$stmt->close();
				
				header("Location: news_listing.php");
			}

		?>
	</body>
</html>