<?php
require_once("../include/connection.php");
session_start();

if(isset($_POST["adminlog"])){

    date_default_timezone_set("Asia/Manila");
    $date = date("M-d-Y h:i A");

    $username = mysqli_real_escape_string($conn, $_POST["admin_user"]);  
    $password = mysqli_real_escape_string($conn, $_POST["admin_password"]);

    $error_msg = "Invalid Email Address or Password, Please try again!";

    // =========================
    // 🔴 CHECK ADMIN FIRST
    // =========================
    $query = mysqli_query($conn, "SELECT * FROM admin_login WHERE admin_user = '$username'") 
        or die(mysqli_error($conn));

    if(mysqli_num_rows($query) > 0){

        $row = mysqli_fetch_array($query);

        if (!password_verify($password, $row["admin_password"])) {
            $_SESSION['error_msg'] = $error_msg;
            header("Location: index.php");
            exit();
        }

        if (strtolower($row['admin_status']) === 'archived') {
            $_SESSION['error_msg'] = "Your account has been archived. Please contact your system administrator.";
            header("Location: index.php");
            exit();
        }
   //  OTP not verified
   // 🔐 OTP CHECK (ADD THIS)
if ($row['otp_verified'] == 0) {

    $_SESSION['otp_email'] = $row['admin_user'];
    $_SESSION['show_otp_modal'] = true;

    header("Location: index.php");
    exit();
}
        // ✅ ADMIN LOGIN SUCCESS
        $_SESSION['admin_user'] = $row['id'];
        $_SESSION['admin_name'] = $row['name'];  
        $_SESSION['admin_role'] = $row['role'];

        // Log
        $ip = $_SERVER["REMOTE_ADDR"];
        $host = gethostbyaddr($ip);
        $remarks = "Has LoggedIn the system at";

        mysqli_query($conn, "INSERT INTO history_log1(id, admin_user, action, ip, host, login_time) 
            VALUES('$row[id]', '$username', '$remarks', '$ip', '$host', '$date')")
            or die(mysqli_error($conn));

        // Role Redirect
        if ($row['role'] === "Records Administrator") {
            header("Location: folder_management.php");

        } elseif ($row['role'] === "System Administrator") {
            header("Location: ../system-administrator/homepage_management.php");
        

        } else {
            $_SESSION['error_msg'] = "Unauthorized role access.";
            header("Location: index.php");
        }

        exit();
    }

    // =========================
    // 🔵 IF NOT ADMIN → CHECK USER
    // =========================
    $stmt = $conn->prepare("SELECT id, name, email_address, user_password, user_status, department_id FROM login_user WHERE email_address = ? LIMIT 1");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1){

        $user = $result->fetch_assoc();

        if (strtolower($user['user_status']) === 'archived') {
            $_SESSION['error_msg'] = "User account is archived.";
            header("Location: index.php");
            exit();
        }

        if (password_verify($password, $user["user_password"])) {

            session_regenerate_id(true);

            $_SESSION["user_no"] = $user["id"];
            $_SESSION["email_address"] = $user["email_address"];
            $_SESSION["department_id"] = $user["department_id"];

            // Log user login
            $ip = $_SERVER["REMOTE_ADDR"];
            $host = gethostbyaddr($ip);
            $remarks = "Has LoggedIn the system at";

            $logStmt = $conn->prepare("INSERT INTO history_log (id, email_address, action, ip, host, login_time) VALUES (?, ?, ?, ?, ?, ?)");

            if ($logStmt) {
                $logStmt->bind_param("isssss", $user["id"], $user["email_address"], $remarks, $ip, $host, $date);
                $logStmt->execute();
                $logStmt->close();
            }

            // ✅ REDIRECT TO USER SYSTEM
            header("Location: ../employee/home.php");
            exit();

        } else {
            $_SESSION['error_msg'] = $error_msg;
            header("Location: index.php");
            exit();
        }

    } else {
        $_SESSION['error_msg'] = $error_msg;
        header("Location: index.php");
        exit();
    }
}
?>