<?php
session_start();
require_once("../include/connection.php");

if(!isset($_SESSION['admin_user'])){
    header("Location: index.php");
    exit();
}

if(isset($_GET['file_id'])){

    $file_id = intval($_GET['file_id']);

    mysqli_query($conn,"
        UPDATE upload_files 
        SET status='Active'
        WHERE id='$file_id'
    ");

}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>