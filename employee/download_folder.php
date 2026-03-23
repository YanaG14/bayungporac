<?php
session_start();
require_once("../include/connection.php");

// ✅ 1. Check employee login
if (!isset($_SESSION['email_address']) || !isset($_SESSION['user_no'])) {
    header("Location: ../login.php");
    exit();
}

$user_department = $_SESSION['department_id'];

// ✅ 2. Validate folder_id
if (!isset($_GET['folder_id'])) {
    die("Folder ID missing");
}

$folder_id = intval($_GET['folder_id']);

// ✅ 3. Check if folder belongs to user's department
$checkFolder = $conn->prepare("
    SELECT f.folder_name 
    FROM folders f
    JOIN folder_departments fd ON f.folder_id = fd.folder_id
    WHERE f.folder_id = ? 
    AND fd.department_id = ?
    AND f.folder_status = 'Active'
");
$checkFolder->bind_param("ii", $folder_id, $user_department);
$checkFolder->execute();
$result = $checkFolder->get_result();

if ($result->num_rows === 0) {
    die("Unauthorized access: Folder not allowed for your department.");
}

$folderData = $result->fetch_assoc();
$folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $folderData['folder_name']);

// ✅ 4. Fetch ONLY files assigned to this department
$fileQuery = $conn->prepare("
    SELECT uf.file_path, uf.name
    FROM upload_files uf
    JOIN file_departments fd ON uf.id = fd.file_id
    WHERE uf.folder_id = ?
    AND fd.department_id = ?
    AND uf.status = 'Active'
");
$fileQuery->bind_param("ii", $folder_id, $user_department);
$fileQuery->execute();
$files = $fileQuery->get_result();

// ✅ 5. Create ZIP
$zip = new ZipArchive();
$zipFileName = $folderName . ".zip";
$zipPath = sys_get_temp_dir() . "/" . $zipFileName;

if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Cannot create ZIP file");
}

// ✅ 6. Add files to ZIP
while ($file = $files->fetch_assoc()) {
    $filePath = "../uploads/" . $file['file_path'];

    if (file_exists($filePath)) {
        $zip->addFile($filePath, $folderName . "/" . basename($file['name']));
    }
}

$zip->close();

// ✅ 7. Force download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="'.$zipFileName.'"');
header('Content-Length: ' . filesize($zipPath));

readfile($zipPath);
unlink($zipPath);
exit();
?>