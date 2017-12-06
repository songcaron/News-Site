<?php
	session_start();
    require 'database.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edit Comment</title>
	</head>
	<body>
		<?php 
			if(isset($_POST['comment'])) {
				$comment_id = $_POST['comment'];
				
				$stmt = $mysqli->prepare("select comment_content from comments where id=?");
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				 
				$stmt->bind_param('i', $comment_id);
			 
				$stmt->execute();
				 
				$stmt->bind_result($content);
				
				while($stmt->fetch()) {
					echo '<form name="submit" method="post">';
					echo	 '<input type="text" name="comment_entry" required=required value="'.htmlentities($content).'">';
					echo 	 '<input type="hidden" name="new_comment" value='.$comment_id.'>';
					echo	 '<input type="submit" name="submit" value="Update Comment">';
					echo '</form>';
				}
			}
			else {
				$comment = $_POST['comment_entry'];
				$comment_id = (int)$_POST['new_comment'];
				
				$stmt = $mysqli->prepare("update comments set comment_content=? where id=?");
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				 
				$stmt->bind_param('si', $comment, $comment_id);
				 
				$stmt->execute();
				 
				$stmt->close();
				
				header("Location: news_listing.php");
			}
		
		?>
	</body>
</html>