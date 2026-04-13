<?php
require_once("../include/connection.php");

if(isset($_GET['letter_id'])){
    $id = intval($_GET['letter_id']);
    $update = mysqli_query($conn, "UPDATE letters SET letter_status='Active' WHERE id='$id'");
    
    if($update){
        session_start();
        $_SESSION['success'] = "Letter restored successfully!";
    } else {
        $_SESSION['error'] = "Failed to restore letter.";
    }
}
header("Location: communication_letters.php"); // back to main letters page
exit();
?>