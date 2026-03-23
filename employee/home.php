<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if(!isset($_SESSION["email_address"])){
    header("location:../login.php");
    exit();
}

require_once("../include/connection.php");

$user_id = $_SESSION['user_no'];
$user_department = $_SESSION['department_id'];

// Get user name and department image
$stmt = $conn->prepare("
    SELECT login_user.name, departments.department_img
    FROM login_user
    JOIN departments 
    ON login_user.department_id = departments.department_id
    WHERE login_user.id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$name = $row['name'];
$department_img = $row['department_img'];

// Check if a folder is clicked
$selected_folder = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;
?>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bayung Porac Archive</title>

<link rel="icon" type="image/png" href="img/municipalLogo.png">

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- jQuery + DataTables -->
<script src="js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" type="text/css" href="media/css/dataTable.css" />
<script src="media/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function(){
    $('#dtable').DataTable({
        "lengthMenu": [[5,10,15,25,50,100,-1],[5,10,15,25,50,100,"All"]],
        "pageLength": 10
    });
});
</script>

<style>
#loader {
  position: fixed;
  left: 0; top: 0;
  width: 100%; height: 100%;
  z-index: 9999;

}
</style>

<script>
$(window).on('load', function(){
    setTimeout(function(){
        $('#loader').fadeOut('slow');
    }, 300);
});
</script>

</head>

<body class="bg-gray-50 font-sans">

<div id="loader" class="fixed inset-0 bg-white flex justify-center items-center z-50 transition-opacity duration-500">
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
  0%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-10px); }
}

.dot {
  display: inline-block;
  animation: bounce 1s infinite ease-in-out;
}

.animation-delay-100 { animation-delay: 0.1s; }
.animation-delay-200 { animation-delay: 0.2s; }
</style>

<!-- NAVBAR -->
<nav class="fixed top-0 w-full bg-green-700 shadow-md z-50">
  <div class="flex justify-between items-center h-16 px-6">
    <div class="flex items-center space-x-3">
      <img src="img/municipalLogo.png" class="w-12 rounded-full border-2 border-white">
      <h1 class="text-white font-bold text-lg md:text-xl">Bayung Porac Archive</h1>
    </div>
    <div class="flex items-center space-x-4 text-white">
      <span class="hidden sm:inline-block">Welcome, <b><?php echo ucwords(htmlentities($name)); ?></b>!</span>
     <a href="#" onclick="confirmUserLogout(this)" 
   class="px-4 py-2 rounded-lg border border-white hover:bg-white hover:text-green-700 transition-all duration-300">
    Log out
</a>
    </div>
  </div>
</nav>

