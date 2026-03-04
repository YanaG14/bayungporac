<?php
session_start();
require_once("../include/connection.php");

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.html');
    exit();
}

// FETCH ARCHIVED DEPARTMENTS
$query = mysqli_query($conn,"SELECT * FROM departments 
                             WHERE department_status='Archived' 
                             ORDER BY department_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Archived Departments</title>

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
        <span class="navbar-brand">Archived Departments</span>

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

<h4><i class="fas fa-archive"></i> Archived Departments</h4>
<hr>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="department_management.php" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Active Departments
    </a>
</div>

<table class="table table-striped table-bordered">
<thead class="blue white-text">
<tr>
    <th>ID</th>
    <th>Department Name</th>
    <th>Image</th>
    <th>Status</th>
    <th width="200">Action</th>
</tr>
</thead>
<tbody>

<?php while($row = mysqli_fetch_assoc($query)) { ?>
<tr>
    <td><?php echo $row['department_id']; ?></td>
    <td><?php echo $row['department_name']; ?></td>
    <td>
        <img src="department_images/<?php echo $row['department_img']; ?>" width="60">
    </td>
    <td><?php echo $row['department_status']; ?></td>
    <td>
        <a href="unarchive_department.php?id=<?php echo $row['department_id']; ?>" 
           class="btn btn-sm btn-success"
           onclick="return confirm('Unarchive this department?');">
           <i class="fas fa-undo"></i> Unarchive
        </a>
    </td>
</tr>
<?php } ?>

</tbody>
</table>

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