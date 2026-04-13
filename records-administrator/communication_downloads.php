<?php
require_once("../include/connection.php");

if(isset($_GET['file_id'])){

    $id = intval($_GET['file_id']);

    // Fetch the letter file
    $query = mysqli_query($conn,"SELECT * FROM letters WHERE id='$id'");
    $file = mysqli_fetch_assoc($query);

    if(!$file){
        die("File not found.");
    }

    // Full path to the file (adjust relative path if needed)
    $filepath = "letter_files/" . $file['file_path'];

    if(file_exists($filepath)){

        // Force download headers
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file['file_name']).'"');
        header('Content-Length: ' . filesize($filepath));
        header('Pragma: public');
        header('Cache-Control: must-revalidate');
        header('Expires: 0');

        readfile($filepath);
        exit;

    } else {
        die("File not found on server: " . htmlspecialchars($filepath));
    }

} else {
    die("No file specified.");
}
?>