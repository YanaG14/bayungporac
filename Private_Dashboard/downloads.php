<?php

require_once("../include/connection.php");

if(isset($_GET['file_id'])){

    $id = intval($_GET['file_id']);

    $query = mysqli_query($conn,"SELECT * FROM upload_files WHERE id='$id'");
    $file = mysqli_fetch_assoc($query);

    if(!$file){
        die("File not found.");
    }

    $filepath = "../uploads/".$file['file_path'];

    if(file_exists($filepath)){

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file['name']).'"');
        header('Content-Length: ' . filesize($filepath));
        header('Pragma: public');
        header('Cache-Control: must-revalidate');
        header('Expires: 0');

        readfile($filepath);

        // update download count
        $newCount = $file['download'] + 1;
        mysqli_query($conn,"UPDATE upload_files SET download='$newCount' WHERE id='$id'");

        exit;

    }else{
        echo "File not found on server.";
    }

}
?>