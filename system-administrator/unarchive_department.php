<?php
require_once("../include/connection.php");

$id = $_GET['id'];

mysqli_query($conn,"UPDATE departments 
                    SET department_status='Active' 
                    WHERE department_id='$id'");

header("Location: department_management.php");
?>