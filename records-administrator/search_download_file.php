<?php
include("../include/connection.php");

if(isset($_GET['id'])){
    $id = intval($_GET['id']);

    $query = mysqli_query($conn, "
        SELECT name, file_path 
        FROM upload_files 
        WHERE id = $id
    ");

    if(mysqli_num_rows($query) > 0){
        $file = mysqli_fetch_assoc($query);

        $filepath = "../uploads/" . $file['file_path'];

        if(file_exists($filepath)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file['name']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "Invalid file.";
    }
}
?>