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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- JQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<!-- TailwindCSS -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
    #loader {
      position: fixed;
      inset: 0;
      z-index: 9999;
     
    }
    .square-logo { width: 180px; object-fit: contain; }
  </style>



<script>
$(document).ready(function(){
    $('#dtable').DataTable({
    paging: false,        // ❌ remove pagination (Previous/Next)
    info: false,          // ❌ remove "Showing 1 to X of X"
    lengthChange: false   // ❌ removes "Show entries"
    searching: false // ❗ disable DataTables search box
});
    $(window).on('load', function(){ $('#loader').fadeOut('slow'); });
});
</script>
</head>

<body class="bg-gray-100 font-sans">
<!-- LOADER -->
<!-- Loader -->
<!-- Loader -->
<!-- Loader -->
 


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
  Welcome, <b><?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?></b>!
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
      <p class="font-bold text-xl text-gray-900"><?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?></p>
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

<!-- MAIN LAYOUT -->
<div class="mt-24 px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8">

<?php include 'sidebar.php'; ?>

  <!-- MAIN CONTENT -->
  <div class="w-full lg:w-3/4 flex-1 h-[600px] sm:h-[620px] lg:h-[655px]">
 
    <div class="bg-white rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 h-full transition-all duration-300 hover:shadow-xl">
      <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4 mb-4 sm:mb-6">

        <!-- HEADER (Responsive) -->
        <h2 class="text-lg sm:text-xl lg:text-2xl font-semibold flex items-center gap-2 text-center sm:text-left flex-1">
          <i class="fas fa-layer-group text-green-500 text-sm sm:text-base"></i>
          <span class="relative">
            RECORDS MANAGEMENT
            <span class="absolute left-0 -bottom-1 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-600 rounded scale-x-75 sm:scale-x-100"></span>
          </span>
        </h2>

        <!-- Action Buttons (Responsive Stack) -->
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto items-center sm:items-end">

          <!-- SEARCH BAR (Full Width Mobile) -->
          <div class="w-full sm:w-64 lg:w-80 flex-shrink-0">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10"></i>
              <input type="text" id="globalSearch" 
                     placeholder="Search folders..."
                     oninput="performSearch()"
                     class="w-full border border-gray-300 rounded-full pl-10 pr-4 py-2.5 sm:py-2.5 
                            focus:ring-2 focus:ring-green-300 focus:outline-none transition-all duration-200 shadow-sm">
            </div> 
          </div>

          <!-- Action Buttons (Mobile Stack) -->
          <div class="flex gap-1.5 sm:gap-2">
            <!-- ADD POST -->
            <button onclick="$('#modalAddFolder').removeClass('hidden');"
            class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg flex items-center justify-center text-green-600 text-sm sm:text-base focus:outline-none">
            <i class="fas fa-plus"></i>
            </button>

            <!-- ARCHIVE -->
            <button onclick="openArchivedFolders();"
            class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg flex items-center justify-center text-yellow-500 text-sm sm:text-base focus:outline-none">
            <i class="fas fa-archive"></i>
          </button>
          </div>

        </div>
      </div>
 
      <!-- Responsive Table Container -->
     <div id="folderTableContainer" class="w-full h-[calc(100%-120px)] sm:h-[calc(100%-140px)] lg:h-[560px] overflow-hidden rounded-xl border shadow-sm">
        <div class="w-full h-full overflow-x-auto overflow-y-auto custom-scrollbar">
          <table id="dtable" class="min-w-[800px] w-full border-gray-200 table-auto">
            <thead class="bg-gray-200 text-black uppercase text-xs sm:text-sm tracking-wider sticky top-0 z-10 shadow-sm">
              <tr>
                <th class="px-3 sm:px-4 py-2.5 text-left font-medium min-w-[180px]">Folder Name</th>
                <th class="px-3 sm:px-4 py-2.5 text-left font-medium min-w-[160px] hidden md:table-cell">Departments</th>
                <th class="px-3 sm:px-4 py-2.5 text-left font-medium min-w-[120px]">Date Created</th>
                <th class="px-3 sm:px-4 py-2.5 text-center font-medium w-16 sm:w-20">Action</th>
              </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
              
              <?php while($row=mysqli_fetch_array($query)){ ?>
              <tr class="hover:bg-gray-50/50 transition-colors duration-150 border-b last:border-b-0">
                
                <!-- Folder Name -->
                <td class="px-3 sm:px-4 py-3 align-middle">
                  <a href="add_document.php?folder_id=<?php echo $row['folder_id']; ?>" 
                     class="flex items-center gap-2 sm:gap-3 text-gray-800 hover:text-green-700 truncate group transition-all duration-200">
                    <i class="fas fa-folder text-yellow-500 text-sm sm:text-base flex-shrink-0"></i>
                    <b class="truncate text-sm font-medium"><?php echo $row['folder_name']; ?></b>
                  </a>
                </td>

                <!-- Departments -->
                <td class="px-3 sm:px-4 py-3 align-middle text-xs sm:text-sm hidden md:table-cell break-words max-w-[200px]">
                  <?php echo $row['departments']; ?>
                </td>

                <!-- Date Created -->
                <td class="px-3 sm:px-4 py-3 align-middle text-xs sm:text-sm">
                  <?php echo date('M j, Y', strtotime($row['created_at'])); ?>
                </td>

                <!-- Action Buttons -->
                <td class="px-3 sm:px-4 py-3 align-middle">
                  <div class="flex justify-center relative">
                    <!-- 3 DOT BUTTON -->
                    <button onclick="toggleMenu(<?php echo $row['folder_id']; ?>)"
                            class="text-gray-500 hover:text-gray-800 text-xl px-2">
                      <i class="fas fa-ellipsis-h text-sm"></i>
                    </button>
                    
                    
                    <!-- DROPDOWN MENU -->
                    <div id="menu-<?php echo $row['folder_id']; ?>"
                         class="hidden absolute top-full mt-1.5 right-0 w-28 sm:w-32 bg-white rounded-lg shadow-lg border border-gray-100 z-50
                                transform scale-95 opacity-0 transition-all duration-200 origin-top-right">
                      
                      <button onclick="openEditModal(<?php echo $row['folder_id']; ?>);"
                              class="w-full flex items-center gap-2 px-3 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg transition-all duration-200">
                        <i class="fas fa-edit text-blue-500 text-xs"></i>Edit
                      </button>

                      <button onclick="confirmArchive(<?php echo $row['folder_id']; ?>);"
                              class="w-full flex items-center gap-2 px-3 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                        <i class="fas fa-archive text-yellow-500 text-xs"></i> Archive
                      </button>

                      <a href="download_folder.php?folder_id=<?php echo $row['folder_id']; ?>"
                         class="w-full flex items-center gap-2 px-3 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg transition-all duration-200 block">
                        <i class="fas fa-download text-green-600 text-xs"></i>
                        Download
                      </a>
                    </div>
                  </div>
                </td>

              </tr>

              <!-- EDIT FOLDER MODAL (Responsive) -->
              <div id="modalEditFolder<?php echo $row['folder_id']; ?>" 
                   class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex justify-center items-center z-50 p-4 sm:p-6">
                <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md lg:max-w-lg p-4 sm:p-6 relative max-h-[90vh] overflow-y-auto">
                  
                  <!-- Close Button -->
                  <button onclick="$('#modalEditFolder<?php echo $row['folder_id']; ?>').addClass('hidden');" 
                          class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold p-1 rounded-full hover:bg-gray-100 transition-all">
                    &times;
                  </button>

                  <!-- Modal Title -->
                  <h3 class="text-lg sm:text-xl lg:text-2xl font-semibold mb-5 sm:mb-6 flex items-center gap-2 text-gray-800 text-center sm:text-left">
                    <i class="fas fa-edit text-blue-600"></i> Edit Folder
                  </h3>

                  <!-- Form -->
                  <form method="POST" action="update_folder.php" class="flex flex-col gap-4">
                    <input type="hidden" name="folder_id" value="<?php echo $row['folder_id']; ?>">

                    <!-- Folder Name -->
                    <input type="text" name="folder_name" value="<?php echo $row['folder_name']; ?>" placeholder="Folder Name" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 sm:py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all shadow-sm text-sm sm:text-base" required>

                    <!-- Assign Departments -->
                    <div>
                      <label class="font-medium text-gray-700 mb-2 block text-sm sm:text-base">Assign Departments</label>
                      <div class="flex flex-col gap-2.5 max-h-40 sm:max-h-48 overflow-y-auto p-3 sm:p-4 border rounded-lg shadow-sm">
                        <?php
                        $dept = mysqli_query($conn,"SELECT * FROM departments");
                        while($d=mysqli_fetch_array($dept)){
                          $check = mysqli_query($conn,"SELECT * FROM folder_departments WHERE folder_id='".$row['folder_id']."' AND department_id='".$d['department_id']."'");
                          $checked = mysqli_num_rows($check)>0 ? "checked" : "";
                        ?>
                        <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition-all text-sm">
                          <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>" class="w-4 h-4 accent-blue-600 rounded shadow-sm" <?php echo $checked; ?>>
                          <span class="truncate"><?php echo $d['department_name']; ?></span>
                        </label>
                        <?php } ?>
                      </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-4 pt-4 border-t border-gray-100">
                      <button type="submit" name="update" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-6 py-2.5 sm:py-3 shadow-md transition-all duration-200 w-full sm:w-auto transform hover:scale-105 text-sm sm:text-base">
                        Update Folder
                      </button>
                      <button type="button" onclick="$('#modalEditFolder<?php echo $row['folder_id']; ?>').addClass('hidden');" 
                              class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-6 py-2.5 sm:py-3 transition-all duration-200 w-full sm:w-auto text-sm sm:text-base">
                        Close
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




      
      <!-- SEARCH RESULTS -->
      <div id="searchResults" class="mt-6 p-4 rounded-xl bg-blue-50 border border-blue-200 hidden"></div>
    </div>
  </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 3px; }
