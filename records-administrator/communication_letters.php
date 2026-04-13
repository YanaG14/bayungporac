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
// Fetch active folders
$query = mysqli_query($conn,"
SELECT 
  l.id,
  l.reference_no,
  l.subject,
  l.sender,
  l.source,
  l.date_received,
  l.status AS letter_status,

  -- File info
  GROUP_CONCAT(DISTINCT uf.file_path SEPARATOR ', ') as file_paths,
  GROUP_CONCAT(DISTINCT uf.name SEPARATOR ', ') as file_names,
  GROUP_CONCAT(DISTINCT uf.status SEPARATOR ', ') as file_statuses,

  -- Upload info
  GROUP_CONCAT(DISTINCT al.name SEPARATOR ', ') as uploaders,
  GROUP_CONCAT(DISTINCT uf.timers SEPARATOR ', ') as upload_dates

FROM letters l

LEFT JOIN upload_files uf 
  ON l.id = uf.id AND uf.status='Active'

LEFT JOIN admin_login al 
  ON uf.email = al.id

GROUP BY l.id
ORDER BY l.date_received DESC
");
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
    $('#dtable').DataTable({
    paging: false,        // ❌ remove pagination (Previous/Next)
    info: false,          // ❌ remove "Showing 1 to X of X"
    lengthChange: false   // ❌ removes "Show entries"
    searching: false // ❗ disable DataTables search box
});
    $(window).on('load', function(){ $('#loader').fadeOut('slow'); });
});
</script>
</head>

<body class="bg-gray-100 font-sans">
<!-- LOADER -->
<!-- Loader -->
<!-- Loader -->
<!-- Loader -->
 


<!-- NAVBAR -->
<nav class="fixed top-0 w-full bg-green-700 shadow-md z-50">
  
  <div class="flex justify-between items-center h-16 px-4 sm:px-6">
    <!-- Logo & Title -->
    <div class="flex items-center space-x-2 sm:space-x-3">
      <img src="js/img/municipalLogo.png" alt="Logo" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white object-cover">
      <h1 class="text-white font-bold text-base sm:text-lg md:text-xl whitespace-nowrap">Bayung Porac Archive</h1>
    </div>

    <!-- Right Side -->
    <div class="flex items-center space-x-2 sm:space-x-4">
      <!-- Desktop Welcome -->
      <span class="hidden md:inline-block text-sm md:text-base text-white">
  Welcome, <b><?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?></b>!
</span>
      
      <!-- Mobile Menu Button -->
      <button id="mobileMenuBtn" 
        class="md:hidden p-2 rounded-lg hover:bg-green-600 hover:bg-opacity-20 transition-all duration-200 group">
        <svg class="w-5 h-5 text-white transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" id="menuIcon">
          <!-- Hamburger (3 lines) -->
          <path class="hamburger" stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 12h16M4 16h16"/>
          
          <!-- X (hidden by default) -->
          <path class="close opacity-0 scale-0" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
      
      <!-- Desktop Logout -->
      <a href="#" onclick="confirmLogout(this)" 
   class="hidden md:inline-block px-4 py-2 text-sm md:text-base text-white rounded-lg border border-white hover:bg-white hover:text-green-700 transition-all duration-300 font-medium">
  Log out
      </a>
    </div>
  </div>
</nav>

