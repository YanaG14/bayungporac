<?php
require_once("../include/connection.php");
session_start();
date_default_timezone_set("Asia/Manila");
$time = date("M-d-Y h:i A", strtotime("+0 HOURS"));

// Record logout time
$email = $_SESSION['admin_user'];
mysqli_query($conn, "UPDATE history_log1 SET `logout_time` = '$time' WHERE `id` = '$email'");

// Clear session
$_SESSION = [];
session_unset();
session_destroy();

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
        showConfirmButton: false,
        timer: 800,
        timerProgressBar: false, 
        icon: 'success',
        title: 'Logged out successfully!'
    }).then(() => {
        window.location.href = '../Private_Dashboard/index.php';
    });
});
</script>
</head>
<body></body>
</html>