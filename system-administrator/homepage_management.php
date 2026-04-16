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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100">

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
  bg-gradient-to-r from-indigo-500/10 to-blue-500/10
  text-indigo-600 font-semibold
  transition-all duration-300
  shadow-sm hover:shadow-md">

  <i class="fas fa-house text-indigo-500 w-4 h-4"></i>
  <span class="truncate">Home Page</span>
</a>


       <a href="department_management.php"
  class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl
  transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">

  <i class="fas fa-building text-gray-500 w-4 h-4"></i>
  <span class="font-medium text-gray-700 truncate">Offices</span>
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
<div class="w-3/4 p-1 h-[calc(100vh-2rem)]">

  <!-- TABS -->
  <div class="p-6 bg-gray-50 rounded-2xl shadow-xl flex flex-col 
            w-full md:w-[1500px] h-[700px] mx-auto">

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

    <button onclick="openComposer()" 
      class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-700 hover:text-white transition shadow">
      Add Slide
    </button>
  </div>

  <!-- SLIDE LIST -->
  <div class="flex-1 overflow-y-auto flex flex-col gap-4 pr-2">

    <?php while($row = mysqli_fetch_assoc($slides)) { ?>
      <div class="bg-gray-50 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all flex flex-col gap-2 relative">

        <!-- Title & Menu -->
        <div class="flex justify-between items-start">
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-lg"><?php echo $row['caption']; ?></p>

            <p class="text-gray-500 text-sm"
               data-full="<?php echo htmlspecialchars($row['description']); ?>">
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

          <!-- 3 DOT MENU -->
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

        <!-- IMAGE -->
        <img src="../uploads/slides/<?php echo $row['image']; ?>" 
             class="w-full h-64 object-cover rounded-xl border mt-2">

      </div>
    <?php } ?>

  </div>
</div>


<script>

  // Slide Menu Toggle Function
function toggleSlideMenu(slideId) {
  const menu = document.getElementById(`slide-menu-${slideId}`);
  const allMenus = document.querySelectorAll('.slide-menu');
  
  // Close all other menus first
  allMenus.forEach(otherMenu => {
    if (otherMenu.id !== `slide-menu-${slideId}`) {
      otherMenu.classList.add('hidden', 'scale-95', 'opacity-0', 'translate-y-2');
      otherMenu.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
    }
  });
  
  // Toggle current menu
  if (menu.classList.contains('hidden')) {
    // Show menu with animation
    menu.classList.remove('hidden');
    // Force reflow
    menu.offsetHeight;
    // Trigger animation
    menu.classList.remove('scale-95', 'opacity-0', 'translate-y-2');
    menu.classList.add('scale-100', 'opacity-100', 'translate-y-0');
  } else {
    // Hide menu with animation
    menu.classList.add('scale-95', 'opacity-0', 'translate-y-2');
    setTimeout(() => {
      menu.classList.add('hidden');
    }, 250);
  }
}

// Delete Slide Function (Better UX)
function deleteSlide(slideId) {
  if (confirm('Are you sure you want to delete this slide? This action cannot be undone.')) {
    // Redirect to delete
    window.location.href = `homepage_management.php?delete_slide=${slideId}`;
  }
}

// Close menus when clicking outside
document.addEventListener('click', function(e) {
  const menus = document.querySelectorAll('.slide-menu');
  const buttons = document.querySelectorAll('[onclick^="toggleSlideMenu"]');
  
  let clickedInsideMenu = false;
  let clickedButton = false;
  
  // Check if clicked inside any menu or button
  menus.forEach(menu => {
    if (menu.contains(e.target)) clickedInsideMenu = true;
  });
  
  buttons.forEach(button => {
    if (button.contains(e.target)) clickedButton = true;
  });
  
  // Close all menus if clicked outside
  if (!clickedInsideMenu && !clickedButton) {
    menus.forEach(menu => {
      menu.classList.add('hidden', 'scale-95', 'opacity-0', 'translate-y-2');
      menu.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
    });
  }
});

// ESC Key closes all menus
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const menus = document.querySelectorAll('.slide-menu');
    menus.forEach(menu => {
      menu.classList.add('hidden', 'scale-95', 'opacity-0', 'translate-y-2');
      menu.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
    });
  }
});
</script>


