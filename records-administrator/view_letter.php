<!DOCTYPE html>
<html lang="en">
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
if(isset($_GET['notif_id'])){
    $notif_id = intval($_GET['notif_id']);

    mysqli_query($conn, "
        UPDATE letter_comments 
        SET is_read = 1 
        WHERE id = $notif_id
    ");
}
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

LEFT JOIN login_user al 
  ON uf.email = al.id

GROUP BY l.id
ORDER BY l.date_received DESC
");
$notif = mysqli_query($conn, "
SELECT 
  c.comment,
  c.created_at,
  l.id AS letter_id,
  l.file_name,
  u.name AS commenter
FROM letter_comments c
JOIN letters l ON c.letter_id = l.id
JOIN login_user u ON c.user_id = u.id
ORDER BY c.created_at DESC
LIMIT 10
");

$notifications = [];

while($n = mysqli_fetch_assoc($notif)) {

    $time = strtotime($n['created_at']);
    $diff = time() - $time;

    if($diff < 60){
        $timeText = "Just now";
    } elseif($diff < 3600){
        $timeText = floor($diff/60) . " mins ago";
    } elseif($diff < 86400){
        $timeText = floor($diff/3600) . " hrs ago";
    } else {
        $timeText = floor($diff/86400) . " days ago";
    }

    $notifications[] = [
      
        "text" => $n['commenter']." commented on \"".$n['file_name']."\"",
        "time" => $timeText,
        "letter_id" => $n['letter_id']   // ✅ ADD THIS LINE
    ];
}

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
      <?php include 'notification.php'; ?>
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
      <p class="font-bold text-xl text-gray-900"><?php echo ucwords(htmlentities($name)); ?></p>
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
  <?php
$letter_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$letter = null;

if($letter_id > 0){
    $stmt = $conn->prepare("SELECT * FROM letters WHERE id=?");
    $stmt->bind_param("i", $letter_id);
    $stmt->execute();
    $letter = $stmt->get_result()->fetch_assoc();
}
?>

<div class="w-full lg:w-3/4 flex-1">

<div class="w-full lg:w-3/4 flex-1">

<?php if($letter): ?>

<div class="bg-white rounded-2xl shadow-lg p-6 space-y-5">

  <!-- BACK + ACTION -->
  <div class="flex justify-between items-center">
    <a href="javascript:history.back()" 
       class="text-sm text-gray-500 hover:underline">
      ← Go back to list
    </a>

  </div>

  <!-- POST HEADER -->
  <div>
    <h2 class="font-semibold text-lg text-gray-800">
      <?= htmlspecialchars($letter['sender']) ?>
    </h2>

    <p class="text-sm text-gray-600 mt-1">
      <b>Ref. No. <?= $letter['reference_no'] ?></b><br>
      <?= htmlspecialchars($letter['subject']) ?>
    </p>

    <p class="text-xs text-gray-400 mt-2">
      Created at: <?= date('M d, Y h:i A', strtotime($letter['created_at'])) ?>
    </p>
  </div>

  <!-- ATTACHMENTS -->
<?php
/* ===============================
   ATTACHMENTS SECTION (UPDATED)
   =============================== */
?>

<div class="border rounded-xl p-4">

  <div class="flex justify-between items-center mb-2">
    <h3 class="font-semibold text-gray-700">📎 Attachments</h3>

    <button onclick="openAttachmentModal()"
            class="text-xs border border-green-600 text-green-600 px-3 py-1 rounded hover:bg-green-500 hover:text-white">
      Manage Attachments
    </button>
  </div>

<?php
$files = mysqli_query($conn, "
  SELECT * FROM letter_files 
  WHERE letter_id='{$letter['id']}'
");

if(mysqli_num_rows($files) > 0){
    while($f = mysqli_fetch_assoc($files)){
?>
    <div class="bg-gray-100 p-3 rounded flex justify-between items-center mb-2">
      <span><?= htmlspecialchars($f['file_name']) ?></span>

      <div class="flex gap-3 text-gray-600">
        <a href="../records-administrator/letter_files/<?= $f['file_path'] ?>" target="_blank">
          <i class="fas fa-eye"></i>
        </a>

        <a href="communication_downloads.php?file_id=<?= $f['file_id'] ?>">
          <i class="fas fa-download"></i>
        </a>
      </div>
    </div>
<?php } } else { ?>
  <p class="text-sm text-gray-500">No attachments found.</p>
<?php } ?>

</div>




<div id="attachmentModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">

  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold">Manage Attachments</h3>
      <button onclick="closeAttachmentModal()" class="text-xl">&times;</button>
    </div>

    <!-- UPLOAD NEW FILE -->
    <form action="letter_edit_upload.php" method="POST" enctype="multipart/form-data" class="mb-4">

      <input type="hidden" name="letter_id" value="<?= $letter['id'] ?>">
<input type="file" id="fileInput" multiple class="w-full border p-2 rounded mb-3" hidden required>
<button type="button"
        onclick="document.getElementById('fileInput').click()"
        class="w-full bg-gray-200 py-2 rounded mb-3">
    Add More Files
</button>
<div id="fileList"></div>
     <button type="button"
        onclick="uploadFiles()"
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
    Upload File
</button>

    </form>

    <!-- EXISTING FILES -->
    <div class="max-h-60 overflow-y-auto space-y-2">

<?php
$files = mysqli_query($conn, "
  SELECT * FROM letter_files 
  WHERE letter_id='{$letter['id']}'
");

while($f = mysqli_fetch_assoc($files)){
?>
      <div class="flex justify-between items-center bg-gray-100 p-2 rounded">
        <span class="text-sm"><?= htmlspecialchars($f['file_name']) ?></span>

        <form action="delete_attachment.php" method="POST">
          <input type="hidden" name="file_id" value="<?= $f['file_id'] ?>">
         <button type="button"
        onclick="deleteFile(<?= $f['file_id'] ?>)"
        class="text-red-600 text-sm hover:underline">
    Remove
</button>
        </form>
      </div>
<?php } ?>

    </div>

  </div>
</div>



<script>
function openAttachmentModal(){
  document.getElementById('attachmentModal').classList.remove('hidden');
}

function closeAttachmentModal(){
  document.getElementById('attachmentModal').classList.add('hidden');
}
</script>


<script>
let fileArray = [];

document.getElementById('fileInput').addEventListener('change', function(e) {
    let newFiles = Array.from(e.target.files);

    fileArray = fileArray.concat(newFiles);

    renderFiles();

    // reset input so same file can be re-added if needed
    this.value = "";
});

function renderFiles() {
    let list = document.getElementById('fileList');
    list.innerHTML = '';

    fileArray.forEach((file, index) => {
        let div = document.createElement('div');
        div.className = "flex justify-between bg-gray-100 p-2 rounded";
        div.innerHTML = `
            <span>${file.name}</span>
            <button onclick="removeFile(${index})" class="text-red-500">x</button>
        `;
        list.appendChild(div);
    });
}

function removeFile(index) {
    fileArray.splice(index, 1);
    renderFiles();
}
</script>





  
  <!-- TAGS -->
  <div class="border rounded-xl p-4">
    <div class="flex justify-between items-center mb-2">
  <h3 class="font-semibold text-gray-700">🏷️ Tags</h3>

  <button onclick="openTagModal()"
          class="text-xs border border-green-600 text-green-600 px-3 py-1 rounded hover:bg-green-500 hover:text-white">
    Manage Tags
  </button>
</div>

    <div class="flex gap-2 flex-wrap">
      <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">
        <?= $letter['source'] ?>
      </span>

    <?php
$status = strtolower($letter['status']);

$color = 'bg-gray-200 text-gray-700';

if ($status == 'open') {
    $color = 'bg-red-200 text-red-700';
} elseif ($status == 'done') {
    $color = 'bg-green-200 text-green-700';
}
?>

<span class="text-xs px-2 py-1 rounded-full <?= $color ?>">
  <?= htmlspecialchars($letter['status']) ?>
</span>
        <span class="bg-blue-300 text-gray-700 text-xs px-2 py-1 rounded-full">
        <?= $letter['file_type'] ?>
      </span>
    </div>
  </div>

  <!-- FORWARDED -->
  <div class="border rounded-xl p-4">
    <div class="flex justify-between items-center mb-2">
  <h3 class="font-semibold text-gray-700 mb-2">📨 Tagged Departments</h3>

  <button onclick="openDeptModal()"
          class="text-xs border border-green-600 text-green-600 px-3 py-1 rounded hover:bg-green-500 hover:text-white">
    Manage Departments
  </button>
</div>
   <?php
$deptQuery = mysqli_query($conn, "
  SELECT d.department_name
  FROM letter_departments ld
  JOIN departments d ON ld.department_id = d.department_id
  WHERE ld.letter_id = '{$letter['id']}'
");

$departments = [];
while($row = mysqli_fetch_assoc($deptQuery)){
    $departments[] = $row['department_name'];
}

$total = count($departments);
$limit = 3;
?>

<div id="deptList">
<?php
foreach($departments as $index => $dept){
    $hiddenClass = ($index >= $limit) ? 'hidden extra-dept' : '';
?>
    <div class="bg-gray-50 border rounded p-3 mb-2 <?= $hiddenClass ?>">
        <?= $dept ?>
    </div>
<?php } ?>
</div>

<?php if($total > $limit): ?>
<div class="text-center mt-2">
    <button onclick="toggleDepartments()" 
            id="toggleDeptBtn"
            class="text-blue-600 text-sm hover:underline">
        See more
    </button>
</div>
<?php endif; ?>
  </div>

  <!-- COMMENTS -->
     <div class="border rounded-xl p-4">
 
    <h3 class="font-semibold text-gray-700 mb-2">💬 Comments</h3>
      <div class="flex gap-2">
      <input type="hidden" id="letterId" value="<?= $letter_id ?>">

      <input type="text" id="commentInput"
             class="flex-1 border rounded px-3 py-2 text-sm"
             placeholder="Write a comment...">

      <button onclick="addComment()"
              class="bg-blue-600 text-white px-4 rounded">
        Send
      </button>
    </div>
 <div class="border-t pt-4">
    <div id="commentList"
         class="h-32 overflow-y-auto bg-gray-50 p-3 rounded mb-3 text-sm">
    </div>

  
  </div>


<?php else: ?>

<div class="bg-white p-6 rounded-xl shadow text-gray-500">
    Letter not found.
</div>

<?php endif; ?>

</div>
      


<!-- TAG MODAL -->
<div id="tagModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

  <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">

    <h2 class="text-lg font-bold mb-4">Manage Tags</h2>

    <form method="POST" action="update_tags.php">

      <input type="hidden" name="letter_id" value="<?= $letter['id'] ?>">

      <!-- SOURCE -->
      <label class="text-sm font-medium">Source</label>
      <select name="source" class="w-full border rounded px-3 py-2 mb-3">
        <option value="Internal" <?= $letter['source']=='Internal'?'selected':'' ?>>Internal</option>
        <option value="External" <?= $letter['source']=='External'?'selected':'' ?>>External</option>   
      </select>

      <!-- STATUS -->
      <label class="text-sm font-medium">Status</label>
      <select name="status" class="w-full border rounded px-3 py-2 mb-3">
        <option value="Open" <?= $letter['status']=='Open'?'selected':'' ?>>Open</option>
        <option value="Done" <?= $letter['status']=='Done'?'selected':'' ?>>Done</option>
      </select>

      <!-- TYPE OF FILE -->
      <label class="text-sm font-medium">Type of File</label>
      <input type="text" name="file_type"
             value="<?= $letter['file_type'] ?? '' ?>"
             class="w-full border rounded px-3 py-2 mb-4"
             placeholder="e.g. Letter, Endorsement, Report">

      <!-- ACTIONS -->
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeTagModal()"
                class="px-4 py-2 bg-gray-300 rounded">
          Cancel
        </button>

        <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded">
          Save
        </button>
      </div>

    </form>
  </div>
</div>
<!-- TAG MODAL END -->

<!-- TAGGED DEPARMTMENTS MODAL -->

<!-- DEPARTMENT TAG MODAL -->
<div id="deptModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">

  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold">Manage Tagged Departments</h3>
      <button onclick="closeDeptModal()" class="text-xl">&times;</button>
    </div>

    <form method="POST" action="update_letter_departments.php">

      <input type="hidden" name="letter_id" value="<?= $letter['id'] ?>">

      <div class="max-h-64 overflow-y-auto space-y-2 border p-3 rounded">

        <?php
        $allDept = mysqli_query($conn, "SELECT * FROM departments WHERE department_status='Active'");

        // get assigned departments first
        $assigned = [];
        $q = mysqli_query($conn, "SELECT department_id FROM letter_departments WHERE letter_id='{$letter['id']}'");
        while($a = mysqli_fetch_assoc($q)){
          $assigned[] = $a['department_id'];
        }

        while($d = mysqli_fetch_assoc($allDept)){
        ?>
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox"
                   name="departments[]"
                   value="<?= $d['department_id'] ?>"
                   <?= in_array($d['department_id'], $assigned) ? 'checked' : '' ?>>
            <?= $d['department_name'] ?>
          </label>
        <?php } ?>

      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button type="button"
                onclick="closeDeptModal()"
                class="px-4 py-2 bg-gray-300 rounded">
          Cancel
        </button>

        <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded">
          Save
        </button>
      </div>

    </form>
  </div>
</div>



 <!-- TAGGED DEPARMTMENTS END -->
    
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
let expanded = false;

function toggleDepartments(){
    let items = document.querySelectorAll('.extra-dept');
    let btn = document.getElementById('toggleDeptBtn');

    expanded = !expanded;

    items.forEach(el => {
        el.classList.toggle('hidden');
    });

    btn.innerText = expanded ? "See less" : "See more";
}
</script>
<!--SCRIPT FOR MODALS-->
<script>
function openTagModal(){
    document.getElementById("tagModal").classList.remove("hidden");
    document.getElementById("tagModal").classList.add("flex");
}

function closeTagModal(){
    document.getElementById("tagModal").classList.add("hidden");
    document.getElementById("tagModal").classList.remove("flex");
}

function openDeptModal(){
  document.getElementById("deptModal").classList.remove("hidden");
}

function closeDeptModal(){
  document.getElementById("deptModal").classList.add("hidden");
}
</script>

<!--END SCRIPT FOR MODALS-->




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
function loadComments(){
    let id = $("#letterId").val();
    if(!id) return;

    $.get("load_comments.php", {letter_id: id}, function(data){
        $("#commentList").html(data);
    });
}

function addComment(){
    let id = $("#letterId").val();
    let comment = $("#commentInput").val();

    if(comment.trim() === "") return;

    $.post("save_comment.php", {
        letter_id: id,
        comment: comment
    }, function(){
        $("#commentInput").val("");
        loadComments();
    });
}

loadComments();
</script>


<script>
function uploadFiles() {
    let formData = new FormData();

    formData.append("letter_id", document.querySelector("input[name='letter_id']").value);

    fileArray.forEach(file => {
        formData.append("files[]", file);
    });

    $.ajax({
        url: "letter_edit_upload.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            alert("Uploaded!");
            location.reload();
        }
    });
}
</script>


<script>
function deleteFile(fileId){

    if(!confirm("Remove this file?")) return;

    $.post("delete_attachment.php", { file_id: fileId }, function(){

        // reload attachments or just refresh page
        location.reload();

    });
}
</script>




<!-- Footer -->
<footer class="mt-10 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>


</body>
</html>