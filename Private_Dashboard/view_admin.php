<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
require_once("../include/connection.php");

// Archive admin
if(isset($_GET['archive_id'])){
    $archive_id = mysqli_real_escape_string($conn, $_GET['archive_id']);
    mysqli_query($conn, "UPDATE admin_login SET admin_status='Archived' WHERE id='$archive_id'") or die(mysqli_error($conn));
    echo "<script>alert('Admin Archived Successfully!'); window.location='view_admin.php';</script>";
    exit();
}

// Unarchive admin
if(isset($_GET['unarchive_id'])){
    $unarchive_id = mysqli_real_escape_string($conn, $_GET['unarchive_id']);
    mysqli_query($conn, "UPDATE admin_login SET admin_status='' WHERE id='$unarchive_id'") or die(mysqli_error($conn));
    echo "<script>alert('Admin Unarchived Successfully!'); window.location='view_admin.php';</script>";
    exit();
}

$edit_id = '';
if(isset($_GET['id'])){
    $edit_id = mysqli_real_escape_string($conn,$_GET['id']);
}

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.html');
    exit();
}
$adminName = $_SESSION['admin_name'];
// EDIT ADMIN FUNCTIONALITY (unchanged)
if (isset($_POST['edit_publish'])) {
    $id_post = mysqli_real_escape_string($conn, $_POST['idtoy']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $admin_user = mysqli_real_escape_string($conn, $_POST['admin_user']);
    $admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);

    mysqli_query($conn, "UPDATE admin_login 
        SET name='$name',
            admin_user='$admin_user',
            admin_password='$admin_password'
        WHERE id='$id_post'") or die(mysqli_error($conn));

    echo "<script>alert('Admin updated successfully'); window.location='view_admin.php';</script>";
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
      background: url('img/lg.flip-book-loader.gif') center/50px no-repeat #f9f9f9;
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
        $('#modalEditAdmin').removeClass('hidden');
      <?php } ?>
    });
  </script>
</head>

<body class="bg-gray-100 font-sans">

<div id="loader"></div>

<!-- NAVBAR -->
<nav class="fixed top-0 w-full bg-green-700 shadow-lg z-50">
  <div class="flex justify-between items-center h-16 px-6">
    <div class="flex items-center space-x-3">
      <img src="js/img/municipalLogo.png" class="w-10 h-10 object-contain" alt="Logo">
      <h1 class="text-white font-semibold text-lg">Bayung Porac Archive</h1>
    </div>
    <div class="flex items-center space-x-4 text-white">
      <span>Welcome, <?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?></span>
      <a href="Logout.php" class="bg-white text-green-800 border border-green-800 px-3 py-1 rounded hover:bg-green-800 hover:text-white hover:border-white transition-colors duration-300">
        Log out
      </a>
    </div>
  </div>
</nav>

<!-- MAIN LAYOUT -->
<div class="mt-24 px-6 flex gap-6">

  <!-- SIDEBAR -->
  <aside class="w-1/4">
    <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-600 flex flex-col items-center space-y-4 h-full">
      <img src="img/adminLogo.png" class="square-logo mb-4">
      <a href="add_document.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-folder"></i> Folders</a>
      <a href="department_management.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-building"></i> Departments</a>
      <a href="view_admin.php" class="w-full px-4 py-2 bg-green-600 text-white rounded flex items-center gap-2"><i class="fas fa-users"></i> Admin Accounts</a>
      <a href="view_user.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-users"></i> Employee Accounts</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <div class="w-3/4 flex-1">
    <div class="bg-white rounded-xl shadow-md p-6 h-full">
      

      <div class="flex justify-end items-center gap-2">
    <button onclick="$('#modalAddAdmin').removeClass('hidden');" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
      <i class="fas fa-user-plus"></i> Add Admin
    </button>

    <button onclick="$('#modalArchivedAdmins').removeClass('hidden');" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 flex items-center gap-2">
      <i class="fas fa-archive"></i> View Archived Admins
    </button>
</div>

      <!-- TABLE -->
      <div class="overflow-x-none">
        <table id="dtable" class="min-w-full border border-gray-200">
          <thead class="bg-green-700 text-white">
            <tr>
              <th class="px-4 py-2">Full Name</th>
              <th class="px-4 py-2">Email Address</th>
              <th class="px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
          <?php
            $query = "SELECT * FROM admin_login WHERE admin_status != 'Archived'";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)){
          ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['admin_user']); ?></td>
              <td class="px-4 py-2 text-center space-x-2">
                <a href="view_admin.php?id=<?php echo $row['id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"><i class="fas fa-edit"></i></a>
                <a href="view_admin.php?archive_id=<?php echo $row['id']; ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Archive this admin?');"><i class="fas fa-archive"></i></a>
              </td>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

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
      <input type="password" name="admin_password" placeholder="Password" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="reg" class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">Save</button>
        <button type="button" onclick="document.getElementById('modalAddAdmin').classList.add('hidden');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">Close</button>
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
      <input type="password" name="admin_password" placeholder="Password" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="edit_publish" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">Update</button>
        <button type="button" onclick="document.getElementById('modalEditAdmin').classList.add('hidden');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">Close</button>
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
              <a href="view_admin.php?unarchive_id=<?php echo $a['id']; ?>" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" onclick="return confirm('Unarchive this admin?');">
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

<!-- Footer -->
<footer class="mt-8 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>

</body>
</html>