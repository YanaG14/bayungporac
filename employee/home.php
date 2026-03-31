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
        $('#page-content').addClass('opacity-100'); // show page
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

<div id="page-content" class="opacity-0 transition-opacity duration-500">

<!-- NAVBAR -->
<!-- Main Navbar -->
<nav class="fixed top-0 w-full bg-green-700 shadow-md z-50">
  <div class="flex justify-between items-center h-16 px-4 sm:px-6">
    <!-- Logo & Title -->
    <div class="flex items-center space-x-2 sm:space-x-3">
      <img src="img/municipalLogo.png" alt="Logo" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white object-cover">
      <h1 class="text-white font-bold text-base sm:text-lg md:text-xl whitespace-nowrap">Bayung Porac Archive</h1>
    </div>

    <!-- Right Side -->
    <div class="flex items-center space-x-2 sm:space-x-4">
      <!-- Desktop Welcome -->
      <span class="hidden md:inline-block text-sm md:text-base">
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
      <a href="logout.php" class="hidden md:inline-block px-4 py-2 text-sm md:text-base rounded-lg border border-white hover:bg-white hover:text-green-700 transition-all duration-300 font-medium">
        Log out
      </a>
    </div>
  </div>
</nav>

<!-- Mobile Dropdown Menu (YELLOW BG + WHITE TEXT) -->
<div id="mobileMenu" class="md:hidden fixed top-20 sm:top-20 left-4 right-4 max-w-sm mx-auto bg-white shadow-2xl rounded-2xl border-2 border-gray-200 z-40 opacity-0 invisible transform scale-95 transition-all duration-300">
  <div class="p-6 space-y-4">
    <!-- Mobile Welcome -->
    <div class="text-center">
      <p class="text-sm font-medium text-gray-600">Welcome,</p>
      <p class="font-bold text-xl text-gray-900"><?php echo ucwords(htmlentities($name)); ?></p>
    </div>
    
    <!-- Smaller Logout Button -->
    <a href="logout.php" 
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



    <!-- MAIN TABLE/FOLDER SECTION -->
<main class="mt-20 px-2 md:px-9 col-span-12 md:col-span-9">
  <div id="parentContainer"
       class="bg-white/90 backdrop-blur-md shadow-lg rounded-2xl p-6
              hover:shadow-xl transition-shadow duration-300
              w-full max-w-full
              min-h-[50vh] md:min-h-[60vh] lg:min-h-[70vh]
              overflow-y-hidden
              mx-auto
              transition-all duration-300 ease-in-out">

    <?php if ($selected_folder === 0): 
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

    <!-- SEARCH BAR -->
    <div class="mb-6 flex justify-center">
        <div class="w-full max-w-xl flex gap-2">
            <input type="text" id="searchInput"
                placeholder="Search"
                class="w-full border rounded-xl px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
    </div>

    <!--FOLDER CONTAINER-->
    <div id="folderContainer" class="flex flex-col gap-4 w-full p-4">

      <?php while($folder = $folders->fetch_assoc()): ?>
        <!-- FOLDER CARD -->
        <div class="folder-card flex flex-col bg-white rounded-lg shadow-md p-3
                    hover:bg-green-50 transition duration-200 cursor-pointer"
             data-name="<?php echo strtolower($folder['folder_name']); ?>"
             onclick="window.location='?folder_id=<?php echo $folder['folder_id']; ?>'">

          <!-- FOLDER HEADER: title + download button -->
          <div class="flex justify-between items-center gap-2">
            <div class="flex items-center gap-2 truncate px-2 py-1">
              <i class="fas fa-folder text-yellow-500 text-sm"></i>
              <span class="font-medium text-gray-700 text-sm truncate">
                <?php echo htmlentities($folder['folder_name']); ?>
              </span>
            </div>

            <!-- DOWNLOAD BUTTON (icon only) -->
            <a href="download_folder.php?folder_id=<?php echo $folder['folder_id']; ?>" 
               class="text-green-600 hover:text-green-800 transition text-lg flex-shrink-0"
               onclick="event.stopPropagation();">
              <i class="fas fa-download"></i>
            </a>
          </div>

        </div>
      <?php endwhile; ?>
    </div>

    <!-- SEARCH RESULTS -->