<!-- Mobile Dropdown Menu -->
<div id="mobileMenu" class="md:hidden fixed top-20 sm:top-20 left-4 right-4 max-w-sm mx-auto bg-white shadow-2xl rounded-2xl border-2 border-gray-200 z-40 opacity-0 invisible transform scale-95 transition-all duration-300">
  <div class="p-6 space-y-4">
    <!-- Mobile Welcome -->
    <div class="text-center">
      <p class="text-sm font-medium text-gray-600">Welcome,</p>
      <p class="font-bold text-xl text-gray-900"><?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?></p>
    </div>
    
    <!-- Mobile Logout -->
    <a href="#" onclick="confirmLogout(this)" 
       class="block w-full max-w-[140px] mx-auto text-center px-6 py-2.5 bg-white text-red-700 font-bold rounded-xl hover:bg-red-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 border border-red-200">
      Log out
    </a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const mobileBtn = document.getElementById('mobileMenuBtn');
  const menuIcon = document.getElementById('menuIcon');
  const hamburgerPaths = menuIcon.querySelectorAll('.hamburger');
  const closePaths = menuIcon.querySelectorAll('.close');
  const mobileMenu = document.getElementById('mobileMenu');
  let isOpen = false;

  mobileBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    
    isOpen = !isOpen;
    
    if (isOpen) {
      // Show menu & change to X
      mobileMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
      mobileMenu.classList.add('opacity-100', 'visible', 'scale-100');
      
      // Animate to X
      hamburgerPaths.forEach(path => {
        path.style.opacity = '0';
        path.style.transform = 'scaleY(0)';
      });
      closePaths.forEach(path => {
        path.style.opacity = '1';
        path.style.transform = 'scale(1)';
      });
      
    } else {
      // Hide menu & change back to hamburger
      mobileMenu.classList.remove('opacity-100', 'visible', 'scale-100');
      mobileMenu.classList.add('opacity-0', 'invisible', 'scale-95');
      
      // Animate back to hamburger
      hamburgerPaths.forEach(path => {
        path.style.opacity = '1';
        path.style.transform = 'scaleY(1)';
      });
      closePaths.forEach(path => {
        path.style.opacity = '0';
        path.style.transform = 'scale(0)';
      });
    }
  });

  // Close when clicking outside
  document.addEventListener('click', function(e) {
    if (!mobileBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
      if (isOpen) {
        // Reset to hamburger
        hamburgerPaths.forEach(path => {
          path.style.opacity = '1';
          path.style.transform = 'scaleY(1)';
        });
        closePaths.forEach(path => {
          path.style.opacity = '0';
          path.style.transform = 'scale(0)';
        });
      }
      mobileMenu.classList.remove('opacity-100', 'visible', 'scale-100');
      mobileMenu.classList.add('opacity-0', 'invisible', 'scale-95');
      isOpen = false;
    }
  });

  // Close on desktop resize
  window.addEventListener('resize', function() {
    if (window.innerWidth >= 768 && isOpen) {
      // Reset to hamburger
      hamburgerPaths.forEach(path => {
        path.style.opacity = '1';
        path.style.transform = 'scaleY(1)';
      });
      closePaths.forEach(path => {
        path.style.opacity = '0';
        path.style.transform = 'scale(0)';
      });
      mobileMenu.classList.remove('opacity-100', 'visible', 'scale-100');
      mobileMenu.classList.add('opacity-0', 'invisible', 'scale-95');
      isOpen = false;
    }
  });
});
</script>

<!-- MAIN LAYOUT -->
<div class="mt-24 px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8">

<?php include 'sidebar.php'; ?>

  <!-- MAIN CONTENT -->
  <div class="w-full lg:w-3/4 flex-1 h-[600px] sm:h-[620px] lg:h-[655px]">
 
    <div class="bg-white rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 h-full transition-all duration-300 hover:shadow-xl">
      <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4 mb-4 sm:mb-6">

        <!-- HEADER (Responsive) -->
        <h2 class="text-lg sm:text-xl lg:text-2xl font-semibold flex items-center gap-2 text-center sm:text-left flex-1">
          <i class="fas fa-layer-group text-green-500 text-sm sm:text-base"></i>
          <span class="relative">
            LETTER COMMUNICATION
            <span class="absolute left-0 -bottom-1 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-600 rounded scale-x-75 sm:scale-x-100"></span>
          </span>
        </h2>

        <!-- Action Buttons (Responsive Stack) -->
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto items-center sm:items-end">

          <!-- SEARCH BAR (Full Width Mobile) -->
          <div class="w-full sm:w-64 lg:w-80 flex-shrink-0">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10"></i>
              <input type="text" id="globalSearch" 
                     placeholder="Search folders..."
                     oninput="performSearch()"
                     class="w-full border border-gray-300 rounded-full pl-10 pr-4 py-2.5 sm:py-2.5 
                            focus:ring-2 focus:ring-green-300 focus:outline-none transition-all duration-200 shadow-sm">
            </div> 
          </div>

          <!-- Action Buttons (Mobile Stack) -->
          <div class="flex gap-1.5 sm:gap-2">
            <!-- ADD LETTER -->
            <button onclick="$('#modalAddFolder').removeClass('hidden');"
            class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg flex items-center justify-center text-green-600 text-sm sm:text-base focus:outline-none">
            <i class="fas fa-plus"></i>
            </button>

            <!-- ARCHIVED LETTERS -->
