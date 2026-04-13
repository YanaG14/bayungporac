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
    $caption = $_POST['caption'];
$description = $_POST['description'];

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
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate text-blue-800">Home Page</span>
        </a>

        <!-- Offices - Fixed -->
        <a href="department_management.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-gray-200/50 backdrop-blur-sm">
           <i class="fas fa-building text-gray-500 group-hover:text-gray-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 shadow-sm rounded p-0.5 bg-white/50 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate text-gray-800">Offices</span>
        </a>

        <!--Employees-->
        <a href="view_user.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-gray-200/50 backdrop-blur-sm">
           <i class="fas fa-users text-gray-500 group-hover:text-gray-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate">Employees</span>
        </a>

        <!--Records Administrators-->
        <a href="view_admin.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-gray-200/50 backdrop-blur-sm">
           <i class="fas fa-user-shield text-gray-500 group-hover:text-gray-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300"></i>
           <span class="font-semibold tracking-wide flex-1 min-w-0 truncate">Records Administrators</span>
        </a>

        <!--System Administrators-->
        <a href="system-administrator.php" 
           class="group flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 text-xs sm:text-sm lg:text-base border border-transparent hover:border-gray-200/50 backdrop-blur-sm">
           <i class="fas fa-server text-gray-500 group-hover:text-gray-600 w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 transition-all duration-300"></i>
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
<div class="w-3/4 p-1 h-[calc(100vh-2rem)]">

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



<!-- Desktop: Compact Navbar Tabs with Icons -->
<div class="hidden md:flex justify-around border-b border-gray-200 mb-4">
  <!-- Slides: Featured photos/events -->
  <button id="tab-slides" class="tab-btn flex flex-col items-center pb-1 text-gray-600 text-sm font-medium border-b-2 border-transparent hover:text-gray-900 hover:border-blue-500 transition-all duration-200"
        onclick="activateTab('slides')">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2zM12.5 5v14m-7-7h14" />
  </svg>
  Slides
</button>

  <!-- Profiles: Municipal officials -->
  <button id="tab-profiles" class="tab-btn flex flex-col items-center pb-1 text-gray-600 text-sm font-medium border-b-2 border-transparent hover:text-gray-900 hover:border-blue-500 transition-all duration-200"
          onclick="activateTab('profiles')">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    Profiles
  </button>

  <!-- Featured: Achievements / random photos -->
  <button id="tab-featured" class="tab-btn flex flex-col items-center pb-1 text-gray-600 text-sm font-medium border-b-2 border-transparent hover:text-gray-900 hover:border-blue-500 transition-all duration-200"
        onclick="activateTab('featured')">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
  </svg>
  Featured
</button>

  <!-- Announcement: Notifications for the people -->
  <button id="tab-events" class="tab-btn flex flex-col items-center pb-1 text-gray-600 text-sm font-medium border-b-2 border-transparent hover:text-gray-900 hover:border-blue-500 transition-all duration-200"
          onclick="activateTab('events')">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
    </svg>
    Announcement
  </button>

  <!-- About: Background of Porac -->
  <button id="tab-about" class="tab-btn flex flex-col items-center pb-1 text-gray-600 text-sm font-medium border-b-2 border-transparent hover:text-gray-900 hover:border-blue-500 transition-all duration-200"
          onclick="activateTab('about')">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    About
  </button>
</div>

<script>
  function activateTab(tab) {
    // Reset all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.classList.remove('text-gray-900', 'border-blue-500');
      btn.classList.add('text-gray-600', 'border-transparent');
    });

    // Activate clicked tab
    const activeTab = document.getElementById(`tab-${tab}`);
    activeTab.classList.remove('text-gray-600', 'border-transparent');
    activeTab.classList.add('text-gray-900', 'border-blue-500');

    // Show corresponding tab content
    showTab(tab);
  }

  // Set default active tab (Slides)
  document.addEventListener('DOMContentLoaded', function() {
    activateTab('slides');
  });
</script>

<style>
/* Active tab text and icon */
.tab-btn.active,
.tab-btn.text-gray-900 {
  color: #0c52e8 !important; /* Text turns blue */
}

.tab-btn.active svg,
.tab-btn.text-gray-900 svg {
  stroke: currentColor !important; /* Icon follows text color */
}
</style>

