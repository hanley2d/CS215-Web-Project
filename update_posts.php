<?
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$db = new mysqli("localhost", "hanley2d", "th3ori3s", "hanley2d");
    if ($db->connect_error) {
        die ("Connection failed: " . $db->connect_error);
    }   
$max_post_id = $_GET['pid'];
$user_id = $_SESSION['user_id'];
$q = "SELECT Posts.post_id, Posts.repost_id, Users.user_id, Users.username, Users.avatar_URL, Posts.post, Posts.post_date, COUNT(Likes.post_id) as Likes, COUNT(Dislikes.post_id) as Dislikes
        FROM Users       
        LEFT JOIN Posts ON Posts.user_id = Users.user_id
        LEFT JOIN Likes ON Posts.post_id = Likes.post_id
        LEFT JOIN Dislikes ON Posts.post_id = Dislikes.post_id 
        WHERE Posts.post_id > $max_post_id       
        GROUP BY Posts.post_id
        ORDER BY Posts.post_date ASC
        LIMIT 20;";

$result = $db->query($q); 
$jsonArray = array();
while ($row = $result->fetch_assoc()) {
    $jsonArray[] = $row;
}
echo json_encode($jsonArray);

$db->close();
?>