<button onclick="openModal('modalArchivedLetters')"
class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg flex items-center justify-center text-red-600 text-sm sm:text-base focus:outline-none"
title="View Archived Letters">
  <i class="fas fa-archive"></i>
</button>
          </div>

        </div>
      </div>
           <!-- Responsive Table Container -->
<div class="w-full h-[calc(100%-120px)] sm:h-[calc(100%-140px)] lg:h-[560px] overflow-hidden rounded-xl border shadow-sm">
  <div class="w-full h-full overflow-x-auto overflow-y-auto custom-scrollbar">
  <table id="dtable" class="min-w-[1000px] w-full border-gray-200 table-auto">
  <thead class="bg-gray-200 text-black uppercase text-xs sm:text-sm tracking-wider sticky top-0 z-10 shadow-sm">
    <tr>
      <th class="px-3 sm:px-4 py-2.5 text-left font-medium">ID</th>
      <th class="px-3 sm:px-4 py-2.5 text-left font-medium">Reference No</th>
      <th class="px-3 sm:px-4 py-2.5 text-left font-medium">Date Received</th>
      <th class="px-3 sm:px-4 py-2.5 text-left font-medium">Subject</th>
      <th class="px-3 sm:px-4 py-2.5 text-left font-medium">Sender</th>
      <th class="px-3 sm:px-4 py-2.5 text-left font-medium">Departments</th>
      <th class="px-3 sm:px-4 py-2.5 text-left font-medium">Source</th>
      <th class="px-3 sm:px-4 py-2.5 text-left font-medium">File Name</th>
      <th class="px-3 sm:px-4 py-2.5 text-left font-medium">Status</th>
      <th class="px-3 sm:px-4 py-2.5 text-center font-medium w-28 sm:w-32">Action</th>
    </tr>
  </thead>
  <tbody class="text-gray-700 divide-y divide-gray-100">
    <?php
    $files = mysqli_query($conn, "SELECT * FROM letters WHERE letter_status= 'Active' ORDER BY created_at DESC");
    while($file = mysqli_fetch_array($files)) { ?>
    
  <tr 
  class="hover:bg-gray-50/50 transition-colors duration-150 border-b last:border-b-0 cursor-pointer"
  onclick="openViewModal(
    '<?php echo addslashes($file['file_name']); ?>',
    '<?php echo addslashes($file['file_path']); ?>'
  )"
>
      <td class="px-3 sm:px-4 py-2"><?php echo $file['id']; ?></td>
      <td class="px-3 sm:px-4 py-2"><?php echo $file['reference_no']; ?></td>
      <td class="px-3 sm:px-4 py-2"><?php echo date('M j, Y', strtotime($file['date_received'])); ?></td>
      <td class="px-3 sm:px-4 py-2"><?php echo htmlentities($file['subject']); ?></td>
      <td class="px-3 sm:px-4 py-2"><?php echo htmlentities($file['sender']); ?></td>
      <td class="px-3 sm:px-4 py-2 text-sm">
