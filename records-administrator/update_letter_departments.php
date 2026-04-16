<?php
require_once("../include/connection.php");

$letter_id = intval($_POST['letter_id']);
$departments = $_POST['departments'] ?? [];

// delete old tags
mysqli_query($conn, "DELETE FROM letter_departments WHERE letter_id=$letter_id");

// insert new tags
foreach($departments as $dept_id){
    $dept_id = intval($dept_id);
    mysqli_query($conn, "
        INSERT INTO letter_departments (letter_id, department_id)
        VALUES ($letter_id, $dept_id)
    ");
}

header("Location: view_letter.php?id=".$letter_id);
exit();
?>