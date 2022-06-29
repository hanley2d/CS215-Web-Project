<?php
    session_start();    
    if(!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    } else {    
        $repost_id = $_GET["pid"];           
        if (isset($_POST["submitted"]) && $_POST["submitted"]) {
            $user_id = $_SESSION["user_id"];
            $username = $_SESSION["username"];
            $post = $_POST["textbox"];
            $post_date = date('Y-m-d H:i:s'); 
            $repost = $_POST["repost"];                       

            $db = new mysqli("localhost", "hanley2d", "th3ori3s", "hanley2d");
            if ($db->connect_error) {
                die ("Connection failed: " . $db->connect_error);
            }   
                     
            if (!$repost) {
            $q = "INSERT INTO Posts (user_id, post, post_date) VALUES ('$user_id', '$post', '$post_date');";
            $result = $db->query($q);
            } else {
                $q = "INSERT INTO Posts (user_id, post, post_date, repost_id) VALUES ('$user_id', '$post', '$post_date', '$repost');";
                $result = $db->query($q);
            }            
            if ($result === true) {
                header("Location: homepage.php");
                $db->close();
            }
        }        
    }
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="A2_stylesheet.css"/>    
    <title>Post something</title>
</head>    

<body>
    <header>        
        <nav>                    
            <ul class="mainNav sticky">
                <li class="navTitle"><a href="homepage.php">Gibbernet</a></li>
                <!-- <li class="navItem"><a href="post_repost.html">post</a></li>                
                <li class="navItem"><a href="login.html">login</a></li>
                <li class="navItem"><a href="signup.html">sign up</a></li> -->
               <li class="navItem"><a href="logout.php">logout</a></li>
            </ul>        
        </nav>
    </header>
    <div class="container">
        <div class="box">            
            <form action="post_repost.php" id="textbox" method="POST">                    
                <h2>Post something. Be nice.</h2> 
                
                <?
                if ($repost_id != NULL) { 
                print ("Repost of post " . $repost_id);?>
                <input type="hidden" name="repost" value="<?=$repost_id?>"></input>
                <?}?>
                       
                <p><textarea  name="textbox" rows="10" cols="50" placeholder="your text goes here."></textarea></p>
                <span id="display_info">256/256</span><br>
                <span id="display_info2"></span>
                <pre><input type="submit" id="submit" value="submit" /></pre>
                <input type="hidden" name="submitted" value="1"/>
                <pre><br></pre>
              </form>
              <script type="text/javascript" src="js/post_repost.js"></script>
        </div>
    </div>

    <footer> 
        <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fwww2.cs.uregina.ca%2F~hanley2d%2FA3%2Fpost_repost.html">Validate HTML</a>

    </footer>
</body>
</html>
