<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
require_once("../include/connection.php");

/// Archive admin
if(isset($_GET['archive_id'])){
    $archive_id = mysqli_real_escape_string($conn, $_GET['archive_id']);
    mysqli_query($conn, "UPDATE admin_login SET admin_status='Archived' WHERE id='$archive_id'") or die(mysqli_error($conn));
    
    // Set a toast session
    $_SESSION['toast'] = 'Admin Archived Successfully!';
    header('Location: view_admin.php');
    exit();
} 

// Unarchive admin
if(isset($_GET['unarchive_id'])){
    $unarchive_id = mysqli_real_escape_string($conn, $_GET['unarchive_id']);
    mysqli_query($conn, "UPDATE admin_login SET admin_status='' WHERE id='$unarchive_id'") or die(mysqli_error($conn));
    
    // Set a toast session
    $_SESSION['toast'] = 'Admin Unarchived Successfully!';
    header('Location: view_admin.php');
    exit();
}

$edit_id = '';
if(isset($_GET['id'])){
    $edit_id = mysqli_real_escape_string($conn,$_GET['id']);
}

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.php');
    exit();
}
$adminName = $_SESSION['admin_name'];
// EDIT ADMIN FUNCTIONALITY (unchanged)
if (isset($_POST['edit_publish'])) {
    $id_post = mysqli_real_escape_string($conn, $_POST['idtoy']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $admin_user = mysqli_real_escape_string($conn, $_POST['admin_user']);
    $admin_password_raw = $_POST['admin_password'];


    // Password validation regex
    $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/';

    // Build the query
    $update_query = "UPDATE admin_login SET name='$name', admin_user='$admin_user'";

    // Only update password if it's not empty
    if (!empty($admin_password_raw)) {
        if (!preg_match($pattern, $admin_password_raw)) {
            echo "<script>alert('Password must be at least 8 characters, include uppercase, lowercase, number, and a symbol.'); window.history.back();</script>";
            exit();
        } 
        $admin_password_hashed = password_hash($admin_password_raw, PASSWORD_DEFAULT);
        $update_query .= ", admin_password='$admin_password_hashed'";
    }

    $update_query .= " WHERE id='$id_post'";

    mysqli_query($conn, $update_query) or die(mysqli_error($conn));

 // Set a toast session
$_SESSION['toast'] = 'Admin updated successfully!';
header('Location: view_admin.php');
exit();
}
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bayung Porac Archive</title>
  <link rel="icon" type="image/png" href="js/img/municipalLogo.png">

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
    .sidebar-link:hover { @apply bg-gray-100; }
  </style>

  <script>
    $(document).ready(function(){
      $('#table3').DataTable({
  paging: false,
  lengthChange: false,
  info: false
});

      $(window).on('load', function(){ $('#loader').fadeOut('slow'); });

      <?php if($edit_id != '') { ?>
        $('#modalEditAdmin').removeClass('hidden');
      <?php } ?>
    });
  </script>
</head>


<body class="bg-gray-100 font-sans">
<!--
<div id="loader" class="fixed inset-0 bg-white flex justify-center items-center z-50">
  <div class="flex space-x-2">
    <span class="dot animate-bounce-delay bg-green-600 w-4 h-4 rounded-full"></span>
    <span class="dot animate-bounce-delay bg-green-600 w-4 h-4 rounded-full animation-delay-100"></span>
    <span class="dot animate-bounce-delay bg-green-600 w-4 h-4 rounded-full animation-delay-200"></span>
  </div>
</div>
-->
<!-- Page Content -->
<div id="page-content" class="opacity-0 transition-opacity duration-500">
  <!-- your full page content here -->
</div>

<style>
/* Bounce animation */
@keyframes bounce {
  0%, 80%, 100% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-10px); /* Slightly less bounce */
  }
}

.dot {
  display: inline-block;
  animation: bounce 1s infinite ease-in-out;
}

.animation-delay-100 {
  animation-delay: 0.1s;
}

.animation-delay-200 {
  animation-delay: 0.2s;
}
</style> 

