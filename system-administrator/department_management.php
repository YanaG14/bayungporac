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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
  info: false,     
  searching: false      // ❌ removes "Showing 1 to..."
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
    
    <div class="flex items-center space-x-3 min-w-0">
      
      <!-- ✅ ONLY TOGGLE BUTTON -->
      <button id="sidebarToggle"
  class="bg-transparent p-2.5 w-11 h-11 flex items-center justify-center border-2 border-white/20 rounded-lg z-50">
  <i id="toggleIcon" class="fas fa-bars text-white text-lg"></i>
</button>

      <!-- Logo + Title -->
      <div class="flex items-center space-x-3 min-w-0 flex-1">
        <img src="js/img/municipalLogo.png" class="w-9 h-9 object-contain">
        <h1 class="text-white font-semibold text-base sm:text-lg truncate">
          Bayung Porac Archive
        </h1>
      </div>

    </div>
  </div>
</nav>

<!-- MAIN LAYOUT -->
<div class="mt-24 px-4 sm:px-6 flex flex-col lg:flex-row gap-4 lg:gap-6 min-h-screen">

<!-- SIDEBAR -->
<aside id="sidebar"
  class="fixed inset-y-0 left-0 z-30 w-72 lg:w-1/4
  transform -translate-x-full transition-transform duration-300 ease-in-out">

  <div class="bg-white/95 backdrop-blur-lg rounded-3xl lg:rounded-[3rem] shadow-2xl p-3 lg:p-8 border flex flex-col h-screen lg:h-[790px] items-center">

    <div id="sidebarContent" class="flex flex-col w-full h-full pt-12 space-y-4">

      <!-- LOGO -->
      <div class="mb-6 flex justify-center">
        <img src="img/adminLogo.png"
          class="w-36 h-36 object-cover rounded-3xl shadow-2xl border-4 border-white">
      </div>

      <!-- ADMIN -->
      <div class="text-center mb-6">
        <span class="font-bold text-lg block">
          <?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?>
        </span>

        <a href="#" onclick="confirmLogout(this)"
          class="mt-2 inline-block bg-green-500 text-white px-4 py-1 rounded-xl">
          Log out
        </a>
      </div>

      <!-- MENU -->
      <nav class="w-full space-y-2 overflow-y-auto">

        <a href="homepage_management.php"
  class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl
  transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">

  <i class="fas fa-house text-gray-500 w-4 h-4"></i>
  <span class="font-medium text-gray-700 truncate"> Home Page</span>
</a>



       <a href="department_management.php"
  class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl
  bg-gradient-to-r from-indigo-500/10 to-blue-500/10
  text-indigo-600 font-semibold
  transition-all duration-300
  shadow-sm hover:shadow-md">

  <i class="fas fa-building text-indigo-500 w-4 h-4"></i>
  <span class="truncate">Offices</span>
</a>



       <a href="view_user.php"
  class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl
  transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">

  <i class="fas fa-user text-gray-500 w-4 h-4"></i>
  <span class="font-medium text-gray-700 truncate">Employees</span>
</a>


      <a href="view_admin.php"
  class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl
  transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">

  <i class="fas fa-user-shield text-gray-500 w-4 h-4"></i>
  <span class="font-medium text-gray-700 truncate"> Records Administrators</span>
</a>


<a href="system-administrator.php"
  class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl
  transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">

  <i class="fas fa-user-shield text-gray-500 w-4 h-4"></i>
  <span class="font-medium text-gray-700 truncate"> System Administrators</span>
</a>


      </nav>
    </div>
  </div>
</aside>


<!-- OVERLAY -->
<div id="sidebarOverlay"
  class="fixed inset-0 bg-black/40 hidden z-20 lg:hidden">
</div>



<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const toggleBtn = document.getElementById('sidebarToggle');
const icon = document.getElementById('toggleIcon');

function openSidebar() {
  sidebar.classList.remove('-translate-x-full');
  overlay.classList.remove('hidden');
  document.body.classList.add('overflow-hidden');

  icon.classList.remove('fa-bars');
  icon.classList.add('fa-times');
}

function closeSidebar() {
  sidebar.classList.add('-translate-x-full');
  overlay.classList.add('hidden');
  document.body.classList.remove('overflow-hidden');

  icon.classList.remove('fa-times');
  icon.classList.add('fa-bars');
}

function toggleSidebar() {
  if (sidebar.classList.contains('-translate-x-full')) {
    openSidebar();
  } else {
    closeSidebar();
  }
}

// Toggle button
toggleBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  toggleSidebar();
});

// Overlay click closes
overlay.addEventListener('click', closeSidebar);

// Auto close when clicking menu links
document.querySelectorAll('#sidebar a').forEach(link => {
  link.addEventListener('click', closeSidebar);
});

// ESC key closes
document.addEventListener('keydown', (e) => {
  if (e.key === "Escape") closeSidebar();
});

// Always start closed
document.addEventListener('DOMContentLoaded', closeSidebar);

