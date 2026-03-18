<?php
session_start();
if (!isset($_SESSION['admin_user'])) {
    header('Location: index.php');
    exit();
}

require_once("../include/connection.php");

if(!isset($_GET['folder_id'])) {
    die("Folder ID missing.");
}

$folder_id = intval($_GET['folder_id']);

// Fetch all active files in the folder
$query = mysqli_query($conn, "SELECT name, file_path FROM upload_files WHERE folder_id='$folder_id' AND status='Active'");

if(mysqli_num_rows($query) == 0) {
    die("No files in this folder.");
}

// Create a temporary ZIP
$zipName = tempnam(sys_get_temp_dir(), 'zip');
$zip = new ZipArchive();

if ($zip->open($zipName, ZipArchive::OVERWRITE) !== TRUE) {
    die("Cannot create ZIP file.");
}

while($file = mysqli_fetch_assoc($query)) {
    $filePath = "../uploads/" . $file['file_path'];
    if(file_exists($filePath)) {
        $zip->addFile($filePath, basename($filePath));
    }
}

$zip->close();

// Download the ZIP
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="folder_'.$folder_id.'.zip"');
header('Content-Length: ' . filesize($zipName));

readfile($zipName);

// Delete temporary file
unlink($zipName);
exit();