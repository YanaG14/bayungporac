<?php
require_once("../include/connection.php");

if(isset($_POST['email']) && isset($_POST['otp'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $otp_input = trim($_POST['otp']); // ✅ important

    // Check if already verified
    $check = $conn->query("SELECT otp_verified, otp_code FROM login_user WHERE email_address='$email' LIMIT 1");

    if($check->num_rows > 0){
        $row = $check->fetch_assoc();

        if($row['otp_verified'] == 1){
            echo "error: Account already verified";
            exit();
        }

        // Compare OTP in PHP (more reliable)
        if($row['otp_code'] == $otp_input){

            $conn->query("UPDATE login_user 
                          SET otp_verified=1, otp_code=NULL 
                          WHERE email_address='$email'");

            echo "success: Account verified successfully!";
        } else {
            echo "error: Invalid OTP";
        }

    } else {
        echo "error: Email not found";
    }
}
?>