.custom-scrollbar::-webkit-scrollbar-thumb { 
  background: linear-gradient(45deg, #464948); 
  border-radius: 3px; 
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #059669; }
</style>

<script>
function toggleMenu(id) {
  const menu = document.getElementById('menu-' + id);
  document.querySelectorAll('[id^="menu-"]').forEach(el => {
    if (el !== menu) el.classList.add('hidden', 'scale-95', 'opacity-0');
  });
  menu.classList.toggle('hidden');
  if (!menu.classList.contains('hidden')) {
    setTimeout(() => menu.classList.remove('scale-95', 'opacity-0'), 10);
  } else {
    setTimeout(() => menu.classList.add('scale-95', 'opacity-0'), 10);
  }
}

function openEditModal(id) {
  $('#modalEditFolder' + id).removeClass('hidden');
  document.body.classList.add('overflow-hidden');
}

document.addEventListener('click', function(e) {
  document.querySelectorAll('[id^="menu-"]').forEach(menu => {
    const btn = menu.previousElementSibling;
    if (!menu.contains(e.target) && !btn?.contains(e.target)) {
      menu.classList.add('hidden', 'scale-95', 'opacity-0');
    }
  });
});
</script>



<!-- ADD POST MODAL -->
<!-- Modal Background -->
<div id="modalAddFolder" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm p-4">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-lg p-4 sm:p-6 animate-fadeIn relative">

    <!-- Close Button -->
    <button onclick="closeModal('modalAddFolder')" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-xl sm:text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-plus text-green-600"></i> Add Post
    </h3>

    <!-- Form -->
    <form id="addFolderForm" method="POST" class="flex flex-col gap-4">

      <!-- Folder Name -->
      <input type="text" name="folder_name" id="folder_name_input" placeholder="Folder Name" 
             class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition w-full" required>
      <p id="folderError" class="text-red-600 text-sm mt-1 hidden">Folder already exists!</p>

      <!-- Assign Departments -->
      <label class="font-medium text-gray-700">Assign Departments</label>
      <div class="flex flex-col gap-2 max-h-40 overflow-y-auto p-2 border rounded-lg">
        <?php
        $dept = mysqli_query($conn,"SELECT * FROM departments");
        while($d=mysqli_fetch_array($dept)){
        ?>
        <label class="flex items-center gap-2">
          <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>" class="accent-green-600">
          <?php echo $d['department_name']; ?>
        </label>
        <?php } ?>
      </div>

      <!-- Buttons -->
      <div class="flex flex-col sm:flex-row justify-end gap-3 mt-4">
        <button type="submit" 
                class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200 w-full sm:w-auto">
          Create Folder
        </button>

        <!-- <button type="button" onclick="closeModal('modalAddFolder')" 
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200 w-full sm:w-auto">
        </button> -->
      </div>

    </form>
  </div>
</div>

<!-- Modal Script -->
<script>
  function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
  }
  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }
