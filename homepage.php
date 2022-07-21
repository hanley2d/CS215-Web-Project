<?php 
    session_start();
    if(!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    } 
    else {        
        $db = new mysqli("localhost", "hanley2d", "password", "hanley2d");
	  	if ($db->connect_error) {
	  		die ("Connection failed: " . $db->connect_error);
		}     
        $q = "SELECT p.post_id, p.repost_id, p.post, p.post_date, u.user_id, u.username, u.avatar_URL, 
        (SELECT COUNT(like_id) FROM Likes l WHERE p.post_id = l.post_id) AS Likes,
        (SELECT COUNT(dislike_id) FROM Dislikes d WHERE p.post_id = d.post_id) AS Dislikes
        FROM Posts p
        LEFT JOIN
        Users u
        ON p.user_id = u.user_id
        ORDER BY p.post_date DESC
        LIMIT 20;";

        $result = $db->query($q);   
        $user_id = $_SESSION["user_id"];          
    } 
?>

<!DOCTYPE html>

<html lang="zxx">
<head>
    <link rel="stylesheet" type="text/css" href="A2_stylesheet.css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>      
    <title>Gibbernet Home</title>
</head>

<body>
    <header>        
        <nav>                    
            <ul class="mainNav sticky">
                <li class="navTitle"><a href="homepage.php">Gibbernet</a></li>
                <li class="navItem"><a href="user_detail.php?user=<?=$_SESSION['username']?>"><?=$_SESSION['username']?></a></li>
                <li class="navItem"><a href="post_repost.php">post</a></li>                
                <!-- <li class="navItem"><a href="login.php">login</a></li>
                <li class="navItem"><a href="signup.php">sign up</a></li> -->
                <li class="navItem"><a href="logout.php">logout</a></li>
            </ul>        
        </nav>
    </header>  
    <div id="container" class="container">  
        
    <?
        while ($row = $result->fetch_assoc()) {
            if($row["repost_id"] === NULL) {
    ?> 
    
        <div id="<?=$row["post_id"]?>" class="post">
                <div class="post_header">
                    <span class="user_name"><a href="user_detail.php?user=<?=$row["username"]?>"><?= $row["username"] ?></a></span>
                    <span class="post_date"><?= $row["post_date"] ?></span>
                    <img src="<?=$row['avatar_URL']?>" alt="avatar"/>
                </div>                
                    <p><?=$row["post"]?></p>
                <div class="post_footer">
                    <? 
                        $thispost = $row["post_id"];
                        $q5 = "select * from Likes where user_id = $user_id and post_id = $thispost;";
                        $r5 = $db->query($q5);
                        if (mysqli_num_rows($r5) > 0) {
                    ?>
                    <span style="color:orange;" id="likeButton_<?=$row["post_id"]?>" class="like material-icons">arrow_circle_up</span>
                    <?}else{?>
                    <span id="likeButton_<?=$row["post_id"]?>" class="like material-icons">arrow_circle_up</span>
                    <?}?>
                    <span id="likeCount_<?=$row["post_id"]?>"><?=$row["Likes"]?></span>
                    <? 
                        $thispost = $row["post_id"];
                        $q5 = "select * from Dislikes where user_id = $user_id and post_id = $thispost;";
                        $r5 = $db->query($q5);
                        if (mysqli_num_rows($r5) > 0) {
                    ?>
                    <span style="color:orange;" id="dislikeButton_<?=$row["post_id"]?>" class="dislike material-icons">arrow_circle_down</span>
                    <?}else{?>
                    <span id="dislikeButton_<?=$row["post_id"]?>" class="dislike material-icons">arrow_circle_down</span>  
                    <?}?>
                    <span id="dislikeCount_<?=$row["post_id"]?>"><?=$row["Dislikes"]?></span>             
                    <a href="post_repost.php?pid=<?=$row["post_id"]?>">repost</a>      
                </div>
        </div>       
    <?
            }                        
            else if ($row["repost_id"] != NULL) {
            
    ?>
        <div id ="<?=$row["post_id"]?>" class="repost">
            <div class="post_header">
                <span class="user_name"><a href="user_detail.php?user=<?=$row["username"]?>"><?= $row["username"] ?> reposted</a></span>
                <span class="post_date"><?= $row["post_date"] ?></span>
                <img src="<?=$row['avatar_URL']?>" alt="avatar"/>
            </div>
                <p><?=$row["post"]?></p>
            <div>
            <p></p>                                  
                <div id="<?=$row["post_id"]?>" class="post">                        
                    <div class="post_header">
                    <?
                        $repost_id = $row["repost_id"];
                        $q3 = "SELECT post, post_date, username, avatar_URL FROM Posts LEFT JOIN Users ON Posts.user_id = Users.user_id WHERE post_id = $repost_id;";
                        $r3 = $db->query($q3); 
                        $repost = $r3->fetch_array();
                    ?> 
                        <span class="user_name"><a href="user_detail.php?user=<?=$row["username"]?>"><?= $repost[2] ?></a></span>
                        <span class="post_date"><?= $repost[1] ?></span>
                        <img src="<?= $repost[3] ?>" alt="avatar"/>
                    </div>                                         
                        <p> <?= $repost[0] ?></p>
                    
                    <div class="post_footer">
                        <!-- <span id="likeButton_<?=$row["post_id"]?>" class="like material-icons">arrow_circle_up</span>
                        <span id="dislikeButton_<?=$row["post_id"]?>" class="dislike material-icons">arrow_circle_down</span>                         -->
                    </div>                                                       
                </div> 
                <div class="post_footer"> 
                <? 
                        $thispost = $row["post_id"];
                        $q5 = "select * from Likes where user_id = $user_id and post_id = $thispost;";
                        $r5 = $db->query($q5);
                        if (mysqli_num_rows($r5) > 0) {
                    ?>
                    <span style="color:orange;" id="likeButton_<?=$row["post_id"]?>" class="like material-icons">arrow_circle_up</span>
                    <?}else{?>
                    <span id="likeButton_<?=$row["post_id"]?>" class="like material-icons">arrow_circle_up</span>    
                    <? } ?>
                    <span id="likeCount_<?=$row["post_id"]?>"><?=$row["Likes"]?></span>
                    <? 
                        $thispost = $row["post_id"];
                        $q5 = "select * from Dislikes where user_id = $user_id and post_id = $thispost;";
                        $r5 = $db->query($q5);
                        if (mysqli_num_rows($r5) > 0) {
                    ?>
                    <span style="color:orange;" id="dislikeButton_<?=$row["post_id"]?>" class="dislike material-icons">arrow_circle_down</span>
                    <?}else{?>
                    <span id="dislikeButton_<?=$row["post_id"]?>" class="dislike material-icons">arrow_circle_down</span>  
                    <?}?>                    
                    <span id="dislikeCount_<?=$row["post_id"]?>"><?=$row["Dislikes"]?></span> 
                    <a href="post_repost.php?pid=<?=$row["post_id"]?>">repost</a>                                                                                         
                </div> 
            </div> 
            <p></p>
        </div>
            
    <?
        }
    }
        $db->close();    
    ?>   
    </div>
    <footer class="footer"> 
        <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fwww2.cs.uregina.ca%2F~hanley2d%2FA3%2Fhomepage.html">Validate HTML</a>
    </footer>
    <script type="text/javascript" src="js/like_dislike.js"></script>
</body>
</html>
