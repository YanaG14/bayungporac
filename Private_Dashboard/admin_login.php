<?php
require_once("../include/connection.php");
session_start();

if(isset($_POST["adminlog"])){

    date_default_timezone_set("Asia/Manila");
    $date = date("M-d-Y h:i A", strtotime("+0 HOURS"));

    $username = mysqli_real_escape_string($conn, $_POST["admin_user"]);  
    $password = mysqli_real_escape_string($conn, $_POST["admin_password"]);

    // Fetch admin by email
    $query = mysqli_query($conn, "SELECT * FROM admin_login WHERE admin_user = '$username'") 
        or die(mysqli_error($conn));
    $row = mysqli_fetch_array($query);
    $counter = mysqli_num_rows($query);

    if ($counter == 0) {
        echo "<script>alert('Invalid Email Address or Password, Please try again!');
              document.location='index.html';</script>";
        exit();
    }

    // Check password
    if (!password_verify($password, $row["admin_password"])) {
        echo "<script>alert('Invalid Email Address or Password, Please try again!');
              document.location='index.html';</script>";
        exit();
    }

    // Check admin_status
    if (strtolower($row['admin_status']) === 'archived') {
        echo "<script>alert('Your account has been archived. You cannot login.');
              document.location='index.html';</script>";
        exit();
    }

    // All good, login admin
    $_SESSION['admin_user'] = $row['id'];
    $_SESSION['admin_name'] = $row['name'];  

    // Get IP and host
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }

    $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $remarks = "Has LoggedIn the system at";

    mysqli_query($conn, "INSERT INTO history_log1(id, admin_user, action, ip, host, login_time) 
                         VALUES('$row[id]', '$username', '$remarks', '$ip', '$host', '$date')")
        or die(mysqli_error($conn));

    // Redirect to folder management
    echo "<script>document.location='folder_management.php';</script>";
    exit();
}
?>