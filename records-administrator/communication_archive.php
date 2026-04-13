<?php
session_start();
require_once("../include/connection.php");

// Ensure admin is logged in
if(!isset($_SESSION['admin_user'])){
    header("Location: index.php");
    exit();
}

// Archive the letter if file_id is provided
if(isset($_GET['file_id'])){
    $file_id = intval($_GET['file_id']);

    mysqli_query($conn, "
        UPDATE letters
        SET letter_status='Archived'
        WHERE id='$file_id'
    ");
}

// Redirect back to previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>