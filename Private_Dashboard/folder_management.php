<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (!isset($_SESSION['admin_user'])) {
header('Location: index.html');
}
require_once("../include/connection.php");
?>

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>Bayung Porac Archive</title>

<link rel="icon" type="image/png" href="js/img/municipalLogo.png">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/mdb.min.css" rel="stylesheet">
<link href="css/style.min.css" rel="stylesheet">

<link rel="stylesheet" href="medias/css/dataTable.css"/>

<style>

.square-logo{
width:300px;
height:auto;
object-fit:contain;
}

#loader{
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
z-index:9999;
background:url('img/lg.flip-book-loader.gif') 50% 50% no-repeat rgb(249,249,249);
}

</style>

</head>


<body class="grey lighten-3">

<div id="loader"></div>

<header>

<!-- NAVBAR -->

<nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
<div class="container-fluid">

<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarSupportedContent">

<ul class="navbar-nav mr-auto"></ul>

<?php

$id = mysqli_real_escape_string($conn,$_SESSION['admin_user']);
$r = mysqli_query($conn,"SELECT * FROM admin_login WHERE id='$id'");
$row = mysqli_fetch_array($r);
$id=$row['admin_user'];

?>

<ul class="navbar-nav nav-flex-icons">

<li style="margin-top:10px;">
Welcome, <?php echo ucwords(htmlentities($id)); ?>
</li>

<li class="nav-item">
<a href="logout.php" class="nav-link border border-light rounded waves-effect">
<i class="far fa-user-circle"></i> Log out
</a>
</li>

</ul>

</div>
</div>
</nav>

<!-- SIDEBAR -->

<div class="sidebar-fixed position-fixed">

<a class="logo-wrapper waves-effect">
<img src="js/img/municipalLogo.png" class="square-logo img-fluid">
</a>

<div class="list-group list-group-flush">

<a href="folder_management.php" class="list-group-item active waves-effect">
<i class="fas fa-folder"></i> Folder Management
</a>

<a href="add_document.php" class="list-group-item list-group-item-action waves-effect">
<i class="fas fa-file-medical"></i> Information Management
</a>

<a href="department_management.php" class="list-group-item list-group-item-action waves-effect">
<i class="fas fa-building"></i> Department Management
</a>

<a href="view_admin.php" class="list-group-item list-group-item-action waves-effect">
<i class="fas fa-users"></i> Admin Accounts
</a>

<a href="view_user.php" class="list-group-item list-group-item-action waves-effect">
<i class="fas fa-users"></i> User Accounts
</a>

</div>
</div>

</header>

<main class="pt-5 mx-lg-5">

<div class="container-fluid mt-5">

<div class="card mb-4">
<div class="card-body d-sm-flex justify-content-between">

<h4>Folder Management</h4>

</div>
</div>

<button class="btn btn-success" data-toggle="modal" data-target="#addFolder">
<i class="fas fa-folder-plus"></i> Add Folder
</button>

<button class="btn btn-warning" data-toggle="modal" data-target="#archivedFolders">
<i class="fas fa-archive"></i> View Archived Folders
</button>

<hr>

<table id="dtable" class="table table-striped">

<thead>
<tr>
<th>Folder Name</th>
<th>Departments</th>
<th>Date Created</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php

