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
require_once("../include/connection.php");
// ADD DEPARTMENT
if(isset($_POST['save'])){
    $name = mysqli_real_escape_string($conn, $_POST['department_name']);
    $img = $_FILES['department_img']['name'];
    $tmp = $_FILES['department_img']['tmp_name'];

    $check = mysqli_query($conn,"SELECT * FROM departments WHERE department_name='$name'");
    if(mysqli_num_rows($check) > 0){
        $_SESSION['duplicate'] = "Department name already exist!";
    } else {
        if(!is_dir("department_images")){
            mkdir("department_images");
        }
        move_uploaded_file($tmp,"department_images/".$img);
        mysqli_query($conn,"INSERT INTO departments (department_name, department_img) VALUES ('$name','$img')");
        $_SESSION['added'] = "Department Successfully Added!";
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
        $_SESSION['duplicate'] = "Department name already exist!";
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

       $_SESSION['success'] = "Department Updated Successfully!";
header("Location: department_management.php");
exit();
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
  </style>

  <script>
    $(document).ready(function(){
      $('#dtable').DataTable({ "pageLength": 10 });
      $(window).on('load', function(){ $('#loader').fadeOut('slow'); });
    });
  </script>
</head>

<body class="bg-gray-100 font-sans">

<div id="loader" class="fixed inset-0 bg-white flex justify-center items-center z-50">
  <div class="flex space-x-2">
    <span class="dot animate-bounce-delay bg-green-600 w-4 h-4 rounded-full"></span>
    <span class="dot animate-bounce-delay bg-green-600 w-4 h-4 rounded-full animation-delay-100"></span>
    <span class="dot animate-bounce-delay bg-green-600 w-4 h-4 rounded-full animation-delay-200"></span>
  </div>
</div>

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
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200 flex flex-col items-center h-screen">

      <!-- Logo -->
      <img src="img/adminLogo.png" class="square-logo mb-6 transition-transform duration-300 hover:scale-105">

      <!-- Menu -->
      <nav class="w-full space-y-2">
        <!-- <a href="folder_management.php" 
        class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
          <i class="fas fa-folder text-gray-600 group-hover:text-green-600 transition-colors"></i>
          <span class="font-medium tracking-wide">Folders</span>
        </a> -->

        
        <a href="department_management.php" 
   class="group flex items-center gap-3 w-full px-4 py-3 rounded-xl 
          bg-gray-50 shadow-md hover:bg-gray-100 hover:shadow-xl hover:-translate-y-1 
          transition-all duration-300">
  <i class="fas fa-building text-green-600"></i>
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
  <div class="w-3/4 flex-1">
    <div class="bg-white rounded-2xl shadow-lg p-6 h-104 transition-all duration-300 hover:shadow-xl">

      <div class="flex justify-between items-center mb-4">
        
        <!-- Title -->
        <h2 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
          <i class="fas fa-building text-green-600"></i> Active Departments
        </h2>

        <!-- Buttons grouped with gap -->
        <div class="flex items-center gap-3">
          <button onclick="document.getElementById('modalAddDepartment').classList.remove('hidden');" 
          class="bg-gradient-to-r from-green-600 to-green-500 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300">
            <i class="fas fa-plus"></i> Add Department
          </button>

          <button onclick="document.getElementById('modalArchivedDepartments').classList.remove('hidden');" 
          class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300">
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
                <a href="#" 
class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" 
onclick="confirmArchiveDepartment(<?php echo $row['department_id']; ?>)">
<i class="fas fa-archive"></i>
</a>
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
              <a href="#"
class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
onclick="confirmUnarchiveDepartment(<?php echo $row['department_id']; ?>)">
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

<!-- NOTIFICATION -->
 <!-- update -->
<?php if(isset($_SESSION['success'])){ ?>
echo "<script>
const Toast = Swal.mixin({
  toast: true,
 position: 'top',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: false,
  showClass: {
    popup: ''
  },
  hideClass: {
    popup: ''
  }
});

Toast.fire({
  icon: 'success',
  title: 'Department Updated Successfully'
});
</script>";
<?php unset($_SESSION['success']); } ?>

<!-- duplicate -->
<?php if(isset($_SESSION['duplicate'])){ ?>
echo "<script>
const Toast = Swal.mixin({
  toast: true,
  position: 'top',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: false,
  showClass: {
    popup: ''
  },
  hideClass: {
    popup: ''
  }
});

Toast.fire({
  icon: 'error',
  title: 'Department name already exist'
});
</script>";
<?php unset($_SESSION['duplicate']); } ?>

<!-- add -->
<?php if(isset($_SESSION['added'])){ ?>
echo "<script>
const Toast = Swal.mixin({
  toast: true,
  position: 'top',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: false,
  showClass: {
    popup: ''
  },
  hideClass: {
    popup: ''
  }
});

Toast.fire({
  icon: 'success',
  title: 'Department Successfully Added'
});
</script>";
<?php unset($_SESSION['added']); } ?>
<!-- NOTIFICATION END -->

<script>
function confirmArchiveDepartment(id){
    Swal.fire({
        title: 'Archive Folder?', 
        html: '<p style="font-size: 0.9rem; margin: 0;">This folder will be moved to archive.</p>',
        icon: null,
        width: '350px',
        showCancelButton: true,
        confirmButtonText: 'Archive',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        customClass: {
            popup: 'swal-custom-popup',
            title: 'swal-title-nowrap'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "archive_department.php?id=" + id;
        }
    });
}
</script>

<style>
.swal-title-nowrap {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 600;
    font-size: 1.2rem; 
    text-align: center;
}
.swal2-popup.swal-custom-popup {
    padding: 1.5rem 1.5rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.swal2-html-container {
    line-height: 1.3;
}
</style>

<script>
function confirmUnarchiveDepartment(id) {
    Swal.fire({
        title: 'Unarchive Department?', 
        html: '<p style="font-size: 0.9rem; margin: 0;">This department will be restored.</p>',
        icon: null,
        width: 350,
        showCancelButton: true,
        confirmButtonText: 'Unarchive',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        customClass: {
            popup: 'swal-custom-popup',
            title: 'swal-title-nowrap'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "unarchive_department.php?id=" + id;
        }
    });
}
</script>

<style>
.swal-title-nowrap {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 600;
    font-size: 1.2rem;
    text-align: center;
}
.swal2-popup.swal-custom-popup {
    padding: 1.5rem 1.5rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.swal2-html-container {
    line-height: 1.3;
}
</style>

<script>
function confirmLogout(el) {
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
        `,
        customClass: {
            popup: 'swal-custom-popup',
            title: 'swal-title-nowrap'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'Logout.php';
        }
    });
}
</script>

<style>
.swal-title-nowrap {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 600;
    font-size: 1.4rem; 
    text-align: center;
}
.swal2-popup.swal-custom-popup {
    padding: 1.5rem 1.5rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.swal2-html-container {
    line-height: 1.3;
}
</style>

<!-- Footer -->
<footer class="mt-9 text-center text-gray-500 text-sm">

  <p class="text-gray-500">
&#169; All Rights Reserved. Developed by the PSU IT Interns.
</p>

</footer>

</body>
</html>