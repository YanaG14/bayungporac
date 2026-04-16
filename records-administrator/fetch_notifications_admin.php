
<?php
include("../include/connection.php");
date_default_timezone_set('Asia/Manila');
$notif = mysqli_query($conn, "
SELECT 
  c.id,
  c.comment,
  c.created_at,
  c.is_read,
  l.id AS letter_id,
  l.file_name,
  u.name AS commenter
FROM letter_comments c
JOIN letters l ON c.letter_id = l.id
JOIN login_user u ON c.user_id = u.id
WHERE c.is_read = 0
ORDER BY c.created_at DESC
LIMIT 10
");
$data = [];

while($row = mysqli_fetch_assoc($notif)){

    $time = strtotime($row['created_at']);
    $diff = time() - $time;

    if($diff < 60){
        $t = "Just now";
    } elseif($diff < 3600){
        $t = floor($diff/60)." mins ago";
    } elseif($diff < 86400){
        $t = floor($diff/3600)." hrs ago";
    } else {
        $t = floor($diff/86400)." days ago";
    }

    $data[] = [
      "id" => $row['id'],  
        "text" => $row['commenter']." commented on \"".$row['file_name']."\"",
        "time" => $t,
        "letter_id" => $row['letter_id']
    ];
}

echo json_encode($data);
?>