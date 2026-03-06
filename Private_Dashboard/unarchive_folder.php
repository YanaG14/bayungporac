<?php

require_once("../include/connection.php");

$id = $_GET['id'];

mysqli_query($conn,"
UPDATE folders
SET folder_status='Active'
WHERE folder_id='$id'
");

header("Location: folder_management.php");

?>