<script>
  function activateTab(tab) {
    // Reset all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.classList.remove('text-gray-900', 'border-blue-500');
      btn.classList.add('text-gray-600', 'border-transparent');
    });

    // Activate clicked tab
    document.getElementById(`tab-${tab}`).classList.add('text-gray-900', 'border-blue-500');
    document.getElementById(`tab-${tab}`).classList.remove('text-gray-600', 'border-transparent');

    // Show corresponding tab content
    showTab(tab);
  }
</script>

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



  
<!-- SLIDES -->
<div id="slides" class="tab-content bg-white p-4 sm:p-6 rounded-2xl shadow-md flex flex-col h-[600px] overflow-hidden">

  <!-- Header -->
  <div class="flex justify-between items-center mb-4 border-b pb-2">
    <h2 class="font-semibold text-xl">Slides</h2>

    <!-- Add Slide Button -->
    <button onclick="openComposer()" 
      class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-700 hover:text-white transition shadow">
      Add Slide
    </button>
  </div>

  <!-- SLIDE LIST -->
  <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-col gap-4 pr-2">

    <?php while($row = mysqli_fetch_assoc($slides)) { ?>
      <div class="bg-gray-50 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all flex flex-col gap-2 relative">

        <!-- Title & Dropdown Button -->
        <div class="flex justify-between items-start">
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-lg"><?php echo $row['caption']; ?></p>
            <p class="text-gray-500 text-sm truncate">
              <?php 
                $desc = $row['description'];
                if(strlen($desc) > 100){ 
                  echo htmlspecialchars(substr($desc,0,100)) . '... '; 
                  echo '<span class="text-blue-600 cursor-pointer" onclick="toggleDescription(this)">See more</span>';
                } else {
                  echo htmlspecialchars($desc);
                }
              ?>
            </p>
          </div>

          <!-- Dropdown Button -->
          <div class="ml-2 relative">
            <button onclick="toggleSlideMenu(<?php echo $row['slide_id']; ?>)" 
                    class="p-2 rounded-lg hover:bg-gray-200">
              <i class="fas fa-ellipsis-v"></i>
            </button>

            <div id="slide-menu-<?php echo $row['slide_id']; ?>" 
                 class="hidden absolute right-0 mt-2 w-36 bg-white border rounded-xl shadow-lg z-50 p-2
                        scale-95 opacity-0 transition-all duration-200 origin-top-right">

              <button onclick="openEditSlide(<?php echo htmlspecialchars(json_encode($row)); ?>)" 
                      class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">
                Edit
              </button>

              <a href="homepage_management.php?delete_slide=<?php echo $row['slide_id']; ?>" 
                 onclick="return confirm('Delete this slide?')" 
                 class="block px-3 py-2 rounded-lg hover:bg-gray-100 text-red-600 text-sm">
                Delete
              </a>

            </div>
          </div>
        </div>

        <!-- Image -->
        <div>
          <img src="../uploads/slides/<?php echo $row['image']; ?>" 
               class="w-full h-64 object-cover rounded-xl border mt-2">
        </div>

      </div>
    <?php } ?>

  </div>
</div>

<script>
function toggleDescription(el){
  const parent = el.parentElement;
  parent.textContent = parent.getAttribute('data-full') || parent.textContent;
}
</script>

<!-- GMAIL STYLE COMPOSER -->
<div id="composer" class="fixed bottom-6 right-6 w-[520px] bg-white rounded-xl shadow-2xl border flex flex-col overflow-hidden hidden z-50">
  <!-- Header -->
  <div class="flex justify-between items-center px-4 py-2 bg-gray-100 cursor-pointer"
       onclick="toggleMinimize()">
    <span class="font-medium text-sm">New Slide</span>

    <div class="flex gap-2">
      <button onclick="event.stopPropagation(); minimizeComposer()" class="text-gray-500 hover:text-black">
        <i class="fas fa-minus"></i>
      </button>
      <button onclick="event.stopPropagation(); closeComposer()" class="text-gray-500 hover:text-red-500">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>

  <!-- BODY -->
  <form method="POST" enctype="multipart/form-data" class="flex flex-col h-[420px]" id="composerBody">

    <!-- Caption -->
    <input 
      type="text" 
      name="caption" 
      placeholder="Caption"
      class="px-4 py-2 border-b focus:outline-none"
      required
    >

    <!-- Description (Rich Editor) -->
<div id="editor"
  contenteditable="true"
  class="flex-1 px-4 py-3 focus:outline-none overflow-y-auto text-sm"
  placeholder="Write your description...">
</div>