<!-- MODAL BACKDROP -->
<div id="composer" class="fixed inset-0 hidden items-center justify-center z-50">

  <!-- BLUR BACKGROUND -->
  <div class="absolute inset-0 bg-black/30 backdrop-blur-md"></div>

  <!-- MODAL BOX -->
  <div class="relative w-[520px] bg-white rounded-xl shadow-2xl border flex flex-col overflow-hidden z-10">

  <!-- Header -->
  <div class="flex justify-between items-center px-4 py-2 bg-gray-100 cursor-pointer"
       onclick="toggleMinimize()">
    <span class="font-medium text-sm">New Slide</span>

    <div class="flex gap-2">
      

<button onclick="event.stopPropagation(); closeComposer()" 
        class="text-gray-500 hover:text-red-500"
        title="Close">
  <i class="fas fa-times"></i>
</button>
    </div>
  </div>

  <!-- MINIMIZED DOCK -->
<div id="composerDock"
     class="hidden fixed bottom-6 right-6 w-[260px] bg-white shadow-xl border rounded-lg px-3 py-2 cursor-pointer z-[9999]"
     onclick="restoreComposer()">

  <div class="flex justify-between items-center">
    <span class="text-sm font-medium">New Slide</span>
    <i class="fas fa-edit text-gray-500"></i>
  </div>

  <div id="dockPreview" class="text-xs text-gray-500 truncate"></div>
</div>

  <!-- BODY -->
<form method="POST" enctype="multipart/form-data" class="flex flex-col h-[420px]" id="composerBody">

  <!-- SCROLLABLE AREA -->
  <div class="flex-1 overflow-y-auto px-0">

    <!-- Caption -->
    <input 
      type="text" 
      name="caption" 
      placeholder="Caption"
      class="w-full px-4 py-2 border-b focus:outline-none sticky top-0 bg-white z-10"
      required
    >

    <!-- Description (Rich Editor) -->
    <div id="editor"
      contenteditable="true"
      class="min-h-[120px] px-4 py-3 focus:outline-none text-sm"
      placeholder="Write your description...">
    </div>

    <!-- Image Preview -->
    <div id="previewContainer" class="hidden px-4 pb-3">
      <img id="previewImg" class="w-full h-auto rounded-lg border">
    </div>

  </div>

  <!-- Hidden input -->
  <input type="hidden" name="description" id="hiddenDescription">

  <!-- FOOTER (ALWAYS VISIBLE) -->
  <div class="flex flex-wrap items-center gap-2 px-3 py-2 border-t bg-gray-50 text-sm shrink-0">

    <!-- Upload -->
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
    <button type="button" onclick="formatDoc('bold')" class="toolbar-btn"><b>B</b></button>
    <button type="button" onclick="formatDoc('italic')" class="toolbar-btn"><i>I</i></button>
    <button type="button" onclick="formatDoc('underline')" class="toolbar-btn"><u>U</u></button>

    <!-- Attach -->
    <label class="toolbar-btn cursor-pointer">
      <i class="fas fa-paperclip"></i>
      <input type="file" name="image" class="hidden" onchange="previewImage(event)" required>
    </label>

    <!-- Cancel -->
    <button type="button" onclick="clearComposer()" class="toolbar-btn text-red-500">
      <i class="fas fa-trash"></i>
    </button>


    
  </div>
</form>
  </div>
</div>

<script>
// Composer
function openComposer(){
  document.getElementById('composer').classList.remove('hidden');
  document.getElementById('composer').classList.add('flex');
}

function closeComposer(){
  document.getElementById('composer').classList.add('hidden');
  document.getElementById('composerDock').classList.add('hidden');
}

function minimizeComposer(){
  document.getElementById('composer').classList.add('hidden');
  document.getElementById('composerDock').classList.remove('hidden');
}

function restoreComposer(){
  // show modal again
  document.getElementById('composer').classList.remove('hidden');
  document.getElementById('composer').classList.add('flex');

  // hide dock
  document.getElementById('composerDock').classList.add('hidden');
}

function toggleMinimize(){
  minimizeComposer();
}

// Editor
function formatDoc(command, value = null){
  document.execCommand(command, false, value);
}

// Submit content
function prepareSubmit(){
  document.getElementById('hiddenDescription').value =
    document.getElementById('editor').innerHTML;
}

// Image preview
function previewImage(event){
  const file = event.target.files[0];
  const preview = document.getElementById('previewImg');
  const container = document.getElementById('previewContainer');

  if(file){
    preview.src = URL.createObjectURL(file);
    container.classList.remove('hidden');
  }
}

// Clear composer
function clearComposer(){
  document.getElementById('editor').innerHTML = "";
  document.querySelector('input[name="caption"]').value = "";
  document.getElementById('previewContainer').classList.add('hidden');
}

