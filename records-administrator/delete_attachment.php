<?php
session_start();
require_once("../include/connection.php");

if(isset($_POST['file_id'])){

    $file_id = intval($_POST['file_id']);

    // 1. Get file path first
    $get = mysqli_query($conn, "
        SELECT file_path 
        FROM letter_files 
        WHERE file_id = $file_id
    ");

    if(mysqli_num_rows($get) > 0){
        $row = mysqli_fetch_assoc($get);
        $file = "../records-administrator/letter_files/" . $row['file_path'];

        // 2. Delete from folder
        if(file_exists($file)){
            unlink($file);
        }

        // 3. Delete from database
        mysqli_query($conn, "
            DELETE FROM letter_files 
            WHERE file_id = $file_id
        ");
    }
}

echo "deleted";
?>

