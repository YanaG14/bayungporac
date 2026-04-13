<?php
session_start();
include '../include/connection.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$letter_id = $_POST['file_id'] ?? '';
$file_name_only = $_POST['file_name_only'] ?? '';
$file_extension = $_POST['file_extension'] ?? '';
$status = $_POST['status'] ?? 'Open';
$departments = $_POST['departments'] ?? []; // ✅ ADD THIS

$sender = $_SESSION['admin_name'];

if ($letter_id) {

    $full_file_name = $file_name_only . $file_extension;

    // 1. Update letter info
    $stmt = $conn->prepare("
        UPDATE letters 
        SET file_name=?, status=?, sender=? 
        WHERE id=?
    ");

    $stmt->bind_param("sssi", $full_file_name, $status, $sender, $letter_id);
    $stmt->execute();

    // 2. DELETE old department links
    $del = $conn->prepare("DELETE FROM letter_departments WHERE letter_id=?");
    $del->bind_param("i", $letter_id);
    $del->execute();

    // 3. INSERT new department links
    if (!empty($departments)) {
        $ins = $conn->prepare("
            INSERT INTO letter_departments (letter_id, department_id) 
            VALUES (?, ?)
        ");

        foreach ($departments as $dept_id) {
            $ins->bind_param("ii", $letter_id, $dept_id);
            $ins->execute();
        }
    }

    header("Location: communication_letters.php");
    exit();

} else {
    die("Invalid letter ID.");
}
?>