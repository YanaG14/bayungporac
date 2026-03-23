
<?php

require_once("../include/connection.php");
// this is logout page when user click button logout in system page

session_start();
  date_default_timezone_set("asia/manila");
  $time = date("M-d-Y h:i A",strtotime("+0 HOURS"));

 $email = $_SESSION['email_address'];
  

mysqli_query($conn,"UPDATE history_log SET `logout_time` = '$time'  WHERE `id` = '$email'");

$_SESSION = NULL;
$_SESSION = [];
session_unset();
session_destroy();
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Logging out...</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        toast: true,
        position: 'top',
        icon: 'success',
        title: 'Logged out successfully!',
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true
    }).then(() => {
        // Redirect to user login page
        window.location.href = '../records-administrator/index.php';
    });
});
</script>
</head>
<body></body>
</html>
 