</script>

<!-- Tailwind Animation (add in your CSS or inside <style>) -->
<style>
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
  }
</style>

<!-- ARCHIVED FOLDERS MODAL -->
<div id="modalArchivedFolders" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-3/4 max-w-3xl max-h-[80vh] p-6 overflow-auto relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalArchivedFolders').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-archive text-yellow-500"></i> Archived Folders
    </h3>

    <!-- Modal Content -->
    <div id="archivedContent" class="overflow-auto max-h-[60vh]">
      Loading archived folders...
    </div>

  </div>
</div>

<!-- Tailwind Keyframe Animation -->
<style>
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
  }
</style>

<script>
function openArchivedFolders() {
    // Show modal
    $('#modalArchivedFolders').removeClass('hidden');
    
    // Load archived folders via AJAX
    $('#archivedContent').html('Loading archived folders...');
    $('#archivedContent').load('load_archived_folders.php');
}
</script>

<script>
function confirmArchive(id){
    Swal.fire({
        title: 'Archive Folder?', // one line title
        html: '<p style="font-size: 0.9rem; margin: 0;">This folder will be moved to archive.</p>',
        icon: null,
        width: '350px',
        showCancelButton: true,
        confirmButtonText: 'Archive',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        customClass: {
            popup: 'swal-custom-popup',
            title: 'swal-title-nowrap'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "archive_folder.php?id=" + id;
        }
    });
}

