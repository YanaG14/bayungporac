<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require_once("../include/connection.php");

if (!isset($_SESSION['admin_user'])) {
    header('Location: index.html');
    exit();
}

// ADD DEPARTMENT
if(isset($_POST['save'])){
    $name = mysqli_real_escape_string($conn, $_POST['department_name']);
    $img = $_FILES['department_img']['name'];
    $tmp = $_FILES['department_img']['tmp_name'];

    $check = mysqli_query($conn,"SELECT * FROM departments WHERE department_name='$name'");
    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Department name already exists!');</script>";
    } else {
        if(!is_dir("department_images")){
            mkdir("department_images");
        }
        move_uploaded_file($tmp,"department_images/".$img);
        mysqli_query($conn,"INSERT INTO departments (department_name, department_img) VALUES ('$name','$img')");
        echo "<script>alert('Department Added Successfully!');</script>";
    }
}

// FETCH ACTIVE DEPARTMENTS
$query = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active' ORDER BY department_id DESC");
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
  </style>

  <script>
    $(document).ready(function(){
      $('#dtable').DataTable({ "pageLength": 10 });
      $(window).on('load', function(){ $('#loader').fadeOut('slow'); });
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
      <span>Welcome, <?php echo ucwords(htmlentities($_SESSION['admin_user'])); ?></span>
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
      <img src="js/img/municipalLogo.png" class="square-logo mb-4">
      <a href="add_document.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-file-medical"></i> Information Management</a>
      <a href="department_management.php" class="w-full px-4 py-2 bg-green-600 text-white rounded flex items-center gap-2"><i class="fas fa-building"></i> Department Management</a>
      <a href="view_admin.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-users"></i> Admin Accounts</a>
      <a href="view_user.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-users"></i> Employee Accounts</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <div class="w-3/4 flex-1">
    <div class="bg-white rounded-xl shadow-md p-6 h-full">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-building"></i> Active Departments</h2>
        <button onclick="$('#modalAddDepartment').removeClass('hidden');" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
          <i class="fas fa-plus"></i> Add Department
        </button>
        <a href="department_archive.php" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 flex items-center gap-2">
          <i class="fas fa-archive"></i> Archived Departments
        </a>
      </div>

      <!-- TABLE -->
      <div class="overflow-x-auto">
        <table id="dtable" class="min-w-full border border-gray-200">
          <thead class="bg-green-700 text-white">
            <tr>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Department Name</th>
              <th class="px-4 py-2">Image</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php while($row = mysqli_fetch_assoc($query)) { ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-2"><?php echo $row['department_id']; ?></td>
              <td class="px-4 py-2"><?php echo $row['department_name']; ?></td>
              <td class="px-4 py-2"><img src="department_images/<?php echo $row['department_img']; ?>" width="60"></td>
              <td class="px-4 py-2"><?php echo $row['department_status']; ?></td>
              <td class="px-4 py-2 text-center space-x-2">
                <a href="edit_department.php?id=<?php echo $row['department_id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"><i class="fas fa-edit"></i></a>
                <a href="archive_department.php?id=<?php echo $row['department_id']; ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Archive this department?');"><i class="fas fa-archive"></i></a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- ADD DEPARTMENT MODAL -->
<div id="modalAddDepartment" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white rounded-xl shadow-lg w-96 p-6 relative">
    <button onclick="$('#modalAddDepartment').addClass('hidden');" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
    <h3 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fas fa-plus"></i> Add Department</h3>
    <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
      <input type="text" name="department_name" placeholder="Department Name" class="border rounded px-3 py-2" required>
      <input type="file" name="department_img" class="border rounded px-3 py-2" required>
      <div class="flex justify-end gap-2">
        <button type="submit" name="save" class="bg-green-700 text-white rounded px-4 py-2 hover:bg-green-800">Save</button>
        <button type="button" onclick="$('#modalAddDepartment').addClass('hidden');" class="bg-gray-300 rounded px-4 py-2 hover:bg-gray-400">Close</button>
      </div>
    </form>
  </div>
</div>

<!-- Footer -->
<footer class="mt-8 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>

</body>
</html>