<!DOCTYPE html>
<html lang="en">

<?php
session_start();
require_once("../include/connection.php");

if(!isset($_SESSION['admin_user'])){
    header("Location: index.html");
}

if(!isset($_GET['folder_id'])){
    header("Location: folder_management.php");
}

$folder_id = intval($_GET['folder_id']);
?>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Bayung Porac Archive</title>
<link rel="icon" type="image/png" href="js/img/municipalLogo.png">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/mdb.min.css" rel="stylesheet">
<link href="css/style.min.css" rel="stylesheet">

<script src="js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" href="medias/css/dataTable.css">
<script src="medias/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function(){
    $('#dtable').DataTable({
        "aLengthMenu": [[5,10,25,50,100,-1],[5,10,25,50,100,"All"]],
        "iDisplayLength":10
    });
    $('#archivedTable').DataTable({
        "aLengthMenu": [[5,10,25,50,100,-1],[5,10,25,50,100,"All"]],
        "iDisplayLength":10
    });
});
</script>
</head>

<body class="grey lighten-3">

<header>
<nav class="navbar fixed-top navbar-expand-lg navbar-light white">
<div class="container-fluid">
<ul class="navbar-nav ml-auto">
<li style="margin-top:10px;">
Welcome, <?php echo $_SESSION['admin_user']; ?>
</li>
<li class="nav-item">
<a href="logout.php" class="nav-link border border-light rounded">
<i class="far fa-user-circle"></i> Logout
</a>
</li>
</ul>
</div>
</nav>
</header>

<div class="sidebar-fixed position-fixed">
<a class="logo-wrapper waves-effect">
<img src="js/img/municipalLogo.png" class="img-fluid" style="width:200px;">
</a>

<div class="list-group list-group-flush">
<a href="folder_management.php" class="list-group-item list-group-item-action">
<i class="fas fa-folder"></i> Folder Management
</a>

<a href="#" class="list-group-item active">
<i class="fas fa-file"></i> Information Management
</a>

<a href="department_management.php" class="list-group-item list-group-item-action">
<i class="fas fa-building"></i> Department Management
</a>
</div>
</div>

<main class="pt-5 mx-lg-5">
<div class="container-fluid mt-5">

<div class="card mb-4">
<div class="card-body">
<h4>Information Management</h4>
</div>
</div>

<div class="mb-3">
<button class="btn btn-success" data-toggle="modal" data-target="#uploadFile">
<i class="fas fa-file-upload"></i> Upload File
</button>

<button class="btn btn-warning" data-toggle="modal" data-target="#archivedFiles">
<i class="fas fa-archive"></i> View Archived Files
</button>
</div>

<hr>

<table id="dtable" class="table table-striped">
<thead>
<tr>
<th>Filename</th>
<th>Size</th>
<th>Uploader</th>
<th>Role</th>
<th>Date Uploaded</th>
<th>Downloads</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$query = mysqli_query($conn,"
SELECT * FROM upload_files
WHERE folder_id='$folder_id' AND status='Active'
ORDER BY id DESC
");

while($file = mysqli_fetch_array($query)){
    $id = $file['id'];
    $name = $file['name'];
    $size = $file['size'];
    $uploads = $file['email'];
    $status = $file['admin_status'];
    $time = $file['timers'];
    $download = $file['download'];
    $filepath = "../uploads/".$file['file_path']; // make sure upload_files table has 'file_path'
?>

<tr>
<td><?php echo htmlentities($name); ?></td>
<td><?php echo floor($size/1000).' KB'; ?></td>
<td><?php echo htmlentities($uploads); ?></td>
<td><?php echo htmlentities($status); ?></td>
<td><?php echo htmlentities($time); ?></td>
<td><?php echo htmlentities($download); ?></td>
<td>
<a href="downloads.php?file_id=<?php echo $id; ?>" class="btn btn-sm btn-outline-primary">
<i class="fa fa-download"></i>
</a>
<a href="<?php echo $filepath; ?>" target="_blank" class="btn btn-sm btn-outline-info">
<i class="fa fa-eye"></i> View
</a>
<a href="archive_file.php?file_id=<?php echo $id; ?>" class="btn btn-sm btn-outline-warning">
<i class="fa fa-archive"></i> Archive
</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
</main>

<!-- UPLOAD FILE MODAL -->
<div class="modal fade" id="uploadFile">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST" action="upload_files.php" enctype="multipart/form-data">
<div class="modal-header">
<h4 class="modal-title">Upload File</h4>
<button class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
<input type="hidden" name="folder_id" value="<?php echo $folder_id; ?>">
<label>Select File(s)</label>
<input type="file" name="files[]" multiple class="form-control" required>
<br>
<label>Assign Departments</label>
<?php
$dept = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
while($d=mysqli_fetch_array($dept)){
?>
<div>
<input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>">
<?php echo htmlentities($d['department_name']); ?>
</div>
<?php } ?>
</div>

<div class="modal-footer">
<button class="btn btn-success" name="upload">Upload File</button>
</div>
</form>
</div>
</div>
</div>

<!-- ARCHIVED FILES MODAL -->
<div class="modal fade" id="archivedFiles">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Archived Files</h4>
<button class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<table id="archivedTable" class="table table-striped">
<thead>
<tr>
<th>Filename</th>
<th>Size</th>
<th>Uploader</th>
<th>Role</th>
<th>Date Uploaded</th>
<th>Downloads</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php
$archived = mysqli_query($conn,"
SELECT * FROM upload_files
WHERE folder_id='$folder_id' AND status='Archived'
ORDER BY id DESC
");
while($f = mysqli_fetch_array($archived)){
    $fid = $f['id'];
    $fname = $f['name'];
    $fsize = $f['size'];
    $fuploads = $f['email'];
    $fstatus = $f['admin_status'];
    $ftime = $f['timers'];
    $fdownload = $f['download'];
    $fpath = "../uploads/".$f['file_path'];
?>
<tr>
<td><?php echo htmlentities($fname); ?></td>
<td><?php echo floor($fsize/1000).' KB'; ?></td>
<td><?php echo htmlentities($fuploads); ?></td>
<td><?php echo htmlentities($fstatus); ?></td>
<td><?php echo htmlentities($ftime); ?></td>
<td><?php echo htmlentities($fdownload); ?></td>
<td>
<a href="<?php echo $fpath; ?>" target="_blank" class="btn btn-sm btn-outline-info">
<i class="fa fa-eye"></i> View
</a>
<a href="unarchive_file.php?file_id=<?php echo $fid; ?>" class="btn btn-sm btn-outline-success">
<i class="fa fa-undo"></i> Unarchive
</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</div>

<script src="js/jquery-3.4.0.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/mdb.min.js"></script>

</body>
</html>