<?php
session_start();
require_once("../include/connection.php");

$letter_id = $_POST['letter_id'];

// loop through uploaded files
for ($i = 0; $i < count($_FILES['files']['name']); $i++) {

    $name = $_FILES['files']['name'][$i];
    $tmp  = $_FILES['files']['tmp_name'][$i];

    $unique = time() . "_" . rand(1000,9999) . "_" . $name;
    $path = "../records-administrator/letter_files/" . $unique;

    move_uploaded_file($tmp, $path);

    mysqli_query($conn, "
        INSERT INTO letter_files (letter_id, file_name, file_path)
        VALUES ('$letter_id', '$name', '$unique')
    ");
}

echo "success";
?>