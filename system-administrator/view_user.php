<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
require_once("../include/connection.php");

// Archive user
if(isset($_GET['archive_id'])){
    $archive_id = mysqli_real_escape_string($conn, $_GET['archive_id']);
    mysqli_query($conn, "UPDATE login_user SET user_status='Archived' WHERE id='$archive_id'") or die(mysqli_error($conn));
    $_SESSION['toast'] = 'User Archived Successfully!';
    header('Location: view_user.php');
    exit();
}

// Unarchive user
if(isset($_GET['unarchive_id'])){
    $unarchive_id = mysqli_real_escape_string($conn, $_GET['unarchive_id']);
    mysqli_query($conn, "UPDATE login_user SET user_status='' WHERE id='$unarchive_id'") or die(mysqli_error($conn));
    $_SESSION['toast'] = 'User Unarchived Successfully!';
    header('Location: view_user.php');
    exit();
}

$edit_id = '';
if(isset($_GET['id'])){
    $edit_id = mysqli_real_escape_string($conn,$_GET['id']);
}
 
if (!isset($_SESSION['admin_user'])) {
    header('Location: index.php');
} else {
    $uname=$_SESSION['admin_user'];
}

$adminName = $_SESSION['admin_name'];
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
    .sidebar-link:hover { @apply bg-gray-100; }
  </style>

  <script>
    $(document).ready(function(){
      $('#dtable').DataTable({
        "pageLength": 10
      });

      $(window).on('load', function(){ $('#loader').fadeOut('slow'); });

      <?php if($edit_id != '') { ?>
        $('#modalRegisterFormss').removeClass('hidden');
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
-->>

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
  <div class="flex justify-between items-center h-16 px-6">
    <div class="flex items-center space-x-3">
      <img src="js/img/municipalLogo.png" class="w-10 h-10 object-contain">
      <h1 class="text-white font-semibold text-lg">Bayung Porac Archive</h1>
    </div>
    <div class="flex items-center space-x-4 text-white">
      <span>Welcome, <?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?>!</span>
     <a href="#" onclick="confirmLogout(this)" class="bg-white text-green-800 border border-green-800 px-3 py-1 rounded hover:bg-green-800 hover:text-white hover:border-white transition-colors duration-300">
  Log out
</a>
    </div>
  </div>
</nav>

<!-- MAIN LAYOUT -->
<div class="mt-24 px-6 flex gap-6">

  <!-- SIDEBAR -->
  <aside class="w-1/4">
    <div id="sidebar" class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200 flex flex-col items-center h-full">

      <!-- Logo -->
      <img src="img/adminLogo.png" class="square-logo mb-6 transition-transform duration-300 hover:scale-105">

      <!-- Menu -->
      <nav class="w-full space-y-2">
       <a href="homepage_management.php" 
class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
          <i class="fas fa-users text-gray-600 group-hover:text-green-600 transition-colors"></i>
          <span class="font-medium tracking-wide">Homepage</span>
</a>


        <a href="department_management.php" 
        class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
          <i class="fas fa-building text-gray-600 group-hover:text-green-600 transition-colors"></i>
          <span class="font-medium tracking-wide">Offices</span>
        </a>

        <a href="view_user.php" 
   class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl 
          bg-gray-50 shadow-md hover:bg-gray-100 hover:shadow-xl hover:-translate-y-1 
          transition-all duration-300">
  <i class="fas fa-users text-green-600"></i>
  <span class="font-medium tracking-wide">Employees</span>
</a>


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
        
      </nav>

    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <div class="w-3/4 flex-1">
    <div id="main-content" class="bg-white rounded-2xl shadow-lg p-6 h-full transition-all duration-300 hover:shadow-xl">

      <div class="flex justify-end items-center gap-3">

        <!-- Add Employee Button -->
        <button onclick="$('#modalRegisterForm2').removeClass('hidden');" 
        class="bg-gradient-to-r from-green-600 to-green-500 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300">
          <i class="fas fa-user-plus"></i> Add Employee
        </button>

        <!-- View Archived Employees Button -->
        <button onclick="$('#modalArchivedUsers').removeClass('hidden');" 
        class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300">
          <i class="fas fa-archive"></i> View Archived Users
        </button>

      </div>

      <!-- TABLE -->
      <div class="overflow-x-none">
        <table id="dtable" class="min-w-full border border-gray-200">
          <thead class="bg-green-700 text-white">
            <tr>
  <th class="px-4 py-2">Full Name</th>
  <th class="px-4 py-2">Department</th>
  <th class="px-4 py-2">Email Address</th>
  <th class="px-4 py-2 text-center">Action</th>
</tr>
          </thead>
          <tbody class="text-gray-700">
          <?php
            $query="SELECT login_user.*, departments.department_name 
        FROM login_user 
        LEFT JOIN departments 
        ON login_user.department_id = departments.department_id
        WHERE user_status != 'Archived'";
            $result=mysqli_query($conn,$query);
            while($rs=mysqli_fetch_array($result)){
                $id = $rs['id'];
                $fname = $rs['name'];
                $department = $rs['department_name'];
                $admin = $rs['email_address'];
          ?>
            <tr class="border-b hover:bg-gray-50">
  <td class="px-4 py-2"><?php echo $fname; ?></td>
  <td class="px-4 py-2">
    <?php echo ($department != '') ? $department : 'No Department'; ?>
  </td>
  <td class="px-4 py-2"><?php echo $admin; ?></td>
              <td class="px-4 py-2 text-center space-x-2">
                <a href="view_user.php?id=<?php echo $rs['id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"><i class="fas fa-edit"></i></a>
                <button onclick="confirmArchive(<?php echo $rs['id']; ?>)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
    <i class="fas fa-archive"></i>
</button>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- ADD EMPLOYEE MODAL -->
<!-- Add Employee Modal -->
<div id="modalRegisterForm2" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[90%] max-w-md p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalRegisterForm2').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-user-plus text-green-600"></i> Add Employee
    </h3>

    <!-- Form -->
    <form action="create_user.php" method="POST" class="flex flex-col gap-4">
      <input type="hidden" name="status" value="Employee">

      <input type="text" name="name" placeholder="Full Name" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>

      <select name="department_id" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>
        <option value="">Select Department</option>
        <?php
        $dept = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
        while($d = mysqli_fetch_array($dept)){
        ?>
          <option value="<?php echo $d['department_id']; ?>">
            <?php echo htmlspecialchars($d['department_name'], ENT_QUOTES); ?>
          </option>
        <?php } ?>
      </select>

      <input type="email" name="email_address" placeholder="Email Address" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>
     <div class="relative">
  <input type="password" 
name="user_password" 
id="add_user_password" 
placeholder="Password"

class="border border-gray-300 rounded-lg px-4 py-2 pr-10 w-full focus:ring-2 focus:ring-green-500 focus:outline-none transition"
pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
title="Password must be at least 8 characters, include uppercase, lowercase, number and a symbol"
required>


  <button type="button" onclick="togglePassword('add_user_password','toggleIconAddUser')"
    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800">
    <i id="toggleIconAddUser" class="fas fa-eye"></i>
  </button>
  </div>
  <p id="userPasswordHelp" class="text-red-600 text-sm mt-1 hidden">
  Password must be at least 8 characters, include uppercase, lowercase, number, and a symbol.
</p>


      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="reguser" class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">Save</button>
        <button type="button" onclick="document.getElementById('modalRegisterForm2').classList.add('hidden');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">Close</button>
      </div>
    </form>
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

<!-- EDIT EMPLOYEE MODAL -->
<?php 
if($edit_id != ''){
  $q = mysqli_query($conn,"SELECT * FROM login_user WHERE id = '$edit_id'") or die(mysqli_error($conn));
  $rs1 = mysqli_fetch_array($q);
  $id1 = $rs1['id'];
  $fname1 = htmlspecialchars($rs1['name'], ENT_QUOTES);
  $admin1 = htmlspecialchars($rs1['email_address'], ENT_QUOTES);
  $pass1 = htmlspecialchars($rs1['user_password'], ENT_QUOTES);
?>
<!-- Edit Employee Modal -->
<div id="modalRegisterFormss" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[90%] max-w-md p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalRegisterFormss').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-user-edit text-blue-600"></i> Edit Employee
    </h3>

    <!-- Form -->
    <form method="POST" class="flex flex-col gap-4">
      <input type="hidden" name="id" value="<?php echo $id1; ?>">

      <input type="text" name="name" value="<?php echo $fname1; ?>" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>

      <select name="department_id" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>
        <option value="">Select Department</option>
        <?php
        $dept = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
        while($d = mysqli_fetch_array($dept)){
            $selected = ($rs1['department_id'] == $d['department_id']) ? "selected" : "";
        ?>
          <option value="<?php echo $d['department_id']; ?>" <?php echo $selected; ?>>
            <?php echo htmlspecialchars($d['department_name'], ENT_QUOTES); ?>
          </option>
        <?php } ?>
      </select>

      <input type="email" name="email_address" value="<?php echo $admin1; ?>" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>
      
      <div class="relative">
  <input type="password" 
name="user_password" 
id="edit_user_password" 
placeholder="Leave blank to keep current password"
class="border border-gray-300 rounded-lg px-4 py-2 pr-10 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
title="Password must be at least 8 characters, include uppercase, lowercase, number and a symbol">
  <button type="button" onclick="togglePassword('edit_user_password','toggleIconEditUser')"
    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800">
    <i id="toggleIconEditUser" class="fas fa-eye"></i>
  </button>
</div>

      <input type="hidden" name="status" value="Employee">

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="edit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">Update</button>
        <button type="button" onclick="document.getElementById('modalRegisterFormss').classList.add('hidden');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">Close</button>
      </div>
    </form>
  </div>
</div>
<?php } ?>

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

<!-- ARCHIVED USERS MODAL -->
<!-- Archived Users Modal -->
<div id="modalArchivedUsers" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-11/12 max-w-4xl p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalArchivedUsers').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-archive text-gray-700"></i> Archived Users
    </h3>

    <!-- Table Content -->
    <div class="overflow-x-auto max-h-[60vh]">
      <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-700 text-white">
          <tr>
            <th class="px-4 py-2 text-left">Full Name</th>
            <th class="px-4 py-2 text-left">Department</th>
            <th class="px-4 py-2 text-left">Email</th>
            <th class="px-4 py-2 text-center">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
        <?php
          $archived = mysqli_query($conn,"SELECT login_user.*, departments.department_name 
                                           FROM login_user 
                                           LEFT JOIN departments 
                                           ON login_user.department_id = departments.department_id
                                           WHERE user_status='Archived'");
          while($a = mysqli_fetch_array($archived)){
        ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2"><?php echo htmlspecialchars($a['name'], ENT_QUOTES); ?></td>
            <td class="px-4 py-2"><?php echo ($a['department_name'] != '') ? htmlspecialchars($a['department_name'], ENT_QUOTES) : 'No Department'; ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($a['email_address'], ENT_QUOTES); ?></td>
            <td class="px-4 py-2 text-center">
              <button onclick="confirmUnarchive(<?php echo $a['id']; ?>)" 
        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
    <i class="fas fa-undo"></i> Unarchive
</button>
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

<!-- Edit Employee POST -->
<?php
if(isset($_POST['edit'])){
    $id_post = mysqli_real_escape_string($conn,$_POST['id']);
    $user_name = mysqli_real_escape_string($conn,$_POST['name']);
    $department_id = mysqli_real_escape_string($conn,$_POST['department_id']);
    $email_address = mysqli_real_escape_string($conn,$_POST['email_address']);
   $user_password_raw = $_POST['user_password'];

// Password validation regex
$pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/';

// Start query
$update_query = "UPDATE login_user SET 
    name='$user_name',
    department_id='$department_id',
    email_address='$email_address'";

// Only update password if not empty
if (!empty($user_password_raw)) {

    if (!preg_match($pattern, $user_password_raw)) {
        echo "<script>alert('Password must be at least 8 characters, include uppercase, lowercase, number, and a symbol.'); window.history.back();</script>";
        exit();
    }

    $user_password_hashed = password_hash($user_password_raw, PASSWORD_DEFAULT);
    $update_query .= ", user_password='$user_password_hashed'";
}

$update_query .= " WHERE id='$id_post'";

mysqli_query($conn, $update_query) or die(mysqli_error($conn));

    mysqli_query($conn,"UPDATE login_user 
SET name='$user_name',
    department_id='$department_id',
    email_address='$email_address',
    user_password='$user_password'
WHERE id='$id_post'")
or die(mysqli_error($conn));

      $_SESSION['toast'] = 'User Updated Successfully!';
    header('Location: view_user.php');
    exit();
}
?>


<script>
<?php if(isset($_SESSION['toast'])): ?>
Swal.fire({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: false,
    icon: 'success',
    title: '<?php echo $_SESSION['toast']; ?>'
});
<?php unset($_SESSION['toast']); endif; ?>
</script>
<script>
const userPasswordInput = document.getElementById('add_user_password');
const userPasswordHelp = document.getElementById('userPasswordHelp');

userPasswordInput.addEventListener('input', function() {
    const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

    if (userPasswordInput.value === "" || pattern.test(userPasswordInput.value)) {
        userPasswordHelp.classList.add('hidden');
    } else {
        userPasswordHelp.classList.remove('hidden');
    }
});
</script>
<script>
function confirmArchive(id){
    Swal.fire({
        title: 'Archive this user?',
        showCancelButton: true,
        confirmButtonText: 'Yes, Archive',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
    }).then((result) => {
        if(result.isConfirmed){
            window.location.href = 'view_user.php?archive_id=' + id;
        }
    });
}

function confirmUnarchive(id){
    Swal.fire({
        title: 'Unarchive this user?',
        showCancelButton: true,
        confirmButtonText: 'Yes, Unarchive',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
    }).then((result) => {
        if(result.isConfirmed){
            window.location.href = 'view_user.php?unarchive_id=' + id;
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
function togglePassword(fieldId, iconId) {
    const passwordField = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if(passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>


<!-- Footer -->
<footer class="mt-8 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>

</body>
</html>