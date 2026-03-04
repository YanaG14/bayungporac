<?php
session_start();
require_once("../include/connection.php");

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.html');
    exit();
}

if(isset($_GET['id'])){

    $id = intval($_GET['id']);

    mysqli_query($conn,"UPDATE departments 
                        SET department_status='Archived' 
                        WHERE department_id='$id'");

}

header("Location: department_management.php");
exit();
?>