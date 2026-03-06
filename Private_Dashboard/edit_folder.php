<?php
session_start();
require_once("../include/connection.php");

if(!isset($_SESSION['admin_user'])){
header("Location:index.html");
}

$id = $_GET['id'];

$folder = mysqli_query($conn,"SELECT * FROM folders WHERE folder_id='$id'");
$f = mysqli_fetch_array($folder);
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Folder</title>

<link rel="stylesheet" href="css/bootstrap.min.css">

</head>

<body>

<div class="container mt-5">

<h3>Edit Folder</h3>

<form method="POST">

<div class="form-group">
<label>Folder Name</label>

<input type="text"
name="folder_name"
class="form-control"
value="<?php echo $f['folder_name']; ?>"
required>

</div>

<label>Assign Departments</label>

<?php

$dept = mysqli_query($conn,"SELECT * FROM departments");

while($d=mysqli_fetch_array($dept)){

$check = mysqli_query($conn,"
SELECT * FROM folder_departments
WHERE folder_id='$id'
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

<br>

<button class="btn btn-success" name="update">Update Folder</button>

<a href="folder_management.php" class="btn btn-secondary">Back</a>

</form>

</div>

</body>
</html>

<?php

if(isset($_POST['update'])){

$name = mysqli_real_escape_string($conn,$_POST['folder_name']);

mysqli_query($conn,"
UPDATE folders
SET folder_name='$name'
WHERE folder_id='$id'
");

mysqli_query($conn,"
DELETE FROM folder_departments
WHERE folder_id='$id'
");

if(isset($_POST['departments'])){

foreach($_POST['departments'] as $dept){

mysqli_query($conn,"
INSERT INTO folder_departments(folder_id,department_id)
VALUES('$id','$dept')
");

}

}

echo "<script>
alert('Folder updated successfully');
window.location='folder_management.php';
</script>";

}

?>