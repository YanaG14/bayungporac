<?php
session_start();
require_once("../include/connection.php");

$user_id = $_SESSION['user_no'];
$letter_id = $_POST['letter_id'];
$comment = $_POST['comment'];

$stmt = $conn->prepare("INSERT INTO letter_comments (letter_id, user_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $letter_id, $user_id, $comment);
$stmt->execute();

echo "success";
?>