<!-- NAVBAR -->
<nav class="fixed top-0 w-full bg-green-700 shadow-lg z-50">
  <div class="flex justify-between items-center h-16 px-4 sm:px-6">
    <!-- Left side: Mobile toggle + Logo + Title -->
    <div class="flex items-center space-x-3 min-w-0">
      <!-- Mobile Toggle Button -->
      <button id="sidebarToggle" class="lg:hidden bg-transparent/0 backdrop-blur-sm p-2.5 rounded-lg shadow-md border-2 border-white/20 p-2.5 w-11 h-11 flex items-center justify-center shrink-0 z-10 outline-none focus:outline-none active:bg-transparent/0 transition-none hover:bg-transparent">
  <i id="toggleIcon" class="fas fa-bars text-white/90 text-lg transition-none"></i>
</button>
      <!-- Logo + Title -->
      <div class="flex items-center space-x-3 min-w-0 flex-1">
        <img src="js/img/municipalLogo.png" class="w-9 h-9 object-contain flex-shrink-0">
        <h1 class="text-white font-semibold text-base sm:text-lg truncate">Bayung Porac Archive</h1>
      </div>
    </div>

    <!-- Right side: Welcome + Logout -->
    
  </div>
</nav>

<!-- MAIN LAYOUT -->
<div class="mt-24 px-6 flex gap-6">


  <!-- SIDEBAR -->
 <aside id="sidebar" class="lg:w-1/4 w-72 lg:h-[650px] fixed lg:static inset-y-0 left-0 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:flex lg:flex-col">
  <!-- NO SHADOW behind + Ultra smooth desktop corners -->
  <div class="bg-white/95 backdrop-blur-lg rounded-3xl lg:rounded-[3rem] shadow-2xl lg:shadow-2xl p-1 sm:p-3 lg:p-8 border border-gray-200/50 flex flex-col h-screen lg:h-[650px] items-center relative overflow-hidden">
    
    <!-- Mobile Close Button -->
    <button onclick="toggleSidebar()" class="lg:hidden absolute top-1 right-1 text-gray-500 text-lg font-bold z-20 p-1 rounded-xl transition-none hover:bg-transparent">
      <i class="fas fa-times"></i>
    </button>

    <!-- Logo & Menu Container -->
    <div id="sidebarContent" class="flex flex-col w-full h-full pt-4 lg:pt-6 transition-all duration-500 ease-out">
      
      <!-- LOGO -->
      <div class="mb-2 lg:mb-8 w-full flex justify-center px-1">
        <img src="img/adminLogo.png"
             class="w-32 h-32 sm:w-36 sm:h-36 lg:w-40 lg:h-40 xl:w-44 xl:h-44 object-cover rounded-3xl transition-all duration-300 hover:scale-105 shadow-2xl border-4 border-white/90 mx-auto">
      </div>

      <!-- Welcome Section -->
      <div class="w-full px-2 sm:px-4 mb-2 lg:mb-8 flex flex-col items-center gap-1.5 lg:gap-3 text-dark text-xs sm:text-sm lg:text-base font-medium">
        
        <!-- Admin Name -->
        <span class="welcome-text text-center truncate max-w-[260px] sm:max-w-none bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent text-xs sm:text-sm lg:text-2xl font-bold tracking-wide leading-tight">
          <?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?>
        </span>
        
        <!-- Logout Button -->
        <a href="#" onclick="confirmLogout(this)" 
           class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3.5 py-1 rounded-xl hover:from-green-600 hover:to-emerald-600 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 font-semibold text-xs whitespace-nowrap shadow-lg border-0 backdrop-blur-sm w-fit mx-auto hover:scale-105">
          Log out
        </a>
      </div>

      <!-- Menu - NO SCROLLBAR desktop + All content visible -->
      <nav class="w-full flex-1 px-2 sm:px-3 space-y-1.5 lg:space-y-3 overflow-y-auto lg:overflow-visible scrollbar-thin scrollbar-thumb-gray-300/70 scrollbar-track-transparent lg:max-h-none max-h-[calc(100vh-240px)] lg:max-h-none">
        
        <!--Home Page-->
        <a href="homepage_management.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-blue-200/50 backdrop-blur-sm">
           <i class="fas fa-home text-gray-500 group-hover:text-blue-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate">Home Page</span>
        </a>

        <!-- Offices - Fixed icon color -->
        <a href="department_management.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-purple-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-purple-200/50 backdrop-blur-sm">
           <i class="fas fa-building text-gray-500 group-hover:text-green-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 shadow-sm rounded p-0.5 bg-white/50 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate text-gray-800">Offices</span>
        </a>

        <!--Employees-->
        <a href="view_user.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-indigo-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-indigo-200/50 backdrop-blur-sm">
           <i class="fas fa-users text-gray-500 group-hover:text-indigo-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate">Employees</span>
        </a>

        <!-- Records Administrators - Active style -->
        <a href="view_admin.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl 
           bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 shadow-md border border-green-200/60
           hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:shadow-2xl hover:-translate-y-1 hover:border-green-300/80
           transition-all duration-300 text-xs sm:text-sm lg:text-base backdrop-blur-sm">
           <i class="fas fa-user-shield text-purple-500 group-hover:text-purple-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300 shadow-sm rounded p-0.5 bg-white/50"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate text-green-800">Records Administrators</span>
        </a>

        <!--System Administrators-->
        <a href="system-administrator.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-indigo-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-indigo-200/50 backdrop-blur-sm">
           <i class="fas fa-server text-gray-500 group-hover:text-indigo-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate">System Administrators</span>
        </a>
      </nav>
    </div>
  </div>
