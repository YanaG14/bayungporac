<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if(!isset($_SESSION["email_address"])){
    header("location:../login.php");
    exit();
}

require_once("../include/connection.php");

$user_id = $_SESSION['user_no'];
$user_department = $_SESSION['department_id'];

// Get user name and department image
$stmt = $conn->prepare("
    SELECT login_user.name, departments.department_img
    FROM login_user
    JOIN departments 
    ON login_user.department_id = departments.department_id
    WHERE login_user.id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$name = $row['name'];
$department_img = $row['department_img'];

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
LEFT JOIN login_user al ON uf.email = al.id

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
<nav class="fixed top-0 w-full bg-green-700 shadow-md z-50">
  <div class="flex justify-between items-center h-16 px-4 sm:px-6">
    <!-- Logo & Title -->
    <div class="flex items-center space-x-2 sm:space-x-3">
      <img src="js/img/municipalLogo.png" alt="Logo" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white object-cover">
      <h1 class="text-white font-bold text-base sm:text-lg md:text-xl whitespace-nowrap">Bayung Porac Archive</h1>
    </div>

    <!-- Right Side -->
    <div class="flex items-center space-x-2 sm:space-x-4">
      <!-- Desktop Welcome -->
      <span class="hidden md:inline-block text-sm md:text-base text-white">
  Welcome, <b><?php echo ucwords(htmlentities($name)); ?></b>!
</span>
      
      <!-- Mobile Menu Button -->
      <button id="mobileMenuBtn" 
        class="md:hidden p-2 rounded-lg hover:bg-green-600 hover:bg-opacity-20 transition-all duration-200 group">
        <svg class="w-5 h-5 text-white transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" id="menuIcon">
          <!-- Hamburger (3 lines) -->
          <path class="hamburger" stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 12h16M4 16h16"/>
          
          <!-- X (hidden by default) -->
          <path class="close opacity-0 scale-0" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
      
      <!-- Desktop Logout -->
      <a href="#" onclick="confirmLogout(this)" 
      class="hidden md:inline-block px-4 py-2 text-sm md:text-base text-white rounded-lg border border-white hover:bg-white hover:text-green-700 transition-all duration-300 font-medium">
    Log out
    </a>
    </div>
  </div>
</nav>

<!-- Mobile Dropdown Menu -->
<div id="mobileMenu" class="md:hidden fixed top-20 sm:top-20 left-4 right-4 max-w-sm mx-auto bg-white shadow-2xl rounded-2xl border-2 border-gray-200 z-40 opacity-0 invisible transform scale-95 transition-all duration-300">
  <div class="p-6 space-y-4">
    <!-- Mobile Welcome -->
    <div class="text-center">
      <p class="text-sm font-medium text-gray-600">Welcome,</p>
      <p class="font-bold text-xl text-gray-900"><?php echo ucwords(htmlentities($name)); ?></p>
    </div>
    
    <!-- Mobile Logout -->
    <a href="#" onclick="confirmLogout(this)" 
       class="block w-full max-w-[140px] mx-auto text-center px-6 py-2.5 bg-white text-red-700 font-bold rounded-xl hover:bg-red-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 border border-red-200">
      Log out
    </a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const mobileBtn = document.getElementById('mobileMenuBtn');
  const menuIcon = document.getElementById('menuIcon');
  const hamburgerPaths = menuIcon.querySelectorAll('.hamburger');
  const closePaths = menuIcon.querySelectorAll('.close');
  const mobileMenu = document.getElementById('mobileMenu');
  let isOpen = false;

  mobileBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    
    isOpen = !isOpen;
    
    if (isOpen) {
      // Show menu & change to X
      mobileMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
      mobileMenu.classList.add('opacity-100', 'visible', 'scale-100');
      
      // Animate to X
      hamburgerPaths.forEach(path => {
        path.style.opacity = '0';
        path.style.transform = 'scaleY(0)';
      });
      closePaths.forEach(path => {
        path.style.opacity = '1';
        path.style.transform = 'scale(1)';
      });
      
    } else {
      // Hide menu & change back to hamburger
      mobileMenu.classList.remove('opacity-100', 'visible', 'scale-100');
      mobileMenu.classList.add('opacity-0', 'invisible', 'scale-95');
      
      // Animate back to hamburger
      hamburgerPaths.forEach(path => {
        path.style.opacity = '1';
        path.style.transform = 'scaleY(1)';
      });
      closePaths.forEach(path => {
        path.style.opacity = '0';
        path.style.transform = 'scale(0)';
      });
    }
  });

  // Close when clicking outside
  document.addEventListener('click', function(e) {
    if (!mobileBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
      if (isOpen) {
        // Reset to hamburger
        hamburgerPaths.forEach(path => {
          path.style.opacity = '1';
          path.style.transform = 'scaleY(1)';
        });
        closePaths.forEach(path => {
          path.style.opacity = '0';
          path.style.transform = 'scale(0)';
        });
      }
      mobileMenu.classList.remove('opacity-100', 'visible', 'scale-100');
      mobileMenu.classList.add('opacity-0', 'invisible', 'scale-95');
      isOpen = false;
    }
  });

  // Close on desktop resize
  window.addEventListener('resize', function() {
    if (window.innerWidth >= 768 && isOpen) {
      // Reset to hamburger
      hamburgerPaths.forEach(path => {
        path.style.opacity = '1';
        path.style.transform = 'scaleY(1)';
      });
      closePaths.forEach(path => {
        path.style.opacity = '0';
        path.style.transform = 'scale(0)';
      });
      mobileMenu.classList.remove('opacity-100', 'visible', 'scale-100');
      mobileMenu.classList.add('opacity-0', 'invisible', 'scale-95');
      isOpen = false;
    }
  });
});
</script>




