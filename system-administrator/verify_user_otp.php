<?php
require_once("../include/connection.php");

if(isset($_POST['verify'])) {

    $email = $_POST['email'];
    $otp_input = $_POST['otp'];

    $res = $conn->query("SELECT * FROM login_user WHERE email_address='$email' AND otp_code='$otp_input'");

    if($res->num_rows > 0){

        $conn->query("UPDATE login_user 
                      SET otp_verified=1, otp_code=NULL 
                      WHERE email_address='$email'");

        echo '<script>alert("Account verified successfully!"); window.location="index.php";</script>';

    } else {
        echo '<script>alert("Invalid OTP"); window.history.back();</script>';
    }
}
?>

<form method="POST">
    <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">

    <input type="text" name="otp" placeholder="Enter OTP" required>

    <button type="submit" name="verify">Verify</button>
</form>