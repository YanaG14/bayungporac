<?php
// Show errors during development (REMOVE in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("../include/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {

    // Validate input
    if (empty($_POST["email_address"]) || empty($_POST["user_password"])) {
        header("Location: ../login.php?error=empty");
        exit();
    }

    $email = trim($_POST["email_address"]);
    $password = $_POST["user_password"];

    // Prepare statement (PREVENTS SQL INJECTION)
    $stmt = $conn->prepare("SELECT id, name, email_address, user_password, user_status, department_id FROM login_user WHERE email_address = ? LIMIT 1");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Check if user is archived
       if (strtolower($user['user_status']) === 'archived') {
    header("Location: ../login.php?error=archived");
    exit();
}

        // Verify hashed password
        if (password_verify($password, $user["user_password"])) {

            // Regenerate session ID (PREVENT SESSION FIXATION)
            session_regenerate_id(true);

            // Store session variables including department_id
            $_SESSION["user_no"] = $user["id"];
            $_SESSION["email_address"] = $user["email_address"];
            $_SESSION["department_id"] = $user["department_id"]; // <-- Added

            // Optional: Log login history
            date_default_timezone_set("Asia/Manila");
            $date = date("M-d-Y h:i A");

            $ip = $_SERVER["REMOTE_ADDR"];
            $host = gethostbyaddr($ip);
            $remarks = "Has LoggedIn the system at";

            $logStmt = $conn->prepare("INSERT INTO history_log (id, email_address, action, ip, host, login_time) VALUES (?, ?, ?, ?, ?, ?)");

            if ($logStmt) {
                $logStmt->bind_param("isssss", $user["id"], $user["email_address"], $remarks, $ip, $host, $date);
                $logStmt->execute();
                $logStmt->close();
            }

            $stmt->close();
            $conn->close();

            // Redirect to home.php after login
            header("Location: ../private_user/home.php");
            exit();

        } else {
            header("Location: ../login.php?error=invalid");
            exit();
        }

    } else {
        header("Location: ../login.php?error=invalid");
        exit();
    }

} else {
    // If accessed directly
    header("Location: ../login.phphtml");
    exit();
}