<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (!isset($_SESSION['admin_user'])) {
    header('Location: index.php');
    exit();
}

$adminName = $_SESSION['admin_name'];
require_once("../include/connection.php");

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

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
#loader {
  position: fixed;
  inset: 0;
  z-index: 9999;
}
.dot {
  display: inline-block;
  animation: bounce 1s infinite ease-in-out;
}
@keyframes bounce {
  0%,80%,100% { transform: translateY(0); }
  40% { transform: translateY(-10px); }
}
.animation-delay-100 { animation-delay: 0.1s; }
.animation-delay-200 { animation-delay: 0.2s; }

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px);}
  to { opacity: 1; transform: translateY(0);}
}
.animate-fadeIn { animation: fadeIn 0.3s ease-out; }
</style>

<script>
$(document).ready(function(){
    $('#dtable').DataTable({
        paging: false,
        info: false,
        lengthChange: false
    });

    $('#archivedTable').DataTable({
        paging: false,
        info: false,
        lengthChange: false
    });
});
</script>

</head>

<body class="bg-gray-100 font-sans">

<!-- LOADER -->
<div id="loader" class="bg-white flex justify-center items-center">
  <div class="flex space-x-2">
    <span class="dot bg-green-600 w-4 h-4 rounded-full"></span>
    <span class="dot bg-green-600 w-4 h-4 rounded-full animation-delay-100"></span>
    <span class="dot bg-green-600 w-4 h-4 rounded-full animation-delay-200"></span>
  </div>
</div>

<div id="page-content" class="opacity-0 transition-opacity duration-500">

<!-- NAVBAR -->
<nav class="fixed top-0 w-full bg-green-700 shadow-lg z-50">
  <div class="flex flex-col sm:flex-row justify-between items-center px-4 sm:px-6 py-2 sm:py-0">

    <div class="flex items-center space-x-3">
      <img src="js/img/municipalLogo.png" class="w-10 h-10">
      <h1 class="text-white font-semibold text-lg">Bayung Porac Archive</h1>
    </div>

    <div class="flex items-center gap-4 text-white text-sm sm:text-base">
      <span>Welcome, <?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?>!</span>

      <a href="#" onclick="confirmLogout()"
         class="bg-white text-green-800 border border-green-800 px-3 py-1 rounded hover:bg-green-800 hover:text-white transition">
        Log out
      </a>
    </div>

  </div>
</nav>

<!-- MAIN -->
<div class="mt-24 px-4 sm:px-6">

<!-- BACK BUTTON -->
<a href="folder_management.php"
class="inline-block mb-4 bg-white px-4 py-2 rounded-xl shadow hover:bg-green-100 transition">
<i class="fas fa-arrow-left"></i> Back
</a>

<div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 h-[700px] flex flex-col hover:shadow-xl transition">

<!-- HEADER -->
<div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4">

<h4 class="text-lg sm:text-xl font-semibold text-gray-700">
Records Management
</h4>

<div class="flex gap-2">

<button onclick="openModal('uploadModal')" 
class="bg-gradient-to-r from-green-600 to-green-500 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition">
<i class="fas fa-file-upload"></i> Upload File
</button>

<button onclick="openModal('archiveModal')" 
class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition">
<i class="fas fa-archive"></i> View Archived Files
</button>

</div>
</div>

<!-- TABLE -->
<div class="flex-1 overflow-y-auto">

<table id="dtable" class="min-w-full">

<thead class="bg-green-600 text-white">
<tr>
<th class="px-4 py-2">Filename</th>
<th class="px-4 py-2">Departments</th>
<th class="px-4 py-2">Uploader</th>
<th class="px-4 py-2">Date Uploaded</th>
<th class="px-4 py-2">Downloads</th>
<th class="px-4 py-2 text-center">Action</th>
</tr>
</thead>

<tbody class="text-gray-700">