<!-- MAIN CONTENT -->
<div class="mt-24 px-6 md:px-12">
  <div class="grid grid-cols-12 gap-6">

    <!-- SIDEBAR 
    <aside class="col-span-12 md:col-span-3 space-y-6">
      <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6 border-t-4 border-green-600 hover:scale-[1.02] transition-transform duration-300">
        <div class="flex flex-col items-center">
          <img src="../records-administrator/department_images/<?php echo $department_img; ?>" class="w-28 h-28 rounded-full shadow-md mb-3">
          <h2 class="text-lg font-semibold text-gray-700">Admin Profile</h2>
        </div>
        <hr class="my-4 border-gray-300">
        <p class="text-sm"><b>Full Name:</b> <?php echo $name; ?></p>
        <p class="text-sm"><b>Position:</b> Admin Staff</p>
      </div>

      <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6 border-t-4 border-green-600 hover:scale-[1.02] transition-transform duration-300">
        <h3 class="font-semibold text-lg mb-3 text-gray-700">File Document Guidelines</h3>
        <ul class="text-sm list-disc ml-5 space-y-1 text-gray-600">
          <li>Ensure revisions are clearly identified</li>
          <li>Documents remain legible</li>
          <li>Prevent unintended use of obsolete documents</li>
        </ul>
      </div>
    </aside> -->

    <!-- MAIN TABLE/FOLDER SECTION -->
    <main class="col-span-12 md:col-span-9">

    <div class="bg-white/90 backdrop-blur-md shadow-lg  h-[650px] w-[1440px] rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300"> <!--container ito-->

    <?php if ($selected_folder === 0): 
        // Show folders assigned to department
        $stmt = $conn->prepare("
            SELECT f.folder_id, f.folder_name
            FROM folders f
            JOIN folder_departments fd ON f.folder_id = fd.folder_id
            WHERE fd.department_id = ? AND f.folder_status='Active'
            ORDER BY f.folder_name ASC
        ");
        $stmt->bind_param("i", $user_department);
        $stmt->execute();
        $folders = $stmt->get_result();
    ?>
      <h2 class="text-xl md:text-2xl font-bold text-gray-700 mb-6">Folders</h2>
       <div class="overflow-y-auto h-[550px] w-[1393px] p-4 rounded-xl shadow-inner"> <!--table ito-->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
  <?php while($folder = $folders->fetch_assoc()): ?>
    
    <div class="flex flex-col gap-2 p-4 bg-white rounded-xl shadow-md hover:bg-green-50 hover:scale-[1.02] transition-transform duration-300">

      <!-- Folder Link -->
      <a href="?folder_id=<?php echo $folder['folder_id']; ?>" 
         class="flex items-center gap-3">
        <i class="fas fa-folder text-yellow-500 text-2xl"></i>
        <span class="font-medium text-gray-700">
          <?php echo htmlentities($folder['folder_name']); ?>
        </span>
      </a>

      <!-- DOWNLOAD BUTTON -->
      <a href="download_folder.php?folder_id=<?php echo $folder['folder_id']; ?>" 
   class="bg-green-600 text-white px-3 py-2 rounded-lg text-center hover:bg-green-700 transition flex items-center justify-center gap-2">
   <i class="fas fa-download"></i> Download Folder
</a>

    </div>

  <?php endwhile; ?>
</div>

    <?php else: 
        // Show files in selected folder
        $stmt = $conn->prepare("
            SELECT uf.id, uf.name, uf.size, uf.email, uf.admin_status, uf.timers, uf.download
            FROM upload_files uf
            JOIN file_departments fd ON uf.id = fd.file_id
            WHERE uf.folder_id = ? AND fd.department_id = ? AND uf.status='Active'
            ORDER BY uf.id DESC
        ");
        $stmt->bind_param("ii", $selected_folder, $user_department);
        $stmt->execute();
        $files = $stmt->get_result();
    ?>
      <h2 class="text-xl md:text-2xl font-bold text-gray-700 mb-4">Documents</h2>
      <div class="overflow-x-auto rounded-xl shadow-inner">
        <table id="dtable" class="min-w-full border border-gray-200">
          <thead class="bg-green-700 text-white">
            <tr>
              <th class="px-4 py-3 text-left">Filename</th>
              <th class="px-4 py-3 text-left">Size</th>
              <th class="px-4 py-3 text-left">Uploader</th>
              <th class="px-4 py-3 text-left">Status</th>
              <th class="px-4 py-3 text-left">Upload Date</th>
              <th class="px-4 py-3 text-left">Downloads</th>
              <th class="px-4 py-3 text-left">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php while($file = $files->fetch_assoc()): ?>
            <tr class="border-b hover:bg-green-50 transition-colors duration-200">
              <td class="px-4 py-2"><?php echo htmlentities($file['name']); ?></td>
              <td class="px-4 py-2"><?php echo floor($file['size']/1000)." KB"; ?></td>
              <td class="px-4 py-2"><?php echo htmlentities($file['email']); ?></td>
              <td class="px-4 py-2"><?php echo htmlentities($file['admin_status']); ?></td>
              <td class="px-4 py-2"><?php echo htmlentities($file['timers']); ?></td>
              <td class="px-4 py-2"><?php echo $file['download']; ?></td>
              <td class="px-4 py-2">
                <a href="downloads.php?file_id=<?php echo $file['id']; ?>" 
                   class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-4 py-2 rounded-full flex items-center gap-2 hover:from-blue-600 hover:to-blue-800 transition-all duration-300" 
                   title="Download">
                   <i class="fas fa-download"></i> Download
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <a href="home.php" class="mt-5 inline-block bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">Back to Folders</a>
    <?php endif; ?>


    </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmUserLogout(el) {
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