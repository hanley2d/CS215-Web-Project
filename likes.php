<?php
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$db = new mysqli("localhost", "hanley2d", "th3ori3s", "hanley2d");
    if ($db->connect_error) {
        die ("Connection failed: " . $db->connect_error);
    }   

$user_id = $_SESSION['user_id'];

//determine if like or dislike
// if dislike
if ($_GET['lid'] == NULL) {
    $post_id = $_GET['did'];

    $q = "SELECT * FROM Likes WHERE user_id = $user_id AND post_id = $post_id;";
    $like_result = $db->query($q);
    $likerow = $like_result->fetch_array();

    $q2 = "SELECT * FROM Dislikes WHERE user_id = $user_id AND post_id = $post_id;";
    $dislike_result = $db->query($q2);
    $dislikerow = $dislike_result->fetch_array();

    // if user hasn't liked or disliked a post 
    if ($likerow[0] == NULL && $dislikerow[0] == NULL) {
        $insert_dislike = "INSERT INTO Dislikes (post_id, user_id) VALUES ('$post_id', '$user_id');";
        $db->query($insert_dislike);
    }

    // if a user dislikes a post and has previously liked a post
    else if ($likerow[0] != NULL && $dislikerow[0] == NULL) {
        $delete_like = "DELETE FROM Likes WHERE post_id = '$post_id' AND user_id = '$user_id';";
        $db->query($delete_like);
        $insert_dislike = "INSERT INTO Dislikes (post_id, user_id) VALUES ('$post_id', '$user_id');";
        $db->query($insert_dislike);
    }
    
    // if a user previously disliked the post and wants to undislike
    // if user previously liked the post and wants to unlike
    else if ($dislikerow[0] != NULL) {
        $delete_like = "DELETE FROM Dislikes WHERE post_id = '$post_id' AND user_id = '$user_id';";
        $db->query($delete_like);
    }

// else if like
} else {
    $post_id = $_GET['lid'];

    $q = "SELECT * FROM Likes WHERE user_id = $user_id AND post_id = $post_id;";
    $like_result = $db->query($q);
    $likerow = $like_result->fetch_array();

    $q2 = "SELECT * FROM Dislikes WHERE user_id = $user_id AND post_id = $post_id;";
    $dislike_result = $db->query($q2);
    $dislikerow = $dislike_result->fetch_array();

    // if user hasn't liked or disliked a post
    if ($likerow[0] == NULL && $dislikerow[0] == NULL) {
        $insert_like = "INSERT INTO Likes (post_id, user_id) VALUES ('$post_id', '$user_id');";
        $db->query($insert_like);
    }
    // if user likes a post and has previously disliked the post
    else if ($likerow[0] == NULL && $dislikerow[0] != NULL) {
        $delete_dislike = "DELETE FROM Dislikes WHERE post_id = '$post_id' AND user_id = '$user_id';";
        $db->query($delete_dislike);
        $insert_like = "INSERT INTO Likes (post_id, user_id) VALUES ('$post_id', '$user_id');";
        $db->query($insert_like);
    }
    // if user previously liked the post and wants to unlike
    else if ($likerow[0] != NULL) {
        $delete_like = "DELETE FROM Likes WHERE post_id = '$post_id' AND user_id = '$user_id';";
        $db->query($delete_like);
    }
}

// array to return
// will return like_id if user has liked a post. else return null
// also return total post like count
// important to ORDER BY DESC in order to get the 20 most recent posts
$q3 = "SELECT p.post_id, (SELECT COUNT(like_id) FROM Likes l WHERE p.post_id = l.post_id) AS likes, 
(SELECT COUNT(dislike_id) FROM Dislikes d WHERE p.post_id = d.post_id) AS dislikes
from Posts p 
group by p.post_id 
ORDER BY p.post_id 
DESC LIMIT 20;";
$result = $db->query($q3);
$jsonArray = array();
while ($row = $result->fetch_assoc()) {
    $jsonArray[] = $row;
}
echo json_encode($jsonArray);

$db->close();
?>