<?php
$deptQuery = mysqli_query($conn, "
    SELECT d.department_name
    FROM letter_departments ld
    JOIN departments d ON ld.department_id = d.department_id
    WHERE ld.letter_id = '{$file['id']}'
");

$departments = [];

while($row = mysqli_fetch_assoc($deptQuery)){
    $departments[] = $row['department_name'];
}

if(count($departments) > 0){
    echo '<span class="text-gray-700">'.implode(', ', $departments).'</span>';
} else {
    echo '<span class="text-gray-400 italic">No departments</span>';
}
?>
</td>
      <td class="px-3 sm:px-4 py-2"><?php echo htmlentities($file['source']); ?></td>
      <td class="px-3 sm:px-4 py-2">
        <a href="letter_files/<?php echo $file['file_path']; ?>" target="_blank" class="text-green-600 hover:underline">
    <?php echo htmlentities($file['file_name']); ?>
</a>
      </td>
      <td class="px-3 sm:px-4 py-2"><?php echo $file['status']; ?></td>
            <td class="px-3 py-3 sm:px-4 sm:py-2">
  <div class="flex justify-center relative">
    <button onclick="toggleMenuFile(<?php echo $file['id']; ?>)" 
            class="flex items-center justify-center w-9 h-9 rounded-full text-gray-600 
                   hover:bg-gray-100 hover:text-gray-900 hover:shadow-md transition-all duration-200
                   group-hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-200"
            title="Actions">
      <i class="fas fa-ellipsis-h text-sm"></i>
    </button>

    <div id="menu-file-<?php echo $file['id']; ?>"
         class="hidden absolute top-full mt-1 right-0 w-44 sm:w-28 bg-white/95 backdrop-blur-sm 
                rounded-xl shadow-lg border border-gray-100 z-50
                transform scale-95 opacity-0 transition-all duration-200
                sm:-left-2 sm:w-32 lg:w-28 lg:right-0">

      <a href="communication_downloads.php?file_id=<?php echo $file['id']; ?>"
         class="block w-full flex items-center gap-2 px-3 py-2.5 text-xs sm:text-sm text-gray-700 
                hover:bg-blue-50 hover:text-blue-700 rounded-t-xl border-b border-gray-50">
        <i class="fa fa-download text-blue-500 w-4"></i><span>Download</span>
      </a>

      <a href="letter_files/<?php echo $file['file_path']; ?>" target="_blank"
         class="block w-full flex items-center gap-2 px-3 py-2.5 text-xs sm:text-sm text-gray-700 
                hover:bg-indigo-50 hover:text-indigo-700 border-b border-gray-50">
        <i class="fa fa-eye text-indigo-500 w-4"></i><span>View</span>
      </a>

      <a href="communication_archive.php?file_id=<?php echo $file['id']; ?>"
         class="block w-full flex items-center gap-2 px-3 py-2.5 text-xs sm:text-sm text-gray-700 
                hover:bg-red-50 hover:text-red-700 border-b border-gray-50">
        <i class="fa fa-archive text-red-500 w-4"></i><span>Archive</span>
      </a>

   <!-- EDIT BUTTON -->
<button onclick="openEditModal(<?php echo $file['id']; ?>, '<?php echo addslashes($file['file_name']); ?>')" 
   class="block w-full text-left flex items-center gap-2 px-3 py-2.5 text-xs sm:text-sm text-gray-700 
          hover:bg-yellow-50 hover:text-yellow-700 border-b border-gray-50">
  <i class="fa fa-edit text-yellow-500 w-4"></i><span>Edit</span>
</button>

    </div>
  </div>
</td>
    </tr>
    <?php } ?>
  </tbody>
</table>
  </div>
</div>
          </table>
        </div>
      </div>
      <!-- SEARCH RESULTS -->
      <div id="searchResults" class="mt-6 p-4 rounded-xl bg-blue-50 border border-blue-200 hidden"></div>
    </div>
  </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 3px; }
