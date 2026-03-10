<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if(!isset($_SESSION["email_address"])){
    header("location:../login.html");
    exit();
}

require_once("../include/connection.php");

$user_id = $_SESSION['user_no'];
$user_department = $_SESSION['department_id'];

// Get user name
$stmt = $conn->prepare("SELECT name FROM login_user WHERE id = ?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$name = $row['name'];

// Check if a folder is clicked
$selected_folder = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;
?>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bayung Porac Archive</title>

<link rel="icon" type="image/png" href="img/municipalLogo.png">

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- jQuery + DataTables -->
<script src="js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" type="text/css" href="media/css/dataTable.css" />
<script src="media/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function(){
    $('#dtable').dataTable({
        "aLengthMenu": [[5,10,15,25,50,100,-1],[5,10,15,25,50,100,"All"]],
        "iDisplayLength": 10
    });
});
</script>

<style>
#loader{
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
z-index:9999;
background:url('img/lg.flip-book-loader.gif') 50% 50% no-repeat #f9f9f9;
}
</style>

<script>
$(window).on('load',function(){
setTimeout(function(){
$('#loader').fadeOut('slow');
});
});
</script>

</head>

<body class="bg-gray-100 font-sans">

<!-- NAVBAR -->
<nav class="fixed top-0 w-full bg-green-700 shadow-lg z-50">
  <div class="flex justify-between items-center h-16 px-4">
    <div class="flex items-center space-x-3">
      <img src="img/municipalLogo.png" class="w-10">
      <h1 class="text-white font-semibold text-lg">Bayung Porac Archive</h1>
    </div>

    <div class="flex items-center text-white font-medium space-x-4">
      <span>Welcome, <?php echo ucwords(htmlentities($name)) . "!"; ?></span>
      <a href="Logout.php" class="bg-white text-green-800 border border-green-800 px-3 py-1 rounded hover:bg-green-800 hover:text-white hover:border-white transition-colors duration-300">
        Log out
      </a>
    </div>
  </div>
</nav>

<!-- MAIN CONTENT -->
<div class="mt-24 w-full px-8">
<div class="grid grid-cols-12 gap-6">

<!-- SIDEBAR -->
<div class="col-span-3">
  <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-600 mb-6">
    <div class="flex flex-col items-center">
      <img src="img/municipalLogo.png" class="w-28 mb-3">
      <h2 class="text-lg font-semibold text-gray-700">Admin Profile</h2>
    </div>
    <hr class="my-4">
    <p class="text-sm"><b>Full Name:</b> <?php echo $name; ?></p>
    <p class="text-sm"><b>Position:</b> Admin Staff</p>
  </div>

  <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-600">
    <h3 class="font-semibold text-lg mb-3 text-gray-700">File Document</h3>
    <ul class="text-sm list-disc ml-5 space-y-1 text-gray-600">
      <li>Ensuring revisions are identified</li>
      <li>Documents remain legible</li>
      <li>Prevent unintended use of obsolete documents</li>
    </ul>
  </div>
</div>

<!-- TABLE / FOLDERS SECTION -->
<div class="col-span-9">
<div class="bg-white shadow-md rounded-xl p-6">

<?php
if ($selected_folder === 0):
    // SHOW FOLDERS ASSIGNED TO USER'S DEPARTMENT
    $stmt = $conn->prepare("
        SELECT f.folder_id, f.folder_name
        FROM folders f
        JOIN folder_departments fd ON f.folder_id = fd.folder_id
        WHERE fd.department_id = ? AND f.folder_status='Active'
        ORDER BY f.folder_name ASC
    ");
    $stmt->bind_param("i", $user_department);
    $stmt->execute();
    $folders = $stmt->get_result();
?>

<h2 class="text-xl font-semibold text-gray-700 mb-4">Folders</h2>
<div class="grid grid-cols-3 gap-4">
<?php while($folder = $folders->fetch_assoc()): ?>
    <a href="?folder_id=<?php echo $folder['folder_id']; ?>" class="p-4 bg-white rounded shadow hover:bg-green-100 transition">
        <?php echo htmlentities($folder['folder_name']); ?>
    </a>
<?php endwhile; ?>
</div>

<?php
else:
    // SHOW FILES IN SELECTED FOLDER ASSIGNED TO USER'S DEPARTMENT
    $stmt = $conn->prepare("
        SELECT uf.id, uf.name, uf.size, uf.email, uf.admin_status, uf.timers, uf.download
        FROM upload_files uf
        JOIN file_departments fd ON uf.id = fd.file_id
        WHERE uf.folder_id = ? AND fd.department_id = ? AND uf.status='Active'
        ORDER BY uf.id DESC
    ");
    $stmt->bind_param("ii", $selected_folder, $user_department);
    $stmt->execute();
    $files = $stmt->get_result();
?>

<h2 class="text-xl font-semibold text-gray-700 mb-4">Files in Folder</h2>
<div class="overflow-x-auto">
<table id="dtable" class="min-w-full border border-gray-200">
<thead class="bg-green-700 text-white">
<tr>
<th class="px-4 py-2">Filename</th>
<th class="px-4 py-2">FileSize</th>
<th class="px-4 py-2">Uploader</th>
<th class="px-4 py-2">Status</th>
<th class="px-4 py-2">Upload Date</th>
<th class="px-4 py-2">Downloads</th>
<th class="px-4 py-2">Action</th>
</tr>
</thead>
<tbody class="text-gray-700">
<?php while($file = $files->fetch_assoc()): ?>
<tr class="border-b hover:bg-gray-50">
<td class="px-4 py-2"><?php echo htmlentities($file['name']); ?></td>
<td class="px-4 py-2"><?php echo floor($file['size']/1000)." KB"; ?></td>
<td class="px-4 py-2"><?php echo htmlentities($file['email']); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($file['admin_status']); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($file['timers']); ?></td>
<td class="px-4 py-2"><?php echo $file['download']; ?></td>
<td class="px-4 py-2">
<a href="downloads.php?file_id=<?php echo $file['id']; ?>" 
   class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-5 py-3 rounded-full flex items-center gap-2 hover:from-blue-600 hover:to-blue-800 transition" 
   title="Download">
   <i class="fas fa-download"></i> Download
</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<a href="home.php" class="mt-4 inline-block bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Back to Folders</a>

<?php endif; ?>

</div>
</div>

</div>
</div>

<!-- Footer -->
<footer class="mt-8 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>

</body>
</html>