</aside>

<style>
/* Custom Scrollbar - Ultra Compact */
#sidebar nav::-webkit-scrollbar {
  width: 3px;
}
#sidebar nav::-webkit-scrollbar-track {
  background: transparent;
  border-radius: 6px;
}
#sidebar nav::-webkit-scrollbar-thumb {
  background: rgba(156, 163, 175, 0.8);
  border-radius: 6px;
}
#sidebar nav::-webkit-scrollbar-thumb:hover {
  background: rgba(107, 114, 128, 1);
}

/* Smooth scrolling */
#sidebar nav {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.8) transparent;
}
</style>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="lg:hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-20 hidden transition-all duration-300" onclick="toggleSidebar()"></div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    const content = document.getElementById('sidebarContent');
    const icon = toggleBtn?.querySelector('i');
    
    sidebar.classList.toggle('-translate-x-full');
    
    if (sidebar.classList.contains('-translate-x-full')) {
        // Closing - move content back up
        content.classList.remove('pt-[30vh]');
        content.classList.add('pt-0');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (icon) {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
        toggleBtn?.classList.remove('bg-green-50', 'ring-2', 'ring-green-300', 'scale-110');
    } else {
        // Opening - move content down 30%
        content.classList.remove('pt-0');
        content.classList.add('pt-[30vh]');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        if (icon) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        }
        toggleBtn?.classList.add('bg-green-50', 'ring-2', 'ring-green-300', 'scale-110');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('sidebarToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }
});

// Auto-close on link click (mobile)
document.addEventListener('click', function(e) {
    if (window.innerWidth < 1024 && e.target.closest('#sidebar a')) {
        setTimeout(toggleSidebar, 150);
    }
});

// Resize handler
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        if (window.innerWidth >= 1024) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const content = document.getElementById('sidebarContent');
            const toggleBtn = document.getElementById('sidebarToggle');
            const icon = toggleBtn?.querySelector('i');
            
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
            content.classList.remove('pt-[30vh]');
            content.classList.add('pt-0');
            document.body.classList.remove('overflow-hidden');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
            toggleBtn?.classList.remove('bg-green-50', 'ring-2', 'ring-green-300', 'scale-110');
        }
    }, 250);
});

// Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar.classList.contains('-translate-x-full')) {
            toggleSidebar();
        }
    }
});
</script>

 <!-- MAIN CONTENT -->
