<?php
require_once("../include/connection.php");
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
if(isset($_POST['reguser'])){ 

    $user_name = mysqli_real_escape_string($conn,$_POST['name']);
    $department_id = mysqli_real_escape_string($conn,$_POST['department_id']);
    $email_address = mysqli_real_escape_string($conn,$_POST['email_address']);
    $user_password_raw = $_POST['user_password'];
    $user_status = mysqli_real_escape_string($conn,$_POST['user_status']);
    
 $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/';
    if (!preg_match($pattern, $user_password_raw)) {
        echo '<script>alert("Password must be at least 8 characters, include uppercase, lowercase, number, and a symbol."); window.history.back();</script>'; 
        exit();
    }
    // Password hashing
    $user_password = password_hash($user_password_raw, PASSWORD_DEFAULT, ['cost'=>12]);

    // Check email exists
    $q_checkadmin = $conn->query("SELECT * FROM login_user WHERE email_address = '$email_address'");
    if($q_checkadmin->num_rows > 0){
        echo '<script>alert("Email Address already taken"); window.location="view_user.php";</script>';
        exit();
    }

    // Insert user FIRST
    $conn->query("INSERT INTO login_user 
    (name,email_address,user_password,user_status,department_id,otp_verified) 
    VALUES 
    ('$user_name','$email_address','$user_password','$user_status','$department_id',0)")
    or die(mysqli_error($conn));

    // Generate OTP
    $otp = rand(100000, 999999);

    // Store OTP
    $conn->query("UPDATE login_user SET otp_code='$otp', otp_verified=0 WHERE email_address='$email_address'")
    or die(mysqli_error($conn));

    // Send OTP via Gmail
    $mail = new PHPMailer(true);

    try { 
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'yanayanaya14@gmail.com';
        $mail->Password   = 'abtt ostl nvlh ehss'; // App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('yanayanaya14@gmail.com', 'Bayung Porac Archive');
        $mail->addAddress($email_address, $user_name);

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Account - OTP Code';
        $mail->Body    = "
            <p>Hi <strong>$user_name</strong>,</p>
            <p>Your OTP code is: <strong>$otp</strong></p>
            <p>Please use this to verify your account.</p>
        ";

        $mail->send();

       echo '<script>
    alert("OTP sent to your email!");
    window.location="view_user.php?otp_email='.$email_address.'";
</script>';

    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>