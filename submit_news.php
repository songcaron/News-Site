<?php
session_start();
require 'database.php';

?>
<!DOCTYPE html>
<html>
<head>
<title>Submit News</title>
</head>
<body>
<?php
	if(!hash_equals($_SESSION['token'], $_POST['token'])){
		die("Request forgery detected");
	}
	if(isset($_POST['title']))
	{	
		//submit title
		$title = trim($_POST['title']);//strip whitespace from beginning and end of a string
		$title = htmlspecialchars($title);//convert special char to html entities
		$title = mysqli_real_escape_string($mysqli, $title);

		//text is sent to database
		$content = trim($_POST['content']);
		$content = htmlspecialchars($content);
		$content = mysqli_real_escape_string($mysqli, $content);
		
		//categories is sent to database
		$category = trim($_POST['categories']);
		
		//link to news 
		//if user inputs something, link is added to database else this field is set to null
		if(!isset($link))
		{
			$stmt = $mysqli-> prepare("insert into stories (title,story_content,category, user_id) values(?,?,?,?)");
			if(!$stmt)
			{
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
		 
			$stmt->bind_param('sssi', $title,$content,$category,$_SESSION['user_id']);
		}
		else 
		{
			$link = trim($_POST['link']);
			$link = htmlspecialchars($link);
			$link = mysqli_real_escape_string($mysqli, $link);
			
			$stmt = $mysqli-> prepare("insert into stories (title,story_content,category,link,user_id) values(?,?,?,?,?)");
			if(!$stmt)
			{
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
		 
			$stmt->bind_param('ssssi', $title,$content,$category,$link,$_SESSION['user_id']);
		}
	 
		$stmt->execute();
	 
		$stmt->close();
	
	}
?>

 <div class="form">
 <h1>News Submission</h1>
	 <form name="submit" action="" method="post">
		 <input type="text" name="title" placeholder="Title" required />
		 <select name = "categories">
			<option value = "Technology"> Technology</option>
			<option value = "TV"> TV </option>
			<option value = "Sports"> Sports </option>
			<option value = "Health"> Health </option>
			<option value = "Money"> Money </option>
			<option value = "Nature"> Nature </option>
		 <input type="text" name="link" placeholder="Link"  />
		 <input type = "text" name="content" placeholder="Content" required/>
		 <input type="submit" name="submit" value="Submit" />
	 </form>
 </div>
</body>
</html>