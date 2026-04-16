<?php
require_once("../include/connection.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id = intval($_POST['letter_id']);
    $source = $_POST['source'];
    $status = $_POST['status'];
    $file_type = $_POST['file_type'];

    $stmt = $conn->prepare("
        UPDATE letters 
        SET source=?, status=?, file_type=?
        WHERE id=?
    ");

    $stmt->bind_param("sssi", $source, $status, $file_type, $id);
    $stmt->execute();

    header("Location: view_letter.php?id=".$id);
}
?>