<div class="lg:w-3/4 w-full p-1 h-auto lg:h-[calc(79vh-2rem)] min-h-[500px]">
  <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 h-auto lg:h-[650px] transition-all duration-300 hover:shadow-xl">
    
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4 gap-4 lg:gap-0">
      
      <!-- Title -->
      <h2 class="text-xl sm:text-2xl font-semibold text-gray-700 flex items-center gap-2 order-2 lg:order-1">
        <i class="fas fa-user-shield text-green-600"></i> Add Record Admin
      </h2>

      <!-- Button group -->
      <div class="flex flex-wrap items-center gap-2 lg:gap-4 order-1 lg:order-2 justify-center lg:justify-end">
        
        <!-- Add Admin -->
        <button onclick="$('#modalAddAdmin').removeClass('hidden');" 
                class="bg-gradient-to-r from-green-600 to-green-500 text-white px-3 sm:px-5 py-2 lg:py-2.5 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300 text-sm sm:text-base whitespace-nowrap">
          <i class="fas fa-user-plus"></i> Add Admin
        </button>

        <!-- Archived Admins -->
        <button onclick="$('#modalArchivedAdmins').removeClass('hidden');" 
                class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-white px-3 sm:px-5 py-2 lg:py-2.5 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300 text-sm sm:text-base whitespace-nowrap">
          <i class="fas fa-archive"></i> View Archived Admins
        </button>

      </div>

    </div>

    <!-- TABLE -->
    <div class="max-h-[400px] lg:max-h-[650px] overflow-y-auto overflow-x-auto lg:overflow-x-hidden">
      <table id="table3" class="w-full border border-gray-200 min-w-full lg:min-w-0">
          <thead class="bg-green-700 text-white sticky top-0 z-10">
            <tr>
              <th class="px-2 sm:px-4 lg:px-4 py-2 text-xs sm:text-sm lg:text-base">Full Name</th>
              <th class="px-2 sm:px-4 lg:px-4 py-2 text-xs sm:text-sm lg:text-base">Email Address</th>
              <th class="px-2 sm:px-4 lg:px-4 py-2 text-xs sm:text-sm lg:text-base text-center">Verification</th>
              <th class="px-2 sm:px-4 lg:px-4 py-2 text-xs sm:text-sm lg:text-base text-center">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 divide-y divide-gray-200">
          <?php
            $query = "SELECT * FROM admin_login 
          WHERE admin_status != 'Archived' 
          AND role = 'Records Administrator'";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)){
          ?>
            <tr class="hover:bg-gray-50 transition-colors border-b lg:border-b-0">
              <td class="px-2 sm:px-4 lg:px-4 py-3 lg:py-2 text-xs sm:text-sm"><?php echo htmlspecialchars($row['name']); ?></td>
              <td class="px-2 sm:px-4 lg:px-4 py-3 lg:py-2 text-xs sm:text-sm"><?php echo htmlspecialchars($row['admin_user']); ?></td>
              <td class="px-2 sm:px-4 lg:px-4 py-3 lg:py-2 text-center">
                <?php if($row['otp_verified'] == 1){ ?>
                  <span class="bg-green-100 text-green-700 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                    Verified
                  </span>
                <?php } else { ?>
                  <span class="bg-red-100 text-red-700 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                    Not Verified
                  </span>
                <?php } ?>
              </td>
              <td class="px-2 sm:px-4 lg:px-4 py-3 lg:py-2 text-center">
                <div class="relative flex justify-center">
                  
                  <!-- 3 DOT BUTTON -->
                  <button onclick="toggleMenu(<?php echo $row['id']; ?>, this)" 
                          class="p-2 rounded-full hover:bg-gray-200 transition z-10 flex items-center justify-center w-10 h-10 lg:p-1 lg:w-auto lg:h-auto">
                    <i class="fas fa-ellipsis-h text-sm"></i>
                  </button>

                  <!-- DROPDOWN MENU -->
                  <div id="menu<?php echo $row['id']; ?>" 
                       class="hidden absolute right-0 sm:right-full sm:mr-2 top-1/2 -translate-y-1/2 lg:top-full lg:-translate-y-0 lg:left-1/2 lg:-translate-x-1/2 lg:right-auto
                              bg-white shadow-lg rounded-xl lg:rounded-lg py-2 w-32 sm:w-36 lg:w-32
                              opacity-0 scale-95 transition-all duration-200 border border-gray-200 z-20">
                    
                    <!-- EDIT -->
                    <a href="view_admin.php?id=<?php echo $row['id']; ?>"
                       onclick="closeMenu(<?php echo $row['id']; ?>)"
                       class="flex items-center gap-2 lg:gap-1 w-full px-3 py-2 hover:bg-gray-100 text-blue-600 text-xs sm:text-sm">
                      <i class="fas fa-edit"></i> Edit
                    </a>

                    <!-- ARCHIVE -->
                    <button onclick="confirmArchiveAdmin(<?php echo $row['id']; ?>); closeMenu(<?php echo $row['id']; ?>)"
                            class="flex items-center gap-2 lg:gap-1 w-full px-3 py-2 hover:bg-gray-100 text-red-600 text-xs sm:text-sm">
                      <i class="fas fa-archive"></i> Archive
                    </button>

                    <!-- VERIFY OTP -->
                    <button type="button"
                      onclick="openOtpModal(
                        <?php echo htmlspecialchars(json_encode($row['admin_user']), ENT_QUOTES, 'UTF-8'); ?>, 
                        <?php echo (int)$row['otp_verified']; ?>
                      ); closeMenu(<?php echo $row['id']; ?>)"
                      class="flex items-center gap-2 lg:gap-1 w-full px-3 py-2 hover:bg-gray-100 text-yellow-600 text-xs sm:text-sm">
                      <i class="fas fa-check"></i> Verify
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
  </div>