$query = mysqli_query($conn,"
SELECT 
f.folder_id,
f.folder_name,
f.created_at,
GROUP_CONCAT(d.department_name SEPARATOR ', ') as departments
FROM folders f
LEFT JOIN folder_departments fd ON f.folder_id = fd.folder_id
LEFT JOIN departments d ON fd.department_id = d.department_id
WHERE f.folder_status='Active'
GROUP BY f.folder_id
ORDER BY f.folder_name ASC
");

while($row=mysqli_fetch_array($query)){

?>

<tr>

<td>
<a href="add_document.php?folder_id=<?php echo $row['folder_id']; ?>">
<b><?php echo $row['folder_name']; ?></b>
</a>
</td>

<td><?php echo $row['departments']; ?></td>

<td><?php echo $row['created_at']; ?></td>

<td>

<button class="btn btn-sm btn-primary"
data-toggle="modal"
data-target="#editFolder<?php echo $row['folder_id']; ?>">

<i class="fa fa-edit"></i>

</button>

<a href="archive_folder.php?id=<?php echo $row['folder_id']; ?>"
class="btn btn-sm btn-danger">

<i class="fa fa-archive"></i>

</a>

</td>

</tr>

<!-- EDIT FOLDER MODAL -->

<div class="modal fade" id="editFolder<?php echo $row['folder_id']; ?>">

<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="update_folder.php">

<div class="modal-header">
<h4 class="modal-title">Edit Folder</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<input type="hidden" name="folder_id" value="<?php echo $row['folder_id']; ?>">

<div class="form-group">

<label>Folder Name</label>

<input type="text"
name="folder_name"
class="form-control"
value="<?php echo $row['folder_name']; ?>"
required>

</div>

<label>Assign Departments</label>

<?php

$dept = mysqli_query($conn,"SELECT * FROM departments");

while($d=mysqli_fetch_array($dept)){

$check = mysqli_query($conn,"
SELECT * FROM folder_departments
WHERE folder_id='".$row['folder_id']."'
AND department_id='".$d['department_id']."'
");

$checked = mysqli_num_rows($check)>0 ? "checked" : "";

?>

<div>

<input type="checkbox"
name="departments[]"
value="<?php echo $d['department_id']; ?>"
<?php echo $checked; ?>>

<?php echo $d['department_name']; ?>

</div>

<?php } ?>

</div>

<div class="modal-footer">

<button class="btn btn-primary" name="update">
Update Folder
</button>

</div>

</form>

</div>
</div>

</div>

<?php } ?>

</tbody>
</table>

</div>
</main>


<!-- ADD FOLDER MODAL -->

<div class="modal fade" id="addFolder">

<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="save_folder.php">

<div class="modal-header">
<h4>Add Folder</h4>
<button class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<label>Folder Name</label>
<input type="text" name="folder_name" class="form-control" required>

<br>

<label>Assign Departments</label>

<?php

$dept = mysqli_query($conn,"SELECT * FROM departments");

while($d=mysqli_fetch_array($dept)){
?>

<div>
<input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>">
<?php echo $d['department_name']; ?>
</div>

<?php } ?>

</div>

<div class="modal-footer">

<button class="btn btn-success" name="save">
Create Folder
</button>

</div>

</form>

</div>
</div>
</div>


<!-- ARCHIVED FOLDERS MODAL -->

<div class="modal fade" id="archivedFolders">

<div class="modal-dialog modal-lg modal-dialog-scrollable">
<div class="modal-content">

<div class="modal-header">

<h4 class="modal-title">Archived Folders</h4>

<button type="button" class="close" data-dismiss="modal">&times;</button>

</div>

<div class="modal-body">

<div id="archivedContent">

Loading archived folders...

</div>

</div>

</div>
</div>

</div>


<footer>

<hr>

<div class="footer-copyright text-center py-3">
<p>All right Reserved © <?php echo date('Y');?> Created By: PSU IT Interns</p>
</div>

</footer>


<!-- SCRIPTS -->

<script src="js/jquery-3.4.0.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/mdb.min.js"></script>

<script src="medias/js/jquery.dataTables.js"></script>

<script>

$(document).ready(function(){

$('#dtable').DataTable({
"aLengthMenu":[[5,10,15,25,50,100,-1],[5,10,15,25,50,100,"All"]],
"iDisplayLength":10
});

$('#archivedFolders').on('show.bs.modal', function () {

$("#archivedContent").load("load_archived_folders.php");

});

});

</script>


<script>

$(window).on('load',function(){

setTimeout(function(){

$('#loader').fadeOut('slow');

},500);

});

</script>

</body>
</html>