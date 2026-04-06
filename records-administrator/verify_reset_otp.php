<?php
session_start();
require_once("../include/connection.php");
header('Content-Type: application/json');

if(isset($_POST['otp'], $_POST['email'])){
    $otp_reset = mysqli_real_escape_string($conn, $_POST['otp']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $user_type = $_SESSION['otp_type'] ?? 'admin';

    if($user_type === 'admin'){
        $q = mysqli_query($conn, "SELECT * FROM admin_login WHERE admin_user='$email' AND otp_reset='$otp_reset'");
    } else {
        $q = mysqli_query($conn, "SELECT * FROM login_user WHERE email_address='$email' AND otp_reset='$otp_reset'");
    }

    if(mysqli_num_rows($q) > 0){
        echo json_encode(['status'=>'success','message'=>'OTP verified!']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Invalid OTP.']);
    }

} else {
    echo json_encode(['status'=>'error','message'=>'OTP or email missing.']);
}