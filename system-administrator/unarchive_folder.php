<?php
require_once("../include/connection.php");
session_start();

if(isset($_GET['id'])){

    $id = $_GET['id'];

    mysqli_query($conn,"
    UPDATE folders
    SET folder_status='Active'
    WHERE folder_id='$id'
    ");

    $_SESSION['success'] = "Folder restored successfully!";
}

header("Location: folder_management.php");
exit();
?>