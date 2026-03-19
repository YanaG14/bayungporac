  <!DOCTYPE html>
  <html lang="en">

  <?php
  session_start();
  if (!isset($_SESSION['admin_user'])) {
      header('Location: index.php');
      exit();
  }

  $adminName = $_SESSION['admin_name'];
  require_once("../include/connection.php");

  if(!isset($_GET['folder_id'])){
      header("Location: folder_management.php");
  }

  $folder_id = intval($_GET['folder_id']);
  ?>

  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Bayung Porac Archive</title>
  <link rel="icon" type="image/png" href="js/img/municipalLogo.png">

  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">

  <script src="js/jquery-1.8.3.min.js"></script>

  <link rel="stylesheet" href="medias/css/dataTable.css">
  <script src="medias/js/jquery.dataTables.js"></script>

  <script>
  $(document).ready(function(){

// 🔥 Destroy any existing DataTable first
  if ($.fn.DataTable.isDataTable('#dtable')) {
    $('#dtable').DataTable().destroy();
  }

  // 🔥 Reinitialize with strict settings
  $('#dtable').DataTable({
    paging: false,
    info: false,
    lengthChange: false,
    searching: false,
    ordering: true,
    dom: 't'   // ONLY table
  });


  $('#archivedTable').DataTable({
  "aLengthMenu": [[5,10,25,50,100,-1],[5,10,25,50,100,"All"]],
  "iDisplayLength":10
  });

  });

  function openModal(id){
  document.getElementById(id).classList.remove("hidden");
  }

  function closeModal(id){
  document.getElementById(id).classList.add("hidden");
  }
  </script>

  </head>

  <body class="bg-gray-100">

  <!-- NAVBAR -->
  <nav class="fixed top-0 w-full bg-green-700 shadow-md z-50">
  <div class="flex justify-between items-center px-6 py-4 text-white">

  <div class="flex items-center space-x-3">
  <img src="js/img/municipalLogo.png" class="w-10 h-10">
  <h1 class="text-lg font-semibold">Bayung Porac Archive</h1>
  </div>

  <div class="flex items-center space-x-6">
  <span>Welcome, <?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?>!</span>

  <a href="logout.php"
  class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-white text-sm">
  <i class="fas fa-sign-out-alt"></i> Logout
  </a>
  </div>

  </div>
  </nav>


  <div class="flex pt-24">

  <!-- SIDEBAR -->

  <a href="folder_management.php"
    class="fixed top-20 left-6 bg-white w-16 h-10 flex items-center justify-center rounded-full shadow hover:bg-green-100 hover:text-green-700 transition-all duration-300 text-gray-700">

    <i class="fas fa-arrow-left"></i>

  </a>

  <!-- MAIN -->