</div>

<script>
function toggleMenu(id, btn) {
    let menu = document.getElementById("menu" + id);

    // close other menus
    document.querySelectorAll("[id^='menu']").forEach(m => {
        if (m.id !== "menu" + id) {
            m.classList.add("hidden", "opacity-0", "scale-95");
        }
    });

    if (!menu.classList.contains("hidden")) {
        closeMenu(id);
        return;
    }

    // Default: open left
    menu.classList.remove("hidden");
    menu.style.right = "100%";
    menu.style.left = "auto";
    menu.classList.remove("opacity-0", "scale-95");
    menu.classList.add("opacity-100", "scale-100");

    // Check if it overflows the table/container
    const rect = menu.getBoundingClientRect();
    if (rect.left < 0) {
        // Not enough space on left → open to the right
        menu.style.left = "100%";
        menu.style.right = "auto";
    }
}

function closeMenu(id) {
    let menu = document.getElementById("menu" + id);
    menu.classList.remove("opacity-100", "scale-100");
    menu.classList.add("opacity-0", "scale-95");

    setTimeout(() => {
        menu.classList.add("hidden");
    }, 150);
}

// Click outside = close
document.addEventListener("click", function(e) {
    document.querySelectorAll("[id^='menu']").forEach(menu => {
        if (!menu.parentElement.contains(e.target)) {
            menu.classList.add("hidden", "opacity-0", "scale-95");
        }
    });
});
</script>

<!-- ADD ADMIN MODAL -->
<!-- Add Admin Modal -->
<div id="modalAddAdmin" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[90%] max-w-md p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalAddAdmin').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-user-plus text-green-600"></i> Add Admin
    </h3>

    <!-- Form -->
    <form action="create_Admin.php" method="POST" class="flex flex-col gap-4">
      <input type="hidden" name="status" value="Admin">

      <input type="text" name="name" placeholder="Full Name" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>
      <input type="email" name="admin_user" placeholder="Email Address" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>
    <div class="relative">
<input type="password" 
id="admin_password" 
name="admin_password"  
placeholder="Password"
class="border border-gray-300 rounded-lg px-4 py-2 pr-10 w-full focus:ring-2 focus:ring-green-500 focus:outline-none transition"
pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
title="Password must be at least 8 characters, include uppercase, lowercase, number and a symbol">

<button type="button"
onclick="togglePassword('admin_password','toggleIconAdd')"
class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800">

<i id="toggleIconAdd" class="fas fa-eye"></i>

</button>
</div>
<!-- Inline validation message -->
<p id="passwordHelp" class="text-red-600 text-sm mt-1 hidden">
  Password must be at least 8 characters, include uppercase, lowercase, number, and a symbol.
</p>

<script>
const passwordInput = document.getElementById('admin_password');
const passwordHelp = document.getElementById('passwordHelp');

passwordInput.addEventListener('input', function() {
    const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;
    if (passwordInput.value === "" || pattern.test(passwordInput.value)) {
        passwordHelp.classList.add('hidden'); // hide message if valid or empty
    } else {
        passwordHelp.classList.remove('hidden'); // show message if invalid
    }
});
</script>

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="reg" class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">Save</button>
        <button type="button" onclick="document.getElementById('modalAddAdmin').classList.add('hidden');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">Close</button>
      </div>
    </form>
  </div>
