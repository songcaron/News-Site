<?php
    session_start();
    require 'database.php';
?>
<!DOCTYPE html> 
<html>
	<head>
        <title>News Listing</title>
        <link rel='stylesheet' type='text/css' href='css/listing_style.css'>
    </head>
	<body>
		<?php
			if(!isset($_SESSION['current_category'])) {
				$_SESSION['current_category'] = "1";
			}
			if(isset($_SESSION['username'])) {
				$current_user_id = $_SESSION['user_id'];
				
				//create story 
				echo "<form action='submit_news.php' method='POST'>";
                echo "<input class='new_story' type='submit' value='Create Story'>";
				echo "<input type='hidden' name='token' value='".$_SESSION['token']."'>";
                echo "</form>";
				
				echo "Welcome back, ".$_SESSION['username']."&nbsp;&nbsp;";
                
				
                //logout                                    
                echo "<form action='news_logout.php' method='POST'>";
                echo "<input class='logout' type='submit' value='Logout'>";
				echo "<input type='hidden' name='token' value='".$_SESSION['token']."'>";
                echo "</form>";
                
				//profile page                                    
                echo "<form action='profile_page.php' method='POST'>";
                echo "<input class='profile' type='submit' value='Profile Page'>";
				echo "<input type='hidden' name='token' value='".$_SESSION['token']."'>";
                echo "</form>";
			}
			else {
				//logout                                    
                echo "<form action='news_register.php' method='POST'>";
                echo "<input class='sign_up' type='submit' value='Sign Up'>";
				echo "<input type='hidden' name='token' value='".$_SESSION['token']."'>";
                echo "</form>";
			}
			
			echo '<form name="change_category" action="change_category.php" method="post">'; 
			echo '<select name = "categories">';
			
			switch ($_SESSION['current_category']) {
				case "1":
					echo 	'<option value = "1" selected>All Categories</option>';
					echo 	'<option value = "Technology"> Technology</option>';
					echo 	'<option value = "TV"> TV </option>';
					echo 	'<option value = "Sports"> Sports </option>';
					echo 	'<option value = "Health"> Health </option>';
					echo 	'<option value = "Money"> Money </option>';
					echo 	'<option value = "Nature"> Nature </option>';
					break;
				case "Technology":
					echo 	'<option value = "1" >All Categories</option>';
					echo 	'<option value = "Technology" selected> Technology</option>';
					echo 	'<option value = "TV"> TV </option>';
					echo 	'<option value = "Sports"> Sports </option>';
					echo 	'<option value = "Health"> Health </option>';
					echo 	'<option value = "Money"> Money </option>';
					echo 	'<option value = "Nature"> Nature </option>';
					break;
				case "TV":
					echo 	'<option value = "1" >All Categories</option>';
					echo 	'<option value = "Technology"> Technology</option>';
					echo 	'<option value = "TV" selected> TV </option>';
					echo 	'<option value = "Sports"> Sports </option>';
					echo 	'<option value = "Health"> Health </option>';
					echo 	'<option value = "Money"> Money </option>';
					echo 	'<option value = "Nature"> Nature </option>';
					break;
				case "Sports":
					echo 	'<option value = "1" >All Categories</option>';
					echo 	'<option value = "Technology"> Technology</option>';
					echo 	'<option value = "TV"> TV </option>';
					echo 	'<option value = "Sports" selected> Sports </option>';
					echo 	'<option value = "Health"> Health </option>';
					echo 	'<option value = "Money"> Money </option>';
					echo 	'<option value = "Nature"> Nature </option>';
					break;
				case "Health":
					echo 	'<option value = "1" >All Categories</option>';
					echo 	'<option value = "Technology"> Technology</option>';
					echo 	'<option value = "TV"> TV </option>';
					echo 	'<option value = "Sports"> Sports </option>';
					echo 	'<option value = "Health" selected> Health </option>';
					echo 	'<option value = "Money"> Money </option>';
					echo 	'<option value = "Nature"> Nature </option>';
					break;
				case "Money":
					echo 	'<option value = "1" >All Categories</option>';
					echo 	'<option value = "Technology"> Technology</option>';
					echo 	'<option value = "TV"> TV </option>';
					echo 	'<option value = "Sports"> Sports </option>';
					echo 	'<option value = "Health"> Health </option>';
					echo 	'<option value = "Money" selected> Money </option>';
					echo 	'<option value = "Nature"> Nature </option>';
					break;
				case "Nature":
					echo 	'<option value = "1">All Categories</option>';
					echo 	'<option value = "Technology"> Technology</option>';
					echo 	'<option value = "TV"> TV </option>';
					echo 	'<option value = "Sports"> Sports </option>';
					echo 	'<option value = "Health"> Health </option>';
					echo 	'<option value = "Money"> Money </option>';
					echo 	'<option value = "Nature" selected> Nature </option>';
					break;
			}
			echo '</select>';
			echo '<input type="submit" name="submit" value="Filter News">';
			echo "<input type='hidden' name='token' value='".$_SESSION['token']."'>";
			echo '</form>';
					
			if($_SESSION['current_category'] == 1) {
				
				$stmt = $mysqli->prepare("select stories.user_id,stories.title,stories.story_content,stories.link,comments.id,comments.user_id,stories.id,comments.comment_content from stories left join comments on (stories.id=comments.story_id) group by (comments.id) order by stories.id");
				if(!$stmt) {
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
			}
			else {
				$stmt = $mysqli->prepare("select stories.user_id,stories.title,stories.story_content,stories.link,comments.id,comments.user_id,stories.id,comments.comment_content from stories left join comments on (stories.id=comments.story_id) where stories.category=? group by (comments.id) order by stories.id");
				if(!$stmt) {
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				
				$stmt->bind_param('s',$_SESSION['current_category']);
			}

			
			$stmt->execute();
			
			$stmt->bind_result($story_user_id,$title,$story_content,$link,$comment_id,$comment_user_id,$story_id,$comment_content);
			
			$current_story_id = 0;
			$last_entry = false; //to know if reached end of story contents (including comments)
			while($stmt->fetch()){
				if($current_story_id != $story_id) {//checks if story is the same as previous entry
					$current_story_id = $story_id;
					if(is_null($link)) { //checks if there is a link associated with a story
						printf("%s"."<br>",
						htmlspecialchars($title)
						);
					}
					else {
						printf("<a href=%s>%s</a>",htmlspecialchars($link),htmlspecialchars($title));

					}
					if($story_user_id==$current_user_id) {
						echo "<form method='POST'>";
						echo "<input type='submit' formaction='add_comment.php' value='Add Comment'>";
						echo "<input type='submit' formaction='edit_story.php' value='Edit Story'>";
						echo "<input type='submit' formaction='delete_story.php' value='Delete Story'>";
						echo "<input type='hidden' name='story' value=".$story_id.">";
						echo "<input type='hidden' name='story_title' value='".$title."'>";
						echo "<input type='hidden' name='token' value='".$_SESSION['token']."'>";
						echo "</form>";
					}
					else {
						echo "<form method='POST'>";
						echo "<input type='submit' formaction='add_comment.php' value='Add Comment'>";
						echo "<input type='hidden' name='story' value=".$story_id.">";
						echo "<input type='hidden' name='story_title' value='".$title."'>";
						echo "<input type='hidden' name='token' value='".$_SESSION['token']."'>";
						echo "</form>";
					}
					printf("%s"."<br>",
						htmlspecialchars($story_content)
						);
				}

				//adds comments to stories
				if($comment_user_id==$current_user_id) {
					echo "<form action='delete_comment.php' method='POST'>";
					echo $comment_content."<input type=submit formaction='edit_comment.php' value='Edit Comment'><input type=submit formaction='delete_comment.php' value='Delete Comment'><br>";
					echo "<input type='hidden' name='comment' value=".$comment_id.">";
					echo "<input type='hidden' name='token' value='".$_SESSION['token']."'>";
					echo "</form>";
				}
				else {
					echo $comment_content;
					echo "<br>";
				}
				
			}
			 
			$stmt->close();
		?>
	</body>
</html> 