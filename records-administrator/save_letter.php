<?php
session_start();
include '../include/connection.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$ref = $_POST['reference_no'] ?? '';
$date = $_POST['date_received'] ?? '';
$subject = $_POST['subject'] ?? '';
$source = $_POST['source'] ?? '';
$file_type = $_POST['file_type'] ?? '';

// AUTO sender
$sender = $_SESSION['admin_name'];

if(isset($_FILES['files'])) {

    $uploadDir = "letter_files/";

    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    // ✅ INSERT LETTER FIRST (ONLY ONCE)
    $stmt = $conn->prepare("
        INSERT INTO letters 
        (reference_no, date_received, subject, sender, source,file_type) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("ssssss", $ref, $date, $subject, $sender, $source, $file_type);
    $stmt->execute();

    $letter_id = $stmt->insert_id;

    // ✅ LOOP ALL FILES
    foreach ($_FILES['files']['name'] as $key => $fileName) {

        if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {

            $tmp = $_FILES['files']['tmp_name'][$key];

            $newFileName = time() . "_" . $fileName;
            $destPath = $uploadDir . $newFileName;

            if(move_uploaded_file($tmp, $destPath)) {

                // ✅ SAVE EACH FILE IN upload_files TABLE
                $stmt2 = $conn->prepare("
    INSERT INTO letter_files (letter_id, file_name, file_path)
    VALUES (?, ?, ?)
");

$stmt2->bind_param("iss", $letter_id, $fileName, $newFileName);
$stmt2->execute();
            }
        }
    }

    // ✅ SAVE DEPARTMENTS
    if (!empty($_POST['departments'])) {
        foreach ($_POST['departments'] as $dept_id) {
            $stmt3 = $conn->prepare("
                INSERT INTO letter_departments (letter_id, department_id)
                VALUES (?, ?)
            ");
            $stmt3->bind_param("ii", $letter_id, $dept_id);
            $stmt3->execute();
        }
    }

    header("Location: communication_letters.php");
    exit();
} else {
        die("Error moving uploaded file.");
    }

?>