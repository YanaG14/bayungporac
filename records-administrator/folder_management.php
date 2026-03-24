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
<div id="loader" class="fixed inset-0 bg-white flex justify-center items-center z-50 transition-opacity duration-500">
  <div class="flex space-x-2">
    <span class="dot animate-bounce-delay bg-green-600 w-4 h-4 rounded-full"></span>
    <span class="dot animate-bounce-delay bg-green-600 w-4 h-4 rounded-full animation-delay-100"></span>
    <span class="dot animate-bounce-delay bg-green-600 w-4 h-4 rounded-full animation-delay-200"></span>
  </div>
</div>

<!-- Page Content -->
<div id="page-content" class="opacity-0 transition-opacity duration-500">
  <!-- your full page content here -->
</div>

<style>
/* Bounce animation */
@keyframes bounce {
  0%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-10px); }
}

.dot {
  display: inline-block;
  animation: bounce 1s infinite ease-in-out;
}

.animation-delay-100 { animation-delay: 0.1s; }
.animation-delay-200 { animation-delay: 0.2s; }
</style>

<script>
  window.addEventListener('load', function() {
    const loader = document.getElementById('loader');
    const pageContent = document.getElementById('page-content');

    // Start fade out
    loader.style.opacity = '0';

    // Wait for transition to finish
    setTimeout(() => {
      loader.style.display = 'none';
      pageContent.style.opacity = '1'; // Reveal page content
    }, 500); // matches transition duration
  });
</script>

<script>
// Wait for the page to fully load
window.addEventListener("load", function() {
  const loader = document.getElementById("loader");
  const content = document.getElementById("page-content");

  loader.classList.add("opacity-0"); // fade out loader
  setTimeout(() => {
    loader.style.display = "none"; // remove loader
    content.classList.remove("opacity-0"); // show page content
  }, 500); // match transition duration
});
</script>



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

    <!-- Right: User Info -->
    <div class="flex flex-col sm:flex-row items-center 
                gap-2 sm:space-x-4 text-white 
                text-sm sm:text-base text-center sm:text-left">

      <span>
        Welcome, <?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?>!
      </span>

      <a href="#" onclick="confirmLogout(this)" 
         class="bg-white text-green-800 border border-green-800 px-3 py-1 rounded 
                hover:bg-green-800 hover:text-white hover:border-white 
                transition-colors duration-300">
        Log out
      </a>

    </div>

  </div>
</nav>

<!-- MAIN LAYOUT -->
<div class="mt-24 px-4 sm:px-6 flex flex-col lg:flex-row gap-6">

  <!-- HAMBURGER BUTTON: only shows on mobile, centered -->
 <div class="lg:hidden w-full flex justify-start items-center mt-4 h-16 px-6">
    <button id="sidebarToggle" class="bg-green-700 text-white p-3 rounded-md text-lg">
        <i class="fas fa-bars"></i>
    </button>
    
</div>

  <!-- CONTAINER -->
  <div class=" h-[655px] w-[1440px] lg:w-3/4 flex-1 lg:ml-0"> 
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 h-full transition-all duration-300 hover:shadow-xl">
      <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4">

        <!-- HEADER -->
<h2 class="text-lg sm:text-xl font-semibold flex items-center gap-2 text-center sm:text-left">
  <i class="fas fa-layer-group text-green-500"></i>
  <span class="relative">
    POSTS
    <span class="absolute left-0 -bottom-1 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-600 rounded"></span>
  </span>
</h2>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
          <button onclick="$('#modalAddFolder').removeClass('hidden');" 
                  class="w-full sm:w-auto justify-center bg-gradient-to-r from-green-600 to-green-500 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300">
            <i class="fas fa-plus"></i> Add Post
          </button>

          <button onclick="openArchivedFolders();" 
                  class="w-full sm:w-auto justify-center bg-gradient-to-r from-yellow-500 to-yellow-400 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg transition-all duration-300 flex items-center gap-2">
            <i class="fas fa-archive"></i> View Archived Post
          </button>
        </div>
      </div>

      <script>
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const sidebarClose = document.getElementById('sidebarClose');

  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.remove('-translate-x-full');
  });

  sidebarClose.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
  });
</script>

      <!-- TABLE -->
      <div class="h-[560px] w-[1440px] overflow-y-auto overflow-x-hidden">
  <table id="dtable" class="min-w-full border-gray-200 table-auto">
    <thead class="bg-green-600 text-white">
      <tr>
        <th class="px-4 py-2 text-left">Folder Name</th>
        <th class="px-4 py-2 text-left">Departments</th>
        <th class="px-4 py-2 text-left">Date Created</th>
        <th class="px-4 py-2 text-center">Action</th>
        <th class="px-4 py-2 text-left">Files</th>
