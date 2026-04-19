<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if(!isset($_SESSION["email_address"])){
    header("location:../login.php");
    exit();
}

$user_id = $_SESSION['user_no'];
$user_department = $_SESSION['department_id'];
require_once("../include/connection.php");
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


// Fetch active folders
$query = mysqli_query($conn,"
SELECT 
f.folder_id, 
f.folder_name,
f.created_at,
GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') as departments,

GROUP_CONCAT(DISTINCT uf.name SEPARATOR ', ') as file_names,
GROUP_CONCAT(DISTINCT al.name SEPARATOR ', ') as uploaders,
GROUP_CONCAT(DISTINCT uf.timers SEPARATOR ', ') as file_dates

FROM folders f
LEFT JOIN folder_departments fd ON f.folder_id = fd.folder_id
LEFT JOIN departments d ON fd.department_id = d.department_id

LEFT JOIN upload_files uf ON f.folder_id = uf.folder_id AND uf.status='Active'
LEFT JOIN login_user al ON uf.email = al.id

WHERE 
f.folder_status='Active'
AND fd.department_id = '$user_department'  -- ✅ FILTER HERE

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
        

        </div>
      </div>

      <!-- Responsive Table Container -->
      <div class="w-full h-[calc(100%-120px)] sm:h-[calc(100%-140px)] lg:h-[560px] overflow-hidden rounded-xl border shadow-sm">
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
                            class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-400">
                      <i class="fas fa-ellipsis-h text-sm"></i>
                    </button>
                    
                    <div id="menu-<?php echo $row['folder_id']; ?>"
                         class="hidden absolute top-full mt-1.5 right-0 w-28 sm:w-32 bg-white rounded-lg shadow-lg border border-gray-100 z-50
                                transform scale-95 opacity-0 transition-all duration-200 origin-top-right">
                      
                   

                      <a href="download_folder.php?folder_id=<?php echo $row['folder_id']; ?>"
                         class="w-full flex items-center gap-2 px-3 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg transition-all duration-200 block">
                        <i class="fas fa-download text-green-600 text-xs"></i>
                        Download
                      </a>
                    </div>
                  </div>
                </td>

              </tr>

              
              </div>

              <?php } ?>

<div id="fileTableContainer" class="hidden mt-6 w-full overflow-auto rounded-xl border shadow-sm">
  <table class="min-w-full table-auto">
    
  <thead id="fileTableHead" class="bg-blue-100 text-black text-sm hidden">
      <tr>
        <th class="px-4 py-2 text-left">File Name</th>
        <th class="px-4 py-2 text-left">Departments</th>
        <th class="px-4 py-2 text-left">Uploader</th>
        <th class="px-4 py-2 text-left">Date Uploaded</th>
        <th class="px-4 py-2 text-center">Action</th>
      </tr>
    </thead>

    <tbody id="fileResultsBody">
      <!-- AJAX FILE RESULTS GO HERE -->
    </tbody>

  </table>
</div>





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
</script>




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

    if (keyword === "") {
        // ❌ hide header
        $("#fileTableHead").css("display", "none");

        $("#fileResultsBody").html("");
        return;
    }

    // ✅ FORCE show header (overrides Tailwind hidden)
    $("#fileTableHead").css("display", "table-header-group");

    $.ajax({
        url: "search_files_folders.php",
        type: "POST",
        dataType: "json",
        data: { keyword: keyword },
        success: function(res) {

            $("#fileResultsBody").html(res.files || `
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">
                        No files found
                    </td>
                </tr>
            `);
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