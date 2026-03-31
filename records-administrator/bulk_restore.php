<?php
require_once("../include/connection.php");

if(isset($_POST['ids'])){
    foreach($_POST['ids'] as $id){
        $id = intval($id);
        mysqli_query($conn,"UPDATE upload_files SET status='Active' WHERE id='$id'");
    }
    echo "success";
}
?>