<th class="px-4 py-2 text-left">Uploaders</th>
<th class="px-4 py-2 text-left">File Dates</th>
      </tr>
    </thead>
    <tbody class="text-gray-700">
      <?php while($row=mysqli_fetch_array($query)){ ?>
      <tr class="border-b">
        
        <!-- Folder Name -->
  <td class="px-4 py-2 align-middle">
    <a href="add_document.php?folder_id=<?php echo $row['folder_id']; ?>" 
       class="flex items-center gap-2 text-gray-800 hover:text-green-700 truncate">
      <i class="fas fa-folder text-yellow-500"></i>
      <b class="truncate"><?php echo $row['folder_name']; ?></b>
    </a>
  </td>

  <!-- Departments -->
  <td class="px-4 py-2 align-middle text-xs sm:text-sm md:text-base break-words max-w-[220px]">
    <?php echo $row['departments']; ?>
  </td>

  <!-- Date Created -->
  <td class="px-4 py-2 align-middle">
    <?php echo $row['created_at']; ?>
  </td>

  <!-- Action Buttons -->
  <td class="px-4 py-2 align-middle">
  <div class="flex justify-center relative">

    <!-- 3 DOT BUTTON (HORIZONTAL) -->
    <button onclick="toggleMenu(<?php echo $row['folder_id']; ?>)"
      class="bg-gray-900 text-white px-3 py-2 rounded-xl hover:bg-gray-800 transition transform hover:scale-105 shadow-md">
      <i class="fas fa-ellipsis-h"></i>
    </button>

    <!-- DROPDOWN MENU -->
    <div id="menu-<?php echo $row['folder_id']; ?>"
      class="hidden absolute top-full mt-2 right-0 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 z-50
             transform scale-95 opacity-0 transition-all duration-200">

      <!-- EDIT -->
      <button onclick="openEditModal(<?php echo $row['folder_id']; ?>)"
        class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 rounded-t-2xl">
        <i class="fas fa-edit text-blue-500"></i>
        Edit
      </button>

      <!-- ARCHIVE -->
      <button onclick="confirmArchive(<?php echo $row['folder_id']; ?>)"
        class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2">
        <i class="fas fa-archive text-red-500"></i>
        Archive
      </button>

      <!-- DOWNLOAD -->
      <a href="download_folder.php?folder_id=<?php echo $row['folder_id']; ?>"
        class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 rounded-b-2xl">
        <i class="fas fa-download text-green-600"></i>
        Download
      </a>

    </div>

  </div>
</td>


<script>
  function toggleMenu(id) {
    const menu = document.getElementById('menu-' + id);

    // Close all other menus first
    document.querySelectorAll('[id^="menu-"]').forEach(el => {
      if (el !== menu) {
        el.classList.add('hidden', 'scale-95', 'opacity-0');
      }
    });

    // Toggle current menu
    if (menu.classList.contains('hidden')) {
      menu.classList.remove('hidden');

      // Animate open
      setTimeout(() => {
        menu.classList.remove('scale-95', 'opacity-0');
        menu.classList.add('scale-100', 'opacity-100');
      }, 10);

    } else {
      closeMenu(id);
    }
  }

  function closeMenu(id) {
    const menu = document.getElementById('menu-' + id);

    menu.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
      menu.classList.add('hidden');
    }, 150);
  }

  function openEditModal(id) {
    closeMenu(id);
    $('#modalEditFolder' + id).removeClass('hidden');
  }

  // CLICK OUTSIDE CLOSE
  document.addEventListener('click', function (event) {
    document.querySelectorAll('[id^="menu-"]').forEach(menu => {
      const button = menu.previousElementSibling;

      if (!menu.contains(event.target) && !button.contains(event.target)) {
        menu.classList.add('scale-95', 'opacity-0');
        setTimeout(() => menu.classList.add('hidden'), 150);
      }
    });
  });
</script>

<td class="px-4 py-2 hidden">
  <?php echo $row['file_names']; ?>
</td>

<td class="px-4 py-2 hidden">
  <?php echo $row['uploaders']; ?>
</td>

<td class="px-4 py-2 hidden">
  <?php echo $row['file_dates']; ?>
</td>
</tr>

      <!-- EDIT FOLDER MODAL -->
      <div id="modalEditFolder<?php echo $row['folder_id']; ?>" 
           class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50 p-4">
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-lg p-4 sm:p-6 relative">

          <!-- Close Button -->
          <button onclick="$('#modalEditFolder<?php echo $row['folder_id']; ?>').addClass('hidden');" 
                  class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

          <!-- Modal Title -->
          <h3 class="text-xl sm:text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
            <i class="fas fa-edit text-blue-600"></i> Edit Folder
          </h3>

          <!-- Form -->
          <form method="POST" action="update_folder.php" class="flex flex-col gap-4">
            <input type="hidden" name="folder_id" value="<?php echo $row['folder_id']; ?>">

            <!-- Folder Name -->
            <input type="text" name="folder_name" value="<?php echo $row['folder_name']; ?>" placeholder="Folder Name" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>

            <!-- Assign Departments -->
            <div class="mt-2">
              <label class="font-medium text-gray-700 mb-1 block">Assign Departments</label>
              <div class="flex flex-col gap-2 max-h-40 overflow-y-auto p-2 border rounded-lg">
                <?php
                $dept = mysqli_query($conn,"SELECT * FROM departments");
                while($d=mysqli_fetch_array($dept)){
                  $check = mysqli_query($conn,"SELECT * FROM folder_departments WHERE folder_id='".$row['folder_id']."' AND department_id='".$d['department_id']."'");
                  $checked = mysqli_num_rows($check)>0 ? "checked" : "";
                ?>
                <label class="flex items-center gap-2">
                  <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>" class="accent-blue-600" <?php echo $checked; ?>>
                  <?php echo $d['department_name']; ?>
                </label>
                <?php } ?>
              </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-3">
              <button type="submit" name="update" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200 w-full sm:w-auto
                      transform hover:scale-105">
                Update Folder
              </button>

              <button type="button" onclick="$('#modalEditFolder<?php echo $row['folder_id']; ?>').addClass('hidden');" 
                      class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200 w-full sm:w-auto">
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
        <button type="button" onclick="closeModal('modalAddFolder')" 
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200 w-full sm:w-auto">
          Close
        </button>
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
<!-- Archived Folders Modal -->
<div id="modalArchivedFolders" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-3/4 max-w-3xl max-h-[80vh] p-6 overflow-auto relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalArchivedFolders').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-archive text-gray-700"></i> Archived Folders
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


<!-- Footer -->
<footer class="mt-10 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>


</body>
</html>