<?php
session_start();
require_once("../include/connection.php");
header('Content-Type: application/json');

if(isset($_POST['email'], $_POST['password'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // raw password
    $user_type = $_SESSION['otp_type'] ?? 'admin';

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if($user_type === 'admin'){
        $table = "admin_login";
        $column = "admin_user";
        $password_column = "admin_password"; // admin table column
    } else {
        $table = "login_user";
        $column = "email_address";
        $password_column = "user_password"; // user table column
    }

    // Update password in database
    $update = mysqli_query($conn, "UPDATE $table SET $password_column='$hashed_password', otp_reset=NULL, otp_reset_created=NULL WHERE $column='$email'");

    if($update){
        echo json_encode(['status'=>'success','message'=>'Password updated successfully.']);
    } else {
        echo json_encode(['status'=>'error','message'=>'DB error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status'=>'error','message'=>'Email or password missing.']);
}