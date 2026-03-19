<?php
session_start();
require_once("../include/connection.php");

if (!isset($_SESSION['admin_user'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['folder_id'])) {
    die("Folder ID missing");
}

$folder_id = intval($_GET['folder_id']);

// Get folder name
$folderQuery = mysqli_query($conn, "SELECT folder_name FROM folders WHERE folder_id='$folder_id'");
$folderData = mysqli_fetch_assoc($folderQuery);

if (!$folderData) {
    die("Folder not found");
}

$folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $folderData['folder_name']);

// Fetch files in folder
$fileQuery = mysqli_query($conn, "
    SELECT file_path, name 
    FROM upload_files 
    WHERE folder_id='$folder_id' AND status='Active'
");

// Create ZIP
$zip = new ZipArchive();
$zipFileName = $folderName . ".zip";
$zipPath = sys_get_temp_dir() . "/" . $zipFileName;

if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Cannot create ZIP file");
}

// Add files to ZIP
while ($file = mysqli_fetch_assoc($fileQuery)) {
    $filePath = "../uploads/" . $file['file_path'];

    if (file_exists($filePath)) {
        // Keep original filename inside ZIP
        $zip->addFile($filePath, $folderName . "/" . basename($file['name']));
    }
}

$zip->close();

// Force download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="'.$zipFileName.'"');
header('Content-Length: ' . filesize($zipPath));
readfile($zipPath);

// Delete temp file
unlink($zipPath);
exit();
?>