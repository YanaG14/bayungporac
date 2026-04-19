<!DOCTYPE html>
<html lang="en">
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

// Check if a folder is clicked
$selected_folder = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;

$query = mysqli_query($conn,"
SELECT 
  l.id,
  l.reference_no,
  l.subject,
  l.sender,
  l.source,
  l.date_received,
  l.status AS letter_status,

  -- File info
  GROUP_CONCAT(DISTINCT uf.file_path SEPARATOR ', ') as file_paths,
  GROUP_CONCAT(DISTINCT uf.name SEPARATOR ', ') as file_names,
  GROUP_CONCAT(DISTINCT uf.status SEPARATOR ', ') as file_statuses,

  -- Upload info
  GROUP_CONCAT(DISTINCT al.name SEPARATOR ', ') as uploaders,
  GROUP_CONCAT(DISTINCT uf.timers SEPARATOR ', ') as upload_dates

FROM letters l

LEFT JOIN upload_files uf 
  ON l.id = uf.id AND uf.status='Active'

LEFT JOIN login_user al 
  ON uf.email = al.id

GROUP BY l.id
ORDER BY l.date_received DESC
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
            LETTER COMMUNICATION
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
                     placeholder="Search letters..."
                     oninput="performSearch()"
                     class="w-full border border-gray-300 rounded-full pl-10 pr-4 py-2.5 sm:py-2.5 
                            focus:ring-2 focus:ring-green-300 focus:outline-none transition-all duration-200 shadow-sm">
            </div> 
          </div>

          <!-- Action Buttons (Mobile Stack) -->
          <div class="flex gap-1.5 sm:gap-2">
            <!-- ADD LETTER 
            <button onclick="$('#modalAddFolder').removeClass('hidden');"
            class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg flex items-center justify-center text-green-600 text-sm sm:text-base focus:outline-none">
            <i class="fas fa-plus"></i>
            </button>
-->
            <!-- ARCHIVED LETTERS 
<button onclick="openModal('modalArchivedLetters')"
class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg flex items-center justify-center text-red-600 text-sm sm:text-base focus:outline-none"
title="View Archived Letters">
  <i class="fas fa-archive"></i>
</button>
-->
          </div>

        </div>
      </div>
           <!-- Responsive Table Container -->
<div class="w-full h-[calc(100%-120px)] sm:h-[calc(100%-140px)] lg:h-[560px] overflow-hidden rounded-xl border shadow-sm">
  <div class="w-full h-full overflow-x-auto overflow-y-auto custom-scrollbar">
  <div id="letterContainer" class="space-y-4 overflow-y-auto h-full pr-2">

<?php
$files = mysqli_query($conn, "
SELECT 
  id,
  reference_no,
  subject,
  sender,
  source,
  date_received,
  created_at,
  file_type,
  file_name,
  status,
  letter_status
FROM letters
WHERE letter_status='Active'
ORDER BY created_at DESC
");
while($file = mysqli_fetch_array($files)) {
?>

<div onclick="window.location.href='view_letter.php?id=<?php echo $file['id']; ?>'"
     class="letter-card bg-white border rounded-xl shadow-sm p-4 hover:shadow-md transition cursor-pointer"
     data-filetype="<?php echo strtolower($file['file_type']); ?>"
     data-filename="<?php echo strtolower($file['file_name']); ?>">

  <!-- HEADER -->
  <div class="flex justify-between items-start">
    
    <div>
      <p class="text-xs text-gray-500">
        Ref. No. <?php echo $file['reference_no']; ?>
      </p>

      <h3 class="text-blue-600 font-semibold text-sm sm:text-base">
        <?php echo htmlentities($file['sender']); ?>
      </h3>

      <p class="text-sm text-gray-700">
        <?php echo htmlentities($file['subject']); ?>
      </p>
    </div>

    <div class="text-xs text-gray-500 text-right">
      <?php echo date('M d, Y h:i A', strtotime($file['created_at'])); ?>
    </div>

  </div>

  <!-- TAGS -->
  <div class="mt-3 flex gap-2 flex-wrap">

    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">
      <?php echo $file['source']; ?>
    </span>
<?php if(strtolower($file['status']) == 'open'){ ?>
  <span class="bg-red-200 text-red-700 text-xs px-2 py-1 rounded-full">
    <?php echo $file['status']; ?>
  </span>

<?php } elseif(strtolower($file['status']) == 'done'){ ?>
  <span class="bg-green-200 text-green-700 text-xs px-2 py-1 rounded-full">
    <?php echo $file['status']; ?>
  </span>

<?php } else { ?>
  <span class="bg-blue-200 text-gray-700 text-xs px-2 py-1 rounded-full">
    <?php echo $file['status']; ?>
  </span>
<?php } ?>
<span class="bg-blue-300 text-gray-700 text-xs px-2 py-1 rounded-full">
      <?php echo !empty($file['file_type']) ? $file['file_type'] : 'No type'; ?>
    </span>

  </div>

  <!-- FOOTER -->
  <div class="mt-3 flex justify-between items-center text-xs text-gray-500">

    <div>
  
    </div>

    <div class="flex gap-2">
 <!--
     
<a href="#"
   onclick="event.stopPropagation(); openEditModal(
     <?php echo $file['id']; ?>,
     '<?php echo addslashes($file['subject']); ?>',
     '<?php echo addslashes($file['reference_no']); ?>'
   );"
   class="text-green-600 hover:underline">
   Edit
</a>
       DOWNLOAD -->
       <!--
     <a href="communication_downloads.php?file_id=<?php echo $file['id']; ?>"
   onclick="event.stopPropagation();"
   class="text-green-600 hover:underline">
   Download
</a>

       ARCHIVE 
   <a href="communication_archive.php?file_id=<?php echo $file['id']; ?>"
   onclick="event.stopPropagation();"
   class="text-red-600 hover:underline">
   Archive
</a>
-->
    </div>

  </div>

</div>

<?php } ?>

</div>
  </div>
</div>
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
        url: "search_files_folders.php",
        type: "POST",
        data: { keyword: keyword },
        success: function(response) {
            $("#searchResults").html(response);
        }
    });
}
</script>

<!-- ACTION BUTTON -->
<script>
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


function closeModal(id){
  document.getElementById(id).classList.add('hidden');
}
</script>
 <!-- END ACTION BUTTON-->

  <!-- ARCHIVE-->
<script>
function openModal(id){
  document.getElementById(id).classList.remove('hidden');
}

function closeModal(id){
  document.getElementById(id).classList.add('hidden');
}

// Restore archived letter
function confirmUnarchiveLetter(id){
    Swal.fire({
        title: 'Restore Letter?',
        html: '<p style="font-size:0.9rem;margin:0;">This letter will be restored.</p>',
        showCancelButton: true,
        confirmButtonText: 'Restore',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if(result.isConfirmed){
            window.location = "communication_unarchive.php?letter_id=" + id;
        }
    });
}
</script>
 <!-- END ARCHINVE-->

<!-- Footer -->
<footer class="mt-10 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>


</body>
</html>