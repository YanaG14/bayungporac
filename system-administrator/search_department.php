<?php
require_once("../include/connection.php");

if(isset($_POST['keyword'])){
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    $query = mysqli_query($conn, "
        SELECT *
        FROM departments
        WHERE department_name LIKE '%$keyword%'
        OR department_status LIKE '%$keyword%'
        ORDER BY department_id DESC
    ");

    if(mysqli_num_rows($query) > 0){
        echo '<div class="bg-white rounded-2xl shadow-lg p-6 mt-6 overflow-hidden">';
        echo '<div class="flex justify-between items-center mb-4 p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-m sm:text-m font-semibold flex items-center gap-2 text-gray-700">
                    <i class="fas fa-building text-green-500"></i>
                    Departments Search Results
                </h3>
                <span class="text-sm text-gray-500">Showing search results dynamically</span>
              </div>';
        echo '<div class="overflow-x-auto rounded-xl border">';
        echo '<table class="min-w-full text-sm text-left text-gray-600">';
        
        // HEADER
        echo '<thead class="bg-gray-200 text-black uppercase text-xs tracking-wider sticky top-0">
                <tr>
                    <th class="px-4 py-3">Department Name</th>
                    <th class="px-4 py-3">Logo</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Created At</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
              </thead>';

        echo '<tbody class="divide-y divide-gray-100">';
        while($row = mysqli_fetch_array($query)){
            $id = $row['department_id'];
            $logo = "../uploads/departments/".$row['department_img'];

            echo "<tr class='hover:bg-gray-50 transition'>";
            echo "<td class='px-4 py-3 font-medium text-gray-800'>".htmlentities($row['department_name'])."</td>";
            echo "<td class='px-4 py-3'><img src='$logo' class='w-10 h-10 object-cover rounded-md'></td>";
            echo "<td class='px-4 py-3'>".htmlentities($row['department_status'])."</td>";
            echo "<td class='px-4 py-3 whitespace-nowrap'>".htmlentities($row['created_at'])."</td>";

            // ACTION DROPDOWN
            echo "<td class='px-4 py-3 text-center'>
                    <div class='relative inline-block'>
                        <button onclick='toggleMenuDept($id)' class='flex items-center justify-center w-8 h-8 rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition duration-200'>
                            <i class='fas fa-ellipsis-h text-sm'></i>
                        </button>

                        <div id='menu-dept-$id' class='hidden absolute top-full mt-1 right-0 w-28 bg-white rounded-lg shadow-sm border border-gray-100 z-50
                                   transform scale-95 opacity-0 transition-all duration-150'>
                            <button onclick=\"openEditDept($id)\" class='w-full flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded-t-lg'>
                                <i class='fas fa-edit text-[10px] text-blue-500'></i>
                                Edit
                            </button>
                            <a href='delete_department.php?id=$id' class='w-full flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded-b-lg'
                               onclick=\"return confirm('Delete this department?')\">
                                <i class='fas fa-trash text-[10px] text-red-500'></i>
                                Delete
                            </a>
                        </div>
                    </div>
                  </td>";
            echo "</tr>";
        }
        echo '</tbody></table>';
        echo '</div>'; // overflow
        echo '</div>'; // card
    } else {
        echo "<div class='mt-6 text-center text-gray-500'>No departments found.</div>";
    }
}
?>

<script>
// DROPDOWN
function toggleMenuDept(id){
    const menu = document.getElementById('menu-dept-' + id);
    document.querySelectorAll('[id^="menu-dept-"]').forEach(el => {
        if(el !== menu){
            el.classList.add('hidden','scale-95','opacity-0');
        }
    });
    if(menu.classList.contains('hidden')){
        menu.classList.remove('hidden');
        setTimeout(()=> {
            menu.classList.remove('scale-95','opacity-0');
            menu.classList.add('scale-100','opacity-100');
        }, 10);
    } else {
        closeMenuDept(id);
    }
}
function closeMenuDept(id){
    const menu = document.getElementById('menu-dept-' + id);
    menu.classList.add('scale-95','opacity-0');
    setTimeout(()=> menu.classList.add('hidden'),150);
}

// Click outside to close
document.addEventListener('click', function (event) {
    document.querySelectorAll('[id^="menu-dept-"]').forEach(menu => {
        const button = menu.previousElementSibling;
        if(!menu.contains(event.target) && !button.contains(event.target)){
            menu.classList.add('scale-95','opacity-0');
            setTimeout(()=> menu.classList.add('hidden'),150);
        }
    });
});

// AJAX load edit modal
function openEditDept(id){
    fetch('get_department_details.php?id=' + id)
    .then(res => res.text())
    .then(data => {
        document.getElementById('editDeptModalContent').innerHTML = data;
        document.getElementById('editDeptModal').classList.remove('hidden');
    });
}
</script>

<!-- EDIT DEPARTMENT MODAL -->
<div id="editDeptModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fadeIn">
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-semibold text-lg">Edit Department</h4>
            <button onclick="closeEditDeptModal()" class="text-gray-500 text-xl">&times;</button>
        </div>
        <div id="editDeptModalContent"><!-- AJAX loaded content --></div>
    </div>
</div>

<script>
function closeEditDeptModal(){
    document.getElementById('editDeptModal').classList.add('hidden');
}
</script>