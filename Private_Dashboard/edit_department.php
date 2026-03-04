<?php
session_start();
require_once("../include/connection.php");

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.html');
    exit();
}

if(!isset($_GET['id'])){
    header("Location: department_management.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM departments WHERE department_id='$id'");
$row = mysqli_fetch_assoc($result);

if(!$row){
    header("Location: department_management.php");
    exit();
}

// UPDATE LOGIC
if(isset($_POST['update'])){

    $name = mysqli_real_escape_string($conn,$_POST['department_name']);

    // Check duplicate except current ID
    $check = mysqli_query($conn,"SELECT * FROM departments 
                                 WHERE department_name='$name' 
                                 AND department_id != '$id'");

    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Department name already exists!');</script>";
    } else {

        // If new image uploaded
        if(!empty($_FILES['department_img']['name'])){
            $img = $_FILES['department_img']['name'];
            $tmp = $_FILES['department_img']['tmp_name'];

            move_uploaded_file($tmp,"department_images/".$img);

            mysqli_query($conn,"UPDATE departments 
                                SET department_name='$name',
                                    department_img='$img'
                                WHERE department_id='$id'");
        } else {
            mysqli_query($conn,"UPDATE departments 
                                SET department_name='$name'
                                WHERE department_id='$id'");
        }

        echo "<script>alert('Department Updated Successfully!'); 
              window.location='department_management.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Department</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/mdb.min.css" rel="stylesheet">
<link href="css/style.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">

<style>
.square-logo {
    width: 200px;
}
.sidebar-fixed {
    width: 240px;
    position: fixed;
    height: 100%;
}
</style>
</head>

<body class="grey lighten-3">

<!-- NAVBAR -->
<nav class="navbar fixed-top navbar-expand-lg navbar-light white">
    <div class="container-fluid">
        <span class="navbar-brand">Edit Department</span>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item mt-2">
                Welcome, <?php echo $_SESSION['admin_user']; ?>
            </li>
            <li class="nav-item">
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- SIDEBAR -->
<div class="sidebar-fixed bg-white">
    <a class="logo-wrapper waves-effect">
        <img src="js/img/municipalLogo.png" class="square-logo img-fluid">
    </a>

    <div class="list-group list-group-flush">
        <a href="add_document.php" class="list-group-item list-group-item-action">
            <i class="fas fa-file-medical"></i> Information Management
        </a>

        <a href="department_management.php" class="list-group-item active">
            <i class="fas fa-building"></i> Department Management
        </a>

        <a href="view_admin.php" class="list-group-item list-group-item-action">
            <i class="fas fa-users"></i> Admin Accounts
        </a>

        <a href="view_user.php" class="list-group-item list-group-item-action">
            <i class="fas fa-users"></i> User Accounts
        </a>
    </div>
</div>

<!-- MAIN CONTENT -->
<main class="pt-5 mx-lg-5" style="margin-left:260px;">
<div class="container-fluid mt-5">

<div class="card">
<div class="card-body">

<h4><i class="fas fa-edit"></i> Edit Department</h4>
<hr>

<form method="POST" enctype="multipart/form-data">

<div class="form-group">
<label>Department Name</label>
<input type="text" name="department_name" 
       value="<?php echo $row['department_name']; ?>" 
       class="form-control" required>
</div>

<div class="form-group">
<label>Current Image</label><br>
<img src="department_images/<?php echo $row['department_img']; ?>" width="80">
</div>

<div class="form-group">
<label>Change Image (Optional)</label>
<input type="file" name="department_img" class="form-control">
</div>

<br>

<button type="submit" name="update" class="btn btn-success">
    <i class="fas fa-save"></i> Update
</button>

<a href="department_management.php" class="btn btn-secondary">
    Cancel
</a>

</form>

</div>
</div>

</div>
</main>

<script src="js/jquery-3.4.0.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/mdb.min.js"></script>

</body>
</html>