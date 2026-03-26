<?php
require_once("../include/connection.php");

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['reg'])){

    $user_name = mysqli_real_escape_string($conn,$_POST['name']);
    $user_email = mysqli_real_escape_string($conn,$_POST['admin_user']);
    $user_password_raw = $_POST['admin_password'];  // raw password
    $user_status = mysqli_real_escape_string($conn,$_POST['status']); // changed to match your form
    // Insert into database with fixed role
$fixed_role = "System Administrator";

    // Password validation regex
    $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/';

    // Check password
    if (!preg_match($pattern, $user_password_raw)) {
        echo '
        <script type="text/javascript">
            alert("Password must be at least 8 characters, include uppercase, lowercase, number, and a symbol.");
            window.history.back();
        </script>
        ';
        exit();
    } 

    // Hash the password after validation
    $user_password_hashed = password_hash($user_password_raw, PASSWORD_DEFAULT, array('cost' => 12));

    // Check if email already exists
    $q_checkadmin = $conn->query("SELECT * FROM `admin_login` WHERE `admin_user` = '$user_email'") or die(mysqli_error($conn));
    if($q_checkadmin->num_rows > 0){
        echo '
        <script type="text/javascript">
            alert("Email Address already taken");
            window.location = "system-administrator.php";
        </script>
        ';
        exit();
    }

    // Insert into database
    $conn->query("INSERT INTO `admin_login` (name, admin_user, admin_password, admin_status, role) 
              VALUES('$user_name', '$user_email', '$user_password_hashed', '$user_status', '$fixed_role')") 
              or die(mysqli_error($conn));

    // 2️⃣ Generate OTP 
    $otp = rand(100000, 999999);

    // 3️⃣ Store OTP in DB
    $conn->query("UPDATE `admin_login` SET otp_code='$otp', otp_verified=0 WHERE admin_user='$user_email'") or die(mysqli_error($conn));

    // 4️⃣ Send OTP via PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'yanayanaya14@gmail.com';
        $mail->Password   = 'abtt ostl nvlh ehss';     // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('yanayanaya14@gmail.com', 'Bayung Porac Archive');
        $mail->addAddress($user_email, $user_name);
 
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Admin Account - OTP Code';
        $mail->Body    = "<p>Hi <strong>$user_name</strong>,</p>
                          <p>Your OTP code for account verification is: <strong>$otp</strong></p>
                          <p>Please enter this code to verify your account.</p>";

        $mail->send();

         echo '<script>
    alert("OTP sent to your email!");
    window.location="view_system-administrator.php?otp_email='.$user_email.'";
</script>';

    } catch (Exception $e) {
        echo "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>