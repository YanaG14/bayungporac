<?php

session_start();
require_once("../include/connection.php");

if(!isset($_SESSION['admin_user'])){
    header("Location: index.php");
    exit;
}

$folder_id = intval($_POST['folder_id']);
$departments = $_POST['departments'] ?? [];

$admin = $_SESSION['admin_user'];
$date = date("M-d-Y h:i A");

$uploadDir = "../uploads/";

// create uploads folder if not existing
if(!is_dir($uploadDir)){
    mkdir($uploadDir,0777,true);
}

$files = $_FILES['files'];

for($i=0; $i<count($files['name']); $i++){

    $name = $files['name'][$i];
    $tmp = $files['tmp_name'][$i];
    $size = $files['size'][$i];

    // ✅ ADD THIS BLOCK HERE
    if ($files['error'][$i] !== UPLOAD_ERR_OK) {
        continue; // skip this file if upload error occurred
    }

    if($name == "") continue;

    // prevent overwrite
    $newName = time()."_".$name;

    $destination = $uploadDir.$newName;

    if(move_uploaded_file($tmp,$destination)){

        mysqli_query($conn,"
        INSERT INTO upload_files
        (folder_id,name,file_path,size,download,timers,admin_status,email)
        VALUES
        ('$folder_id','$name','$newName','$size','0','$date','Admin','$admin')
        ");

        $file_id = mysqli_insert_id($conn);

        foreach($departments as $dept){

            mysqli_query($conn,"
            INSERT INTO file_departments(file_id,department_id)
            VALUES('$file_id','$dept')
            ");

        }

    }

}

header("Location: add_document.php?folder_id=".$folder_id);
exit;

?>