<!-- MAIN LAYOUT-->
<div class="mt-20 px-4 sm:px-6">

<!-- BACK BUTTON -->
<a href="folder_management.php"
class="inline-block mb-4 bg-white px-4 py-2 rounded-xl shadow hover:bg-green-100 transition">
<i class="fas fa-arrow-left"></i> Back
</a>

<!--CONTAINER-->
<div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 h-[615px] flex flex-col hover:shadow-xl transition-all duration-300">

<!-- HEADER - STACKS ON MOBILE -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 pb-3 border-b border-gray-100">
  
  <!-- LEFT: TITLE -->
  <h4 class="text-lg sm:text-xl font-semibold flex items-center gap-2 text-gray-700 flex-shrink-0 order-1">
    <i class="fas fa-folder-open text-green-500"></i>
    <span class="relative">
      RECORDS MANAGEMENT
      <span class="absolute left-0 -bottom-1 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-600 rounded"></span>
    </span>
  </h4>

  <!-- RIGHT: SEARCH + ACTION BUTTONS -->
  <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3 w-full sm:w-auto order-2">
    
    <!-- SEARCH (FULL WIDTH MOBILE) -->
    <div class="flex-1 min-w-0 w-full sm:w-80">
      <div class="relative">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10"></i>
        <input type="text" id="globalSearch" 
          placeholder="Search records..." 
          oninput="performSearch()"
          class="w-full border border-gray-300 rounded-full pl-10 pr-4 py-2.5 sm:py-2 
                 focus:ring-2 focus:ring-blue-300 focus:outline-none transition-all duration-200
                 bg-gray-50 hover:bg-white shadow-sm">
      </div>
    </div>

  
  </div>
</div>

