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
}

/* =========================
   HANDLE EVENTS
========================= */
if(isset($_POST['add_event'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn,"INSERT INTO homepage_events (title,description)
    VALUES ('$title','$desc')");
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
  <div class="flex justify-between items-center h-16 px-6">
    <div class="flex items-center space-x-3">
      <img src="js/img/municipalLogo.png" class="w-10 h-10">
      <h1 class="text-white font-semibold">Bayung Porac Archive</h1>
    </div>

    <div class="text-white flex gap-4 items-center">
      <span>Welcome, <?php echo $_SESSION['admin_name']; ?>!</span>
      <a href="#" onclick="confirmLogout()" class="bg-white text-green-700 px-3 py-1 rounded">Logout</a>
    </div>
  </div>
</nav>

<div class="mt-24 px-6 flex gap-6">

<!-- SIDEBAR -->
  <aside class="w-1/4">
  <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200 flex flex-col items-center h-[1300]">

    <!-- Logo -->
    <img src="img/adminLogo.png"
     class="square-logo mb-6 transition-transform duration-300 hover:scale-105"
     style="width:180px; height:180px; object-fit:cover; border-radius:12px;">

    <!-- Menu -->
    <nav class="w-full space-y-3">
      <!-- Homepage -->
      <a href="homepage_management.php" 
         class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl 
                bg-gray-50 shadow-md hover:bg-gray-100 hover:shadow-xl hover:-translate-y-1 
                transition-all duration-300">
        <i class="fas fa-home text-green-600"></i>
        <span class="font-medium tracking-wide">Homepage</span>
      </a>

      <!-- Offices -->
      <a href="department_management.php" 
         class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 
                hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <i class="fas fa-building text-gray-600 group-hover:text-green-600 transition-colors"></i>
        <span class="font-medium tracking-wide">Offices</span>
      </a>

      <!-- Employees -->
      <a href="view_user.php" 
         class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 
                hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <i class="fas fa-users text-gray-600 group-hover:text-green-600 transition-colors"></i>
        <span class="font-medium tracking-wide">Employees</span>
      </a>

      <!-- Records Administrators -->
      <a href="view_admin.php" 
         class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 
                hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <i class="fas fa-users text-gray-600 group-hover:text-green-600 transition-colors"></i>
        <span class="font-medium tracking-wide">Records Administrators</span>
      </a>

      <!-- System Administrators -->
      <a href="system-administrator.php" 
         class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 
                hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <i class="fas fa-users text-gray-600 group-hover:text-green-600 transition-colors"></i>
        <span class="font-medium tracking-wide">System Administrators</span>
      </a>
    </nav>

  </div>
</aside>

<!-- MAIN CONTENT -->
<div class="w-3/4 p-1 h-[calc(79vh-2rem)]">

  <!-- TABS -->
  <div class="p-6 bg-gray-50 rounded-2xl shadow-xl h-full flex flex-col">

  <!-- Tab Buttons -->
  <div class="flex flex-wrap gap-3 mb-6">

  <button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md 
hover:shadow-xl hover:-translate-y-1 
transition-all duration-200 font-medium text-gray-700 active:bg-green-600 active:text-white"
onclick="showTab('slides')">Slides</button>

<button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md 
hover:shadow-xl hover:-translate-y-1 
transition-all duration-200 font-medium text-gray-700"
onclick="showTab('profiles')">Profiles</button>

<button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md 
hover:shadow-xl hover:-translate-y-1 
transition-all duration-200 font-medium text-gray-700"
onclick="showTab('featured')">Featured</button>

<button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md 
hover:shadow-xl hover:-translate-y-1 
transition-all duration-200 font-medium text-gray-700"
onclick="showTab('events')">Announcement</button>

<button class="tab-btn px-5 py-2 rounded-2xl bg-white shadow-md 
hover:shadow-xl hover:-translate-y-1 
transition-all duration-200 font-medium text-gray-700"
onclick="showTab('about')">About</button>
</div>

<style>
  /* Active tab style */
  .tab-btn.active {
    background-color: #10B981; /* Tailwind green-500 */
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); /* stronger shadow for active tab */
  }
</style>

  <!-- SLIDES -->
 <div id="slides" class="tab-content bg-white p-6 rounded-2xl shadow-md flex flex-col flex-1 overflow-hidden">
    <h2 class="font-semibold text-xl mb-4 border-b pb-2">Slides</h2>

    <!-- Add Slide Form -->
    <form method="POST" enctype="multipart/form-data" class="flex flex-wrap gap-3 mb-6 items-center bg-gray-50 p-4 rounded-xl shadow-inner">
      <input type="text" name="caption" placeholder="Caption" class="border p-2 rounded-xl flex-1 min-w-[150px]" required>
      <input type="text" name="description" placeholder="Description" class="border p-2 rounded-xl flex-1 min-w-[150px]" required>
      <input type="file" name="image" class="border p-2 rounded-xl" required>
      <button name="add_slide" class="bg-green-600 text-white px-5 py-2 rounded-xl hover:bg-green-700 transition-colors">Add</button>
    </form>

    <!-- Slide List -->
    <div class="space-y-4 overflow-y-auto flex-1 pr-2">
      <?php while($row = mysqli_fetch_assoc($slides)) { ?>
        <div class="flex flex-wrap items-center justify-between bg-gray-50 p-4 rounded-xl shadow-sm hover:shadow-md transition-all">
          <div class="flex items-center gap-4">
            <img src="../uploads/slides/<?php echo $row['image']; ?>" class="w-20 h-20 object-cover rounded-xl border">
            <div>
              <p class="font-medium"><?php echo $row['caption']; ?></p>
              <p class="text-gray-500 text-sm"><?php echo $row['description']; ?></p>
            </div>
          </div>


          <div class="relative inline-block text-left mt-2 sm:mt-0">

  <!-- 3 horizontal lines button -->
  <button onclick="toggleSlideMenu(<?php echo $row['slide_id']; ?>)" 
          class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-xl transition">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Dropdown (LEFT side, centered vertically) -->
  <div id="slide-menu-<?php echo $row['slide_id']; ?>" 
       class="hidden absolute right-full mr-2 top-1/2 -translate-y-1/2 w-36 bg-white border rounded-xl shadow-lg z-50 p-2
              transform scale-95 opacity-0 transition-all duration-200 origin-right">

    <div class="flex flex-col gap-2">

      <!-- Edit -->
      <button 
        onclick="openEditSlide(<?php echo htmlspecialchars(json_encode($row)); ?>)" 
        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
        Edit
      </button>

      <!-- Delete -->
      <a href="homepage_management.php?delete_slide=<?php echo $row['slide_id']; ?>" 
         onclick="return confirm('Delete this slide?')" 
         class="w-full bg-gray-100 hover:bg-gray-200 text-red-600 px-4 py-2 rounded-lg text-sm transition text-center">
        Delete
      </a>

    </div>

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

  setTimeout(() => {
    menu.classList.add('hidden');
  }, 200);
}

// Close when clicking outside
document.addEventListener('click', function(e){
  document.querySelectorAll('[id^="slide-menu-"]').forEach(menu => {
    const button = menu.previousElementSibling;

    if (!menu.contains(e.target) && !button.contains(e.target)) {
      closeSlideMenu(menu);
    }
  });
});
</script>

          
        </div>
      <?php } ?>
    </div>

</div>

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
  <h2 class="font-semibold text-xl mb-6 border-b pb-2">Profiles</h2>

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

  <!-- Dropdown (LEFT side, perfectly centered vertically) -->
  <div id="menu-<?php echo $row['profile_id']; ?>" 
       class="hidden absolute right-full mr-2 top-1/2 -translate-y-1/2 w-36 bg-white border rounded-xl shadow-lg z-50 p-2
              transform scale-95 opacity-0 transition-all duration-200 origin-right">

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

  <!-- Dropdown (LEFT side, centered vertically) -->
  <div id="featured-menu-<?php echo $row['featured_id']; ?>" 
       class="hidden absolute right-full mr-2 top-1/2 -translate-y-1/2 w-36 bg-white border rounded-xl shadow-lg z-50 p-2
              transform scale-95 opacity-0 transition-all duration-200 origin-right">

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