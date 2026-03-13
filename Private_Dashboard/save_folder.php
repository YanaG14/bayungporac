<?php
require_once("../include/connection.php");
session_start();

$folder_name = $_POST['folder_name'];
$departments = $_POST['departments'] ?? [];

// Check if folder already exists
$check = mysqli_query($conn, "SELECT * FROM folders WHERE folder_name='$folder_name'");

if(mysqli_num_rows($check) > 0){
    // Instead of redirecting, return response for AJAX
    echo "exists";
    exit();
}

// Insert new folder
mysqli_query($conn, "INSERT INTO folders(folder_name) VALUES('$folder_name')");
$folder_id = mysqli_insert_id($conn);

// Insert departments
foreach($departments as $dept){
    mysqli_query($conn,"
        INSERT INTO folder_departments(folder_id,department_id)
        VALUES('$folder_id','$dept')
    ");
}

// Create folder directory
$path = "uploads/".$folder_name;
if(!file_exists($path)){
    mkdir($path,0777,true);
}

// KEEP your success toast system
$_SESSION['success'] = 'Folder Created Successfully!';
echo "success";
exit();
?>