</div>


<!--OTP MODAL--->
<div id="otpModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md relative">

    <button onclick="closeOtpModal()" class="absolute top-3 right-4 text-xl">&times;</button>

    <h2 class="text-xl font-semibold mb-4">Verify Account</h2>

    <!-- Email display -->
    <p class="text-sm mb-3 text-gray-600">
      Email: <span id="otp_email_display" class="font-semibold"></span>
    </p>

    <!-- VERIFIED MESSAGE -->
    <div id="verifiedMessage" class="hidden text-center">
      <p class="text-green-600 font-semibold text-lg">
        ✅ Account already verified
      </p>
    </div>

    <!-- OTP FORM -->
    <form id="otpForm">
      <input type="hidden" id="otp_email">

      <div id="otpFormSection">
        <input type="text" id="otp_code" class="w-full border p-2 rounded mb-4" placeholder="Enter OTP" required>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">
          Verify
        </button>
      </div>
    </form>

    <p id="otpMessage" class="text-sm mt-2"></p>

  </div>
</div>
<!--OTP MODAL END--->

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

<!-- EDIT ADMIN MODAL -->
<?php 
if($edit_id != ''){
    $q = mysqli_query($conn,"SELECT * FROM admin_login WHERE id='$edit_id'");
    $rs = mysqli_fetch_assoc($q);
?>
<!-- Edit Admin Modal -->
<div id="modalEditAdmin" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[90%] max-w-md p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalEditAdmin').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-user-edit text-blue-600"></i> Edit Admin
    </h3>

    <!-- Form -->
  <form method="POST" class="flex flex-col gap-4">
      <input type="hidden" name="idtoy" value="<?php echo $rs['id']; ?>">

      <input type="text" name="name" value="<?php echo htmlspecialchars($rs['name']); ?>" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>
      <input type="email" name="admin_user" value="<?php echo htmlspecialchars($rs['admin_user']); ?>" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>

      <!-- Password Field with Validation -->
      <div class="relative">
<input type="password" 
name="admin_password" 
id="edit_admin_password" 
placeholder="Leave blank to keep current password"
class="border border-gray-300 rounded-lg px-4 py-2 pr-10 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
title="Password must be at least 8 characters, include uppercase, lowercase, number and a symbol">

<button type="button"
onclick="togglePassword('edit_admin_password','toggleIconEdit')"
class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800">

<i id="toggleIconEdit" class="fas fa-eye"></i>

</button>
</div>
      <!-- Inline validation message -->
      <p id="editPasswordHelp" class="text-red-600 text-sm mt-1 hidden">
        Password must be at least 8 characters, include uppercase, lowercase, number, and a symbol.
      </p>

      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="edit_publish" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">Update</button>
        <button type="button" onclick="document.getElementById('modalEditAdmin').classList.add('hidden');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">Close</button>
      </div>
    </form>
  </div>
</div>
<script>
const editPasswordInput = document.getElementById('edit_admin_password');
const editPasswordHelp = document.getElementById('editPasswordHelp');

editPasswordInput.addEventListener('input', function() {
    const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;
    if (editPasswordInput.value === "" || pattern.test(editPasswordInput.value)) {
        editPasswordHelp.classList.add('hidden'); // hide message if valid or empty
    } else {
        editPasswordHelp.classList.remove('hidden'); // show message if invalid
    }
});
</script>
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
<?php } ?>

