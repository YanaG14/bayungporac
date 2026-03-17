<?php
require_once("../include/connection.php");

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

    echo '
    <script type="text/javascript">
        alert("Saved Admin Info");
        window.location = "system-administrator.php";
    </script>
    ';
}
?>