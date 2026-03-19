<?php
require_once("../include/connection.php");

if(isset($_POST['verify'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $otp_input = mysqli_real_escape_string($conn, $_POST['otp']);

    // Validate OTP format
    if(!preg_match('/^\d{6}$/', $otp_input)){
        echo '<script>alert("OTP must be 6 digits."); window.history.back();</script>';
        exit();
    }

    // Check OTP and whether already verified
    $res = $conn->query("SELECT * FROM admin_login WHERE admin_user='$email' AND otp_code='$otp_input' AND otp_verified=0") or die(mysqli_error($conn));

    if($res->num_rows > 0){
        // Mark as verified
        $conn->query("UPDATE admin_login SET otp_verified=1, otp_code=NULL WHERE admin_user='$email'") or die(mysqli_error($conn));
        echo '<script>alert("Account verified successfully!"); window.location="view_admin.php";</script>';
    } else {
        echo '<script>alert("Invalid OTP or already verified. Try again."); window.history.back();</script>';
    }
}
?>

<form method="POST">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
    <label>Enter OTP sent to your email:</label>
    <input type="text" name="otp" required maxlength="6" pattern="\d{6}" title="Enter 6-digit OTP">
    <button type="submit" name="verify">Verify</button>
</form>