<!-- ARCHIVED ADMINS MODAL -->
<!-- Archived Admins Modal -->
<div id="modalArchivedAdmins" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-11/12 max-w-4xl p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalArchivedAdmins').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-archive text-gray-700"></i> Archived Admins
    </h3>

    <!-- Table Content -->
    <div class="overflow-x-auto max-h-[60vh]">
      <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-700 text-white">
          <tr>
            <th class="px-4 py-2 text-left">Full Name</th>
            <th class="px-4 py-2 text-left">Email</th>
            <th class="px-4 py-2 text-center">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
        <?php
          $archived = mysqli_query($conn,"SELECT * FROM admin_login WHERE admin_status='Archived'");
          while($a = mysqli_fetch_assoc($archived)){
        ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2"><?php echo htmlspecialchars($a['name']); ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($a['admin_user']); ?></td>
            <td class="px-4 py-2 text-center">
              <a href="#" 
   class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" 
   onclick="confirmUnarchiveAdmin(<?php echo $a['id']; ?>)">
   <i class="fas fa-undo"></i> Unarchive
</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if(isset($_SESSION['toast'])): ?>
Swal.fire({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    icon: 'success',
    title: '<?php echo $_SESSION['toast']; ?>'
});
<?php unset($_SESSION['toast']); endif; ?>
</script>


<script>
function confirmArchiveAdmin(id) {
    Swal.fire({
        title: 'Archive Admin?',
        text: 'This admin will be moved to archive.',
        icon: null, // you can change to 'warning' or 'info' for a nicer look
        width: '350px',
        showCancelButton: true,
        confirmButtonText: 'Archive',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        showClass: {
            popup: ''
        },
        hideClass: {
            popup: ''
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "view_admin.php?archive_id=" + id;
        }
    });
}
</script>
<script>
function confirmUnarchiveAdmin(id) {
    Swal.fire({
        title: 'Unarchive Admin?',
        text: 'This admin will be restored from archive.',
        icon: null, // can be 'success' or 'info' for better visual
        width: '350px',
        showCancelButton: true,
        confirmButtonText: 'Unarchive',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#16a34a', // Tailwind green-600
        cancelButtonColor: '#6b7280',
        showClass: {
            popup: ''
        },
        hideClass: {
            popup: ''
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "view_admin.php?unarchive_id=" + id;
        }
    });
}
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
document.getElementById("otpForm").addEventListener("submit", function(e){
    e.preventDefault(); // 🚨 prevent page reload

    const email = document.getElementById("otp_email").value;
    const otp = document.getElementById("otp_code").value.trim(); // ✅ FIX: trim spaces

    fetch("verify_otp.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `email=${encodeURIComponent(email)}&otp=${encodeURIComponent(otp)}`
    })
    .then(res => res.text())
    .then(data => {
        const messageEl = document.getElementById("otpMessage");
        messageEl.innerText = data;

        if(data.includes("success")){
          messageEl.innerText = data;
            setTimeout(() => {
                window.location.href = "view_admin.php"; //
            }, 1000);
        } else {
            // ✅ FIX: reset input so user can try again properly
            const otpInput = document.getElementById("otp_code");
            otpInput.value = "";
            otpInput.focus();
             messageEl.innerText = ""; // clear first

        setTimeout(() => {
            messageEl.innerText = data; // re-assign after short delay
             }, 50);
        }
    })
    .catch(err => {
        console.error(err);
        document.getElementById("otpMessage").innerText = "Error verifying OTP";
    });
});
</script>





<script>
  function togglePassword(fieldId, iconId){

const passwordField = document.getElementById(fieldId);
const icon = document.getElementById(iconId);

if(passwordField.type === "password"){
    passwordField.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
}else{
    passwordField.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
}

}
</script>


<script>

function closeOtpModal(){
    document.getElementById("otpModal").classList.add("hidden");
}
</script>
<script>
function openOtpModal(email, verified){
    document.getElementById("otp_email").value = email;
    document.getElementById("otp_email_display").innerText = email;

    const formSection = document.getElementById("otpFormSection");
    const verifiedMessage = document.getElementById("verifiedMessage");

    if(verified == 1){
        // Show verified message
        formSection.classList.add("hidden");
        verifiedMessage.classList.remove("hidden");
    } else {
        // Show OTP form
        formSection.classList.remove("hidden");
        verifiedMessage.classList.add("hidden");
    }

    document.getElementById("otpModal").classList.remove("hidden");
}

function closeOtpModal(){
    document.getElementById("otpModal").classList.add("hidden");
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get("otp_email");

    if (email) {
        openOtpModal(email, 0); // not verified
    }

});
</script>



<!-- Footer -->
<footer class="mt-9 text-center text-gray-500 text-sm">

  <p class="text-gray-500">
&#169; All Rights Reserved. Developed by the PSU IT Interns.
</p>

</footer>

</body>
</html>