<input type="hidden" name="description" id="hiddenDescription">

    <!-- Image Preview -->
    <div id="previewContainer" class="hidden px-4 pb-2">
  <img id="previewImg" class="w-full h-auto rounded-lg border">
  <img id="previewImg" class="w-full max-w-full h-auto rounded-lg border">
</div>

    <!-- Footer Toolbar -->
<div class="flex flex-wrap items-center justify-start gap-2 px-3 py-2 border-t bg-gray-50 text-sm">

  <!-- LEFT: ALL TOOLS -->
  <div class="flex flex-wrap items-center gap-2">

    <!-- Upload Button (Oval, beside Undo) -->
    <button type="submit"
      name="add_slide"
      onclick="prepareSubmit()"
      class="bg-blue-600 text-white px-4 py-1 rounded-full hover:bg-blue-700 transition"
      title="Upload Slide">
      Upload
    </button>

    <!-- Undo / Redo -->
    <button type="button" onclick="formatDoc('undo')" class="toolbar-btn" title="Undo">
      <i class="fas fa-undo"></i>
    </button>

    <button type="button" onclick="formatDoc('redo')" class="toolbar-btn" title="Redo">
      <i class="fas fa-redo"></i>
    </button>

    <!-- Font -->
    <select onchange="formatDoc('fontName', this.value)" class="toolbar-select" title="Font">
      <option value="Arial">Arial</option>
      <option value="Times New Roman">Times</option>
      <option value="Courier New">Courier</option>
    </select>

    <!-- Size -->
    <select onchange="formatDoc('fontSize', this.value)" class="toolbar-select" title="Text Size">
      <option value="2">Small</option>
      <option value="3" selected>Normal</option>
      <option value="5">Large</option>
    </select>

    <!-- Bold / Italic / Underline -->
    <button type="button" onclick="formatDoc('bold')" class="toolbar-btn" title="Bold"><b>B</b></button>
    <button type="button" onclick="formatDoc('italic')" class="toolbar-btn" title="Italic"><i>I</i></button>
    <button type="button" onclick="formatDoc('underline')" class="toolbar-btn" title="Underline"><u>U</u></button>

    <!-- Attach -->
    <label class="toolbar-btn cursor-pointer" title="Attach Image">
      <i class="fas fa-paperclip"></i>
      <input type="file" name="image" class="hidden" onchange="previewImage(event)" required>
    </label>

    <!-- Cancel -->
    <button type="button" onclick="clearComposer()" class="toolbar-btn text-red-500" title="Cancel">
      <i class="fas fa-trash"></i>
    </button>

  </div>
</div>



<script>
function openComposer(){
  document.getElementById('composer').classList.remove('hidden');
}

function closeComposer(){
  document.getElementById('composer').classList.add('hidden');
}

function minimizeComposer(){
  document.getElementById('composerBody').classList.toggle('hidden');
}

function toggleMinimize(){
  minimizeComposer();
}

function previewImage(event){
  const file = event.target.files[0];
  const preview = document.getElementById('previewImg');
  const container = document.getElementById('previewContainer');

  if(file){
    preview.src = URL.createObjectURL(file);
    container.classList.remove('hidden');
  }
}
</script>

<style>
.toolbar-btn{
  padding: 6px 8px;
  border-radius: 6px;
  transition: 0.2s;
}
.toolbar-btn:hover{
  background:#e5e7eb;
}

.toolbar-select{
  border:1px solid #ddd;
  border-radius:6px;
  padding:3px 6px;
  font-size:12px;
}

/* Oval Upload Button */
button[name="add_slide"]{
  border-radius: 9999px; /* oval */
  font-weight: 500;
  transition: all 0.2s;
}

button[name="add_slide"]:hover{
  background-color: #2563eb; /* darker blue on hover */
}
</style>

<script>
// Formatting
function formatDoc(command, value = null){
  document.execCommand(command, false, value);
}

// Convert editor content before submit
function prepareSubmit(){
  document.getElementById('hiddenDescription').value =
    document.getElementById('editor').innerHTML;
}

// Clear / Cancel
function clearComposer(){
  document.getElementById('editor').innerHTML = "";
  document.querySelector('input[name="caption"]').value = "";
  document.getElementById('previewContainer').classList.add('hidden');
}

</script>

<script>
// Toggle the dropdown menu
function toggleSlideMenu(id){
  const menu = document.getElementById('slide-menu-' + id);

  // Close all other menus
  document.querySelectorAll('[id^="slide-menu-"]').forEach(el => {
    if(el !== menu){
      el.classList.add('hidden','scale-95','opacity-0');
    }
  });

  // Toggle current menu
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