<?php
$query = mysqli_query($conn, "
SELECT uf.*, al.name AS uploader_name
FROM upload_files uf
LEFT JOIN admin_login al ON uf.email = al.id
WHERE uf.folder_id='$folder_id' AND uf.status='Active'
ORDER BY uf.id DESC
");

while($file = mysqli_fetch_array($query)){
$id = $file['id'];
$name = $file['name'];
$uploads = $file['uploader_name'];
$time = $file['timers'];
$download = $file['download'];
$filepath = "../uploads/".$file['file_path'];
?>

<tr class="border-b hover:bg-green-50 hover:shadow-lg hover:-translate-y-1 transition">

<td class="px-4 py-2"><?php echo htmlentities($name); ?></td>

<td class="px-4 py-2">
<?php
$dept_query = mysqli_query($conn,"
SELECT d.department_name 
FROM file_departments fd
JOIN departments d ON fd.department_id = d.department_id
WHERE fd.file_id = $id
");
$names=[];
while($d=mysqli_fetch_array($dept_query)){
$names[]=$d['department_name'];
}
echo implode(', ',$names);
?>
</td>

<td class="px-4 py-2"><?php echo htmlentities($uploads); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($time); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($download); ?></td>

<td class="px-4 py-2">
<div class="flex justify-center gap-2">

<a href="downloads.php?file_id=<?php echo $id; ?>" 
class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
<i class="fa fa-download"></i>
</a>

<a href="<?php echo $filepath; ?>" target="_blank"
class="bg-indigo-500 text-white px-3 py-1 rounded hover:bg-indigo-600">
<i class="fa fa-eye"></i>
</a>

<a href="archive_file.php?file_id=<?php echo $id; ?>"
class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
<i class="fa fa-archive"></i>
</a>

<button onclick="openModal('editModal<?php echo $id; ?>')"
class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
<i class="fa fa-edit"></i>
</button>

</div>
</td>

</tr>

<!-- EDIT MODAL -->
<div id="editModal<?php echo $id; ?>" 
class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">

<div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fadeIn">

<div class="flex justify-between items-center mb-4">
<h4 class="font-semibold text-lg">Edit File</h4>
<button onclick="closeModal('editModal<?php echo $id; ?>')" class="text-gray-500 text-xl">&times;</button>
</div>

<form method="POST" action="update_file.php">
<input type="hidden" name="file_id" value="<?php echo $id; ?>">
<input type="hidden" name="folder_id" value="<?php echo $folder_id; ?>">

<?php 
$file_parts = pathinfo($file['name']);
$filename_no_ext = $file_parts['filename'];
$extension = $file_parts['extension'];
?>

<label>File Name</label>
<input type="text" name="file_name" class="w-full border p-2 rounded mt-2"
value="<?php echo htmlentities($filename_no_ext); ?>" required>

<span class="text-gray-500 text-sm">.<?php echo $extension; ?></span>

<br><br>

<label>Assign Departments</label>

<?php
$departments = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
$assigned = mysqli_query($conn,"SELECT department_id FROM file_departments WHERE file_id='$id'");
$assigned_dept = [];

while($ad=mysqli_fetch_array($assigned)){
$assigned_dept[] = $ad['department_id'];
}

while($d=mysqli_fetch_array($departments)){
?>
<div class="mt-2">
<input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>"
<?php echo in_array($d['department_id'],$assigned_dept)?'checked':''; ?>>
<?php echo htmlentities($d['department_name']); ?>
</div>
<?php } ?>

<div class="mt-4 text-right">
<button name="update_file"
class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
Save Changes
</button>
</div>

</form>
</div>
</div>

<?php } ?>

</tbody>
</table>

</div>
</div>
</div>

</div>

<script>
function openModal(id){ document.getElementById(id).classList.remove('hidden'); }
function closeModal(id){ document.getElementById(id).classList.add('hidden'); }

function confirmLogout(){
Swal.fire({
title:'Logout?',
showCancelButton:true,
confirmButtonColor:'#dc2626'
}).then((r)=>{
if(r.isConfirmed){ window.location='Logout.php'; }
});
}

window.addEventListener('load', function() {
const loader = document.getElementById('loader');
const content = document.getElementById('page-content');

loader.style.opacity='0';
setTimeout(()=>{
loader.style.display='none';
content.style.opacity='1';
},500);
});
</script>

<!-- UPLOAD MODAL -->
<div id="uploadModal"
class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">

<div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fadeIn">

<div class="flex justify-between items-center mb-4">
<h4 class="font-semibold text-lg">Upload File</h4>
<button onclick="closeModal('uploadModal')" class="text-gray-500 text-xl">&times;</button>
</div>

<form method="POST" action="upload_files.php" enctype="multipart/form-data">
<input type="hidden" name="folder_id" value="<?php echo $folder_id; ?>">

<label>Select File(s)</label>
<input type="file" name="files[]" multiple class="w-full border p-2 rounded mt-2" required>

<br><br>

<label>Assign Departments</label>
<?php
$dept = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
while($d=mysqli_fetch_array($dept)){
?>
<div class="mt-2">
<input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>">
<?php echo htmlentities($d['department_name']); ?>
</div>
<?php } ?>

<div class="mt-4 text-right">
<button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
Upload File
</button>
</div>

</form>
</div>
</div>

<!-- ARCHIVED MODAL -->
<div id="archiveModal"
class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">

<div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-4xl p-6 animate-fadeIn">

<div class="flex justify-between items-center mb-4">
<h4 class="font-semibold text-lg">Archived Files</h4>
<button onclick="closeModal('archiveModal')" class="text-gray-500 text-xl">&times;</button>
</div>

<table id="archivedTable" class="min-w-full text-sm">
<thead class="bg-gray-700 text-white">
<tr>
<th class="p-3">Filename</th>
<th class="p-3">Size</th>
<th class="p-3">Uploader</th>
<th class="p-3">Role</th>
<th class="p-3">Date Uploaded</th>
<th class="p-3">Downloads</th>
<th class="p-3">Action</th>
</tr>
</thead>

<tbody>
<?php
$archived = mysqli_query($conn, "
SELECT uf.*, al.name AS uploader_name
FROM upload_files uf
LEFT JOIN admin_login al ON uf.email = al.id
WHERE uf.folder_id='$folder_id' AND uf.status='Archived'
ORDER BY uf.id DESC
");

while($f = mysqli_fetch_array($archived)){
$fid = $f['id'];
$fpath = "../uploads/".$f['file_path'];
?>
<tr class="border-b">
<td class="p-3"><?php echo htmlentities($f['name']); ?></td>
<td class="p-3"><?php echo floor($f['size']/1000).' KB'; ?></td>
<td class="p-3"><?php echo htmlentities($f['uploader_name']); ?></td>
<td class="p-3"><?php echo htmlentities($f['admin_status']); ?></td>
<td class="p-3"><?php echo htmlentities($f['timers']); ?></td>
<td class="p-3"><?php echo htmlentities($f['download']); ?></td>
<td class="p-3 space-x-2">
<a href="<?php echo $fpath; ?>" target="_blank"
class="bg-indigo-500 text-white px-2 py-1 rounded">
<i class="fa fa-eye"></i>
</a>
<a href="unarchive_file.php?file_id=<?php echo $fid; ?>"
class="bg-green-500 text-white px-2 py-1 rounded">
<i class="fa fa-undo"></i>
</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
</div>



</body>
</html>