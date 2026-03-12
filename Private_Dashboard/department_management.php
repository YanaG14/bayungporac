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

// UPDATE DEPARTMENT FROM MODAL
if(isset($_POST['update_department'])){
    $id = intval($_POST['update_department']);
    $name = mysqli_real_escape_string($conn, $_POST['department_name']);

    // Check duplicate except current ID
    $check = mysqli_query($conn,"SELECT * FROM departments 
                                 WHERE department_name='$name' 
                                 AND department_id != '$id'");

    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Department name already exists!');</script>";
    } else {
        if(!empty($_FILES['department_img']['name'])){
            $img = $_FILES['department_img']['name'];
            $tmp = $_FILES['department_img']['tmp_name'];
            move_uploaded_file($tmp,"department_images/".$img);

            mysqli_query($conn,"UPDATE departments 
                                SET department_name='$name', department_img='$img'
                                WHERE department_id='$id'");
        } else {
            mysqli_query($conn,"UPDATE departments 
                                SET department_name='$name'
                                WHERE department_id='$id'");
        }

        echo "<script>alert('Department Updated Successfully!'); 
              window.location='department_management.php';</script>";
    }
}

// FETCH ARCHIVED DEPARTMENTS
$archived_query = mysqli_query($conn,"SELECT * FROM departments 
                                      WHERE department_status='Archived' 
                                      ORDER BY department_id DESC");

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
      <img src="img/adminLogo.png" class="square-logo mb-4">
      <a href="folder_management.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-folder"></i> Folders</a>
      <a href="department_management.php" class="w-full px-4 py-2 bg-green-600 text-white rounded flex items-center gap-2"><i class="fas fa-building"></i> Departments</a>
      <a href="view_admin.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-users"></i> Admin Accounts</a>
      <a href="view_user.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-users"></i> Employee Accounts</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <div class="w-3/4 flex-1">
  <div class="bg-white rounded-xl shadow-md p-6 h-full">
    <div class="flex justify-between items-center mb-4">
      
      <!-- Title -->
      <h2 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
        <i class="fas fa-building"></i> Active Departments
      </h2>

      <!-- Buttons grouped with gap -->
      <div class="flex items-center gap-3">
        <button onclick="document.getElementById('modalAddDepartment').classList.remove('hidden');" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
          <i class="fas fa-plus"></i> Add Department
        </button>
        <button onclick="document.getElementById('modalArchivedDepartments').classList.remove('hidden');" 
        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 flex items-center gap-2">
  <i class="fas fa-archive"></i> Archived Departments
</button>
      </div>

    </div>

      <!-- TABLE -->
      <div class="overflow-x-none">
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
                <button onclick="document.getElementById('modalEditDepartment<?php echo $row['department_id']; ?>').classList.remove('hidden');" 
        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
  <i class="fas fa-edit"></i>
</button>
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
<!-- Add Department Modal -->
<div id="modalAddDepartment" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[90%] max-w-md p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalAddDepartment').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-plus text-green-600"></i> Add Department
    </h3>

    <!-- Form -->
    <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
      <input type="text" name="department_name" placeholder="Department Name" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>
      <input type="file" name="department_img" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="save" class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">Save</button>
        <button type="button" onclick="document.getElementById('modalAddDepartment').classList.add('hidden');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">Close</button>
      </div>
    </form>
  </div>
</div>

<?php
// Generate edit modals for each active department
mysqli_data_seek($query, 0); // Reset pointer to the start
while($row_edit = mysqli_fetch_assoc($query)) { ?>
<div id="modalEditDepartment<?php echo $row_edit['department_id']; ?>" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm overflow-auto">
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[90%] max-w-md p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalEditDepartment<?php echo $row_edit['department_id']; ?>').classList.add('hidden');" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-edit text-blue-600"></i> Edit Department
    </h3>

    <!-- Edit Form -->
    <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
      <input type="hidden" name="department_id" value="<?php echo $row_edit['department_id']; ?>">
      
      <label class="font-semibold">Department Name</label>
      <input type="text" name="department_name" value="<?php echo $row_edit['department_name']; ?>" 
             class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>
      
      <label class="font-semibold">Current Image</label>
      <img src="department_images/<?php echo $row_edit['department_img']; ?>" width="80">

      <label class="font-semibold">Change Image (Optional)</label>
      <input type="file" name="department_img" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">

      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="update_department" value="<?php echo $row_edit['department_id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">
          Update
        </button>
        <button type="button" onclick="document.getElementById('modalEditDepartment<?php echo $row_edit['department_id']; ?>').classList.add('hidden');" 
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">
          Close
        </button>
      </div>
    </form>

  </div>
</div>
<?php } ?>

<!-- ARCHIVED DEPARTMENTS MODAL -->
<div id="modalArchivedDepartments" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm overflow-auto">
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-[95%] max-w-5xl p-6 relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalArchivedDepartments').classList.add('hidden');" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-archive text-yellow-500"></i> Archived Departments
    </h3>

    <!-- Archived Departments Table -->
    <div class="overflow-x-auto max-h-[70vh]">
      <table class="min-w-full border border-gray-200">
        <thead class="bg-yellow-500 text-white">
          <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Department Name</th>
            <th class="px-4 py-2">Image</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2 text-center">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <?php while($row = mysqli_fetch_assoc($archived_query)) { ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2"><?php echo $row['department_id']; ?></td>
            <td class="px-4 py-2"><?php echo $row['department_name']; ?></td>
            <td class="px-4 py-2">
              <img src="department_images/<?php echo $row['department_img']; ?>" width="60">
            </td>
            <td class="px-4 py-2"><?php echo $row['department_status']; ?></td>
            <td class="px-4 py-2 text-center">
              <a href="unarchive_department.php?id=<?php echo $row['department_id']; ?>" 
                 class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
                 onclick="return confirm('Unarchive this department?');">
                 <i class="fas fa-undo"></i>
              </a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Close Button -->
    <div class="flex justify-end mt-4">
      <button onclick="document.getElementById('modalArchivedDepartments').classList.add('hidden');" 
              class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">
        Close
      </button>
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