.custom-scrollbar::-webkit-scrollbar-thumb { 
  background: linear-gradient(45deg, #464948); 
  border-radius: 3px; 
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #059669; }
</style>



<!-- ADD LETTER MODAL -->
<div id="modalAddFolder" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm p-4">
  <!-- Modal Card -->
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-lg p-4 sm:p-6 animate-fadeIn relative">

    <!-- Close Button -->
    <button onclick="closeModal('modalAddFolder')" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Modal Title -->
    <h3 class="text-xl sm:text-2xl font-semibold mb-5 flex items-center gap-2 text-gray-800">
      <i class="fas fa-plus text-green-600"></i> Add Post
    </h3>

    <!-- Form -->
<form action="save_letter.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">

<input type="text" name="reference_no" placeholder="Reference No" required>
<input type="date" name="date_received" required>
<input type="text" name="subject" placeholder="Subject" required>

<select name="source" required>
  <option value="">Select Source</option>
  <option value="Internal">Internal</option>
  <option value="External">External</option>
</select>

<!-- FILE -->
<input type="file" name="file" accept=".pdf,image/*" required>

<!-- ✅ DEPARTMENT TAGGING -->
<div class="border rounded-lg p-3">
  <label class="font-semibold text-sm text-gray-700 mb-2 block">
    Tag Departments (Who can see this file)
  </label>

  <div class="max-h-40 overflow-y-auto space-y-2">
    <?php
    $dept = mysqli_query($conn, "SELECT * FROM departments WHERE department_status='Active'");
    while($d = mysqli_fetch_assoc($dept)) {
    ?>
      <label class="flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>">
        <?php echo $d['department_name']; ?>
      </label>
    <?php } ?>
  </div>
</div>

<button class="bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
  Save
</button>

</form>
  </div>
</div>
<!--END  ADD LETTER MODAL -->


<!--END  ADD LETTER MODAL -->
<div id="modalEditLetter"
     class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50 p-4">

  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">

    <div class="flex justify-between items-center mb-4">
      <h4 class="font-semibold text-lg">Edit File</h4>
      <button onclick="closeModal('modalEditLetter')" class="text-xl">&times;</button>
    </div>

    <form method="POST" action="communication_edit.php">

      <input type="hidden" id="editLetterId" name="file_id">
      <input type="hidden" id="editFileExtension" name="file_extension">

      <label class="text-sm font-medium">File Name</label>

      <div class="flex gap-2 items-center">
        <input type="text" id="editFileNameOnly" name="file_name_only"
               class="w-full border p-2 rounded" required>

        <span id="fileExtensionDisplay" class="text-gray-500"></span>
      </div>

<!-- DEPARTMENTS -->
  <div class="mt-3 border p-3 rounded">
    <label class="font-semibold text-sm block mb-2">
      Departments
    </label>

    <?php
    $dept = mysqli_query($conn, "SELECT * FROM departments WHERE department_status='Active'");
    while($d = mysqli_fetch_assoc($dept)) {
    ?>
      <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="departments[]" value="<?php echo $d['department_id']; ?>">
        <?php echo $d['department_name']; ?>
      </label>
    <?php } ?>
  </div>



      <div class="mt-5 flex justify-end gap-2">
        <button type="button" onclick="closeModal('modalEditLetter')"
                class="px-4 py-2 bg-gray-200 rounded">
          Cancel
        </button>

        <button class="px-4 py-2 bg-green-600 text-white rounded">
          Save
        </button>
      </div>

    </form>

  </div>
</div>
<!-- END EDIT MODAL -->


<!-- ARCHIVED LETTERS MODAL -->
<div id="modalArchivedLetters" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm p-4">
  <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-4xl p-4 sm:p-6 animate-fadeIn relative">

    <!-- Close Button -->
    <button onclick="closeModal('modalArchivedLetters')" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <h3 class="text-xl sm:text-2xl font-semibold mb-4 flex items-center gap-2 text-gray-800">
      <i class="fas fa-archive text-red-600"></i> Archived Letters
    </h3>

    <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar">
      <table class="min-w-full table-auto border">
        <thead class="bg-gray-200 text-black uppercase text-xs sm:text-sm tracking-wider sticky top-0 z-10 shadow-sm">
          <tr>
            <th class="px-3 py-2 text-left">Reference No</th>
            <th class="px-3 py-2 text-left">Date Received</th>
            <th class="px-3 py-2 text-left">Subject</th>
            <th class="px-3 py-2 text-left">Sender</th>
            <th class="px-3 py-2 text-left">Source</th>
            <th class="px-3 py-2 text-left">File Name</th>
            <th class="px-3 py-2 text-center">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 divide-y divide-gray-100">
        <?php
        $archived = mysqli_query($conn, "
          SELECT * 
          FROM letters 
          WHERE letter_status='Archived' 
          ORDER BY date_received DESC
        ");
        while($a = mysqli_fetch_array($archived)) { ?>
          <tr class="hover:bg-gray-50/50 transition-colors duration-150">
            <td class="px-3 py-2"><?php echo $a['reference_no']; ?></td>
            <td class="px-3 py-2"><?php echo date('M j, Y', strtotime($a['date_received'])); ?></td>
            <td class="px-3 py-2"><?php echo htmlentities($a['subject']); ?></td>
            <td class="px-3 py-2"><?php echo htmlentities($a['sender']); ?></td>
            <td class="px-3 py-2"><?php echo htmlentities($a['source']); ?></td>
            <td class="px-3 py-2">
              <a href="letter_files/<?php echo $a['file_path']; ?>" target="_blank" class="text-red-600 hover:underline">
                <?php echo htmlentities($a['file_name']); ?>
              </a>
            </td>
            <td class="px-3 py-2 text-center">
              <button onclick="confirmUnarchiveLetter(<?php echo $a['id']; ?>)" 
                      class="text-green-600 hover:text-green-700 text-sm px-2 py-1 rounded">
                <i class="fas fa-undo"></i> Restore
              </button>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
<!--END ARCHIVED LETTERS MODAL -->

<!--VIEW LETTERS MODAL -->
<!-- VIEW LETTERS MODAL -->
<div id="modalViewLetter"
     class="hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex justify-center items-center z-50 p-4">

  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-6 relative flex flex-col">

    <!-- Close -->
    <button onclick="closeModal('modalViewLetter')" 
            class="absolute top-3 right-4 text-2xl text-gray-500 hover:text-gray-800">&times;</button>

    <!-- Title -->
    <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
      <i class="fas fa-file-alt text-green-600"></i>
      <span id="viewFileName">File</span>
    </h3>

    <!-- FILE PREVIEW -->
    <div class="w-full h-[200px] border rounded-xl overflow-hidden bg-gray-100 relative">

      <!-- PDF VIEW -->
      <iframe id="viewFilePreview"
              class="w-full h-full hidden"
              frameborder="0"></iframe>

      <!-- IMAGE VIEW -->
      <img id="viewImagePreview"
           class="w-full h-full object-contain hidden" />

      <!-- NO PREVIEW -->
      <div id="noPreview" class="flex items-center justify-center h-full text-gray-500">
        No preview available
      </div>

    </div>

    <!-- ✅ COMMENTS SECTION -->
    <div class="mt-4 border rounded-xl p-3 bg-gray-50 flex flex-col">

      <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
        <i class="fas fa-comments text-blue-500"></i>
        Comments
      </h4>

      <!-- Comments List -->
      <div id="commentList" class="h-32 overflow-y-auto space-y-2 text-sm text-gray-700 pr-1">

        <!-- Example comment (static for now) -->
        <div class="bg-white p-2 rounded-lg border">
          No comments yet.
        </div>

      </div>

      <!-- Input Box -->
      <div class="mt-3 flex gap-2">

        <input type="text"
               id="commentInput"
               placeholder="Write a comment..."
               class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">

        <button onclick="addComment()"
                class="bg-blue-600 text-white px-4 rounded-lg text-sm hover:bg-blue-700 transition">
          Send
        </button>

      </div>

    </div>

  </div>
</div>
<!--END ARCHIVED LETTERS MODAL -->



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





<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if(isset($_SESSION['success'])): ?>
Swal.fire({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    icon: 'success',
    title: '<?php echo $_SESSION['success']; ?>'
});
<?php unset($_SESSION['success']); endif; ?>

<?php if(isset($_SESSION['error'])): ?>
Swal.fire({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    icon: 'error',
    title: '<?php echo $_SESSION['error']; ?>'
});
<?php unset($_SESSION['error']); endif; ?>
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
function confirmLogout(el) {
    Swal.fire({
        title: 'Are you sure you want to logout?', // one line
        html: '<p style="font-size: 0.9rem; margin: 0;">You will be logged out of your account.</p>',
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

<script>
function performSearch() {
    let keyword = document.getElementById("globalSearch").value.toLowerCase();

    // FILTER TABLE ROWS (FOLDERS)
    let table = document.getElementById("dtable");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        let rowText = rows[i].innerText.toLowerCase();

        if (rowText.includes(keyword)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }

    // AJAX SEARCH FOR FILES (UNCHANGED)
    $.ajax({
        url: "search_files_folders.php",
        type: "POST",
        data: { keyword: keyword },
        success: function(response) {
            $("#searchResults").html(response);
        }
    });
}
</script>

<!-- ACTION BUTTON -->
<script>
function toggleMenuFile(id) {
  const menu = document.getElementById('menu-file-' + id);

  // Close all other menus first
  document.querySelectorAll('[id^="menu-file-"]').forEach(el => {
    if (el.id !== 'menu-file-' + id) {
      el.classList.add('hidden', 'scale-95', 'opacity-0');
    }
  });

  // Toggle current menu
  if (menu.classList.contains('hidden')) {
    menu.classList.remove('hidden');
    // Force reflow
    menu.offsetHeight;
    menu.classList.remove('scale-95', 'opacity-0');
    menu.classList.add('scale-100', 'opacity-100');
  } else {
    closeMenuFile(id);
  }
}

function closeMenuFile(id) {
  const menu = document.getElementById('menu-file-' + id);
  if (menu) {
    menu.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
      menu.classList.add('hidden');
    }, 150);
  }
}


function openViewModal(fileName, filePath) {
  document.getElementById('viewFileName').textContent = fileName;

  const iframe = document.getElementById('viewFilePreview');
  const img = document.getElementById('viewImagePreview');
  const noPreview = document.getElementById('noPreview');

  // reset
  iframe.classList.add('hidden');
  img.classList.add('hidden');
  noPreview.classList.add('hidden');

  const ext = filePath.split('.').pop().toLowerCase();
  const fullPath = "letter_files/" + filePath;

  if (ext === "pdf") {

    // 🔥 CLEAN PDF VIEW (NO TOOLBAR)
    iframe.src = fullPath + "#toolbar=0&navpanes=0&scrollbar=0";
    iframe.classList.remove('hidden');

  } 
  else if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
    img.src = fullPath;
    img.classList.remove('hidden');
  } 
  else {
    noPreview.classList.remove('hidden');
  }

  document.getElementById('modalViewLetter').classList.remove('hidden');
}

function openEditModal(id, fullFileName) {
  const lastDot = fullFileName.lastIndexOf(".");
  let nameOnly = fullFileName;
  let extension = "";

  if (lastDot !== -1) {
    nameOnly = fullFileName.substring(0, lastDot);
    extension = fullFileName.substring(lastDot);
  }

  document.getElementById('editLetterId').value = id;
  document.getElementById('editFileNameOnly').value = nameOnly;
  document.getElementById('fileExtensionDisplay').textContent = extension;
  document.getElementById('editFileExtension').value = extension;

  // reset all checkboxes first
  document.querySelectorAll('.dept-checkbox').forEach(cb => {
    cb.checked = false;
  });

  // fetch assigned departments
  fetch("get_letter_departments.php?letter_id=" + id)
    .then(res => res.json())
    .then(data => {
      data.forEach(deptId => {
        let cb = document.querySelector('.dept-checkbox[value="'+deptId+'"]');
        if (cb) cb.checked = true;
      });
    });

  document.getElementById('modalEditLetter').classList.remove('hidden');
}

function closeModal(id){
  document.getElementById(id).classList.add('hidden');
}
</script>
 <!-- END ACTION BUTTON-->

  <!-- ARCHIVE-->
<script>
function openModal(id){
  document.getElementById(id).classList.remove('hidden');
}

function closeModal(id){
  document.getElementById(id).classList.add('hidden');
}

// Restore archived letter
function confirmUnarchiveLetter(id){
    Swal.fire({
        title: 'Restore Letter?',
        html: '<p style="font-size:0.9rem;margin:0;">This letter will be restored.</p>',
        showCancelButton: true,
        confirmButtonText: 'Restore',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if(result.isConfirmed){
            window.location = "communication_unarchive.php?letter_id=" + id;
        }
    });
}
</script>
 <!-- END ARCHINVE-->



<!-- Footer -->
<footer class="mt-10 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>


</body>
</html>