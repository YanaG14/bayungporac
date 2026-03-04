<?php
session_start();
require_once("../include/connection.php");

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.html');
    exit();
}

// ADD DEPARTMENT
if(isset($_POST['save'])){

    $name = mysqli_real_escape_string($conn, $_POST['department_name']);
    $img = $_FILES['department_img']['name'];
    $tmp = $_FILES['department_img']['tmp_name'];

    // Check duplicate
    $check = mysqli_query($conn,"SELECT * FROM departments WHERE department_name='$name'");
    
    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Department name already exists!');</script>";
    } else {

        if(!is_dir("department_images")){
            mkdir("department_images");
        }

        move_uploaded_file($tmp,"department_images/".$img);

        mysqli_query($conn,"INSERT INTO departments (department_name, department_img) 
                            VALUES ('$name','$img')");

        echo "<script>alert('Department Added Successfully!');</script>";
    }
}

// FETCH ACTIVE DEPARTMENTS
$query = mysqli_query($conn,"SELECT * FROM departments 
                             WHERE department_status='Active' 
                             ORDER BY department_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Department Management</title>

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
        <span class="navbar-brand">Department Management</span>

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

<h4><i class="fas fa-building"></i> Active Departments</h4>
<hr>

<!-- TOP LEFT BUTTONS -->
<div class="mb-3">

    <button class="btn btn-success" data-toggle="modal" data-target="#addDepartment">
        <i class="fas fa-plus"></i> Add Department
    </button>

    <a href="department_archive.php" class="btn btn-warning">
        <i class="fas fa-archive"></i> Archived Departments
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
        <a href="edit_department.php?id=<?php echo $row['department_id']; ?>" 
           class="btn btn-sm btn-info">
           <i class="fas fa-edit"></i> Edit
        </a>

        <a href="archive_department.php?id=<?php echo $row['department_id']; ?>" 
           class="btn btn-sm btn-warning"
           onclick="return confirm('Archive this department?');">
           <i class="fas fa-archive"></i> Archive
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

<!-- ADD MODAL -->
<div class="modal fade" id="addDepartment">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" enctype="multipart/form-data">

<div class="modal-header">
<h5 class="modal-title">Add Department</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<div class="form-group">
<label>Department Name</label>
<input type="text" name="department_name" class="form-control" required>
</div>

<div class="form-group">
<label>Department Image</label>
<input type="file" name="department_img" class="form-control" required>
</div>

</div>

<div class="modal-footer">
<button type="submit" name="save" class="btn btn-success">Save</button>
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

</form>

</div>
</div>
</div>

<script src="js/jquery-3.4.0.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/mdb.min.js"></script>

</body>
</html>