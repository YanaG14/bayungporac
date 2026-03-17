<?php
require_once("../include/connection.php");
session_start();

$id = $_GET['id'];

mysqli_query($conn,"
UPDATE folders
SET folder_status='Archived'
WHERE folder_id='$id'
");

//toast message
$_SESSION['success'] = "Folder archived successfully!";

header("Location: folder_management.php");
exit();
?>