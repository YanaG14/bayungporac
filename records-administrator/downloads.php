<?php
require_once("include/connection.php"); // adjust path if needed

if(isset($_GET['file_id'])){
    $id = intval($_GET['file_id']);

    $query = mysqli_query($conn,"SELECT * FROM letters WHERE id='$id'");
    $file = mysqli_fetch_assoc($query);

    if(!$file){
        die("File not found in database.");
    }

    $filepath = "letter_files/" . $file['file_path']; // correct relative path

    if(file_exists($filepath)){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file['file_name']).'"');
        header('Content-Length: ' . filesize($filepath));
        header('Pragma: public');
        header('Cache-Control: must-revalidate');
        header('Expires: 0');

        readfile($filepath);

        // update download count if you have a column for it
        if(isset($file['download'])){
            $newCount = $file['download'] + 1;
            mysqli_query($conn,"UPDATE letters SET download='$newCount' WHERE id='$id'");
        }

        exit;
    } else {
        die("File not found on server: " . $filepath);
    }
} else {
    die("No file specified.");
}
?>