document.addEventListener("click", function(e){
  const modal = document.querySelector("#composer > div.relative");
  const overlay = document.getElementById("composer");

  if (!modal.contains(e.target) && overlay.contains(e.target)) {
    closeComposer();
  }
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
  document.querySelectorAll('.tab-content').forEach(el => {
    el.classList.add('hidden');
  });

  const active = document.getElementById(tab);
  if(active){
    active.classList.remove('hidden');
  }
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
  title: '<span style="font-size:20px; font-weight:600; color:#111827;">Edit Slide</span>',
  width: '720px',
  background: '#f9fafb',
  showClass: {
    popup: 'animate__animated animate__fadeInUp'
  },
  hideClass: {
    popup: 'animate__animated animate__fadeOutDown'
  },

  html: `
    <form id="editSlideForm" enctype="multipart/form-data"
      style="display:flex; flex-direction:column; gap:16px; text-align:left;">

      <input type="hidden" name="id" value="${data.slide_id}">

      <!-- Caption -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Caption</label>
        <input 
          name="caption" 
          value="${data.caption}"
          placeholder="Enter caption..."
          style="
            width:100%;
            margin-top:6px;
            padding:12px 14px;
            border-radius:12px;
            border:1px solid #e5e7eb;
            background:#fff;
            outline:none;
            font-size:14px;
            transition:0.2s;
          "
          onfocus="this.style.borderColor='#6366f1'"
          onblur="this.style.borderColor='#e5e7eb'"
        >
      </div>

      <!-- Description -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Description</label>
        <textarea 
          name="description"
          placeholder="Enter description..."
          style="
            width:100%;
            margin-top:6px;
            height:140px;
            padding:12px 14px;
            border-radius:12px;
            border:1px solid #e5e7eb;
            background:#fff;
            resize:none;
            outline:none;
            font-size:14px;
            transition:0.2s;
          "
          onfocus="this.style.borderColor='#6366f1'"
          onblur="this.style.borderColor='#e5e7eb'"
        >${data.description}</textarea>
      </div>

      <!-- Upload Card -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Slide Image</label>

        <div style="
          margin-top:6px;
          border:2px dashed #d1d5db;
          border-radius:14px;
          padding:18px;
          text-align:center;
          background:#fff;
          transition:0.2s;
        "
        onmouseover="this.style.borderColor='#6366f1'"
        onmouseout="this.style.borderColor='#d1d5db'">

          <input 
            type="file" 
            name="image"
            style="width:100%; font-size:13px;"
          >

          <p style="margin-top:8px; font-size:12px; color:#9ca3af;">
            PNG, JPG up to 5MB
          </p>
        </div>
      </div>

    </form>
  `,

  showCancelButton: true,
  confirmButtonText: 'Update Slide',
  cancelButtonText: 'Cancel',

  confirmButtonColor: '#6366f1',
  cancelButtonColor: '#9ca3af',

  customClass: {
    popup: 'rounded-2xl shadow-2xl'
  },

  buttonsStyling: true,

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
  title: '<span style="font-size:20px; font-weight:600; color:#111827;">Edit Profile</span>',
  width: '680px',
  background: '#f9fafb',
  showClass: {
    popup: 'animate__animated animate__fadeInUp'
  },
  hideClass: {
    popup: 'animate__animated animate__fadeOutDown'
  },

  html: `
    <form id="editProfileForm" enctype="multipart/form-data"
      style="display:flex; flex-direction:column; gap:16px; text-align:left;">

      <input type="hidden" name="id" value="${data.profile_id}">

      <!-- Name -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Name</label>
        <input 
          name="name"
          value="${data.name}"
          placeholder="Enter full name"
          style="
            width:100%;
            margin-top:6px;
            padding:12px 14px;
            border-radius:12px;
            border:1px solid #e5e7eb;
            background:#fff;
            font-size:14px;
            outline:none;
            transition:0.2s;
          "
          onfocus="this.style.borderColor='#6366f1'"
          onblur="this.style.borderColor='#e5e7eb'"
        >
      </div>

      <!-- Role -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Role</label>
        <input 
          name="role"
          value="${data.role}"
          placeholder="Enter role"
          style="
            width:100%;
            margin-top:6px;
            padding:12px 14px;
            border-radius:12px;
            border:1px solid #e5e7eb;
            background:#fff;
            font-size:14px;
            outline:none;
            transition:0.2s;
          "
          onfocus="this.style.borderColor='#6366f1'"
          onblur="this.style.borderColor='#e5e7eb'"
        >
      </div>

      <!-- Upload Card -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Profile Image</label>

        <div style="
          margin-top:6px;
          border:2px dashed #d1d5db;
          border-radius:14px;
          padding:18px;
          text-align:center;
          background:#fff;
          transition:0.2s;
        "
        onmouseover="this.style.borderColor='#6366f1'"
        onmouseout="this.style.borderColor='#d1d5db'">

          <input 
            type="file" 
            name="image"
            style="width:100%; font-size:13px;"
          >

          <p style="margin-top:8px; font-size:12px; color:#9ca3af;">
            PNG, JPG up to 5MB
          </p>
        </div>
      </div>

    </form>
  `,

  showCancelButton: true,
  confirmButtonText: 'Update Profile',
  cancelButtonText: 'Cancel',

  confirmButtonColor: '#6366f1',
  cancelButtonColor: '#9ca3af',

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
  title: '<span style="font-size:20px; font-weight:600; color:#111827;">Edit Featured</span>',
  width: '680px',
  background: '#f9fafb',
  showClass: {
    popup: 'animate__animated animate__fadeInUp'
  },
  hideClass: {
    popup: 'animate__animated animate__fadeOutDown'
  },

  html: `
    <form id="editFeaturedForm" enctype="multipart/form-data"
      style="display:flex; flex-direction:column; gap:16px; text-align:left;">

      <input type="hidden" name="id" value="${data.featured_id}">

      <!-- Title -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Title</label>
        <input 
          name="title"
          value="${data.title}"
          placeholder="Enter featured title"
          style="
            width:100%;
            margin-top:6px;
            padding:12px 14px;
            border-radius:12px;
            border:1px solid #e5e7eb;
            background:#fff;
            font-size:14px;
            outline:none;
            transition:0.2s;
          "
          onfocus="this.style.borderColor='#6366f1'"
          onblur="this.style.borderColor='#e5e7eb'"
        >
      </div>

      <!-- Upload Card -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Featured Image</label>

        <div style="
          margin-top:6px;
          border:2px dashed #d1d5db;
          border-radius:14px;
          padding:18px;
          text-align:center;
          background:#fff;
          transition:0.2s;
        "
        onmouseover="this.style.borderColor='#6366f1'"
        onmouseout="this.style.borderColor='#d1d5db'">

          <input 
            type="file" 
            name="image"
            style="width:100%; font-size:13px;"
          >

          <p style="margin-top:8px; font-size:12px; color:#9ca3af;">
            PNG, JPG up to 5MB
          </p>
        </div>
      </div>

    </form>
  `,

  showCancelButton: true,
  confirmButtonText: 'Update Featured',
  cancelButtonText: 'Cancel',

  confirmButtonColor: '#6366f1',
  cancelButtonColor: '#9ca3af',

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
  title: '<span style="font-size:20px; font-weight:600; color:#111827;">Edit Event</span>',
  width: '700px',
  background: '#f9fafb',
  showClass: {
    popup: 'animate__animated animate__fadeInUp'
  },
  hideClass: {
    popup: 'animate__animated animate__fadeOutDown'
  },

  html: `
    <form id="editEventForm"
      style="display:flex; flex-direction:column; gap:16px; text-align:left;">

      <input type="hidden" name="id" value="${data.event_id}">

      <!-- Title -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Event Title</label>
        <input 
          name="title"
          value="${data.title}"
          placeholder="Enter event title"
          style="
            width:100%;
            margin-top:6px;
            padding:12px 14px;
            border-radius:12px;
            border:1px solid #e5e7eb;
            background:#fff;
            font-size:14px;
            outline:none;
            transition:0.2s;
          "
          onfocus="this.style.borderColor='#6366f1'"
          onblur="this.style.borderColor='#e5e7eb'"
        >
      </div>

      <!-- Description -->
      <div>
        <label style="font-size:13px; font-weight:500; color:#6b7280;">Description</label>
        <textarea 
          name="description"
          placeholder="Enter event description"
          style="
            width:100%;
            height:160px;
            margin-top:6px;
            padding:12px 14px;
            border-radius:12px;
            border:1px solid #e5e7eb;
            background:#fff;
            resize:none;
            font-size:14px;
            outline:none;
            transition:0.2s;
          "
          onfocus="this.style.borderColor='#6366f1'"
          onblur="this.style.borderColor='#e5e7eb'"
        >${data.description}</textarea>
      </div>

    </form>
  `,

  showCancelButton: true,
  confirmButtonText: 'Update Event',
  cancelButtonText: 'Cancel',

  confirmButtonColor: '#6366f1',
  cancelButtonColor: '#9ca3af',

  customClass: {
    popup: 'rounded-2xl shadow-2xl'
  },

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