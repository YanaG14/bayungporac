<?php
include("../include/connection.php");

if(isset($_POST['id'])){
    $id = intval($_POST['id']);

    $update = mysqli_query($conn, "
        UPDATE upload_files 
        SET status = 'Archived' 
        WHERE id = $id
    ");

    if($update){
        echo "success";
    } else {
        echo "error";
    }
}
?>