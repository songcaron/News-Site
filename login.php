<?php
session_start();
require 'database.php';

if(isset($_SESSION['user']) != "")
{
	header("Location: ");
}


if(isset($_POST['login'])
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
        $error = true;
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
        $stmt = $mysqli->prepare("select username, password from users order by password)");
        $row = mysql_fetch_array($stmt);
        $count = mysql_num_rows($stmt);
        if($count == 1 && $row['password'] == $password)
        {
         $_SESSION['user'] = $row['username'];
         header("Location: ");//location for page after login successful
        }
        else
            echo "Incorrect login information. Please try again.";        
    }
}

?>
<!DOCTYPE html>
<html>
<body>
 <div class="form">
 <h1>Registration</h1>
	 <form name="login" action="" method="post">
		 <input type="text" name="username" placeholder="Username" required />
		 <input type="password" name="password" placeholder="Password" required />
		 <input type="submit" name="login" value="Login" />
	 </form>
 </div>
</body>
</html>