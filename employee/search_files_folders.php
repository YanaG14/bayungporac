<?php
session_start();
require_once("../include/connection.php");

$user_department = $_SESSION['department_id'];

if(isset($_POST['keyword'])){
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    $query = mysqli_query($conn, "
    SELECT uf.*, f.folder_name, al.name AS uploader_name,
           GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') as departments
    FROM upload_files uf
    LEFT JOIN folders f ON uf.folder_id = f.folder_id
    LEFT JOIN admin_login al ON uf.email = al.id
    LEFT JOIN file_departments fd ON uf.id = fd.file_id
    LEFT JOIN departments d ON fd.department_id = d.department_id
    WHERE uf.status='Active'
    AND fd.department_id = '$user_department'
    AND (
        uf.name LIKE '%$keyword%'
        OR f.folder_name LIKE '%$keyword%'
        OR al.name LIKE '%$keyword%'
        OR uf.timers LIKE '%$keyword%'
        OR d.department_name LIKE '%$keyword%'
    )
    GROUP BY uf.id
    ORDER BY uf.id DESC
");

if(mysqli_num_rows($query) > 0){

echo '<div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-xl p-6 mt-6 mx-9 border border-gray-100">';

echo '<h3 class="text-xl font-semibold text-gray-800 mb-4">Files Search Results</h3>';

echo '<div class="overflow-x-auto rounded-2xl">';
echo '<table class="min-w-full text-sm border-separate border-spacing-y-2">';

echo '<thead>
        <tr class="text-dark-600 text-left">
            <th class="p-3">Filename</th>
            <th class="p-3">Folder</th>
            <th class="p-3">Departments</th>
            <th class="p-3">Uploader</th>
            <th class="p-3">Date</th>
            <th class="p-3 text-center">Action</th>
        </tr>
      </thead><tbody>';

while($row = mysqli_fetch_array($query)){
    $id = $row['id'];
    $filepath = "../uploads/".$row['file_path'];

    echo "<tr class='bg-white shadow-sm hover:shadow-md transition rounded-xl'>";

    echo "<td class='p-3 font-medium text-dark-800'>".htmlentities($row['name'])."</td>";
    echo "<td class='p-3 text-dark-600'>".htmlentities($row['folder_name'])."</td>";
    echo "<td class='p-3 text-dark-600'>".htmlentities($row['departments'])."</td>";
    echo "<td class='p-3 text-dark-600'>".htmlentities($row['uploader_name'])."</td>";
    echo "<td class='p-3 text-dark-600'>".htmlentities($row['timers'])."</td>";

    // ACTION COLUMN (with dropdown button styled)
    echo "<td class='p-3 text-center'>
            <div class='flex justify-center gap-2'>

                <!-- DOWNLOAD -->
                <a href='downloads.php?file_id=$id'
                   class='bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-xl shadow hover:scale-105 transition duration-300'
                   title='Download'>
                   <i class='fas fa-download'></i>
                </a>

                <!-- VIEW -->
                <a href='$filepath' target='_blank'
                   class='bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-3 py-2 rounded-xl shadow hover:scale-105 transition duration-300'
                   title='View'>
                   <i class='fas fa-eye'></i>
                </a>

            </div>
        </td>";

    echo "</tr>";
}

echo '</tbody></table></div>';
echo '</div>';

} else {
    echo "<div class='mt-6 text-gray-500 text-center'>No files found.</div>";
}
}
?>

<!-- JS FOR DROPDOWN -->
<script>
function toggleMenuSearch(id){
    const menu = document.getElementById('menu-search-' + id);

    document.querySelectorAll('[id^="menu-search-"]').forEach(el => {
        if(el !== menu){
            el.classList.add('hidden','scale-95','opacity-0');
        }
    });

    if(menu.classList.contains('hidden')){
        menu.classList.remove('hidden');
        setTimeout(()=>{
            menu.classList.remove('scale-95','opacity-0');
            menu.classList.add('scale-100','opacity-100');
        },10);
    } else {
        closeMenuSearch(id);
    }
}

function closeMenuSearch(id){
    const menu = document.getElementById('menu-search-' + id);
    menu.classList.add('scale-95','opacity-0');
    setTimeout(()=> menu.classList.add('hidden'),150);
}

// Close when clicking outside
document.addEventListener('click', function (event) {
    document.querySelectorAll('[id^="menu-search-"]').forEach(menu => {
        const button = menu.previousElementSibling;

        if (!menu.contains(event.target) && !button.contains(event.target)) {
            menu.classList.add('scale-95', 'opacity-0');
            setTimeout(() => menu.classList.add('hidden'), 150);
        }
    });
});

// Open edit modal
function openEditFromSearch(id){
    fetch('get_file_details.php?id=' + id)
    .then(res => res.text())
    .then(data => {
        document.getElementById('editModalContent').innerHTML = data;
        document.getElementById('editModal').classList.remove('hidden');
    });
}
</script>

<!-- MODAL DESIGN -->
<div id="editModal" 
class="hidden fixed inset-0 bg-black/40 backdrop-blur-md flex justify-center items-center z-50">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 animate-fadeIn border border-gray-100">

        <div class="flex justify-between items-center mb-4">
            <h4 class="font-semibold text-lg text-gray-800">Edit File</h4>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <div id="editModalContent">
            <!-- AJAX LOADED CONTENT -->
        </div>

    </div>
</div>

<script>
function closeEditModal(){
    document.getElementById('editModal').classList.add('hidden');
}
</script>