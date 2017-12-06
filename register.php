<?php
session_start();
require 'database.php';

if(isset($_SESSION['user']) != "")
{
	header("Location: ");
}


if(isset($_POST['register'])
{
	$username = trim($_POST['username']);//strip whitespace from beginning and end of a string
	$username = htmlspecialchars($username);//convert special char to html entities
	$username = mysqli_real_escape_string($mysqli, $username);
	
	$password = trim($_POST['password']);
	$password = htmlspecialchars($password);
	$password = mysqli_real_escape_string($mysqli, $password);
	
	//username validation
	if(empty($username))
	{
		echo "Please enter a username";
	}
	//password validation
	if(empty($password))
	{
		echo "Please enter a password";
	}
	else if(strlen($password) < 8)
	{
		echo "Password must be at least 8 characters long";
	}
	//password hashed/salted
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	if(password_verify($password, $hashed_password))
	{
		$stmt = $mysqli->prepare("insert into users (username, password)(?, ?)");
			if(!$stmt)
			{
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
		$stmt-> blind_param('sss', $username, $password);
		$stmt-> execute();
		$stmt-> close();
		echo "You have successfully registered! You may log in now.";
	}
}

?>
<!DOCTYPE html>
<html>
<body>
 <div class="form">
 <h1>News Submission</h1>
	 <form name="submit" action="" method="post">
		 <input type="text" name="title" placeholder="Title" required />
			
		 
	<input type="" name="password" placeholder="Password" required />
		 <input type="submit" name="register" value="Register" />
	 </form>
 </div>
</body>
</html>