<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
require_once("../include/connection.php");

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

  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dyZ6e1hX5IxQhGZf1iP2r+1SgiO8l4J7Lk2STJ+Sj2rT9HBjlA0TzHhRgH5hO+hF3f4+ziG4o1M0s7KfGk4d0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    #loader {
      @apply fixed inset-0 z-50 bg-gray-100 bg-center bg-no-repeat;
      background-image: url('img/lg.flip-book-loader.gif');
      background-size: 50px;
    }
    .glass-card {
      @apply bg-white/10 backdrop-blur-md rounded-2xl shadow-xl p-6;
    }
    .sidebar-logo {
      @apply w-16 h-16 object-contain mx-auto my-4;
    }
    .sidebar-link:hover {
      @apply bg-green-600 text-white;
    }
    .modal-backdrop {
      @apply absolute inset-0 bg-black bg-opacity-50;
    }
  </style>

  <script>
    $(document).ready(function(){
      $('#dtable').DataTable({
        "aLengthMenu": [[5,10,15,25,50,100,-1],[5,10,15,25,50,100,"All"]],
        "iDisplayLength": 10
      });

      $(window).on('load', function(){
        $('#loader').fadeOut('slow');
      });

      <?php if($edit_id != '') { ?>
        $('#modalRegisterFormss').show();
      <?php } ?>
    });
  </script>
</head>

<body class="bg-gray-100">

<div id="loader"></div>

<!-- Navbar -->
<header class="fixed top-0 w-full bg-green-600 text-white shadow-md z-40">
  <div class="container mx-auto flex justify-between items-center px-4 py-4">
    <div class="flex items-center space-x-4">
      <img src="js/img/municipalLogo.png" class="w-12 h-12 object-contain" alt="Logo">
      <span class="font-bold text-lg">MUNICIPALITY OF PORAC</span>
    </div>
    <div class="flex items-center space-x-4">
      <span>Welcome, <?php echo ucwords(htmlentities($uname)); ?></span>
      <a href="logout.php" class="flex items-center px-3 py-1 border border-white rounded hover:bg-white hover:text-green-600 transition">
        <i class="far fa-user-circle mr-1"></i> Log out
      </a>
    </div>
  </div>
</header>

<!-- Sidebar -->
<aside class="fixed top-20 left-0 w-64 h-full bg-white shadow-md p-4 hidden md:block">
  <img src="js/img/municipalLogo.png" class="sidebar-logo" alt="Logo">
  <nav class="mt-6 flex flex-col space-y-2">
    <a href="add_document.php" class="sidebar-link px-3 py-2 rounded flex items-center space-x-2">
      <i class="fas fa-file-medical"></i> <span>Information Management</span>
    </a>
    <a href="department_management.php" class="sidebar-link px-3 py-2 rounded flex items-center space-x-2">
      <i class="fas fa-building"></i> <span>Department Management</span>
    </a>
    <a href="view_admin.php" class="sidebar-link px-3 py-2 rounded flex items-center space-x-2">
      <i class="fas fa-users"></i> <span>Admin Accounts</span>
    </a>
    <a href="view_user.php" class="sidebar-link bg-green-600 text-white px-3 py-2 rounded flex items-center space-x-2">
      <i class="fas fa-users"></i> <span>Employee Accounts</span>
    </a>
  </nav>
</aside>

