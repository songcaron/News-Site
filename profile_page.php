<?php
	session_start();
	require 'database.php';
?>
<!DOCTYPE html>
<html>
	<head>
	<title>Profile Page</title>
	</head>
		<body>
			<?php
				
				if(!hash_equals($_SESSION['token'], $_POST['token'])){
					die("Request forgery detected");
				}
				if(isset($_SESSION['username']))
				{
					
					echo "Welcome to your profile, ".$_SESSION['username']."&nbsp;&nbsp;";
					$current_user_id = $_SESSION['user_id'];
					
			
					//delete user
					echo "<form action='news_deleteuser.php' method='POST'>";
					echo "<input class='delete' type='submit' value='Delete Account'>";
					echo "</form>";
					
					//change password
					
					echo "<input type = "password" class = "form-control" id = "password" placeholder = "Password" name = "password">";
					echo "<input type = "password" class = "form-control" id = "hashednewpwd" placeholder = "New Password" name = "newpassword">";
					echo "<input type = "password" class = "form-control" id = "hashedconpwd" placeholder = "Confirm New Password" name = "confirmednewpassword">";
					$username = $_SESSION['username'];
					$password = $_SESSION['password'];
					
					$newpassword = trim($_POST['newpassword']);
					$newpassword = htmlspecialchars($newpassword);
					$newpassword = mysqli_real_escape_string($mysqli, $newpassword);
					$hashednewpwd = password_hash($newpassword, PASSWORD_DEFAULT);
					
					$confirmednewpassword = trim($_POST['confirmednewpassword']);
					$confirmednewpassword = htmlspecialchars($confirmednewpassword);
					$confirmednewpassword = mysqli_real_escape_string($mysqli, $confirmednewpassword);
					$hashedconpwd = password_hash($confirmednewpassword, PASSWORD_DEFAULT);
					
					$result = mysql_query("SELECT count(*) FROM users WHERE username = '$username'");
					if(mysql_result($result, 0) == 1) //finds combination of user/pass
					{
						if($hashednewpwd == $hashedconpwd)
						{
							$stmt = mysql_query("UPDATE users SET password = '$hashednewpwd' WHERE username = '$username' AND password = '$password'");
							if($stmt)
							{
								echo "You have successfully changed your password!";
							}
							else
							{
								echo "Both pathwords must match.";
							}
						}
						else
						{
							echo "Your password is incorrect.";
						}
					}
			$stmt = $mysqli->prepare("select stories.user_id,stories.title,stories.story_content,stories.link,comments.id,comments.user_id,comments.story_id,comments.comment_content from stories left join comments on (stories.id=comments.story_id) group by (comments.id) order by comments.story_id");
			if(!$stmt) {
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			
			$stmt->execute();
			
			$stmt->bind_result($story_user_id,$title,$story_content,$link,$comment_id,$comment_user_id,$story_id,$comment_content);
			
			$current_story_id = 0;
			$set_entry = false; //to skip print of duplicate story
			while($stmt->fetch()){
				if($current_story_id != $story_id) {//checks if story is the same as previous entry
					$current_story_id = $story_id;
					if(is_null($link)) { //checks if there is a link associated with a story
						printf("%s"."<br>",
						htmlspecialchars($title)
						);
					}
					else {
						printf("<a href=%s>%s</a><br>",htmlspecialchars($link),htmlspecialchars($title));

					}
					printf("%s"."<br>",
						htmlspecialchars($story_content)
						);
				}
				else if($current_story_id == $story_id) {
					
				}
				else {
					echo "<br><br>";
				}

				//adds comments to stories
				if($comment_user_id==$current_user_id) {
					echo "<form action='delete_comment.php' method='POST'>";
					echo $comment_content."<input type=submit value='Delete Comment'><br>";
					echo "<input type='hidden' name='comment' value=".$comment_id.">";
					echo "</form>";
				}
				else {
					echo $comment_content;
					echo "<br>";
				}
				
			}
			 
			$stmt->close();
			}
		?>
	</body>
</html>
		
		
					
					
					
					
	