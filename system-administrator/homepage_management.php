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
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200 flex flex-col items-center h-screen">

      <!-- Logo -->
      <img src="img/adminLogo.png" class="square-logo mb-6 transition-transform duration-300 hover:scale-105">

      <!-- Menu -->
      <nav class="w-full space-y-2">
        <a href="homepage_management.php" class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl 
          bg-gray-50 shadow-md hover:bg-gray-100 hover:shadow-xl hover:-translate-y-1 
          transition-all duration-300"
>
  <i class="fas fa-home text-green-600"></i>
  <span class="font-medium">Homepage</span>
</a>
        
        <a href="department_management.php" class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50"
   >
  <i class="fas fa-building text-gray-600"></i>
  <span class="font-medium tracking-wide">Offices</span>
</a>

<a href="view_user.php" 
        class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
          <i class="fas fa-users text-gray-600 group-hover:text-green-600 transition-colors"></i>
          <span class="font-medium tracking-wide">Employees</span>
        </a>
      </nav>

        <a href="view_admin.php" 
        class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
          <i class="fas fa-users text-gray-600 group-hover:text-green-600 transition-colors"></i>
          <span class="font-medium tracking-wide">Records Administrators</span>
        </a>

        <a href="system-administrator.php" 
        class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
          <i class="fas fa-users text-gray-600 group-hover:text-green-600 transition-colors"></i>
          <span class="font-medium tracking-wide">System Administrators</span>
        </a>

        

    </div>
  </aside>

<!-- MAIN CONTENT -->
<div class="w-3/4">

  <!-- TABS -->
  <div class="flex gap-3 mb-4">
    <button class="tab-btn px-4 py-2 rounded-xl bg-white shadow active" onclick="showTab('slides')">Slides</button>
    <button class="tab-btn px-4 py-2 rounded-xl bg-white shadow" onclick="showTab('profiles')">Profiles</button>
    <button class="tab-btn px-4 py-2 rounded-xl bg-white shadow" onclick="showTab('featured')">Featured</button>
    <button class="tab-btn px-4 py-2 rounded-xl bg-white shadow" onclick="showTab('events')">Events</button>
    <button class="tab-btn px-4 py-2 rounded-xl bg-white shadow" onclick="showTab('about')">About</button>
  </div>

  <!-- SLIDES -->
  <div id="slides" class="tab-content bg-white p-6 rounded-xl shadow">
    <h2 class="font-semibold mb-4">Slides</h2>

    <form method="POST" enctype="multipart/form-data" class="flex gap-2 mb-4">
      <input type="text" name="caption" placeholder="Caption" class="border p-2 rounded" required>
      <input type="text" name="description" placeholder="Description" class="border p-2 rounded" required>
      <input type="file" name="image" required>
      <button name="add_slide" class="bg-green-600 text-white px-4 rounded">Add</button>
    </form>

    <?php while($row = mysqli_fetch_assoc($slides)) { ?>
      <div class="border p-3 mb-2 flex justify-between items-center">
        <div class="flex justify-between items-center">
          <img src="../uploads/slides/<?php echo $row['image']; ?>" width="80">
          <p><?php echo $row['caption']; ?></p>
          <div class="flex gap-2">
  <button 
    onclick="openEditSlide(<?php echo htmlspecialchars(json_encode($row)); ?>)"
    class="bg-blue-500 text-white px-3 py-1 rounded">
    Edit
  </button>

  <a href="homepage_management.php?delete_slide=<?php echo $row['slide_id']; ?>" 
     onclick="return confirm('Delete this slide?')" 
     class="bg-red-500 text-white px-3 py-1 rounded">
     Delete
  </a>
</div>
        </div>
      </div>
    <?php } ?>
  </div>

  <!-- PROFILES -->
  <div id="profiles" class="tab-content hidden bg-white p-6 rounded-xl shadow">
    <h2 class="font-semibold mb-4">Profiles</h2>

    <form method="POST" enctype="multipart/form-data" class="flex gap-2 mb-4">
      <input type="text" name="role" placeholder="Role" class="border p-2 rounded" required>
      <input type="text" name="name" placeholder="Name" class="border p-2 rounded" required>
      <input type="text" name="description" placeholder="Description" class="border p-2 rounded">
      <input type="file" name="image" required>
      <button name="add_profile" class="bg-green-600 text-white px-4 rounded">Add</button>
    </form>

    <?php while($row = mysqli_fetch_assoc($profiles)) { ?>
  <div class="border p-3 mb-2 flex justify-between items-center">
    
    <div class="flex items-center gap-4">
      <img src="../uploads/profiles/<?php echo $row['image']; ?>" width="80">
      <p><?php echo $row['name']; ?> - <?php echo $row['role']; ?></p>
    </div>

    <div class="flex gap-2">
      <button 
        onclick="openEditProfile(<?php echo htmlspecialchars(json_encode($row)); ?>)"
        class="bg-blue-500 text-white px-3 py-1 rounded">
        Edit
      </button>

      <a href="homepage_management.php?delete_profile=<?php echo $row['profile_id']; ?>" 
         onclick="return confirm('Delete this profile?')" 
         class="bg-red-500 text-white px-3 py-1 rounded">
         Delete
      </a>
    </div>

  </div>
<?php } ?>
  </div>

  <!-- FEATURED -->
  <div id="featured" class="tab-content hidden bg-white p-6 rounded-xl shadow">
  <h2 class="font-semibold mb-4">Featured</h2>

  <form method="POST" enctype="multipart/form-data" class="flex gap-2 mb-4">
    <input type="text" name="title" placeholder="Title" class="border p-2 rounded" required>
    <input type="file" name="image" required>
    <button name="add_featured" class="bg-green-600 text-white px-4 rounded">Add</button>
  </form>

  <?php while($row = mysqli_fetch_assoc($featured)) { ?>
    <div class="border p-3 mb-2 flex justify-between items-center">
      <div class="flex items-center gap-4">
        <img src="../uploads/featured/<?php echo $row['image']; ?>" width="80">
        <p><?php echo $row['title']; ?></p>
      </div>

      <div class="flex gap-2">
        <button 
          onclick="openEditFeatured(<?php echo htmlspecialchars(json_encode($row)); ?>)"
          class="bg-blue-500 text-white px-3 py-1 rounded">
          Edit
        </button>

        <a href="homepage_management.php?delete_featured=<?php echo $row['featured_id']; ?>" 
           onclick="return confirm('Delete this featured item?')" 
           class="bg-red-500 text-white px-3 py-1 rounded">
           Delete
        </a>
      </div>
    </div>
  <?php } ?>
