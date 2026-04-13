<?php
session_start();
include '../include/connection.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$ref = $_POST['reference_no'] ?? '';
$date = $_POST['date_received'] ?? '';
$subject = $_POST['subject'] ?? '';
$source = $_POST['source'] ?? '';

// AUTO sender
$sender = $_SESSION['admin_name'];

if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    $originalFileName = $file;
    $newFileName = time() . "_" . $file;

    $uploadDir = "letter_files/";

    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    $destPath = $uploadDir . $newFileName;

    if(move_uploaded_file($tmp, $destPath)){

        // ✅ 1. INSERT LETTER FIRST
        $stmt = $conn->prepare("
            INSERT INTO letters 
            (reference_no, date_received, subject, sender, source, file_name, file_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssss",
            $ref,
            $date,
            $subject,
            $sender,
            $source,
            $originalFileName,
            $newFileName
        );

        $stmt->execute();

        // ✅ 2. GET INSERTED LETTER ID
        $letter_id = $stmt->insert_id;

        // ✅ 3. SAVE DEPARTMENTS (THIS WAS MISSING)
        if (!empty($_POST['departments'])) {

            foreach ($_POST['departments'] as $dept_id) {

                $stmt2 = $conn->prepare("
                    INSERT INTO letter_departments (letter_id, department_id)
                    VALUES (?, ?)
                ");

                $stmt2->bind_param("ii", $letter_id, $dept_id);
                $stmt2->execute();
            }
        }

        header("Location: communication_letters.php");
        exit();

    } else {
        die("Error moving uploaded file.");
    }

} else {
    die("No file uploaded or upload error.");
}
?>