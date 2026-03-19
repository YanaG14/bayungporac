<?php
require_once("../include/connection.php");

if(isset($_POST['verify'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $otp_input = mysqli_real_escape_string($conn, $_POST['otp']);

    if(!preg_match('/^\d{6}$/', $otp_input)){
        echo '<script>alert("OTP must be 6 digits."); window.history.back();</script>';
        exit();
    }

    // =========================
    // 🔍 CHECK ADMIN TABLE
    // =========================
    $admin = $conn->query("SELECT * FROM admin_login 
                           WHERE admin_user='$email' 
                           AND otp_code='$otp_input' 
                           AND otp_verified=0");

    if($admin->num_rows > 0){

        $conn->query("UPDATE admin_login 
                      SET otp_verified=1, otp_code=NULL 
                      WHERE admin_user='$email'");

        echo '<script>
            alert("Admin account verified successfully!");
            window.location="index.php";
        </script>';
        exit();
    }

    // =========================
    // 🔍 CHECK USER TABLE
    // =========================
    $user = $conn->query("SELECT * FROM login_user 
                          WHERE email_address='$email' 
                          AND otp_code='$otp_input' 
                          AND otp_verified=0");

    if($user->num_rows > 0){

        $conn->query("UPDATE login_user 
                      SET otp_verified=1, otp_code=NULL 
                      WHERE email_address='$email'");

        echo '<script>
            alert("User account verified successfully!");
            window.location="index.php";
        </script>';
        exit();
    }

    // =========================
    // ❌ INVALID
    // =========================
    echo '<script>
        alert("Invalid OTP or already verified.");
        window.history.back();
    </script>';
}
?>

<form method="POST">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">

    <label>Enter OTP sent to your email:</label>

    <input type="text" name="otp" required maxlength="6"
        pattern="\d{6}" title="Enter 6-digit OTP">

    <button type="submit" name="verify">Verify</button>
</form>