<!-- RESPONSIVE TABLE CONTAINER -->
<div class="flex-1 min-h-0 overflow-hidden rounded-xl border border-gray-200">
  <div class="h-full w-full overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
    <table id="dtable" class="min-w-full border-collapse table-auto w-[1200px] lg:w-[1615px]">
      <thead class="bg-gradient-to-r from-gray-200 to-gray-100 text-black uppercase text-xs sm:text-sm tracking-wider sticky top-0 z-10 shadow-sm">
        <tr>
          <th class="px-3 py-3 sm:px-4 sm:py-2 text-left font-medium min-w-[200px] sm:min-w-[250px]">Filename</th>
          <th class="px-3 py-3 sm:px-4 sm:py-2 text-left font-medium min-w-[150px]">Departments</th>
          <th class="px-3 py-3 sm:px-4 sm:py-2 text-left font-medium min-w-[120px]">Uploader</th>
          <th class="px-3 py-3 sm:px-4 sm:py-2 text-left font-medium min-w-[140px]">Date Uploaded</th>
          <th class="px-3 py-3 sm:px-4 sm:py-2 text-center font-medium min-w-[80px]">Action</th>
        </tr>
      </thead>
      <tbody class="text-gray-700 divide-y divide-gray-100 bg-white">
      
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

        <tr class="hover:bg-gray-50 transition-colors duration-150 group">
          <td class="px-3 py-3 sm:px-4 sm:py-2 font-medium text-sm truncate max-w-[200px] sm:max-w-none">
            <?php echo htmlentities($name); ?>
          </td>

          <td class="px-3 py-3 sm:px-4 sm:py-2 text-sm">
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
            echo !empty($names) ? implode(', ', array_slice($names, 0, 2)) . (count($names) > 2 ? '...' : '') : '—';
            ?>
          </td>

          <td class="px-3 py-3 sm:px-4 sm:py-2 text-sm font-medium">
            <?php echo htmlentities($uploads) ?: '—'; ?>
          </td>

          <td class="px-3 py-3 sm:px-4 sm:py-2 text-sm text-gray-500">
            <?php echo htmlentities($time); ?>
          </td>

          <td class="px-3 py-3 sm:px-4 sm:py-2">
            <div class="flex justify-center relative">
              <button onclick="toggleMenuFile(<?php echo $id; ?>)" 
                class="flex items-center justify-center w-9 h-9 rounded-full text-gray-600 
                       hover:bg-gray-100 hover:text-gray-900 hover:shadow-md transition-all duration-200
                       group-hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-200"
                title="Actions">
                <i class="fas fa-ellipsis-h text-sm"></i>
              </button>

              <div id="menu-file-<?php echo $id; ?>"
                class="hidden absolute top-full mt-1 right-0 w-44 sm:w-28 bg-white/95 backdrop-blur-sm 
                       rounded-xl shadow-lg border border-gray-100 z-50
                       transform scale-95 opacity-0 transition-all duration-200
                       sm:-left-2 sm:w-32 lg:w-28 lg:right-0">
                
                <a href="downloads.php?file_id=<?php echo $id; ?>"
                  class="block w-full flex items-center gap-2 px-3 py-2.5 text-xs sm:text-sm text-gray-700 
                         hover:bg-blue-50 hover:text-blue-700 rounded-t-xl border-b border-gray-50">
                  <i class="fa fa-download text-blue-500 w-4"></i><span>Download</span>
                </a>

                <a href="<?php echo $filepath; ?>" target="_blank"
                   class="block w-full flex items-center gap-2 px-3 py-2.5 text-xs sm:text-sm text-gray-700 
                          hover:bg-indigo-50 hover:text-indigo-700 border-b border-gray-50"
                   onclick="window.open(this.href, '_blank'); return false;">
                  <i class="fa fa-eye text-indigo-500 w-4"></i><span>View</span>
                </a>

                <a href="archive_file.php?file_id=<?php echo $id; ?>"
                  class="block w-full flex items-center gap-2 px-3 py-2.5 text-xs sm:text-sm text-gray-700 
                         hover:bg-red-50 hover:text-red-700 border-b border-gray-50">
                  <i class="fa fa-archive text-red-500 w-4"></i><span>Archive</span>
                </a>

                <button onclick="openModal('editModal<?php echo $id; ?>')"
                  class="block w-full flex items-center gap-2 px-3 py-2.5 text-xs sm:text-sm text-gray-700 
                         hover:bg-green-50 hover:text-green-700 text-left rounded-b-xl">
                  <i class="fa fa-edit text-green-500 w-4"></i><span>Edit</span>
                </button>
              </div>
            </div>
          </td>
        </tr>