<div id="searchResults"></div>

  </div>
</main>

<!-- SEARCH JS WITH SMOOTH ANIMATION -->
<script>
  const searchInput = document.getElementById('searchInput');
  const folderCards = document.querySelectorAll('.folder-card');
  const parentContainer = document.getElementById('parentContainer');

  searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;

    folderCards.forEach(card => {
      const folderName = card.getAttribute('data-name');
      if(folderName.includes(query) || query === '') {
        card.style.display = 'flex'; // show card
        visibleCount++;
      } else {
        card.style.display = 'none'; // hide card
      }
    });

    // Smoothly adjust parent container height when searching
    if(query === '') {
      // Reset to responsive default height
      parentContainer.style.height = '';
      parentContainer.style.overflowY = 'hidden';
    } else {
      // Shrink to fit visible folders + small padding
      const folderHeight = folderCards[0]?.offsetHeight || 100;
      const gap = 16; // gap-4 in px
      parentContainer.style.height = `${visibleCount * (folderHeight + gap) + 40}px`; 
      parentContainer.style.overflowY = 'auto';
    }
  });

  // Adjust width dynamically on screen resize
  window.addEventListener('resize', () => {
    parentContainer.style.width = `${Math.min(window.innerWidth - 32, 1455)}px`;
  });
</script>

        <?php else: 
            // Show files in selected folder
            $stmt = $conn->prepare("
                SELECT uf.id, uf.name, uf.size, uf.email, uf.admin_status, uf.timers, uf.download, uf.file_path,
                       al.name AS uploader_name
                FROM upload_files uf
                JOIN file_departments fd ON uf.id = fd.file_id
                LEFT JOIN admin_login al ON uf.email = al.id
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
                <th class="px-4 py-3 text-left">Uploader</th>
                <th class="px-4 py-3 text-left">Upload Date</th>
                <th class="px-4 py-3 text-left">Downloads</th>
                <th class="px-4 py-3 text-left">Action</th>
              </tr>
            </thead>
            <tbody class="text-gray-700">
              <?php while($file = $files->fetch_assoc()): ?>
              <?php $filepath = "../uploads/" . $file['file_path']; ?>
              <tr class="border-b hover:bg-green-50 transition-colors duration-200">
                <td class="px-4 py-2"><?php echo htmlentities($file['name']); ?></td>
                <td class="px-4 py-2"><?php echo htmlentities($file['uploader_name']); ?></td>
                <td class="px-4 py-2"><?php echo htmlentities($file['timers']); ?></td>
                <td class="px-4 py-2"><?php echo $file['download']; ?></td>
                <td class="px-4 py-2">
                  <div class="flex gap-2">
                    <a href="downloads.php?file_id=<?php echo $file['id']; ?>" 
                       class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                       <i class="fas fa-download"></i>
                    </a>
                    <a href="<?php echo $filepath; ?>" target="_blank"
                       class="bg-indigo-500 text-white px-3 py-1 rounded hover:bg-indigo-600">
                       <i class="fas fa-eye"></i>
                    </a>
                  </div>
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



<script>
function searchFiles(){
    let keyword = document.getElementById("searchInput").value.toLowerCase();

    let folders = document.querySelectorAll(".folder-card");
    folders.forEach(folder => {
        let name = folder.getAttribute("data-name");
        folder.style.display = name.includes(keyword) ? "" : "none";
    });

    if(keyword.trim() === ""){
        document.getElementById("searchResults").innerHTML = "";
        return;
    }

    fetch("search_files_folders.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "keyword=" + encodeURIComponent(keyword)
    })
    .then(response => response.text())
    .then(data => { document.getElementById("searchResults").innerHTML = data; });
}

document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("searchInput").addEventListener("keyup", searchFiles);
});
</script>

<!-- Footer -->
<footer class="mt-9 text-center text-gray-500 text-sm">
  <p class="text-gray-500">&#169; All Rights Reserved. Developed by the PSU IT Interns.</p>
</footer>

</div> <!-- page-content -->
</body>
</html>