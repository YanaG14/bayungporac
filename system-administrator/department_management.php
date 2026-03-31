<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require_once("../include/connection.php");

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.php');
    exit();
}
$adminName = $_SESSION['admin_name'];
require_once("../include/connection.php");
// ADD DEPARTMENT
if(isset($_POST['save'])){
    $name = mysqli_real_escape_string($conn, $_POST['department_name']);
    $img = $_FILES['department_img']['name'];
    $tmp = $_FILES['department_img']['tmp_name'];

    $check = mysqli_query($conn,"SELECT * FROM departments WHERE department_name='$name'");
    if(mysqli_num_rows($check) > 0){
        $_SESSION['duplicate'] = "Department name already exist!";
    } else {
        if(!is_dir("department_images")){
            mkdir("department_images");
        }
        move_uploaded_file($tmp,"department_images/".$img);
        mysqli_query($conn,"INSERT INTO departments (department_name, department_img) VALUES ('$name','$img')");
        $_SESSION['added'] = "Department Successfully Added!";
    }
}

// UPDATE DEPARTMENT FROM MODAL
if(isset($_POST['update_department'])){
    $id = intval($_POST['update_department']);
    $name = mysqli_real_escape_string($conn, $_POST['department_name']);

    // Check duplicate except current ID
    $check = mysqli_query($conn,"SELECT * FROM departments 
                                 WHERE department_name='$name' 
                                 AND department_id != '$id'");

    if(mysqli_num_rows($check) > 0){
        $_SESSION['duplicate'] = "Department name already exist!";
    } else {
        if(!empty($_FILES['department_img']['name'])){
            $img = $_FILES['department_img']['name'];
            $tmp = $_FILES['department_img']['tmp_name'];
            move_uploaded_file($tmp,"department_images/".$img);

            mysqli_query($conn,"UPDATE departments 
                                SET department_name='$name', department_img='$img'
                                WHERE department_id='$id'");
        } else {
            mysqli_query($conn,"UPDATE departments 
                                SET department_name='$name'
                                WHERE department_id='$id'");
        }

       $_SESSION['success'] = "Department Updated Successfully!";
header("Location: department_management.php");
exit();
    }
}

// FETCH ARCHIVED DEPARTMENTS
$archived_query = mysqli_query($conn,"SELECT * FROM departments 
                                      WHERE department_status='Archived' 
                                      ORDER BY department_id DESC");

// FETCH ACTIVE DEPARTMENTS
$query = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active' ORDER BY department_id DESC");
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
  paging: false,        // ❌ removes Previous/Next
  lengthChange: false,   // ❌ removes "Show entries"
  info: false,           // ❌ removes "Showing 1 to..."
});
      $(window).on('load', function(){ $('#loader').fadeOut('slow'); });
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
<div class="mt-24 px-4 sm:px-6 flex flex-col lg:flex-row gap-4 lg:gap-6 min-h-screen">

<!-- SIDEBAR -->
<aside id="sidebar" class="lg:w-1/4 w-72 lg:h-[650px] fixed lg:static inset-y-0 left-0 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:flex lg:flex-col">
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

      <!-- Menu - NO SCROLLBAR + Full height on desktop -->
      <nav class="w-full flex-1 px-2 sm:px-3 space-y-1.5 lg:space-y-3 overflow-y-auto lg:overflow-visible scrollbar-thin scrollbar-thumb-gray-300/70 scrollbar-track-transparent lg:max-h-none max-h-[calc(100vh-240px)] lg:max-h-none">
        
        <!--Home Page-->
        <a href="homepage_management.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-blue-200/50 backdrop-blur-sm">
           <i class="fas fa-home text-gray-500 group-hover:text-blue-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate">Home Page</span>
        </a>

        <!-- Offices -->
        <a href="department_management.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl 
           bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 shadow-md border border-green-200/60
           hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:shadow-2xl hover:-translate-y-1 hover:border-green-300/80
           transition-all duration-300 text-xs sm:text-sm lg:text-base backdrop-blur-sm">
           <i class="fas fa-building text-green-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 shadow-sm rounded p-0.5 bg-white/50"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate text-green-800">Offices</span>
        </a>

        <!--Employees-->
        <a href="view_user.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-indigo-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-indigo-200/50 backdrop-blur-sm">
           <i class="fas fa-users text-gray-500 group-hover:text-indigo-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate">Employees</span>
        </a>

        <!--Records Administrators-->
        <a href="view_admin.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-purple-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-purple-200/50 backdrop-blur-sm">
           <i class="fas fa-user-shield text-gray-500 group-hover:text-purple-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate">Records Administrators</span>
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
      <i class="fas fa-building text-green-600"></i> Active Departments
    </h2>

    <!-- Buttons grouped with gap -->
    <div class="flex flex-wrap items-center gap-2 lg:gap-3 order-1 lg:order-2 justify-center lg:justify-end">
      <button onclick="document.getElementById('modalAddDepartment').classList.remove('hidden');" 
              class="bg-gradient-to-r from-green-600 to-green-500 text-white px-3 sm:px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300 text-sm sm:text-base whitespace-nowrap">
        <i class="fas fa-plus"></i> Add Department
      </button>

      <button onclick="document.getElementById('modalArchivedDepartments').classList.remove('hidden');" 
              class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-white px-3 sm:px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300 text-sm sm:text-base whitespace-nowrap">
        <i class="fas fa-archive"></i> Archived
      </button>
    </div>
  </div>
  
  <!-- TABLE -->
  <div class="max-h-[400px] lg:max-h-[450px] overflow-y-auto overflow-x-auto lg:overflow-x-hidden">
    <table id="dtable" class="w-full border border-gray-200 min-w-full lg:min-w-0">
        <thead class="bg-green-700 text-white sticky top-0 z-10">
          <tr>
            <th class="px-2 sm:px-4 py-2 text-xs sm:text-sm">ID</th>
            <th class="px-2 sm:px-4 py-2 text-xs sm:text-sm">Department Name</th>
            <th class="px-2 sm:px-4 py-2 text-xs sm:text-sm">Image</th>
            <th class="px-2 sm:px-4 py-2 text-xs sm:text-sm">Status</th>
            <th class="px-2 sm:px-4 py-2 text-xs sm:text-sm text-center">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 divide-y divide-gray-200">
          <?php while($row = mysqli_fetch_assoc($query)) { ?>
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm"><?php echo $row['department_id']; ?></td>
            <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm font-medium"><?php echo $row['department_name']; ?></td>
            <td class="px-2 sm:px-4 py-3"><img src="department_images/<?php echo $row['department_img']; ?>" class="w-12 h-12 sm:w-14 sm:h-14 lg:w-15 lg:h-15 object-cover rounded" alt="Dept Image"></td>
            <td class="px-2 sm:px-4 py-3">
              <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800"><?php echo $row['department_status']; ?></span>
            </td>
            <td class="px-2 sm:px-4 py-3 text-center">
              <div class="relative flex justify-center">
                <!-- 3 DOT BUTTON -->
                <button onclick="toggleMenu(<?php echo $row['department_id']; ?>)" 
                        class="p-2 rounded-full hover:bg-gray-200 transition z-10 flex items-center justify-center w-10 h-10">
                  <i class="fas fa-ellipsis-h text-sm"></i>
                </button>

                <!-- DROPDOWN MENU -->
                <div id="menu<?php echo $row['department_id']; ?>" 
                     class="hidden absolute right-0 sm:right-full sm:mr-2 top-1/2 -translate-y-1/2
                            bg-white shadow-lg rounded-xl py-2 w-32 sm:w-36
                            opacity-0 scale-95 transition-all duration-200 border border-gray-200">
                  <!-- EDIT -->
                  <button onclick="document.getElementById('modalEditDepartment<?php echo $row['department_id']; ?>').classList.remove('hidden'); closeMenu(<?php echo $row['department_id']; ?>)"
                          class="flex items-center gap-2 w-full px-3 py-2 hover:bg-gray-100 text-blue-600 text-sm">
                    <i class="fas fa-edit"></i> Edit
                  </button>

                  <!-- ARCHIVE -->
                  <button onclick="confirmArchiveDepartment(<?php echo $row['department_id']; ?>); closeMenu(<?php echo $row['department_id']; ?>)"
                          class="flex items-center gap-2 w-full px-3 py-2 hover:bg-gray-100 text-red-600 text-sm">
                    <i class="fas fa-archive"></i> Archive
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
</div>



<script>
function toggleMenu(id) {
    let menu = document.getElementById("menu" + id);

    // Close all other menus
    document.querySelectorAll("[id^='menu']").forEach(m => {
        if (m !== menu) {
            m.classList.add("hidden", "opacity-0", "scale-95");
        }
    });

    // Toggle current menu
    if (menu.classList.contains("hidden")) {
        menu.classList.remove("hidden");

        setTimeout(() => {
            menu.classList.remove("opacity-0", "scale-95");
            menu.classList.add("opacity-100", "scale-100");
        }, 10);
    } else {
        closeMenu(id);
    }
}

function closeMenu(id) {
    let menu = document.getElementById("menu" + id);
    menu.classList.add("opacity-0", "scale-95");

    setTimeout(() => {
        menu.classList.add("hidden");
    }, 200);
}

// Close when clicking outside
document.addEventListener("click", function(e) {
    if (!e.target.closest(".relative")) {
        document.querySelectorAll("[id^='menu']").forEach(menu => {
            menu.classList.add("hidden", "opacity-0", "scale-95");
        });
    }
});
</script>
              

<!-- ADD DEPARTMENT MODAL -->
<!-- Add Department Modal -->
<div id="modalAddDepartment" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[90%] max-w-md p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalAddDepartment').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-plus text-green-600"></i> Add Department
    </h3>

    <!-- Form -->
    <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
      <input type="text" name="department_name" placeholder="Department Name" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>
      <input type="file" name="department_img" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="save" class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">Save</button>
        <button type="button" onclick="document.getElementById('modalAddDepartment').classList.add('hidden');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">Close</button>
      </div>
    </form>
  </div>
</div>

<?php
// Generate edit modals for each active department
mysqli_data_seek($query, 0); // Reset pointer to the start
while($row_edit = mysqli_fetch_assoc($query)) { ?>
<div id="modalEditDepartment<?php echo $row_edit['department_id']; ?>" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm overflow-auto">
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[90%] max-w-md p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalEditDepartment<?php echo $row_edit['department_id']; ?>').classList.add('hidden');" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-edit text-blue-600"></i> Edit Department
    </h3>

    <!-- Edit Form -->
    <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
      <input type="hidden" name="department_id" value="<?php echo $row_edit['department_id']; ?>">
      
      <label class="font-semibold">Department Name</label>
      <input type="text" name="department_name" value="<?php echo $row_edit['department_name']; ?>" 
             class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>
      
      <label class="font-semibold">Current Image</label>
      <img src="department_images/<?php echo $row_edit['department_img']; ?>" width="80">

      <label class="font-semibold">Change Image (Optional)</label>
      <input type="file" name="department_img" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">

      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="update_department" value="<?php echo $row_edit['department_id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">
          Update
        </button>
        <button type="button" onclick="document.getElementById('modalEditDepartment<?php echo $row_edit['department_id']; ?>').classList.add('hidden');" 
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">
          Close
        </button>
      </div>
    </form>

  </div>
</div>
<?php } ?>

<!-- ARCHIVED DEPARTMENTS MODAL -->
<div id="modalArchivedDepartments" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm overflow-auto">
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[95%] max-w-5xl p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalArchivedDepartments').classList.add('hidden');" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-archive text-yellow-500"></i> Archived Departments
    </h3>

    <!-- Archived Departments Table -->
    <div class="overflow-x-auto max-h-[70vh]">
      <table class="min-w-full border border-gray-200">
        <thead class="bg-yellow-500 text-white">
          <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Department Name</th>
            <th class="px-4 py-2">Image</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2 text-center">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <?php while($row = mysqli_fetch_assoc($archived_query)) { ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2"><?php echo $row['department_id']; ?></td>
            <td class="px-4 py-2"><?php echo $row['department_name']; ?></td>
            <td class="px-4 py-2">
              <img src="department_images/<?php echo $row['department_img']; ?>" width="60">
            </td>
            <td class="px-4 py-2"><?php echo $row['department_status']; ?></td>
            <td class="px-4 py-2 text-center">
              <a href="#"
class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
onclick="confirmUnarchiveDepartment(<?php echo $row['department_id']; ?>)">
<i class="fas fa-undo"></i>
</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Close Button -->
    <div class="flex justify-end mt-4">
      <button onclick="document.getElementById('modalArchivedDepartments').classList.add('hidden');" 
              class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">
        Close
      </button>
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

<!-- NOTIFICATION -->
 <!-- update -->
<?php if(isset($_SESSION['success'])){ ?>
echo "<script>
const Toast = Swal.mixin({
  toast: true,
 position: 'top',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: false,
  showClass: {
    popup: ''
  },
  hideClass: {
    popup: ''
  }
});

Toast.fire({
  icon: 'success',
  title: 'Department Updated Successfully'
});
</script>";
<?php unset($_SESSION['success']); } ?>

<!-- duplicate -->
<?php if(isset($_SESSION['duplicate'])){ ?>
echo "<script>
const Toast = Swal.mixin({
  toast: true,
  position: 'top',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: false,
  showClass: {
    popup: ''
  },
  hideClass: {
    popup: ''
  }
});

Toast.fire({
  icon: 'error',
  title: 'Department name already exist'
});
</script>";
<?php unset($_SESSION['duplicate']); } ?>

<!-- add -->
<?php if(isset($_SESSION['added'])){ ?>
echo "<script>
const Toast = Swal.mixin({
  toast: true,
  position: 'top',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: false,
  showClass: {
    popup: ''
  },
  hideClass: {
    popup: ''
  }
});

Toast.fire({
  icon: 'success',
  title: 'Department Successfully Added'
});
</script>";
<?php unset($_SESSION['added']); } ?>
<!-- NOTIFICATION END -->

<script>
function confirmArchiveDepartment(id){
    Swal.fire({
        title: 'Archive Folder?', 
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
            window.location = "archive_department.php?id=" + id;
        }
    });
}
</script>

<style>
.swal-title-nowrap {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 600;
    font-size: 1.2rem; 
    text-align: center;
}
.swal2-popup.swal-custom-popup {
    padding: 1.5rem 1.5rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.swal2-html-container {
    line-height: 1.3;
}
</style>

<script>
function confirmUnarchiveDepartment(id) {
    Swal.fire({
        title: 'Unarchive Department?', 
        html: '<p style="font-size: 0.9rem; margin: 0;">This department will be restored.</p>',
        icon: null,
        width: 350,
        showCancelButton: true,
        confirmButtonText: 'Unarchive',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        customClass: {
            popup: 'swal-custom-popup',
            title: 'swal-title-nowrap'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "unarchive_department.php?id=" + id;
        }
    });
}
</script>

<style>
.swal-title-nowrap {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 600;
    font-size: 1.2rem;
    text-align: center;
}
.swal2-popup.swal-custom-popup {
    padding: 1.5rem 1.5rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.swal2-html-container {
    line-height: 1.3;
}
</style>

<script>
function confirmLogout(el) {
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

<style>
.swal-title-nowrap {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 600;
    font-size: 1.4rem; 
    text-align: center;
}
.swal2-popup.swal-custom-popup {
    padding: 1.5rem 1.5rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.swal2-html-container {
    line-height: 1.3;
}
</style>

<!-- Footer -->
<footer class="mt-9 text-center text-gray-500 text-sm">

  <p class="text-gray-500">
&#169; All Rights Reserved. Developed by the PSU IT Interns.
</p>

</footer>

</body>
</html>