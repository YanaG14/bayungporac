<?php
session_start();
require_once("../include/connection.php");

if(isset($_POST['edit_file'])){
    $file_id = intval($_POST['file_id']);
    $folder_id = intval($_POST['folder_id']);
    $new_name = trim($_POST['file_name']);
    $departments = $_POST['departments'] ?? [];

    // Get current file info
    $file = mysqli_fetch_assoc(mysqli_query($conn,"SELECT name FROM upload_files WHERE id='$file_id'"));
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

    $final_name = $new_name.'.'.$ext;

    // Update file name
    mysqli_query($conn,"UPDATE upload_files SET name='$final_name' WHERE id='$file_id'");

   // Delete existing department assignments for THIS FILE
mysqli_query($conn,"DELETE FROM file_departments WHERE file_id='$file_id'");

// Insert new department assignments for THIS FILE
foreach($departments as $dep_id){
    mysqli_query($conn,"INSERT INTO file_departments (file_id, department_id) VALUES ('$file_id', '$dep_id')");
}

    if(!empty($folder_id)){
    header("Location: add_document.php?folder_id=".$folder_id);
} else {
    header("Location: folder_management.php");
}
exit();
}
?>