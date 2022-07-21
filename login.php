<?php
	// check to see if the form was submitted
	if (isset($_POST["submitted"]) && $_POST["submitted"]) {
		// get the username and password and check that they aren't empty
		$email = trim($_POST["email"]);
		$password = trim($_POST["pwrd"]);
		if (strlen($email) > 0 && strlen($password) > 0) {
			// load the database and verify the username/password
			$db = new mysqli("localhost", "hanley2d", "password", "hanley2d");
		  	if ($db->connect_error) {
		  		die ("Connection failed: " . $db->connect_error);
		  	}
		  
		  	$q = "SELECT user_id, username FROM Users WHERE email = '$email' AND password = '$password';";
		  	$result = $db->query($q);
		  
		  	if ($row = $result->fetch_assoc()) {
		  		// login successful
		  		session_start();
				$_SESSION["user_id"] = $row["user_id"];
				$_SESSION["username"] = $row["username"];
				header("Location: homepage.php");
				$db->close();
				exit();
			} else {
				// login unsuccessful
				$error = ("The username/password combination was incorrect.");
				$db->close();
			}
		} else {
			$error = ("You must enter a non-blank username/password combination to login.");
		}
	} else {
		$error = "";
	}
?>


<!DOCTYPE html>

<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="A2_stylesheet.css">    
    <!-- <script type="text/javascript" src="A3_validate.js"></script>     -->
</head>   

<body>
    <header>        
        <nav>                    
            <ul class="mainNav sticky">
                <li class="navTitle"><a href="homepage.php">Gibbernet</a></li>
                <!-- <li class="navItem"><a href="post_repost.html">post</a></li>                
                <li class="navItem"><a href="login.html">login</a></li>
                <li class="navItem"><a href="signup.html">sign up</a></li>                                       -->
            </ul>        
        </nav>
    </header>
    <div class="container">
        
        <div class="box">
            <p class="error"><?=$error?></p>
            <form action="login.php" id="login" method="POST">
                <h2>login</h2>
                <label id="msg_email" class="err_msg"></label><br>
                
                <input type="text" name="email" placeholder="email"><br>
                <label id="msg_pswd" class="err_msg"></label><br>
               
                <input type="password" name="pwrd" placeholder="password">
                <br> <br>
                <input type="submit" value="submit">
                <input type="hidden" name="submitted" value="1" />
            </form>
            <!-- <div id="display_info"></div> -->
            <script type="text/javascript" src="js/login-r.js"></script>
            <p>Not signed up? <br> <a href="signup.php">sign up here</a></p>
        </div>
    </div>

    <footer class="footer"> 
        
        <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fwww2.cs.uregina.ca%2F~hanley2d%2FA3%2Flogin.html">Validate HTML</a>

    </footer>
</body>

</html>