</div>

  <!-- EVENTS -->
  <div id="events" class="tab-content hidden bg-white p-6 rounded-xl shadow">
  <h2 class="font-semibold mb-4">Events</h2>

  <form method="POST" class="flex gap-2 mb-4">
    <input type="text" name="title" placeholder="Title" class="border p-2 rounded" required>
    <input type="text" name="description" placeholder="Description" class="border p-2 rounded">
    <button name="add_event" class="bg-green-600 text-white px-4 rounded">Add</button>
  </form>

  <?php while($row = mysqli_fetch_assoc($events)) { ?>
    <div class="border p-3 mb-2 flex justify-between items-center">
      <p><?php echo $row['title']; ?></p>

      <div class="flex gap-2">
        <button 
          onclick="openEditEvent(<?php echo htmlspecialchars(json_encode($row)); ?>)"
          class="bg-blue-500 text-white px-3 py-1 rounded">
          Edit
        </button>

        <a href="homepage_management.php?delete_event=<?php echo $row['event_id']; ?>" 
           onclick="return confirm('Delete this event?')" 
           class="bg-red-500 text-white px-3 py-1 rounded">
           Delete
        </a>
      </div>
    </div>
  <?php } ?>
</div>

  <!-- ABOUT -->
  <div id="about" class="tab-content hidden bg-white p-6 rounded-xl shadow">
    <h2 class="font-semibold mb-4">About</h2>

    <form method="POST">
      <textarea name="content" class="w-full border p-3 rounded h-40"><?php echo $aboutRow['content'] ?? ''; ?></textarea>
      <button name="save_about" class="mt-3 bg-green-600 text-white px-4 py-2 rounded">Save</button>
    </form>
  </div>

</div>
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
  title: 'Edit Slide',
  html: `
    <form id="editSlideForm" enctype="multipart/form-data">
      <input type="hidden" name="id" value="${data.slide_id}">
      
      <input name="caption" class="swal2-input" value="${data.caption}">
      <input name="description" class="swal2-input" value="${data.description}">
      <input type="file" name="image" class="swal2-input">
    </form>
  `,
  showCancelButton: true,
  confirmButtonText: 'Update',
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
  title: 'Edit Profile',
  html: `
    <form id="editProfileForm" enctype="multipart/form-data">
      <input type="hidden" name="id" value="${data.profile_id}">
      
      <input name="name" class="swal2-input" value="${data.name}">
      <input name="role" class="swal2-input" value="${data.role}">
      <input type="file" name="image" class="swal2-input">
    </form>
  `,
  showCancelButton: true,
  confirmButtonText: 'Update',
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
  title: 'Edit Featured',
  html: `
    <form id="editFeaturedForm" enctype="multipart/form-data">
      <input type="hidden" name="id" value="${data.featured_id}">
      
      <input name="title" class="swal2-input" value="${data.title}">
      <input type="file" name="image" class="swal2-input">
    </form>
  `,
  showCancelButton: true,
  confirmButtonText: 'Update',
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
  html: `
    <form id="editEventForm">
      <input type="hidden" name="id" value="${data.event_id}">
      
      <input name="title" class="swal2-input" value="${data.title}">
      <input name="description" class="swal2-input" value="${data.description}">
    </form>
  `,
  showCancelButton: true,
  confirmButtonText: 'Update',
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