<?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ALL EDIT MODALS (Generated in loop) -->
<?php
// Reset query to generate modals
mysqli_data_seek($query, 0);
while($file = mysqli_fetch_array($query)){
$id = $file['id'];
$file_parts = pathinfo($file['name']);
$filename_no_ext = $file_parts['filename'];
$extension = $file_parts['extension'];

// Get assigned departments
$assigned = mysqli_query($conn,"SELECT department_id FROM file_departments WHERE file_id='$id'");
$assigned_dept = [];
while($ad=mysqli_fetch_array($assigned)){
  $assigned_dept[] = $ad['department_id'];
}
?>

<!-- EDIT MODAL -->
<div id="editModal<?php echo $id; ?>" 
  class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50 p-4">

  <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fadeIn">
    <div class="flex justify-between items-center mb-4">
      <h4 class="font-semibold text-lg">Edit File</h4>
      <button onclick="closeModal('editModal<?php echo $id; ?>')" 
        class="text-gray-500 text-xl hover:text-gray-700 transition">&times;</button>
    </div>

    <form method="POST" action="update_file.php">
      <input type="hidden" name="file_id" value="<?php echo $id; ?>">
      <input type="hidden" name="folder_id" value="<?php echo $folder_id; ?>">

      <label class="block text-sm font-medium text-gray-700 mb-1">File Name</label>
      <input type="text" name="file_name" 
        class="w-full border border-gray-300 p-3 rounded-lg mt-1 focus:ring-2 focus:ring-blue-300 focus:outline-none"
        value="<?php echo htmlentities($filename_no_ext); ?>" required>
      <span class="text-gray-500 text-sm ml-1">.<?php echo $extension; ?></span>

      <label class="block text-sm font-medium text-gray-700 mt-4 mb-2">Assign Departments</label>
      
      <?php
      $departments = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
      while($d=mysqli_fetch_array($departments)){
      ?>
      <div class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition">
        <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>"
          id="dept_<?php echo $d['department_id']; ?>_<?php echo $id; ?>"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
          <?php echo in_array($d['department_id'],$assigned_dept)?'checked':''; ?>>
        <label for="dept_<?php echo $d['department_id']; ?>_<?php echo $id; ?>" 
          class="ml-2 text-sm font-medium text-gray-700 cursor-pointer select-none">
          <?php echo htmlentities($d['department_name']); ?>
        </label>
      </div>
      <?php } ?>

      <div class="mt-6 flex gap-2 justify-end">
        <button type="button" onclick="closeModal('editModal<?php echo $id; ?>')"
          class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
          Cancel
        </button>
        <button name="update_file"
          class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium">
          Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

<?php } ?>

</div>

<!-- COMPLETE WORKING JAVASCRIPT -->
<script>
function openModal(id){ 
  document.getElementById(id).classList.remove('hidden'); 
  document.body.style.overflow = 'hidden'; // Prevent background scroll
}

function closeModal(id){ 
  document.getElementById(id).classList.add('hidden'); 
  document.body.style.overflow = 'auto';
}

function toggleMenuFile(id) {
  const menu = document.getElementById('menu-file-' + id);

  // Close all other menus first
  document.querySelectorAll('[id^="menu-file-"]').forEach(el => {
    if (el.id !== 'menu-file-' + id) {
      el.classList.add('hidden', 'scale-95', 'opacity-0');
    }
  });

  // Toggle current menu
  if (menu.classList.contains('hidden')) {
    menu.classList.remove('hidden');
    // Force reflow
    menu.offsetHeight;
    menu.classList.remove('scale-95', 'opacity-0');
    menu.classList.add('scale-100', 'opacity-100');
  } else {
    closeMenuFile(id);
  }
}

function closeMenuFile(id) {
  const menu = document.getElementById('menu-file-' + id);
  if (menu) {
    menu.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
      menu.classList.add('hidden');
    }, 150);
  }
}

// Global search function (placeholder - implement your logic)
function performSearch() {
  const searchTerm = document.getElementById('globalSearch').value.toLowerCase();
  const rows = document.querySelectorAll('#dtable tbody tr');
  
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    if (text.includes(searchTerm)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

// Close menus on outside click
document.addEventListener('click', function(event) {
  let clickedMenuButton = false;
  document.querySelectorAll('button[onclick^="toggleMenuFile"]').forEach(button => {
    if (button.contains(event.target)) {
      clickedMenuButton = true;
    }
  });
  
  if (!clickedMenuButton) {
    document.querySelectorAll('[id^="menu-file-"]').forEach(menu => {
      menu.classList.add('scale-95', 'opacity-0');
      setTimeout(() => menu.classList.add('hidden'), 150);
    });
  }
});

// Close modals on escape key
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    document.querySelectorAll('[id^="editModal"]').forEach(modal => {
      if (!modal.classList.contains('hidden')) {
        closeModal(modal.id);
      }
    });
  }
});

// Prevent body scroll when modal open
document.querySelectorAll('[id^="editModal"]').forEach(modal => {
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeModal(modal.id);
    }
  });
});
</script>

<style>
/* Custom scrollbar for table */
.scrollbar-thin::-webkit-scrollbar {
  height: 8px;
  width: 8px;
}
.scrollbar-thin::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Fade in animation for modals */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px) scale(0.95); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeIn {
  animation: fadeIn 0.2s ease-out;
}
</style>

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