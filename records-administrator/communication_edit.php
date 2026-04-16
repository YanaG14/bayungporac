<?php
session_start();
include '../include/connection.php';

$letter_id = $_POST['file_id'] ?? '';
$subject = $_POST['subject'] ?? '';
$reference_no = $_POST['reference_no'] ?? '';

if ($letter_id) {

    $stmt = $conn->prepare("
        UPDATE letters 
        SET subject=?, reference_no=? 
        WHERE id=?
    ");

    $stmt->bind_param("ssi", $subject, $reference_no, $letter_id);
    $stmt->execute();

    $_SESSION['success'] = "Letter updated successfully";
    header("Location: communication_letters.php");
    exit();

} else {
    die("Invalid letter ID.");
}
?>