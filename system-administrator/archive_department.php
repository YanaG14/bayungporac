<?php
session_start();
require_once("../include/connection.php");

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.php');
    exit();
}

if(isset($_GET['id'])){

    $id = intval($_GET['id']);

    // Archive the department
    mysqli_query($conn,"UPDATE departments 
                        SET department_status='Archived' 
                        WHERE department_id='$id'");

    // Set a session toast for success
    $_SESSION['archived'] = "Department archived successfully!";
}

// Redirect back to department management page
header("Location: department_management.php");
exit();
?>