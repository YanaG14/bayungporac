<?php
session_start();
require_once("../include/connection.php");

if(!isset($_SESSION['admin_user'])){
header("Location:index.html");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Archived Folders</title>

<link rel="stylesheet" href="css/bootstrap.min.css">

</head>

<body>

<div class="container mt-5">

<h3>Archived Folders</h3>

<a href="folder_management.php" class="btn btn-primary mb-3">
Back to Folder Management
</a>

<table class="table table-striped">

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
WHERE f.folder_status='Archived'
GROUP BY f.folder_id
ORDER BY f.folder_name ASC
");

while($row=mysqli_fetch_array($query)){

?>

<tr>

<td><?php echo $row['folder_name']; ?></td>

<td><?php echo $row['departments']; ?></td>

<td><?php echo $row['created_at']; ?></td>

<td>

<a href="unarchive_folder.php?id=<?php echo $row['folder_id']; ?>"
class="btn btn-success btn-sm">

Unarchive

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>