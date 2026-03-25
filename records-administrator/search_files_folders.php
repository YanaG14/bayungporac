<?php
require_once("../include/connection.php");

if(isset($_POST['keyword'])){
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    $query = mysqli_query($conn, "
    SELECT uf.*, f.folder_name, al.name AS uploader_name,
           GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') as departments
    FROM upload_files uf
    LEFT JOIN folders f ON uf.folder_id = f.folder_id
    LEFT JOIN admin_login al ON uf.email = al.id
    LEFT JOIN folder_departments fd ON f.folder_id = fd.folder_id
    LEFT JOIN departments d ON fd.department_id = d.department_id
    WHERE uf.status='Active'
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

    echo '<div class="bg-white rounded-2xl shadow-lg p-6 mt-6 overflow-hidden">';

    echo '<div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Files Search Results</h3>
          </div>';

    echo '<div class="overflow-x-auto rounded-xl border">';

    echo '<table class="min-w-full text-sm text-left text-gray-600">';

    // HEADER
    echo '<thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider sticky top-0">
            <tr>
                <th class="px-4 py-3">Filename</th>
                <th class="px-4 py-3">Folder</th>
                <th class="px-4 py-3">Departments</th>
                <th class="px-4 py-3">Uploader</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3 text-center">Action</th>
            </tr>
          </thead>';

    echo '<tbody class="divide-y divide-gray-100">';

    while($row = mysqli_fetch_array($query)){
        $id = $row['id'];
        $filepath = "../uploads/".$row['file_path'];

        echo "<tr class='hover:bg-gray-50 transition'>";

        echo "<td class='px-4 py-3 font-medium text-gray-800'>".htmlentities($row['name'])."</td>";
        echo "<td class='px-4 py-3'>".htmlentities($row['folder_name'])."</td>";
        echo "<td class='px-4 py-3'>".htmlentities($row['departments'])."</td>";
        echo "<td class='px-4 py-3'>".htmlentities($row['uploader_name'])."</td>";
        echo "<td class='px-4 py-3 whitespace-nowrap'>".htmlentities($row['timers'])."</td>";

        // ACTION
        echo "<td class='px-4 py-3 text-center'>
                <div class='relative inline-block'>

                    <button onclick='toggleMenuSearch($id)'
                        class='bg-gray-900 text-white px-3 py-2 rounded-lg hover:bg-gray-800 transition shadow-sm'>
                        <i class='fas fa-ellipsis-h'></i>
                    </button>

                    <div id='menu-search-$id'
                        class='hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-50 
                               transform scale-95 opacity-0 transition-all duration-200'>

                        <a href='downloads.php?file_id=$id'
                            class='flex items-center gap-2 px-4 py-3 hover:bg-gray-50 rounded-t-xl'>
                            <i class='fa fa-download text-blue-500'></i>
                            Download
                        </a>

                        <a href='$filepath' target='_blank'
                            class='flex items-center gap-2 px-4 py-3 hover:bg-gray-50'>
                            <i class='fa fa-eye text-indigo-500'></i>
                            View
                        </a>

                        <a href='archive_file.php?file_id=$id'
                            class='flex items-center gap-2 px-4 py-3 hover:bg-gray-50'>
                            <i class='fa fa-archive text-red-500'></i>
                            Archive
                        </a>

                        <button onclick=\"openEditFromSearch($id)\"
                            class='w-full text-left flex items-center gap-2 px-4 py-3 hover:bg-gray-50 rounded-b-xl'>
                            <i class='fa fa-edit text-green-500'></i>
                            Edit
                        </button>

                    </div>

                </div>
              </td>";

        echo "</tr>";
    }

    echo '</tbody></table>';
    echo '</div>'; // overflow
    echo '</div>'; // card

} else {
    echo "<div class='mt-6 text-center text-gray-500'>No files found.</div>";
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

// Open edit modal (you must create this in main page)
function openEditFromSearch(id){
    fetch('get_file_details.php?id=' + id)
    .then(res => res.text())
    .then(data => {
        document.getElementById('editModalContent').innerHTML = data;
        document.getElementById('editModal').classList.remove('hidden');
    });
}
</script>

<div id="editModal" 
class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fadeIn">

        <div class="flex justify-between items-center mb-4">
            <h4 class="font-semibold text-lg">Edit File</h4>
            <button onclick="closeEditModal()" class="text-gray-500 text-xl">&times;</button>
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