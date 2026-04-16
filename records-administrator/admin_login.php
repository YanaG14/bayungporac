<?php
require_once("../include/connection.php");
session_start();

header('Content-Type: application/json');

date_default_timezone_set("Asia/Manila");
$date = date("M-d-Y h:i A");

$username = $_POST["admin_user"] ?? '';
$password = $_POST["admin_password"] ?? '';

$error_msg = "Invalid Email Address or Password";

// =========================
// 🔴 CHECK ADMIN FIRST
// =========================
$query = mysqli_query($conn, "SELECT * FROM admin_login WHERE admin_user = '$username'");

if(mysqli_num_rows($query) > 0){

    $row = mysqli_fetch_assoc($query);

    // ❌ WRONG PASSWORD
    if (!password_verify($password, $row["admin_password"])) {
        echo json_encode(["status" => "error"]);
        exit();
    }

    // ❌ ARCHIVED
    if (strtolower($row['admin_status']) === 'archived') {
        echo json_encode([
            "status" => "error",
            "message" => "Account archived"
        ]);
        exit();
    }

    // 🔐 OTP NOT VERIFIED
    if ($row['otp_verified'] == 0) {
        echo json_encode([
            "status" => "otp",
            "email" => $row['admin_user']
        ]);
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
        VALUES('$row[id]', '$username', '$remarks', '$ip', '$host', '$date')");

    // 🎯 ROLE RESPONSE
    echo json_encode([
        "status" => "success",
        "role" => $row['role']
    ]);
    exit();
}


// =========================
// 🔵 IF NOT ADMIN → CHECK USER
// =========================
$stmt = $conn->prepare("SELECT id, name, email_address, user_password, user_status, department_id, otp_verified FROM login_user WHERE email_address = ? LIMIT 1");

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1){

    $user = $result->fetch_assoc();

    // ❌ ARCHIVED USER
    if (strtolower($user['user_status']) === 'archived') {
        echo json_encode([
            "status" => "error",
            "message" => "Account archived"
        ]);
        exit();
    }

    // ✅ PASSWORD CHECK
    if (password_verify($password, $user["user_password"])) {

        // 🔐 OTP NOT VERIFIED
        if ((int)$user['otp_verified'] === 0) {
            echo json_encode([
                "status" => "otp",
                "email" => $user['email_address']
            ]);
            exit();
        }

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

        // ✅ SUCCESS (USER)
        echo json_encode([
            "status" => "user_success"
        ]);
        exit();

    } else {
        echo json_encode(["status" => "error"]);
        exit();
    }

} else {
    echo json_encode(["status" => "error"]);
    exit();
}
?>