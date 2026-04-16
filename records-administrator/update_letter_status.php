<?php
require_once("../include/connection.php");

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if ($id && $status) {

    $stmt = $conn->prepare("
        UPDATE letters 
        SET `status` = ? 
        WHERE id = ?
    ");

    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    echo "success";
} else {
    echo "missing data";
}
?>