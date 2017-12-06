<?php
session_start();
require 'database.php';

if(isset($_SESSION['user']) != "")
{
	header("Location: news_listing.php");
}


if(isset($_POST['login']))
{
    $username = trim($_POST['username']);//strip whitespace from beginning and end of a string
    $username = htmlspecialchars($username);//convert special char to html entities
    $username = mysqli_real_escape_string($mysqli, $username);
    
    $password = trim($_POST['password']);
    $password = htmlspecialchars($password);
    $password = mysqli_real_escape_string($mysqli, $password);
    
    //username validation
    // if(empty($username))
    // {
        // echo "Please enter a username";
    // }
    //password validation
    // if(empty($password))
    // {
        // echo "Please enter a password";
    // }
    // else if(strlen($password) < 8)
    // {
        // echo "Password must be at least 8 characters long";
    // }
    //password hashed/salted
	
	$stmt = $mysqli->prepare("SELECT id,username,password FROM users WHERE username=?");
 
	// Bind the parameter
	$stmt->bind_param('s', $username);
	$stmt->execute();
	
	// Bind the results
	$stmt->bind_result($user_id,$user_name, $pwd_hash);
	$stmt->fetch();
	
    if(password_verify($password, $pwd_hash))
    {
		$_SESSION['username'] = $username;
		$_SESSION['user_id'] = $user_id;
		
		$_SESSION['token'] = bin2hex(random_bytes(32));
		 
		header("Location: news_listing.php");
    }
	else {
		echo "Login information incorrect. Please try again.";
	}
}

?>
<!DOCTYPE html>
<html>
<body>
 <div class="form">
 <h1>Login</h1>
	 <form name="login" action="" method="post">
		 <input type="text" name="username" placeholder="Username" required />
		 <input type="password" name="password" placeholder="Password" required />
		 <input type="submit" name="login" value="Login" />
	 </form>
 </div>
</body>
</html>