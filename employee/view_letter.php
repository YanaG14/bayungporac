<!DOCTYPE html>
<html lang="en">
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
  Welcome, <b><?php echo ucwords(htmlentities($name)); ?></b>!
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

<?php if($letter): ?>

<div class="bg-white rounded-2xl shadow-lg p-6">
<div class="mb-4">
    <a href="javascript:history.back()" 
       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm transition">
        ← Back
    </a>
</div>
    <!-- HEADER -->
    <h2 class="text-2xl font-bold text-gray-800">
        <?= htmlspecialchars($letter['subject']) ?>
    </h2>

    <p class="text-gray-500 mt-1">
        Reference No: <?= $letter['reference_no'] ?>
    </p>

    <p class="text-gray-500 mb-4">
        Sender: <?= htmlspecialchars($letter['sender']) ?>
    </p>

    <!-- FILE PREVIEW -->
<!-- FILE PREVIEW -->
<div class="border rounded-xl h-[190px] overflow-hidden bg-gray-50">

<?php
$file = $letter['file_path'] ?? '';
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

// FIX PATH HERE (IMPORTANT)
$filePath = "../records-administrator/letter_files/" . $file;
?>

<?php if(!empty($file) && file_exists($filePath)) { ?> 

    <?php if($ext == "pdf"){ ?>
        <iframe src="<?= $filePath ?>#toolbar=0"
                class="w-full h-full"></iframe>

    <?php } elseif(in_array($ext, ['jpg','jpeg','png','gif','webp'])) { ?>
        <img src="<?= $filePath ?>"
             class="w-full h-full object-contain">

    <?php } else { ?>
        <div class="flex items-center justify-center h-full text-gray-500">
            File type not supported for preview
        </div>
    <?php } ?> 

<?php } else { ?>
    <div class="flex items-center justify-center h-full text-gray-500">
        No file found or wrong path
    </div>
<?php } ?>

</div>

    <!-- COMMENTS -->
    <div class="mt-6 border-t pt-4">

        <h3 class="font-semibold text-gray-700 mb-2">Comments</h3>

        <div id="commentList"
             class="h-32 overflow-y-auto bg-gray-50 p-3 rounded mb-3 text-sm">
            Loading comments...
        </div>

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

    </div>

</div>

<?php else: ?>

<div class="bg-white p-6 rounded-xl shadow text-gray-500">
    Select a letter to view.
</div>

<?php endif; ?>

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







<!-- Footer -->
<footer class="mt-10 text-center text-gray-600">
  <p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>


</body>
</html>