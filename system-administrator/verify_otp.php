<?php
require_once("../include/connection.php");

if(isset($_POST['email']) && isset($_POST['otp'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $otp_input = trim($_POST['otp']);

    // Check admin
    $check = $conn->query("SELECT otp_verified, otp_code FROM admin_login WHERE admin_user='$email' LIMIT 1");

    if($check->num_rows > 0){
        $row = $check->fetch_assoc();

        if($row['otp_verified'] == 1){
            echo "error: Account already verified";
            exit();
        }

        if($row['otp_code'] == $otp_input){

            $conn->query("UPDATE admin_login 
                          SET otp_verified=1, otp_code=NULL 
                          WHERE admin_user='$email'");

            echo "success: Admin verified successfully!";
        } else {
            echo "error: Invalid OTP";
        }

    } else {
        echo "error: Email not found";
    }
}
?>