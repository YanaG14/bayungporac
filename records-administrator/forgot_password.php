<?php
session_start();
require_once("../include/connection.php");
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if(isset($_POST['email'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $user_query = mysqli_query($conn, "SELECT * FROM login_user WHERE email_address='$email'");
    $admin_query = mysqli_query($conn, "SELECT * FROM admin_login WHERE admin_user='$email'");

    if(mysqli_num_rows($user_query) > 0 || mysqli_num_rows($admin_query) > 0){
        $row = mysqli_num_rows($user_query) > 0 ? mysqli_fetch_assoc($user_query) : mysqli_fetch_assoc($admin_query);
        $name = $row['name'] ?? $row['admin_name'] ?? $email;

        $table = mysqli_num_rows($user_query) > 0 ? "login_user" : "admin_login";
        $column = mysqli_num_rows($user_query) > 0 ? "email_address" : "admin_user";

        // Check if OTP already exists and is not expired
        $otp_created = $row['otp_reset_created'] ?? null;
        $otp_valid = false;

        if($otp_created){
            $otp_time = strtotime($otp_created);
            $now_time = time();
            // 5 minutes = 300 seconds
            if(($now_time - $otp_time) < 300){
                $otp = $row['otp_reset']; // keep existing OTP
                $otp_valid = true;
            }
        }

        // If no valid OTP, generate a new one
        if(!$otp_valid){
            $otp = rand(100000, 999999);
            $now = date('Y-m-d H:i:s');
            mysqli_query($conn, "UPDATE $table SET otp_reset='$otp', otp_reset_created='$now' WHERE $column='$email'");
        }

        $_SESSION['otp_email'] = $email;
        $_SESSION['otp_type'] = mysqli_num_rows($user_query) > 0 ? 'user' : 'admin';

        // Send Email
        $mail = new PHPMailer(true);
        try{
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yanayanaya14@gmail.com';
            $mail->Password = 'abtt ostl nvlh ehss'; // Google App password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yanayanaya14@gmail.com', 'Bayung Porac Archive');
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body = "<p>Hello <strong>$name</strong>,</p>
                           <p>Your OTP code for password reset is:</p>
                           <h2>$otp</h2>
                           <p>This OTP is valid for 5 minutes.</p>";

            $mail->send();

            echo json_encode(['status'=>'success','email'=>$email,'message'=>'OTP sent successfully!']);
            exit;

        } catch (Exception $e){
          echo json_encode(['status'=>'error','message'=>"Email not found!"]);
            exit;
        }

    } else {
        echo json_encode(['status'=>'error','message'=>"Email not found!"]);
        
    }
}exit;
?>