<?php

require_once("../include/connection.php");

if(isset($_POST['update'])){

$id = $_POST['folder_id'];
$name = mysqli_real_escape_string($conn,$_POST['folder_name']);

mysqli_query($conn,"
UPDATE folders
SET folder_name='$name'
WHERE folder_id='$id'
");

mysqli_query($conn,"
DELETE FROM folder_departments
WHERE folder_id='$id'
");

if(isset($_POST['departments'])){

foreach($_POST['departments'] as $dept){

mysqli_query($conn,"
INSERT INTO folder_departments(folder_id,department_id)
VALUES('$id','$dept')
");

}

}

header("Location: folder_management.php");

}

?>