<!-- Main Content -->
<main class="ml-0 md:ml-64 pt-28 px-4">
  <div class="glass-card">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">User Account Management</h2>
      <button type="button" onclick="$('#modalRegisterForm2').show();" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500 transition flex items-center space-x-2">
        <i class="fas fa-user-plus"></i> <span>Add Employee</span>
      </button>
    </div>

    <div class="overflow-x-auto">
      <table id="dtable" class="min-w-full divide-y divide-gray-200 table-auto">
        <thead class="bg-green-600 text-white">
          <tr>
            <th class="px-4 py-2 text-left">Full Name</th>
            <th class="px-4 py-2 text-left">Email Address</th>
            <th class="px-4 py-2 text-center">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php
            $query="SELECT * FROM login_user";
            $result=mysqli_query($conn,$query);
            while($rs=mysqli_fetch_array($result)){
                $id = $rs['id'];
                $fname = $rs['name'];
                $admin = $rs['email_address'];
          ?>
          <tr>
            <td class="px-4 py-2"><?php echo $fname; ?></td>
            <td class="px-4 py-2"><?php echo $admin; ?></td>
            <td class="px-4 py-2 text-center space-x-2">
              <a href="view_user.php?id=<?php echo $rs['id']; ?>" class="text-blue-600 hover:text-blue-800"><i class="fas fa-user-edit"></i></a>
              <a href="delete_user.php?id=<?php echo htmlentities($rs['id']); ?>" class="text-red-600 hover:text-red-800"><i class="far fa-trash-alt"></i></a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer class="mt-8 text-center text-gray-600">
    <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
  </footer>
</main>

<!-- Add Employee Modal -->
<div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="modalRegisterForm2">
  <div class="modal-backdrop"></div>
  <div class="glass-card relative z-10 w-full max-w-md">
    <form action="create_user.php" method="POST" class="space-y-4">
      <div class="flex justify-between items-center">
        <h4 class="font-bold text-lg"><i class="fas fa-user-plus"></i> Add Employee</h4>
        <button type="button" onclick="$('#modalRegisterForm2').hide();" class="text-gray-700">&times;</button>
      </div>
      <input type="hidden" name="status" value="Employee">
      <input type="text" name="name" placeholder="Full Name" class="w-full p-2 border rounded" required>
      <input type="email" name="email_address" placeholder="Email Address" class="w-full p-2 border rounded" required>
      <input type="password" name="user_password" placeholder="Password" class="w-full p-2 border rounded" required>
      <div class="flex justify-center">
        <button type="submit" name="reguser" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Sign Up</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Employee Modal -->
<?php 
  if($edit_id != ''){
    $q = mysqli_query($conn,"select * from login_user where id = '$edit_id'") or die (mysqli_error($conn));
    $rs1 = mysqli_fetch_array($q);
    $id1 = $rs1['id'];
    $fname1 = $rs1['name'];
    $admin1 = $rs1['email_address'];
    $pass1 = $rs1['user_password'];
?>
<div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="modalRegisterFormss">
  <div class="modal-backdrop"></div>
  <div class="glass-card relative z-10 w-full max-w-md">
    <form method="POST" class="space-y-4">
      <div class="flex justify-between items-center">
        <h4 class="font-bold text-lg"><i class="fas fa-user-edit"></i> Edit Employee</h4>
        <button type="button" onclick="$('#modalRegisterFormss').hide();" class="text-gray-700">&times;</button>
      </div>
      <input type="hidden" name="id" value="<?php echo $id1;?>">
      <input type="text" name="name" value="<?php echo $fname1;?>" class="w-full p-2 border rounded">
      <input type="email" name="email_address" value="<?php echo $admin1;?>" class="w-full p-2 border rounded">
      <input type="password" name="user_password" value="<?php echo $pass1;?>" class="w-full p-2 border rounded">
      <input type="text" name="status" value="Employee" class="w-full p-2 border rounded" readonly>
      <div class="flex justify-center">
        <button type="submit" name="edit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">UPDATE</button>
      </div>
    </form>
  </div>
</div>
<?php } ?>

<?php 
if(isset($_POST['edit'])){
    $id_post = mysqli_real_escape_string($conn,$_POST['id']);
    $user_name = mysqli_real_escape_string($conn,$_POST['name']);
    $email_address = mysqli_real_escape_string($conn,$_POST['email_address']);
    $user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);

    mysqli_query($conn,"UPDATE login_user 
        SET name='$user_name',
            email_address='$email_address',
            user_password='$user_password'
        WHERE id='$id_post'") or die(mysqli_error($conn));

    echo "<script>alert('Success Edit User/Employee!!!');document.location='view_user.php'</script>";
    exit();
}
?>

</body>
</html>