function confirmLogout(el) {
    Swal.fire({
        title: 'Are you sure you want to logout?', // one line
        html: '<p style="font-size: 0.9rem; margin: 0;">You will be logged out of your account.</p>',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Logout',
        cancelButtonText: 'Cancel',
        backdrop: `
            rgba(0,0,0,0.4)
            url("img/lg.flip-book-loader.gif") 
            center top
            no-repeat
            blur(3px)
        `,
        customClass: {
            popup: 'swal-custom-popup',
            title: 'swal-title-nowrap'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'Logout.php';
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if(isset($_SESSION['success'])): ?>
Swal.fire({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    icon: 'success',
    title: '<?php echo $_SESSION['success']; ?>'
});
<?php unset($_SESSION['success']); endif; ?>

<?php if(isset($_SESSION['error'])): ?>
Swal.fire({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    icon: 'error',
    title: '<?php echo $_SESSION['error']; ?>'
});
<?php unset($_SESSION['error']); endif; ?>
</script>



<script>
$(document).ready(function() {
  $('#addFolderForm').on('submit', function(e) {
    e.preventDefault(); // prevent normal form submission

    var folderName = $('#folder_name_input').val();
    var departments = [];

    // Only checkboxes inside Add Post form
    $('#addFolderForm input[name="departments[]"]:checked').each(function() {
      departments.push($(this).val());
    });

    $.ajax({
      url: 'save_folder.php',
      type: 'POST',
      data: {
        folder_name: folderName,
        departments: departments
      },
      success: function(response) {
        if (response.trim() === 'exists') {
          $('#folderError').removeClass('hidden'); // show error
        } else if (response.trim() === 'success') {
          $('#folderError').addClass('hidden');
          location.reload();
        }
      }
    });
  });
});
</script>

<script>
function confirmLogout(el) {
    // Get button position (optional for toast positioning)
    const rect = el.getBoundingClientRect();

    Swal.fire({
        title: 'Are you sure you want to logout?',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Logout',
        cancelButtonText: 'Cancel',
        backdrop: `
            rgba(0,0,0,0.4)
            url("img/lg.flip-book-loader.gif") 
            center top
            no-repeat
            blur(3px)
        `
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to logout.php
            window.location.href = 'Logout.php';
        }
    });
}
</script>

<script>
function toggleMenuFileSearch(id){
    document.querySelectorAll('[id^="menu-file-search-"]').forEach(el => {
        if(el.id !== 'menu-file-search-' + id){
            el.classList.add('hidden');
        }
    });

    const menu = document.getElementById('menu-file-search-' + id);
    if(menu){
        menu.classList.toggle('hidden');
    }
}


function toggleFiles(folder_id) {
    const row = document.getElementById('files-' + folder_id);
    const container = document.getElementById('files-content-' + folder_id);

    if (!row) return;

    // Toggle visibility
    if (!row.classList.contains('hidden')) {
        row.classList.add('hidden');
        return;
    }

    row.classList.remove('hidden');

    // Load files only once
    if (container.innerHTML.trim() === 'Loading files...') {
        $.ajax({
            url: 'load_folder_files.php',
            type: 'GET',
            data: { folder_id: folder_id },
            success: function(response) {
                container.innerHTML = response;
            }
        });
    }
}
</script>

<script>
function performSearch() {
    let keyword = $("#globalSearch").val().trim();

    $.ajax({
        url: "search_files_folders.php",
        type: "POST",
        data: { keyword: keyword },
        success: function(response) {
            // Replace table rows instead of separate div
            $("#dtable tbody").html(response);
        }
    });
}
</script>

<!-- Footer -->
<footer class="mt-10 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>


</body>
</html>