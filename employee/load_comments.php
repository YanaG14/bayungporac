<?php
require_once("../include/connection.php");

$letter_id = $_GET['letter_id'];

$query = mysqli_query($conn, "
    SELECT 
        lc.comment,
        lc.created_at,
        lu.name,
        d.department_name
    FROM letter_comments lc
    JOIN login_user lu ON lc.user_id = lu.id
    LEFT JOIN departments d ON lu.department_id = d.department_id
    WHERE lc.letter_id = '$letter_id'
    ORDER BY lc.created_at DESC
");

if(mysqli_num_rows($query) == 0){
    echo '<div class="bg-white p-2 rounded-lg border">No comments yet.</div>';
}

while($row = mysqli_fetch_assoc($query)){
    echo '
    <div class="bg-white p-2 rounded-lg border mb-2">
        <b>' . htmlspecialchars($row['name']) . '</b>
        <span class="text-xs text-gray-500">
            ' . htmlspecialchars($row['department_name']) . '
        </span>
        <br>
        ' . htmlspecialchars($row['comment']) . '
        <div class="text-xs text-gray-400 mt-1">
            ' . $row['created_at'] . '
        </div>
    </div>';
}
?>