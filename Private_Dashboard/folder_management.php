<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_user'])) {
    header('Location: index.html');
    exit();
}

$adminName = $_SESSION['admin_name'];
require_once("../include/connection.php");

// Fetch active folders
$query = mysqli_query($conn,"
SELECT 
f.folder_id,
f.folder_name,
f.created_at,
GROUP_CONCAT(d.department_name SEPARATOR ', ') as departments
FROM folders f
LEFT JOIN folder_departments fd ON f.folder_id = fd.folder_id
LEFT JOIN departments d ON fd.department_id = d.department_id
WHERE f.folder_status='Active'
GROUP BY f.folder_id
ORDER BY f.folder_name ASC
");
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
      <img src="js/img/municipalLogo.png" class="w-10 h-10 object-contain">
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
      <a href="folder_management.php" class="w-full px-4 py-2 bg-green-600 text-white rounded flex items-center gap-2"><i class="fas fa-folder"></i> Folders</a>
      <!--<a href="add_document.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-file-medical"></i> Information Management</a>-->
      <a href="department_management.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-building"></i> Departments</a>
      <a href="view_admin.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-users"></i> Admin Accounts</a>
      <a href="view_user.php" class="w-full px-4 py-2 rounded hover:bg-gray-100 flex items-center gap-2"><i class="fas fa-users"></i> Employee Accounts</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <div class="w-3/4 flex-1">
    <div class="bg-white rounded-xl shadow-md p-6 h-full">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-folder"></i> Folders</h2>
        <div class="flex gap-2">
          <button onclick="$('#modalAddFolder').removeClass('hidden');" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2"><i class="fas fa-plus"></i> Add Folder</button>
          <button onclick="openArchivedFolders();" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition-colors duration-300">
    <i class="fas fa-archive"></i> View Archived Folders
</button>
        </div>
      </div>

      <!-- TABLE -->
      <div class="overflow-x-none">
        <table id="dtable" class="min-w-full border border-gray-200">
          <thead class="bg-green-700 text-white">
            <tr>
              <th class="px-4 py-2">Folder Name</th>
              <th class="px-4 py-2">Departments</th>
              <th class="px-4 py-2">Date Created</th>
              <th class="px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
          <?php while($row=mysqli_fetch_array($query)){ ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-2 text-center space-x-2">
  <a href="add_document.php?folder_id=<?php echo $row['folder_id']; ?>" class="flex items-center gap-2 text-gray-800 hover:text-green-700">
    <i class="fas fa-folder text-yellow-500"></i>
    <b><?php echo $row['folder_name']; ?></b>
  </a>
</td>
              <td class="px-4 py-2 text-center space-x-2"><?php echo $row['departments']; ?></td>
              <td class="px-4 py-2 text-center space-x-2"><?php echo $row['created_at']; ?></td>
              <td class="px-4 py-2 text-center space-x-2">
                <button onclick="$('#modalEditFolder<?php echo $row['folder_id']; ?>').removeClass('hidden');" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"><i class="fas fa-edit"></i></button>
                <a href="archive_folder.php?id=<?php echo $row['folder_id']; ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Archive this department?');"><i class="fas fa-archive"></i></a>
              </td>
            </tr>

            <!-- EDIT FOLDER MODAL -->
           <!-- EDIT FOLDER MODAL -->
<div id="modalEditFolder<?php echo $row['folder_id']; ?>" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">
  <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl w-105 p-6 relative">
    
    <!-- Close Button -->
    <button onclick="$('#modalEditFolder<?php echo $row['folder_id']; ?>').addClass('hidden');" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
    
    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-edit text-blue-600"></i> Edit Folder
    </h3>
    
    <!-- Form -->
    <form method="POST" action="update_folder.php" class="flex flex-col gap-4">
      <input type="hidden" name="folder_id" value="<?php echo $row['folder_id']; ?>">

      <!-- Folder Name -->
      <input type="text" name="folder_name" value="<?php echo $row['folder_name']; ?>" placeholder="Folder Name" 
             class=" w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>

      <!-- Assign Departments (moved below text field) -->
      <div class="mt-2">
        <label class="font-medium text-gray-700 mb-1 block">Assign Departments</label>
        <div class="flex flex-col gap-2 max-h-40 overflow-y-auto p-2 border rounded-lg">
          <?php
          $dept = mysqli_query($conn,"SELECT * FROM departments");
          while($d=mysqli_fetch_array($dept)){
            $check = mysqli_query($conn,"SELECT * FROM folder_departments WHERE folder_id='".$row['folder_id']."' AND department_id='".$d['department_id']."'");
            $checked = mysqli_num_rows($check)>0 ? "checked" : "";
          ?>
          <label class="flex items-center gap-2">
            <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>" class="accent-blue-600" <?php echo $checked; ?>>
            <?php echo $d['department_name']; ?>
          </label>
          <?php } ?>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-3">
        <button type="submit" name="update" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">
          Update Folder
        </button>
        <button type="button" onclick="$('#modalEditFolder<?php echo $row['folder_id']; ?>').addClass('hidden');" 
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">
          Close
        </button>
      </div>
    </form>
  </div>
</div>
          <?php } ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

</div>

<!-- ADD FOLDER MODAL -->
<!-- Modal Background -->
<div id="modalAddFolder" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm transition-opacity">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-105 p-6 relative animate-fadeIn">
    
    <!-- Close Button -->
    <button onclick="closeModal('modalAddFolder')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
    
    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-plus text-green-600"></i> Add Folder
    </h3>
    
    <!-- Form -->
    <form method="POST" action="save_folder.php" class="flex flex-col gap-4">
      <input type="text" name="folder_name" placeholder="Folder Name" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none transition" required>
      
      <label class="font-medium text-gray-700">Assign Departments</label>
      <div class="flex flex-col gap-2 max-h-40 overflow-y-auto">
        <?php
        $dept = mysqli_query($conn,"SELECT * FROM departments");
        while($d=mysqli_fetch_array($dept)){
        ?>
        <label class="flex items-center gap-2">
          <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>" class="accent-green-600">
          <?php echo $d['department_name']; ?>
        </label>
        <?php } ?>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" name="save" class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md transition duration-200">Create Folder</button>
        <button type="button" onclick="closeModal('modalAddFolder')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-5 py-2 transition duration-200">Close</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Script -->
<script>
  function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
  }
  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }
</script>

<!-- Tailwind Animation (add in your CSS or inside <style>) -->
<style>
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
  }
</style>

<!-- ARCHIVED FOLDERS MODAL -->
<!-- Archived Folders Modal -->
<div id="modalArchivedFolders" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-3/4 max-w-3xl max-h-[80vh] p-6 overflow-auto relative animate-fadeIn">

    <!-- Close Button -->
    <button onclick="document.getElementById('modalArchivedFolders').classList.add('hidden');" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-archive text-gray-700"></i> Archived Folders
    </h3>

    <!-- Modal Content -->
    <div id="archivedContent" class="overflow-auto max-h-[60vh]">
      Loading archived folders...
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

<script>
function openArchivedFolders() {
    // Show modal
    $('#modalArchivedFolders').removeClass('hidden');
    
    // Load archived folders via AJAX
    $('#archivedContent').html('Loading archived folders...');
    $('#archivedContent').load('load_archived_folders.php');
}
</script>

<!-- Footer -->
<footer class="mt-8 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>


</body>
</html>