<main class="flex-1 p-8">

  <!-- PAGE CARD CONTAINER (like folder_management.php) -->
  <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 h-[700px] flex flex-col transition-all duration-300 hover:shadow-xl">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4">

      <h4 class="text-xl sm:text-2xl font-semibold text-gray-700 text-center sm:text-left">
        Records Management
      </h4>

      <div class="flex gap-3">
        <button onclick="openModal('uploadModal')" 
          class="bg-gradient-to-r from-green-600 to-green-500 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300">
          <i class="fas fa-file-upload"></i> Upload File
        </button>

        <button onclick="openModal('archiveModal')" 
          class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-white px-4 py-2 rounded-xl hover:scale-105 hover:shadow-lg flex items-center gap-2 transition-all duration-300">
          <i class="fas fa-archive"></i> View Archived Files
        </button>
      </div>

    </div>

    <!-- TABLE CONTAINER (SCROLLABLE) -->
    <div class="flex-1 overflow-y-auto overflow-x-auto">

      <table id="dtable" class="min-w-full text-sm border-gray-200">

        <thead class="bg-green-600 text-white sticky top-0 z-10">
          <tr>
            <th class="p-3">Filename</th>
            <th class="p-3">Departments</th>
            <th class="p-3">Uploader</th>
            <th class="p-3">Date Uploaded</th>
            <th class="p-3">Downloads</th>
            <th class="p-3">Action</th>
          </tr>
        </thead>

        <tbody>
        <?php
        // JOIN admin_login to get uploader name
      $query = mysqli_query($conn, "
      SELECT uf.*, al.name AS uploader_name
      FROM upload_files uf
      LEFT JOIN admin_login al ON uf.email = al.id
      WHERE uf.folder_id='$folder_id' AND uf.status='Active'
      ORDER BY uf.id DESC
  ");

        while($file = mysqli_fetch_array($query)){
          $id = $file['id'];
          $name = $file['name'];
          $size = $file['size'];
          $uploads = $file['uploader_name']; // name from join
          $status = $file['admin_status'];
          $time = $file['timers'];
          $download = $file['download'];
          $filepath = "../uploads/".$file['file_path'];
        ?>

        <tr class="border-b">
    <td class="p-3"><?php echo htmlentities($name); ?></td>
    <td class="p-3"><?php 
        // Fetch departments for this file
        $dept_query = mysqli_query($conn,"
    SELECT d.department_name 
    FROM file_departments fd
    JOIN departments d ON fd.department_id = d.department_id
    WHERE fd.file_id = $id
  ");
        $dept_names = [];
        while($dep = mysqli_fetch_array($dept_query)){
            $dept_names[] = $dep['department_name'];
        }
        echo implode(', ', $dept_names);
    ?></td>
    <td class="p-3"><?php echo htmlentities($uploads); ?></td>
    <td class="p-3"><?php echo htmlentities($time); ?></td>
    <td class="p-3"><?php echo htmlentities($download); ?></td>
    <td class="p-3">
      <div class="flex items-center gap-2 justify-center">
        <a href="downloads.php?file_id=<?php echo $id; ?>"
          class="flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white w-8 h-8 rounded text-sm">
          <i class="fa fa-download"></i>
        </a>
        <a href="<?php echo $filepath; ?>" target="_blank"
          class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 text-white w-8 h-8 rounded text-sm">
          <i class="fa fa-eye"></i>
        </a>
        <a href="archive_file.php?file_id=<?php echo $id; ?>"
          class="flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white w-8 h-8 rounded text-sm">
          <i class="fa fa-archive"></i>
        </a>
        <button onclick="openModal('editModal<?php echo $id; ?>')"
          class="flex items-center justify-center bg-green-500 hover:bg-green-600 text-white w-8 h-8 rounded text-sm">
          <i class="fa fa-edit"></i>
        </button>
      </div>
    </td>
  </tr>

  <!-- EDIT MODAL FOR THIS FILE -->
  <div id="editModal<?php echo $id; ?>" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
      <div class="flex justify-between items-center border-b p-4">
        <h4 class="font-semibold text-lg">Edit File</h4>
        <button onclick="closeModal('editModal<?php echo $id; ?>')" class="text-gray-500">&times;</button>
      </div>
      <form method="POST" action="update_file.php">
        <div class="p-6">
          <input type="hidden" name="file_id" value="<?php echo $id; ?>">
          <input type="hidden" name="folder_id" value="<?php echo $folder_id; ?>">

          <?php 
          $file_parts = pathinfo($file['name']);
          $filename_no_ext = $file_parts['filename'];
          $extension = $file_parts['extension'];
          ?>
          <label>File Name</label>
          <input type="text" name="file_name" class="w-full border p-2 rounded mt-2" value="<?php echo htmlentities($filename_no_ext); ?>" required>
          <span class="text-gray-500 text-sm">File extension: .<?php echo $extension; ?></span>

          
          <br><br>
          <label>Assign Departments</label>
          <?php
          $departments = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
          // Get departments assigned to THIS file
          $assigned = mysqli_query($conn,"SELECT department_id FROM file_departments WHERE file_id='$id'");
          $assigned_dept = [];
          while($ad=mysqli_fetch_array($assigned)){
              $assigned_dept[] = $ad['department_id'];
          }
          while($d=mysqli_fetch_array($departments)){
          ?>
          <div class="mt-2">
            <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>" 
              <?php echo in_array($d['department_id'],$assigned_dept)?'checked':''; ?>>
            <?php echo htmlentities($d['department_name']); ?>
          </div>
          <?php } ?>
        </div>

        <div class="p-4 border-t text-right">
          <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded" name="update_file">
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>

        <?php } ?>
              </tbody>
      </table>

    </div> <!-- end scroll container -->

  </div> <!-- end card -->

</main>

  <!-- UPLOAD MODAL -->
  <div id="uploadModal"
  class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
  <div class="flex justify-between items-center border-b p-4">
  <h4 class="font-semibold text-lg">Upload File</h4>
  <button onclick="closeModal('uploadModal')" class="text-gray-500">&times;</button>
  </div>

  <form method="POST" action="upload_files.php" enctype="multipart/form-data">
  <div class="p-6">
  <input type="hidden" name="folder_id" value="<?php echo $folder_id; ?>">

  <label>Select File(s)</label>
  <input type="file" name="files[]" multiple class="w-full border p-2 rounded mt-2" required>

  <br><br>

  <label>Assign Departments</label>
  <?php
  $dept = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active'");
  while($d=mysqli_fetch_array($dept)){
  ?>
  <div class="mt-2">
  <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>">
  <?php echo htmlentities($d['department_name']); ?>
  </div>
  <?php } ?>
  </div>

  <div class="p-4 border-t text-right">
  <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
  name="upload">
  Upload File
  </button>
  </div>
  </form>
  </div>
  </div>



  <!-- ARCHIVED MODAL -->
  <div id="archiveModal"
  class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">

  <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
  <div class="flex justify-between items-center border-b p-4">
  <h4 class="font-semibold text-lg">Archived Files</h4>
  <button onclick="closeModal('archiveModal')" class="text-gray-500">&times;</button>
  </div>

  <div class="p-6">
  <table id="archivedTable" class="min-w-full text-sm">
  <thead class="bg-gray-700 text-white">
  <tr>
  <th class="p-3">Filename</th>
  <th class="p-3">Size</th>
  <th class="p-3">Uploader</th>
  <th class="p-3">Role</th>
  <th class="p-3">Date Uploaded</th>
  <th class="p-3">Downloads</th>
  <th class="p-3">Action</th>
  </tr>
  </thead>

  <tbody>
  <?php
  $archived = mysqli_query($conn, "
      SELECT uf.*, al.name AS uploader_name
      FROM upload_files uf
      LEFT JOIN admin_login al ON uf.email = al.id
      WHERE uf.folder_id='$folder_id' AND uf.status='Archived'
      ORDER BY uf.id DESC
  ");

  while($f = mysqli_fetch_array($archived)){
  $fid = $f['id'];
  $fname = $f['name'];
  $fsize = $f['size'];
  $fuploads = $f['uploader_name']; // use joined name
  $fstatus = $f['admin_status'];
  $ftime = $f['timers'];
  $fdownload = $f['download'];
  $fpath = "../uploads/".$f['file_path'];
  ?>
  <tr class="border-b">
  <td class="p-3"><?php echo htmlentities($fname); ?></td>
  <td class="p-3"><?php echo floor($fsize/1000).' KB'; ?></td>
  <td class="p-3"><?php echo htmlentities($fuploads); ?></td>
  <td class="p-3"><?php echo htmlentities($fstatus); ?></td>
  <td class="p-3"><?php echo htmlentities($ftime); ?></td>
  <td class="p-3"><?php echo htmlentities($fdownload); ?></td>
  <td class="p-3 space-x-2">
  <a href="<?php echo $fpath; ?>" target="_blank" class="bg-indigo-500 hover:bg-indigo-600 text-white px-2 py-1 rounded text-sm">
  <i class="fa fa-eye"></i>
  </a>
  <a href="unarchive_file.php?file_id=<?php echo $fid; ?>" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-sm">
  <i class="fa fa-undo"></i>
  </a>
  </td>
  </tr>
  <?php } ?>
  </tbody>
  </table>
  </div>
  </div>
  </div>

  </body>
  </html>