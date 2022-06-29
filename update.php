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

$user_id = $_SESSION['user_id'];
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