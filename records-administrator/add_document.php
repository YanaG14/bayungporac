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

// Fetch active folders
$query = mysqli_query($conn,"
SELECT 
f.folder_id,
f.folder_name,
f.created_at,
GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') as departments,

-- Include file-related searchable data
GROUP_CONCAT(DISTINCT uf.name SEPARATOR ', ') as file_names,
GROUP_CONCAT(DISTINCT al.name SEPARATOR ', ') as uploaders,
GROUP_CONCAT(DISTINCT uf.timers SEPARATOR ', ') as file_dates

FROM folders f
LEFT JOIN folder_departments fd ON f.folder_id = fd.folder_id
LEFT JOIN departments d ON fd.department_id = d.department_id

-- Join files
LEFT JOIN upload_files uf ON f.folder_id = uf.folder_id AND uf.status='Active'
LEFT JOIN admin_login al ON uf.email = al.id

WHERE f.folder_status='Active'

GROUP BY f.folder_id
ORDER BY f.folder_name ASC
");



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
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
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

table.dataTable,
table.dataTable th,
table.dataTable td {
    border: none !important;
}

table.dataTable thead th {
    border-bottom: none !important;
}
</style>

<script>
$(document).ready(function(){
    $('#dtable').DataTable({
        paging: false,
        info: false,
        lengthChange: false,
         searching: false
    });

    $('#archivedTable').DataTable({
        paging: false,
        info: false,
        lengthChange: false,
         searching: false
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
  <div class="flex flex-col sm:flex-row justify-between items-center 
              h-auto sm:h-16 px-4 sm:px-6 py-2 sm:py-0 gap-2 sm:gap-0">

    <!-- Left: Logo + Title -->
    <div class="flex items-center space-x-3">
      <img src="js/img/municipalLogo.png" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
      <h1 class="text-white font-semibold text-base sm:text-lg text-center sm:text-left">
        Bayung Porac Archive
      </h1>
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

<!-- MAIN LAYOUT-->
<div class="mt-20 px-4 sm:px-6">

<!-- BACK BUTTON -->
<a href="folder_management.php"
class="inline-block mb-4 bg-white px-4 py-2 rounded-xl shadow hover:bg-green-100 transition">
<i class="fas fa-arrow-left"></i> Back
</a>

<!--CONTAINER-->
<div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 h-[615px] flex flex-col hover:shadow-xl transition">

<!-- HEADER -->
<div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4">
<h4 class="text-lg sm:text-xl font-semibold flex items-center gap-2 text-gray-700">
    <i class="fas fa-folder-open text-green-500"></i>
    <span class="relative">
      RECORDS MANAGEMENT
      <span class="absolute left-0 -bottom-1 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-600 rounded"></span>
    </span>
  </h4>

  
<!--ACTION BUTTONS-->
<div class="flex gap-3">

<div class="flex w-full sm:w-auto">
  <div class="relative w-full sm:w-80">

    <!-- ICON -->
    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>

    <!-- INPUT -->
    <input type="text" id="globalSearch" 
      placeholder="Search"
      oninput="performSearch()"
      class="w-full border border-gray-300 rounded-full pl-10 pr-4 py-2 
             focus:ring-2 focus:ring-blue-300 focus:outline-none">
    
  </div>
</div>


<!-- UPLOAD FILE (ICON ONLY) -->
<button onclick="openModal('uploadModal')" 
  class="text-green-600 hover:text-green-800 transition duration-200 text-lg focus:outline-none">
  <i class="fas fa-file-upload"></i>
</button>

<!-- VIEW ARCHIVED FILES (ICON ONLY) -->
<button onclick="openArchivedFiles()"
  class="text-yellow-500 hover:text-yellow-600 transition duration-200 text-lg focus:outline-none">
  <i class="fas fa-archive"></i>
</button>

</div>
</div>

<!-- TABLE -->
<div class="h-[560px] w-[1420px] overflow-y-auto overflow-x-hidden rounded-xl border">
<table id="dtable" class="min-w-full border-gray-200 table-auto">
<thead class="bg-gray-200 text-black uppercase text-s tracking-wider sticky top-0">
<tr>
  <th class="px-4 py-2 text-left">Filename</th>
  <th class="px-4 py-2 text-left">Departments</th>
  <th class="px-4 py-2 text-left">Uploader</th>
  <th class="px-4 py-2 text-left">Date Uploaded</th>
  <th class="px-4 py-2 text-center">Action</th>
</tr>
</thead>
<tbody class="text-gray-700 bg-gray-30">

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

<tr>

<td class="px-4 py-2"><?php echo htmlentities($name); ?></td>

<td class="px-4 py-2 ">
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
<!-- <td class="px-4 py-2"><?php echo htmlentities($download); ?></td> -->

<td class="px-4 py-2">
  <div class="flex justify-center relative">

    <!-- 3 DOT BUTTON (MODERN SMALL) -->
<button onclick="toggleMenuFile(<?php echo $id; ?>)"
  class="flex items-center justify-center w-8 h-8 rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition duration-200">
  <i class="fas fa-ellipsis-h text-sm"></i>
</button>

    <!-- DROPDOWN -->
    <div id="menu-file-<?php echo $id; ?>"
      class="hidden absolute top-full mt-1 right-0 w-28 bg-white rounded-lg shadow-sm border border-gray-100 z-50
         transform scale-95 opacity-0 transition-all duration-150">

      <!-- DOWNLOAD -->
      <a href="downloads.php?file_id=<?php echo $id; ?>"
        class="w-full flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded-b-lg">
        <i class="fa fa-download text-blue-500"></i>
        Download
      </a>

      <!-- VIEW (FIXED) -->
<a href="<?php echo $filepath; ?>" target="_blank"
   class="w-full flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded-b-lg"
   onclick="window.open(this.href, '_blank'); return false;">
  <i class="fa fa-eye text-indigo-500"></i>
  View
</a>

      <!-- ARCHIVE -->
      <a href="archive_file.php?file_id=<?php echo $id; ?>"
        class="w-full flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded-b-lg">
        <i class="fa fa-archive text-red-500"></i>
        Archive
      </a>

      <!-- EDIT -->
      <button onclick="openModal('editModal<?php echo $id; ?>')"
        class="w-full flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded-b-lg">
        <i class="fa fa-edit text-green-500"></i>
        Edit
      </button>

    </div>

  </div>
</td>

<script>
  function toggleMenuFile(id) {
    const menu = document.getElementById('menu-file-' + id);

    // Close other menus
    document.querySelectorAll('[id^="menu-file-"]').forEach(el => {
      if (el !== menu) {
        el.classList.add('hidden', 'scale-95', 'opacity-0');
      }
    });

    // Toggle current
    if (menu.classList.contains('hidden')) {
      menu.classList.remove('hidden');

      setTimeout(() => {
        menu.classList.remove('scale-95', 'opacity-0');
        menu.classList.add('scale-100', 'opacity-100');
      }, 10);

    } else {
      closeMenuFile(id);
    }
  }

  function closeMenuFile(id) {
    const menu = document.getElementById('menu-file-' + id);

    menu.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
      menu.classList.add('hidden');
    }, 150);
  }

  function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
  }

  // Click outside to close
  document.addEventListener('click', function (event) {
    document.querySelectorAll('[id^="menu-file-"]').forEach(menu => {
      const button = menu.previousElementSibling;

      if (!menu.contains(event.target) && !button.contains(event.target)) {
        menu.classList.add('scale-95', 'opacity-0');
        setTimeout(() => menu.classList.add('hidden'), 150);
      }
    });
  });
</script>

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

<!-- ARCHIVED FILES MODAL -->
<div id="archiveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm p-4">

  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-4xl max-h-[80vh] p-6 overflow-auto relative animate-fadeIn">

    <!-- Close -->
    <button onclick="closeModal('archiveModal')" 
      class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Title -->
    <h3 class="text-2xl font-semibold mb-4 flex items-center gap-2 text-gray-800">
      <i class="fas fa-archive text-yellow-500"></i> Archived Files
    </h3>

    <!-- SEARCH BAR -->
    <div class="mb-4">
      <div class="relative w-full">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        <input type="text" id="archiveSearch"
          placeholder="Search archived files..."
          oninput="searchArchived()"
          class="w-full border border-gray-300 rounded-full pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
      </div>
    </div>

    <!-- CONTENT -->
    <div id="archivedContent" class="overflow-auto max-h-[60vh] rounded-xl border">
      Loading archived files...
    </div>

  </div>
</div>

<script>
function searchArchived() {
    let keyword = document.getElementById("archiveSearch").value.toLowerCase();
    let rows = document.querySelectorAll("#archivedContent table tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(keyword) ? "" : "none";
    });
}
</script>

<script>
function openArchivedFiles() {
    // Show modal
    document.getElementById('archiveModal').classList.remove('hidden');

    // Load content
    document.getElementById('archivedContent').innerHTML = 'Loading archived files...';

    $('#archivedContent').load('load_archived_files.php?folder_id=<?php echo $folder_id; ?>');
}
</script>

<script>
function performSearch() {
    let keyword = document.getElementById("globalSearch").value.toLowerCase();

    // FILTER TABLE ROWS (FOLDERS)
    let table = document.getElementById("dtable");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        let rowText = rows[i].innerText.toLowerCase();

        if (rowText.includes(keyword)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }

    // AJAX SEARCH FOR FILES (UNCHANGED)
    $.ajax({
        url: "search_files_documents.php",
        type: "POST",
        data: { keyword: keyword },
        success: function(response) {
            $("#searchResults").html(response);
        }
    });
}
</script>

<!-- Footer -->
<footer class="mt-6 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>


<!-- FILE PREVIEW MODAL -->
<div id="previewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4">

  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[85vh] relative overflow-hidden">

    <!-- CLOSE -->
    <button onclick="closePreview()" 
      class="absolute top-3 right-4 text-gray-600 hover:text-black text-2xl font-bold z-50">&times;</button>

    <!-- CONTENT -->
    <div id="previewContent" class="w-full h-full flex items-center justify-center bg-gray-100">
      Loading preview...
    </div>

  </div>
</div>

<script>
function openPreview(filePath) {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');

    modal.classList.remove('hidden');

    let ext = filePath.split('.').pop().toLowerCase();

    if (ext === 'pdf') {
        content.innerHTML = `<iframe src="${filePath}" class="w-full h-full"></iframe>`;
    } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
        content.innerHTML = `<img src="${filePath}" class="max-h-full max-w-full object-contain">`;
    } else {
        content.innerHTML = `
            <div class="text-center">
                <p class="mb-3 text-gray-700">Preview not available</p>
                <a href="${filePath}" target="_blank" class="text-blue-600 underline">
                    Open File
                </a>
            </div>
        `;
    }
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewContent').innerHTML = 'Loading preview...';
}
</script>


<script>
function toggleSelectAll(source) {
    document.querySelectorAll('.fileCheckbox').forEach(cb => {
        cb.checked = source.checked;
    });
}

function confirmBulkRestore() {
    let selected = [];

    document.querySelectorAll('.fileCheckbox:checked').forEach(cb => {
        selected.push(cb.value);
    });

    if (selected.length === 0) {
        Swal.fire('No files selected');
        return;
    }

    Swal.fire({
        title: 'Restore selected files?',
        text: selected.length + ' file(s) will be restored.',
        showCancelButton: true,
        confirmButtonText: 'Restore',
        confirmButtonColor: '#16a34a'
    }).then((result) => {
        if (result.isConfirmed) {
            bulkRestore(selected);
        }
    });
}

function bulkRestore(ids) {
    $.ajax({
        url: 'bulk_restore.php',
        type: 'POST',
        data: { ids: ids },
        success: function() {
            openArchivedFiles(); // reload modal

            Swal.fire({
                icon: 'success',
                title: 'Restored!',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}
</script>


</body>
</html>