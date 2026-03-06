<?php
require_once("../include/connection.php");

$folder_name = $_POST['folder_name'];
$departments = $_POST['departments'];

$check = mysqli_query($conn,"SELECT * FROM folders WHERE folder_name='$folder_name'");

if(mysqli_num_rows($check)>0){
echo "<script>alert('Folder already exists');window.location='folder_management.php';</script>";
exit();
}

mysqli_query($conn,"INSERT INTO folders(folder_name) VALUES('$folder_name')");
$folder_id = mysqli_insert_id($conn);

foreach($departments as $dept){

mysqli_query($conn,"
INSERT INTO folder_departments(folder_id,department_id)
VALUES('$folder_id','$dept')
");

}

$path="uploads/".$folder_name;

if(!file_exists($path)){
mkdir($path,0777,true);
}

echo "<script>alert('Folder Created');window.location='folder_management.php';</script>";

?>