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
    echo "<script>alert('User Archived Successfully!'); window.location='view_user.php';</script>";
    exit();
}

// Unarchive user
if(isset($_GET['unarchive_id'])){
    $unarchive_id = mysqli_real_escape_string($conn, $_GET['unarchive_id']);
    mysqli_query($conn, "UPDATE login_user SET user_status='' WHERE id='$unarchive_id'") or die(mysqli_error($conn));
    echo "<script>alert('User Unarchived Successfully!'); window.location='view_user.php';</script>";
    exit();
}

$edit_id = '';
if(isset($_GET['id'])){
    $edit_id = mysqli_real_escape_string($conn,$_GET['id']);
}

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.html');
} else {
    $uname=$_SESSION['admin_user'];
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
        $('#modalRegisterFormss').removeClass('hidden');
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
      <span>Welcome, <?php echo ucwords(htmlentities($uname)); ?></span>
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
    <div id="sidebar" class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-600 flex flex-col items-center space-y-4 h-full">
      <img src="js/img/municipalLogo.png" class="square-logo mb-4">
      <a href="add_document.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-file-medical"></i> Information Management</a>
      <a href="department_management.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-building"></i> Department Management</a>
      <a href="view_admin.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-users"></i> Admin Accounts</a>
      <a href="view_user.php" class="w-full px-4 py-2 bg-green-600 text-white rounded flex items-center gap-2"><i class="fas fa-users"></i> Employee Accounts</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <div class="w-3/4 flex-1">
    <div id="main-content" class="bg-white rounded-xl shadow-md p-6 h-full">
      

      <div class="flex justify-end items-center gap-2">
    <button onclick="$('#modalRegisterForm2').removeClass('hidden');" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
      <i class="fas fa-user-plus"></i> Add Employee
    </button>

    <button onclick="$('#modalArchivedUsers').removeClass('hidden');" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 flex items-center gap-2">
      <i class="fas fa-archive"></i> View Archived Users
    </button>
</div>

      <!-- TABLE -->
      <div class="overflow-x-auto">
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
                <a href="view_user.php?archive_id=<?php echo htmlentities($rs['id']); ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Archive this user?');"><i class="fas fa-archive"></i></a>
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
<div id="modalRegisterForm2" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white rounded-xl shadow-lg w-96 p-6 relative">
    <button onclick="$('#modalRegisterForm2').addClass('hidden');" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
    <h3 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fas fa-user-plus"></i> Add Employee</h3>
    <form action="create_user.php" method="POST" class="flex flex-col gap-4">
      <input type="hidden" name="status" value="Employee">
      <input type="text" name="name" placeholder="Full Name" class="border rounded px-3 py-2" required>

      <select name="department_id" class="border rounded px-3 py-2" required>
  <option value="">Select Department</option>
  <?php
  $dept = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
  while($d = mysqli_fetch_array($dept)){
  ?>
    <option value="<?php echo $d['department_id']; ?>">
      <?php echo $d['department_name']; ?>
    </option>
  <?php } ?>
</select>

      <input type="email" name="email_address" placeholder="Email Address" class="border rounded px-3 py-2" required>
      <input type="password" name="user_password" placeholder="Password" class="border rounded px-3 py-2" required>
      <div class="flex justify-end gap-2">
        <button type="submit" name="reguser" class="bg-green-700 text-white rounded px-4 py-2 hover:bg-green-800">Save</button>
        <button type="button" onclick="$('#modalRegisterForm2').addClass('hidden');" class="bg-gray-300 rounded px-4 py-2 hover:bg-gray-400">Close</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT EMPLOYEE MODAL -->
<?php 
  if($edit_id != ''){
    $q = mysqli_query($conn,"select * from login_user where id = '$edit_id'") or die (mysqli_error($conn));
    $rs1 = mysqli_fetch_array($q);
    $id1 = $rs1['id'];
    $fname1 = $rs1['name'];
    $admin1 = $rs1['email_address'];
    $pass1 = $rs1['user_password'];
?>
<div id="modalRegisterFormss" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white rounded-xl shadow-lg w-96 p-6 relative">
    <button onclick="$('#modalRegisterFormss').addClass('hidden');" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
    <h3 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fas fa-user-edit"></i> Edit Employee</h3>
    <form method="POST" class="flex flex-col gap-4">
      <input type="hidden" name="id" value="<?php echo $id1;?>">
      <input type="text" name="name" value="<?php echo $fname1;?>" class="border rounded px-3 py-2">

      <select name="department_id" class="border rounded px-3 py-2">
  <option value="">Select Department</option>
  <?php
  $dept = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
  while($d = mysqli_fetch_array($dept)){
      $selected = ($rs1['department_id'] == $d['department_id']) ? "selected" : "";
  ?>
    <option value="<?php echo $d['department_id']; ?>" <?php echo $selected; ?>>
      <?php echo $d['department_name']; ?>
    </option>
  <?php } ?>
</select>

      <input type="email" name="email_address" value="<?php echo $admin1;?>" class="border rounded px-3 py-2">
      <input type="password" name="user_password" value="<?php echo $pass1;?>" class="border rounded px-3 py-2">
      <input type="text" name="status" value="Employee" class="border rounded px-3 py-2" readonly>
      <div class="flex justify-end gap-2">
        <button type="submit" name="edit" class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700">Update</button>
        <button type="button" onclick="$('#modalRegisterFormss').addClass('hidden');" class="bg-gray-300 rounded px-4 py-2 hover:bg-gray-400">Close</button>
      </div>
    </form>
  </div>
</div>
<?php } ?>

<!-- ARCHIVED USERS MODAL -->
<div id="modalArchivedUsers" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white rounded-xl shadow-lg w-11/12 max-w-4xl p-6 relative">
    <button onclick="$('#modalArchivedUsers').addClass('hidden');" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
    <h3 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fas fa-archive"></i> Archived Users</h3>
    
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-700 text-white">
          <tr>
            <th class="px-4 py-2">Full Name</th>
            <th class="px-4 py-2">Department</th>
            <th class="px-4 py-2">Email</th>
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
            <td class="px-4 py-2"><?php echo $a['name']; ?></td>
            <td class="px-4 py-2"><?php echo ($a['department_name'] != '') ? $a['department_name'] : 'No Department'; ?></td>
            <td class="px-4 py-2"><?php echo $a['email_address']; ?></td>
            <td class="px-4 py-2 text-center">
              <a href="view_user.php?unarchive_id=<?php echo $a['id']; ?>" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" onclick="return confirm('Unarchive this user?');"><i class="fas fa-undo"></i> Unarchive</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Edit Employee POST -->
<?php
if(isset($_POST['edit'])){
    $id_post = mysqli_real_escape_string($conn,$_POST['id']);
    $user_name = mysqli_real_escape_string($conn,$_POST['name']);
    $department_id = mysqli_real_escape_string($conn,$_POST['department_id']);
    $email_address = mysqli_real_escape_string($conn,$_POST['email_address']);
    $user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);

    mysqli_query($conn,"UPDATE login_user 
SET name='$user_name',
    department_id='$department_id',
    email_address='$email_address',
    user_password='$user_password'
WHERE id='$id_post'")
or die(mysqli_error($conn));

    echo "<script>alert('Success Edit User/Employee!!!');document.location='view_user.php'</script>";
    exit();
}
?>

<!-- Footer -->
<footer class="mt-8 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>

</body>
</html>