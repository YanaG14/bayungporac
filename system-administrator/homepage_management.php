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

/* =========================
   HANDLE SLIDES
========================= */
if(isset($_POST['add_slide'])){
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    if(!is_dir("../uploads/slides")){
        mkdir("../uploads/slides", 0777, true);
    }

    move_uploaded_file($tmp, "../uploads/slides/".$img);

    mysqli_query($conn,"INSERT INTO homepage_slides (image, caption, description)
    VALUES ('$img','$caption','$desc')");

    // Redirect after POST
    header("Location: homepage_management.php");
    exit();
}

/* =========================
   HANDLE PROFILES
========================= */
if(isset($_POST['add_profile'])){
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    if(!is_dir("../uploads/profiles")){
        mkdir("../uploads/profiles", 0777, true);
    }

    move_uploaded_file($tmp, "../uploads/profiles/".$img);

    mysqli_query($conn,"INSERT INTO homepage_profiles (role,name,description,image)
    VALUES ('$role','$name','$desc','$img')");

    // Redirect after POST
    header("Location: homepage_management.php");
    exit();
}

/* =========================
   HANDLE FEATURED
========================= */
if(isset($_POST['add_featured'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);

    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    if(!is_dir("../uploads/featured")){
        mkdir("../uploads/featured", 0777, true);
    }

    move_uploaded_file($tmp, "../uploads/featured/".$img);

    mysqli_query($conn,"INSERT INTO homepage_featured (title,image)
    VALUES ('$title','$img')");

    // Redirect after POST
    header("Location: homepage_management.php");
    exit();
}

/* =========================
   HANDLE EVENTS
========================= */
if(isset($_POST['add_event'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn,"INSERT INTO homepage_events (title,description)
    VALUES ('$title','$desc')");

    // Redirect after POST
    header("Location: homepage_management.php");
    exit();
}

/* =========================
   HANDLE ABOUT
========================= */
if(isset($_POST['save_about'])){
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $check = mysqli_query($conn,"SELECT * FROM homepage_about LIMIT 1");

    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn,"UPDATE homepage_about SET content='$content' WHERE about_id=1");
    } else {
        mysqli_query($conn,"INSERT INTO homepage_about (content) VALUES ('$content')");
    }

    // Redirect after POST
    header("Location: homepage_management.php");
    exit();
}

/* =========================
   DELETE SLIDES
========================= */
if(isset($_GET['delete_slide'])){
    $id = $_GET['delete_slide'];

    mysqli_query($conn,"DELETE FROM homepage_slides WHERE slide_id='$id'");
    header("Location: homepage_management.php");
}

/* =========================
   DELETE PROFILES
========================= */
if(isset($_GET['delete_profile'])){
    $id = $_GET['delete_profile'];

    mysqli_query($conn,"DELETE FROM homepage_profiles WHERE profile_id='$id'");
    header("Location: homepage_management.php");
}

/* =========================
   DELETE FEATURED
========================= */
if(isset($_GET['delete_featured'])){
    $id = $_GET['delete_featured'];

    mysqli_query($conn,"DELETE FROM homepage_featured WHERE featured_id='$id'");
    header("Location: homepage_management.php");
}

/* =========================
   DELETE EVENTS
========================= */
if(isset($_GET['delete_event'])){
    $id = $_GET['delete_event'];

    mysqli_query($conn,"DELETE FROM homepage_events WHERE event_id='$id'");
    header("Location: homepage_management.php");
}

if(isset($_POST['update_slide'])){
    $id = $_POST['id'];
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $img = $_FILES['image']['name'];

    if($img != ""){
        $tmp = $_FILES['image']['tmp_name'];

        if(!is_dir("../uploads/slides")){
            mkdir("../uploads/slides", 0777, true);
        }

        move_uploaded_file($tmp, "../uploads/slides/".$img);

        mysqli_query($conn,"UPDATE homepage_slides 
            SET caption='$caption', description='$description', image='$img' 
            WHERE slide_id='$id'");
    } else {
        mysqli_query($conn,"UPDATE homepage_slides 
            SET caption='$caption', description='$description' 
            WHERE slide_id='$id'");
    }

    header("Location: homepage_management.php");
}

if(isset($_POST['update_profile'])){
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $img = $_FILES['image']['name'];

    if($img != ""){
        $tmp = $_FILES['image']['tmp_name'];

        if(!is_dir("../uploads/profiles")){
            mkdir("../uploads/profiles", 0777, true);
        }

        move_uploaded_file($tmp, "../uploads/profiles/".$img);

        mysqli_query($conn,"UPDATE homepage_profiles 
            SET name='$name', role='$role', image='$img' 
            WHERE profile_id='$id'");
    } else {
        mysqli_query($conn,"UPDATE homepage_profiles 
            SET name='$name', role='$role' 
            WHERE profile_id='$id'");
    }

    header("Location: homepage_management.php");
}

if(isset($_POST['update_featured'])){
    $id = $_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);

    $img = $_FILES['image']['name'];

    if($img != ""){
        $tmp = $_FILES['image']['tmp_name'];

        if(!is_dir("../uploads/featured")){
            mkdir("../uploads/featured", 0777, true);
        }

        move_uploaded_file($tmp, "../uploads/featured/".$img);

        mysqli_query($conn,"UPDATE homepage_featured 
            SET title='$title', image='$img' 
            WHERE featured_id='$id'");
    } else {
        mysqli_query($conn,"UPDATE homepage_featured 
            SET title='$title' 
            WHERE featured_id='$id'");
    }

    header("Location: homepage_management.php");
}

if(isset($_POST['update_event'])){
    $id = $_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn,"UPDATE homepage_events 
        SET title='$title', description='$description' 
        WHERE event_id='$id'");

    header("Location: homepage_management.php");
}

/* =========================
   FETCH DATA
========================= */
$slides = mysqli_query($conn,"SELECT * FROM homepage_slides ORDER BY slide_id DESC");
$profiles = mysqli_query($conn,"SELECT * FROM homepage_profiles ORDER BY profile_id DESC");
$featured = mysqli_query($conn,"SELECT * FROM homepage_featured ORDER BY featured_id DESC");
$events = mysqli_query($conn,"SELECT * FROM homepage_events ORDER BY event_id DESC");
$about = mysqli_query($conn,"SELECT * FROM homepage_about LIMIT 1");
$aboutRow = mysqli_fetch_assoc($about);
?>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Homepage Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="js/img/municipalLogo.png">

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
.tab-btn.active { background: #16a34a; color: white; }
</style>

</head>

<body class="bg-gray-100">

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

<script>
function confirmLogout() {
  if(confirm("Are you sure you want to logout?")) {
    window.location.href = 'logout.php'; // replace with your logout URL
  }
}
</script>

<!-- Main Layout -->
<div class="mt-24 px-6 flex gap-6">

<!-- SIDEBAR -->


<!-- Sidebar -->
<aside id="sidebar" class="lg:w-1/4 w-72 lg:h-[690px] fixed lg:static inset-y-0 left-0 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:flex lg:flex-col">
  <!-- NO SHADOW behind + Ultra smooth desktop corners -->
  <div class="bg-white/95 backdrop-blur-lg rounded-3xl lg:rounded-[3rem] shadow-2xl lg:shadow-2xl p-1 sm:p-3 lg:p-8 border border-gray-200/50 flex flex-col h-screen lg:h-[690px] items-center relative overflow-hidden">
    
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
        
        <!--Home Page - Active style -->
        <a href="homepage_management.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl 
           bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 shadow-md border border-green-200/60
           hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:shadow-2xl hover:-translate-y-1 hover:border-green-300/80
           transition-all duration-300 text-xs sm:text-sm lg:text-base backdrop-blur-sm">
           <i class="fas fa-home text-blue-500 group-hover:text-blue-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300 shadow-sm rounded p-0.5 bg-white/50"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate text-green-800">Home Page</span>
        </a>

        <!-- Offices - Fixed -->
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
<div class="w-3/4 p-1 h-[calc(79vh-2rem)]">

  <!-- TABS -->
  <div class="p-6 bg-gray-50 rounded-2xl shadow-xl h-full flex flex-col">

  <!-- Tab Buttons -->
  <!-- Mobile: Dropdown -->
<div class="block md:hidden mb-4">
  <select id="mobileTabs" class="w-full px-4 py-2 rounded-2xl bg-white shadow-md text-gray-700 font-medium">
    <option value="slides">Slides</option>
    <option value="profiles">Profiles</option>
    <option value="featured">Featured</option>
    <option value="events">Announcement</option>
    <option value="about">About</option>
  </select>
</div>

<!-- Desktop: Buttons -->
<div class="hidden md:flex flex-wrap gap-3 mb-6">
  <button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-200 font-medium text-gray-700 active:bg-green-600 active:text-white"
          onclick="showTab('slides')">Slides</button>

  <button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-200 font-medium text-gray-700"
          onclick="showTab('profiles')">Profiles</button>

  <button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-200 font-medium text-gray-700"
          onclick="showTab('featured')">Featured</button>

  <button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-200 font-medium text-gray-700"
          onclick="showTab('events')">Announcement</button>

  <button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-200 font-medium text-gray-700"
          onclick="showTab('about')">About</button>
</div>

<script>
// Mobile dropdown functionality
document.getElementById('mobileTabs').addEventListener('change', function() {
  const tab = this.value;
  showTab(tab);

  // Optional: highlight the selected tab visually
  this.querySelectorAll('option').forEach(opt => opt.selected = opt.value === tab);
});

// Example showTab function if not already defined
function showTab(tabId) {
  document.querySelectorAll('.tab-content').forEach(tc => tc.style.display = 'none');
  const activeTab = document.getElementById(tabId);
  if(activeTab) activeTab.style.display = 'block';
}
</script>

<style>
  /* Active tab style */
  .tab-btn.active {
    background-color: #10B981; /* Tailwind green-500 */
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); /* stronger shadow for active tab */
  }
</style>

  <!-- SLIDES -->
 <!-- SLIDES -->
<div id="slides" class="tab-content bg-white p-4 sm:p-6 rounded-2xl shadow-md flex flex-col flex-1 overflow-hidden">
  <h2 class="font-semibold text-xl mb-4 border-b pb-2">Slides</h2>

  <!-- Add Slide Form -->
  <form method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row flex-wrap gap-3 mb-6 items-center bg-gray-50 p-4 rounded-xl shadow-inner w-full">
    <input type="text" name="caption" placeholder="Caption" class="border p-3 rounded-xl flex-1 min-w-[120px] w-full sm:w-full md:w-auto" required>
    <input type="text" name="description" placeholder="Description" class="border p-3 rounded-xl flex-1 min-w-[120px] w-full sm:w-full md:w-auto" required>
    <input type="file" name="image" class="border p-3 rounded-xl w-full sm:w-full md:w-auto" required>
    <button name="add_slide" class="bg-green-600 text-white px-5 py-3 rounded-xl hover:bg-green-700 transition-colors w-full sm:w-full md:w-auto">
      Add
    </button>
  </form>

  <!-- Slide List -->
  <div class="flex flex-col gap-4 overflow-y-auto">
    <?php while($row = mysqli_fetch_assoc($slides)) { ?>
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-gray-50 p-4 rounded-xl shadow-sm hover:shadow-md transition-all gap-4 w-full">

        <!-- Slide Info -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full">
          <img src="../uploads/slides/<?php echo $row['image']; ?>" class="w-full sm:w-28 h-28 object-cover rounded-xl border flex-shrink-0">
          <div class="flex-1 min-w-0">
            <p class="font-medium text-lg sm:text-base truncate"><?php echo $row['caption']; ?></p>
            <p class="text-gray-500 text-sm sm:text-xs truncate"><?php echo $row['description']; ?></p>
          </div>
        </div>

        <!-- Dropdown Menu -->
        <div class="relative mt-3 sm:mt-0 w-full sm:w-auto">
          <button onclick="toggleSlideMenu(<?php echo $row['slide_id']; ?>)" 
                  class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-xl transition w-full sm:w-auto">
            <i class="fas fa-bars"></i>
          </button>

          <div id="slide-menu-<?php echo $row['slide_id']; ?>" 
               class="hidden absolute left-0 sm:right-full sm:left-auto top-full sm:top-1/2 mt-2 sm:mt-0 w-full sm:w-36 bg-white border rounded-xl shadow-lg z-50 p-2
                      scale-95 opacity-0 transition-all duration-200 origin-top-left sm:origin-top-right">
            <div class="flex flex-col gap-2">
              <button onclick="openEditSlide(<?php echo htmlspecialchars(json_encode($row)); ?>)" 
                      class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition text-left">
                Edit
              </button>
              <a href="homepage_management.php?delete_slide=<?php echo $row['slide_id']; ?>" 
                 onclick="return confirm('Delete this slide?')" 
                 class="w-full bg-gray-100 hover:bg-gray-200 text-red-600 px-4 py-2 rounded-lg text-sm transition text-left">
                Delete
              </a>
            </div>
          </div>
        </div>

      </div>
    <?php } ?>
  </div>
</div>

<script>
function toggleSlideMenu(id){
  const menu = document.getElementById('slide-menu-' + id);

  // Close other menus
  document.querySelectorAll('[id^="slide-menu-"]').forEach(el => {
    if(el !== menu){
      el.classList.add('hidden','scale-95','opacity-0');
    }
  });

  if(menu.classList.contains('hidden')){
    menu.classList.remove('hidden');
    setTimeout(() => {
      menu.classList.remove('scale-95','opacity-0');
      menu.classList.add('scale-100','opacity-100');
    }, 10);
  } else {
    closeSlideMenu(menu);
  }
}

function closeSlideMenu(menu){
  menu.classList.remove('scale-100','opacity-100');
  menu.classList.add('scale-95','opacity-0');
  setTimeout(() => menu.classList.add('hidden'), 200);
}

// Close menu when clicking outside
document.addEventListener('click', function(e){
  document.querySelectorAll('[id^="slide-menu-"]').forEach(menu => {
    const button = menu.previousElementSibling;
    if (!menu.contains(e.target) && !button.contains(e.target)) closeSlideMenu(menu);
  });
});
</script>


<style>
/* Optional: active tab highlight */
.tab-btn.active {
  background-color: #10B981; /* Tailwind green-500 */
  color: white;
}
</style>

  <!-- PROFILES -->
  <div id="profiles" class="tab-content hidden bg-white p-6 rounded-2xl shadow-md flex flex-col flex-1 overflow-hidden">

  <!-- Heading -->
  <h2 class="font-semibold text-xl mb-6 border-b pb-2">Officials</h2>

  <!-- Add Profile Form -->
  <form method="POST" enctype="multipart/form-data" class="flex flex-wrap gap-3 mb-6 items-center bg-gray-50 p-4 rounded-2xl shadow-inner">
    <input type="text" name="role" placeholder="Role" class="border p-2 rounded-xl flex-1 min-w-[150px]" required>
    <input type="text" name="name" placeholder="Name" class="border p-2 rounded-xl flex-1 min-w-[150px]" required>
    <input type="text" name="description" placeholder="Description" class="border p-2 rounded-xl flex-1 min-w-[150px]">
    <input type="file" name="image" class="border p-2 rounded-xl" required>
    <button name="add_profile" class="bg-green-600 text-white px-5 py-2 rounded-xl hover:bg-green-700 transition-colors">Add</button>
  </form>

  <!-- Profile List -->
  <div class="space-y-4 overflow-y-auto flex-1 pr-2">
    <?php while($row = mysqli_fetch_assoc($profiles)) { ?>
      <div class="flex flex-wrap items-center justify-between bg-gray-50 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all">
        
        <div class="flex items-center gap-4">
          <img src="../uploads/profiles/<?php echo $row['image']; ?>" class="w-20 h-20 object-cover rounded-xl border">
          <div>
            <p class="font-medium"><?php echo $row['name']; ?></p>
            <p class="text-gray-500 text-sm"><?php echo $row['role']; ?></p>
            <?php if(!empty($row['description'])) { ?>
              <p class="text-gray-400 text-sm"><?php echo $row['description']; ?></p>
            <?php } ?>
          </div>
        </div>


        

      <div class="relative inline-block text-left mt-2 sm:mt-0">

  <!-- 3 horizontal lines button -->
  <button onclick="toggleMenu(<?php echo $row['profile_id']; ?>)" 
          class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-xl transition">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Dropdown -->
  <div id="menu-<?php echo $row['profile_id']; ?>" 
       class="hidden absolute 
              left-full ml-2 
              sm:right-full sm:left-auto sm:mr-2 
              top-1/2 -translate-y-1/2 
              w-36 bg-white border rounded-xl shadow-lg z-50 p-2
              transform scale-95 opacity-0 transition-all duration-200 
              origin-left sm:origin-right">

    <div class="flex flex-col gap-2">

      <!-- Edit -->
      <button 
        onclick="openEditProfile(<?php echo htmlspecialchars(json_encode($row)); ?>)" 
        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
        Edit
      </button>

      <!-- Delete -->
      <a href="homepage_management.php?delete_profile=<?php echo $row['profile_id']; ?>" 
         onclick="return confirm('Delete this profile?')" 
         class="w-full bg-gray-100 hover:bg-gray-200 text-red-600 px-4 py-2 rounded-lg text-sm transition text-center">
        Delete
      </a>

    </div>

  </div>
</div>

<script>
function toggleMenu(id){
  const menu = document.getElementById('menu-' + id);

  // Close other menus
  document.querySelectorAll('[id^="menu-"]').forEach(el => {
    if(el !== menu){
      el.classList.add('hidden','scale-95','opacity-0');
    }
  });

  if(menu.classList.contains('hidden')){
    menu.classList.remove('hidden');

    setTimeout(() => {
      menu.classList.remove('scale-95','opacity-0');
      menu.classList.add('scale-100','opacity-100');
    }, 10);

  } else {
    closeMenu(menu);
  }
}

function closeMenu(menu){
  menu.classList.remove('scale-100','opacity-100');
  menu.classList.add('scale-95','opacity-0');

  setTimeout(() => {
    menu.classList.add('hidden');
  }, 200);
}

// Close when clicking outside
document.addEventListener('click', function(e){
  document.querySelectorAll('[id^="menu-"]').forEach(menu => {
    const button = menu.previousElementSibling;

    if (!menu.contains(e.target) && !button.contains(e.target)) {
      closeMenu(menu);
    }
  });
});
</script>

      </div>
    <?php } ?>
  </div>
</div>

  <!-- FEATURED -->
 <div id="featured" class="tab-content hidden bg-white p-6 rounded-2xl shadow-md flex flex-col flex-1 overflow-hidden">

  <!-- Heading -->
  <h2 class="font-semibold text-xl mb-6 border-b pb-2">Featured</h2>

  <!-- Add Featured Form -->
  <form method="POST" enctype="multipart/form-data" class="flex flex-wrap gap-3 mb-6 items-center bg-gray-50 p-4 rounded-2xl shadow-inner">
    <input type="text" name="title" placeholder="Title" class="border p-2 rounded-xl flex-1 min-w-[150px]" required>
    <input type="file" name="image" class="border p-2 rounded-xl" required>
    <button name="add_featured" class="bg-green-600 text-white px-5 py-2 rounded-xl hover:bg-green-700 transition-colors">Add</button>
  </form>

  <!-- Featured List -->
  <div class="space-y-4 overflow-y-auto flex-1 pr-2">
    <?php while($row = mysqli_fetch_assoc($featured)) { ?>
      <div class="flex flex-wrap items-center justify-between bg-gray-50 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all">
        
        <div class="flex items-center gap-4">
          <img src="../uploads/featured/<?php echo $row['image']; ?>" class="w-20 h-20 object-cover rounded-xl border">
          <p class="font-medium"><?php echo $row['title']; ?></p>
        </div>

        

        <div class="relative inline-block text-left mt-2 sm:mt-0">

  <!-- 3 horizontal lines button -->
  <button onclick="toggleFeaturedMenu(<?php echo $row['featured_id']; ?>)" 
          class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-xl transition">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Dropdown -->
  <div id="featured-menu-<?php echo $row['featured_id']; ?>" 
       class="hidden absolute 
              left-full ml-2 
              sm:right-full sm:left-auto sm:mr-2 
              top-1/2 -translate-y-1/2 
              w-36 bg-white border rounded-xl shadow-lg z-50 p-2
              transform scale-95 opacity-0 transition-all duration-200 
              origin-left sm:origin-right">

    <div class="flex flex-col gap-2">

      <!-- Edit -->
      <button 
        onclick="openEditFeatured(<?php echo htmlspecialchars(json_encode($row)); ?>)" 
        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
        Edit
      </button>

      <!-- Delete -->
      <a href="homepage_management.php?delete_featured=<?php echo $row['featured_id']; ?>" 
         onclick="return confirm('Delete this featured item?')" 
         class="w-full bg-gray-100 hover:bg-gray-200 text-red-600 px-4 py-2 rounded-lg text-sm transition text-center">
        Delete
      </a>

    </div>

  </div>
</div>

<script>
function toggleFeaturedMenu(id){
  const menu = document.getElementById('featured-menu-' + id);

  // Close other menus
  document.querySelectorAll('[id^="featured-menu-"]').forEach(el => {
    if(el !== menu){
      el.classList.add('hidden','scale-95','opacity-0');
    }
  });

  if(menu.classList.contains('hidden')){
    menu.classList.remove('hidden');

    setTimeout(() => {
      menu.classList.remove('scale-95','opacity-0');
      menu.classList.add('scale-100','opacity-100');
    }, 10);

  } else {
    closeFeaturedMenu(menu);
  }
}

function closeFeaturedMenu(menu){
  menu.classList.remove('scale-100','opacity-100');
  menu.classList.add('scale-95','opacity-0');

  setTimeout(() => {
    menu.classList.add('hidden');
  }, 200);
}

// Close when clicking outside
document.addEventListener('click', function(e){
  document.querySelectorAll('[id^="featured-menu-"]').forEach(menu => {
    const button = menu.previousElementSibling;

    if (!menu.contains(e.target) && !button.contains(e.target)) {
      closeFeaturedMenu(menu);
    }
  });
});
</script>


      </div>
    <?php } ?>
  </div>

</div>

  <!-- EVENTS -->
 <div id="events" class="tab-content hidden bg-white p-6 rounded-2xl shadow-md">

  <!-- Heading -->
  <h2 class="font-semibold text-xl mb-6 border-b pb-2">Events</h2>

  <!-- Add Event Form -->
  <form method="POST" class="flex flex-wrap gap-3 mb-6 items-center bg-gray-50 p-4 rounded-2xl shadow-inner">
    <input type="text" name="title" placeholder="Title" class="border p-2 rounded-xl flex-1 min-w-[150px]" required>
    <input type="text" name="description" placeholder="Description" class="border p-2 rounded-xl flex-1 min-w-[150px]">
    <button name="add_event" class="bg-green-600 text-white px-5 py-2 rounded-xl hover:bg-green-700 transition-colors">Add</button>
  </form>

  <!-- Event List -->
  <div class="space-y-4 overflow-y-auto flex-1 pr-2 h-[220px]">
    <?php while($row = mysqli_fetch_assoc($events)) { ?>
      <div class="flex flex-wrap items-center justify-between bg-gray-50 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all">
        
        <p class="font-medium"><?php echo $row['title']; ?></p>
        <?php if(!empty($row['description'])) { ?>
          <p class="text-gray-500 text-sm"><?php echo $row['description']; ?></p>
        <?php } ?>

        <div class="relative inline-block text-left mt-2 sm:mt-0">

  <!-- 3 horizontal lines button -->
  <button onclick="toggleEventMenu(<?php echo $row['event_id']; ?>)" 
          class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-xl transition">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Dropdown (RIGHT side, centered vertically) -->
  <div id="event-menu-<?php echo $row['event_id']; ?>" 
       class="hidden absolute left-full ml-2 top-1/2 -translate-y-1/2 w-36 bg-white border rounded-xl shadow-lg z-50 p-2
              transform scale-95 opacity-0 transition-all duration-200 origin-left">

    <div class="flex flex-col gap-2">

      <!-- Edit -->
      <button 
        onclick="openEditEvent(<?php echo htmlspecialchars(json_encode($row)); ?>)" 
        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
        Edit
      </button>

      <!-- Delete -->
      <a href="homepage_management.php?delete_event=<?php echo $row['event_id']; ?>" 
         onclick="return confirm('Delete this event?')" 
         class="w-full bg-gray-100 hover:bg-gray-200 text-red-600 px-4 py-2 rounded-lg text-sm transition text-center">
        Delete
      </a>

    </div>

  </div>
</div>

<script>
function toggleEventMenu(id){
  const menu = document.getElementById('event-menu-' + id);

  // Close other menus
  document.querySelectorAll('[id^="event-menu-"]').forEach(el => {
    if(el !== menu){
      el.classList.add('hidden','scale-95','opacity-0');
    }
  });

  if(menu.classList.contains('hidden')){
    menu.classList.remove('hidden');

    setTimeout(() => {
      menu.classList.remove('scale-95','opacity-0');
      menu.classList.add('scale-100','opacity-100');
    }, 10);

  } else {
    closeEventMenu(menu);
  }
}

function closeEventMenu(menu){
  menu.classList.remove('scale-100','opacity-100');
  menu.classList.add('scale-95','opacity-0');

  setTimeout(() => {
    menu.classList.add('hidden');
  }, 200);
}

// Close when clicking outside
document.addEventListener('click', function(e){
  document.querySelectorAll('[id^="event-menu-"]').forEach(menu => {
    const button = menu.previousElementSibling;

    if (!menu.contains(e.target) && !button.contains(e.target)) {
      closeEventMenu(menu);
    }
  });
});
</script>

      </div>
    <?php } ?>
  </div>

</div>

  <!-- ABOUT -->
  <div id="about" class="tab-content hidden bg-white p-6 rounded-2xl shadow-md">

  <!-- Heading -->
  <h2 class="font-semibold text-xl mb-6 border-b pb-2">About</h2>

  <!-- About Form -->
  <form method="POST" class="flex flex-col gap-4">
    <textarea name="content" class="w-full border p-4 rounded-2xl h-[260px] resize-none shadow-inner" placeholder="Enter content here..."><?php echo $aboutRow['content'] ?? ''; ?></textarea>
    <button name="save_about" class="self-start bg-green-600 text-white px-6 py-2 rounded-2xl hover:bg-green-700 transition-colors">Save</button>
  </form>

</div>

<script>
function showTab(tab){
  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
  document.getElementById(tab).classList.remove('hidden');

  document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
  event.target.classList.add('active');
}

function confirmLogout(){
  Swal.fire({
    title: 'Logout?',
    showCancelButton: true,
    confirmButtonText: 'Logout'
  }).then((result)=>{
    if(result.isConfirmed){
      window.location = 'Logout.php';
    }
  });
}
</script>

<script>
function openEditSlide(data){
Swal.fire({
  title: '<span style="font-size:22px; font-weight:600;">Edit Slide</span>',
  width: '700px',
  background: '#f9fafb',
  showClass: {
    popup: 'animate__animated animate__fadeInUp'
  },
  hideClass: {
    popup: 'animate__animated animate__fadeOutDown'
  },
  html: `
    <form id="editSlideForm" enctype="multipart/form-data"
      style="display:flex; flex-direction:column; gap:14px; text-align:left;">

      <input type="hidden" name="id" value="${data.slide_id}">

      <!-- Caption -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#374151;">Caption</label>
        <input 
          name="caption" 
          value="${data.caption}" 
          class="swal2-input"
          style="width:100%; margin-top:4px; border-radius:10px; padding:10px; border:1px solid #e5e7eb;"
        >
      </div>

      <!-- Description -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#374151;">Description</label>
        <textarea 
          name="description"
          style="width:100%; height:160px; margin-top:4px; border-radius:10px; padding:10px; border:1px solid #e5e7eb; resize:none;"
        >${data.description}</textarea>
      </div>

      <!-- Image Upload -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#374151;">Slide Image</label>
        <input 
          type="file" 
          name="image"
          class="swal2-input"
          style="width:100%; margin-top:4px; border-radius:10px; padding:8px; border:1px solid #e5e7eb; background:white;"
        >
      </div>

    </form>
  `,
  showCancelButton: true,
  confirmButtonText: 'Update',
  cancelButtonText: 'Cancel',
  confirmButtonColor: '#16a34a',
  cancelButtonColor: '#6b7280',

  customClass: {
    popup: 'rounded-2xl shadow-2xl'
  },

  preConfirm: () => {
    const form = document.getElementById('editSlideForm');
    const formData = new FormData(form);
    formData.append('update_slide', true);

    return fetch('homepage_management.php', {
      method: 'POST',
      body: formData
    }).then(() => location.reload());
  }
});
}

function openEditProfile(data){
Swal.fire({
  title: '<span style="font-size:22px; font-weight:600;">Edit Profile</span>',
  width: '650px',
  background: '#f9fafb',
  showClass: {
    popup: 'animate__animated animate__fadeInUp'
  },
  hideClass: {
    popup: 'animate__animated animate__fadeOutDown'
  },
  html: `
    <form id="editProfileForm" enctype="multipart/form-data"
      style="display:flex; flex-direction:column; gap:14px; text-align:left;">

      <input type="hidden" name="id" value="${data.profile_id}">

      <!-- Name -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#374151;">Name</label>
        <input 
          name="name" 
          value="${data.name}" 
          class="swal2-input"
          style="width:100%; margin-top:4px; border-radius:10px; padding:10px; border:1px solid #e5e7eb;"
        >
      </div>

      <!-- Role -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#374151;">Role</label>
        <input 
          name="role" 
          value="${data.role}" 
          class="swal2-input"
          style="width:100%; margin-top:4px; border-radius:10px; padding:10px; border:1px solid #e5e7eb;"
        >
      </div>

      <!-- Image Upload -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#374151;">Profile Image</label>
        <input 
          type="file" 
          name="image"
          class="swal2-input"
          style="width:100%; margin-top:4px; border-radius:10px; padding:8px; border:1px solid #e5e7eb; background:white;"
        >
      </div>

    </form>
  `,
  showCancelButton: true,
  confirmButtonText: 'Update',
  cancelButtonText: 'Cancel',
  confirmButtonColor: '#16a34a',
  cancelButtonColor: '#6b7280',

  customClass: {
    popup: 'rounded-2xl shadow-2xl'
  },

  preConfirm: () => {
    const form = document.getElementById('editProfileForm');
    const formData = new FormData(form);
    formData.append('update_profile', true);

    return fetch('homepage_management.php', {
      method: 'POST',
      body: formData
    }).then(() => location.reload());
  }
});
}

function openEditFeatured(data){
Swal.fire({
  title: '<span style="font-size:22px; font-weight:600;">Edit Featured</span>',
  width: '650px',
  background: '#f9fafb',
  showClass: {
    popup: 'animate__animated animate__fadeInUp'
  },
  hideClass: {
    popup: 'animate__animated animate__fadeOutDown'
  },
  html: `
    <form id="editFeaturedForm" enctype="multipart/form-data"
      style="display:flex; flex-direction:column; gap:14px; text-align:left;">

      <input type="hidden" name="id" value="${data.featured_id}">

      <!-- Title -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#374151;">Title</label>
        <input 
          name="title" 
          value="${data.title}" 
          class="swal2-input"
          style="width:100%; margin-top:4px; border-radius:10px; padding:10px; border:1px solid #e5e7eb;"
        >
      </div>

      <!-- Image Upload -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#374151;">Featured Image</label>
        <input 
          type="file" 
          name="image"
          class="swal2-input"
          style="width:100%; margin-top:4px; border-radius:10px; padding:8px; border:1px solid #e5e7eb; background:white;"
        >
      </div>

    </form>
  `,
  showCancelButton: true,
  confirmButtonText: 'Update',
  cancelButtonText: 'Cancel',
  confirmButtonColor: '#16a34a',
  cancelButtonColor: '#6b7280',

  customClass: {
    popup: 'rounded-2xl shadow-2xl'
  },

  preConfirm: () => {
    const form = document.getElementById('editFeaturedForm');
    const formData = new FormData(form);
    formData.append('update_featured', true);

    return fetch('homepage_management.php', {
      method: 'POST',
      body: formData
    }).then(() => location.reload());
  }
});
}

function openEditEvent(data){
Swal.fire({
  title: 'Edit Event',
  width: '600px',
  html: `
    <form id="editEventForm" style="display:flex; flex-direction:column; gap:12px;">

      <input type="hidden" name="id" value="${data.event_id}">

      <!-- Title (wider / full width) -->
      <input 
        name="title" 
        class="swal2-input" 
        placeholder="Event Title"
        value="${data.title}" 
        style="width:100%; margin:0;"
      >

      <!-- Description (larger textarea) -->
      <textarea 
        name="description" 
        class="swal2-textarea" 
        placeholder="Event Description"
        style="width:100%; height:150px; resize:none; margin:0;"
      >${data.description}</textarea>

    </form>
  `,
  showCancelButton: true,
  confirmButtonText: 'Update',
  cancelButtonText: 'Cancel',
  confirmButtonColor: '#16a34a',
  preConfirm: () => {
    const form = document.getElementById('editEventForm');
    const formData = new FormData(form);
    formData.append('update_event', true);

    return fetch('homepage_management.php', {
      method: 'POST',
      body: formData
    }).then(() => location.reload());
  }
});
}
</script>



</body>

</html>