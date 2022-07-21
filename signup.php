<?php
$validate = true;
$error = "";
$reg_Email = "/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/";
$reg_Pswd = "/^(\S*)?\d+(\S*)?$/";
//$reg_Bday = "/^\d{1,2}\/\d{1,2}\/\d{4}$/";          
        //    "/^\d{4}-\d{1,2}-\d{1,2}$/"  YYYY-MM-DD
        //    "/^\d{1,2}\/\d{1,2}\/\d{4}$/"   MM/DD/YYYY
$email = "";
$date = "mm/dd/yyyy";


if (isset($_POST["submitted"]))
{    
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $date = trim($_POST["dob"]);
    $password = trim($_POST["pwrd"]);  
    $cpassword = trim($_POST["cpswrd"]);   

    $img_name = $_FILES['avatar']['name'];
    $img_size = $_FILES['avatar']['size'];
    $img_tmp = $_FILES['avatar']['tmp_name'];
    $uploadOk = 1;
    $img_message = "";
    
    $directory = 'avatars/';
    $target_file = $directory.$img_name;
       
    $db = new mysqli("localhost", "hanley2d", "password", "hanley2d");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }
    
    if($password==$cpassword)
    {

        $q1 = "SELECT * FROM Users WHERE email = '$email';";
        $r1 = $db->query($q1);    

        //if the email address is already taken.
        if($r1->num_rows > 0)
        {
            $validate = false;
            echo "email taken";
        }              
      
        else
        {
            $emailMatch = preg_match($reg_Email, $email);
            if($email == null || $email == "" || $emailMatch == false)
            {
                $validate = false;
                echo 'email error';
            }        
                
            $pswdLen = strlen($password);
            $pswdMatch = preg_match($reg_Pswd, $password);
            if($password == null || $password == "" || $pswdLen < 6 || $pswdMatch == false)
            {
                $validate = false;
                echo 'password error';
            } 
        
    }
}    

    //Check if image file is a actual image or fake image
    if(isset($_POST["submitted"])) {            
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if($check !== false) {
        $imageMessage .= "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
        } else {
            $imageMessage .= "File is not an image.";
            $uploadOk = 0;
        }
    }      

    // Check file size
    if ($_FILES["avatar"]["size"] > 500000) {
    $imageMessage .= "Sorry, your file is too large.";
    $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    $imageMessage .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    $imageMessage .= "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } 
    else {           
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
        $imageMessage .= "The file ". htmlspecialchars( basename( $_FILES["avatar"]["name"])). " has been uploaded.";       
        } 
        else {
            $imageMessage .= "Sorry, there was an error uploading your file.";
        }
    }
    if($validate == true)
    {            
        move_uploaded_file($img_tmp, $target_file);

        $dateFormat = date("Y-m-d", strtotime($date));        
        $q2 = "INSERT INTO Users (username, email, password, dob, avatar_URL) VALUES  ('$username', '$email', '$password', '$date', '$target_file');";       
        $r2 = $db->query($q2);        
        if ($r2 === true)
        {
            header("Location: login.php");
            $db->close();
            exit();
        }
    }
    else
    {
        $error = "Email address is not available. Signup failed.";
        $db->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup to Gibbernet</title>
    <link rel="stylesheet" type="text/css" href="A2_stylesheet.css">   
    <!-- <script type="text/javascript" src="A3_validate.js"></script>      -->
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
            <form id="signup" action="signup.php" method="POST" enctype="multipart/form-data">                       
                <h2>create an account</h2>
                <label id="msg_uname" class="err_msg"></label><br>
                
                <input type="text" name="username" placeholder="username"><br>
                <label id="msg_email" class="err_msg"></label><br>
                
                <input type="email" name="email" placeholder="email"><br>
                <label id="msg_dob" class="err_msg"><?= $error ?></label><br>
                date of birth <br>
                <input type="date" name="dob"><br>
                <label id="msg_ava" class="err_msg"><?= $img_message ?></label><br>
                avatar image <br>
                <input type="file" name="avatar" id="avatar"/><br>
                <label id="msg_pswd" class="err_msg"></label><br>
                
                <input type="password" name="pwrd" placeholder="password"/> <br> 
                <label id="msg_pswdr" class="err_msg"></label><br>               
                
                <input type="password" name="cpswrd" placeholder="confirm password"/>
                <br><br>
                <input type="hidden" name="submitted" value="1"/>
                <input type="submit" value="submit" name="submit"/>
                
            </form>              
            <!-- <div id="display_info"></div>             -->
            <script type="text/javascript" src="js/signup-r.js"></script>
            <p>Already have an account? <br> <a href="login.php">login here</a></p>        
        </div> 
    </div>

    <footer class="footer"> 
        <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fwww2.cs.uregina.ca%2F~hanley2d%2FA3%2Fsignup.html">Validate HTML</a>

    </footer>    

</body>

</html>