// Safety fix on resize
window.addEventListener('resize', () => {
  if (window.innerWidth >= 1024) {
    overlay.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }
});
</script>


  <!-- MAIN CONTENT -->
 <div class="lg:w-3/4 w-full p-1 h-auto lg:h-[calc(79vh-2rem)] min-h-[500px]">
    <div class="p-6 bg-gray-50 rounded-2xl shadow-xl flex flex-col 
            w-full md:w-[1450px] h-[700px] mx-auto">
      <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4 gap-4 lg:gap-0">
    
      <!-- Title -->
    <h2 class="text-lg sm:text-xl lg:text-2xl font-semibold flex items-center gap-2 text-center sm:text-left flex-1">
          <i class="fas fa-building text-green-600"></i>
          <span class="relative">
            ACTIVE DEPARTMENTS
            <span class="absolute left-0 -bottom-1 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-600 rounded scale-x-75 sm:scale-x-100"></span>
          </span>
        </h2>

    
    <!-- Buttons grouped with gap -->
    <div class="flex flex-wrap items-center gap-2 lg:gap-3 order-1 lg:order-2 justify-center lg:justify-end">
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
  <!-- ADD DEPARTMENT -->
      <button onclick="document.getElementById('modalAddDepartment').classList.remove('hidden');" 
        class="text-green-600 hover:text-green-800 transition-colors duration-200 focus:outline-none">
    <i class="fas fa-plus text-lg"></i>
</button>
<!-- ARCHIVE -->
      <button onclick="document.getElementById('modalArchivedDepartments').classList.remove('hidden');" 
        class="text-yellow-500 hover:text-yellow-700 transition-colors duration-200 focus:outline-none">
    <i class="fas fa-archive text-lg"></i>
</button>
     
    </div>
    
  </div>
  
  <!-- Responsive Table Container -->
      <div class="w-full h-[calc(100%-120px)] sm:h-[calc(100%-140px)] lg:h-[560px] overflow-hidden rounded-xl border shadow-sm overflow-x-auto overflow-y-auto custom-scrollbar">
          <table id="dtable" class="min-w-[800px] w-full border-gray-200 table-auto">
            <thead class="bg-gray-200 text-black uppercase text-xs sm:text-sm tracking-wider sticky top-0 z-10 shadow-sm">
              <tr>
                <th class="px-3 sm:px-4 py-2.5 text-left font-medium min-w-[180px]hidden md:table-cell">LOGO</th>
                <th class="px-3 sm:px-4 py-2.5 text-left font-medium min-w-[160px]">DEPARTMENTS</th>
                <th class="px-3 sm:px-4 py-2.5 text-left font-medium min-w-[120px]">STATUS </th>
                <th class="px-3 sm:px-4 py-2.5 text-center font-medium w-16 sm:w-20">ACTION</th>
              </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">

          <?php while($row = mysqli_fetch_assoc($query)) { ?>
          <tr class="hover:bg-gray-50/50 transition-colors duration-150 border-b last:border-b-0">

           <!-- <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm"><?php echo $row['department_id']; ?></td> -->
            <td class="px-2 sm:px-4 py-3"><img src="department_images/<?php echo $row['department_img']; ?>" class="w-12 h-12 sm:w-14 sm:h-14 lg:w-15 lg:h-15 object-cover rounded" alt="Dept Image"></td>
            <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm font-medium"><?php echo $row['department_name']; ?></td>
            <td class="px-2 sm:px-4 py-3">
              <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-bkue-800"><?php echo $row['department_status']; ?></span>
            </td>

            <!-- Action Buttons -->
                <td class="px-3 sm:px-4 py-3 align-middle">
                  <div class="flex justify-center relative">
                <!-- 3 DOT BUTTON -->
                <button onclick="toggleMenu(<?php echo $row['department_id']; ?>)" 
                        class="text-gray-500 hover:text-gray-800 text-xl px-2">
                  <i class="fas fa-ellipsis-h text-sm"></i>
                </button>

                <!-- DROPDOWN MENU -->
                <div id="menu<?php echo $row['department_id']; ?>" 
                     class="hidden absolute top-full mt-1.5 right-0 w-28 sm:w-32 bg-white rounded-lg shadow-lg border border-gray-100 z-50
                                transform scale-95 opacity-0 transition-all duration-200 origin-top-right">
                            
                  <!-- EDIT -->
                  <button onclick="document.getElementById('modalEditDepartment<?php echo $row['department_id']; ?>').classList.remove('hidden'); closeMenu(<?php echo $row['department_id']; ?>)"
                          class="w-full flex items-center gap-2 px-3 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg transition-all duration-200">
                    <i class="fas fa-edit text-blue-500 text-xs"></i> Edit
                  </button>

                  <!-- ARCHIVE -->
                  <button onclick="confirmArchiveDepartment(<?php echo $row['department_id']; ?>); closeMenu(<?php echo $row['department_id']; ?>)"
                          class="w-full flex items-center gap-2 px-3 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg transition-all duration-200">
                    <i class="fas fa-archive text-red-500 text-xs"></i> Archive
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

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 3px; }
.custom-scrollbar::-webkit-scrollbar-thumb { 
  background: linear-gradient(45deg, #6b7371); 
  border-radius: 3px; 
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #059669; }


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