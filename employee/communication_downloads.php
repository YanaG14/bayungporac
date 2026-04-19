<?php
require_once("../include/connection.php");

if(isset($_GET['file_id'])){

    $id = intval($_GET['file_id']);

    // ✅ FIXED: correct table
    $query = mysqli_query($conn,"SELECT * FROM letter_files WHERE file_id='$id'");
    $file = mysqli_fetch_assoc($query);

    if(!$file){
        die("File not found.");
    }

    $filepath = "../records-administrator/letter_files/" . $file['file_path'];

    if(file_exists($filepath)){

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