<?php
require_once("../include/connection.php");

if(isset($_POST['keyword'])){
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    $query = mysqli_query($conn, "
        SELECT *
        FROM users
        LEFT JOIN departments ON users.department_id = departments.department_id
        WHERE name LIKE '%$keyword%'
        OR email_address LIKE '%$keyword%'
        OR user_status LIKE '%$keyword%'
        OR department_name LIKE '%$keyword%'
        ORDER BY id DESC
    ");

    if(mysqli_num_rows($query) > 0){
        echo '<div class="bg-white rounded-2xl shadow-lg p-6 mt-6 overflow-hidden">';
        echo '<div class="flex justify-between items-center mb-4 p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-m sm:text-m font-semibold flex items-center gap-2 text-gray-700">
                    <i class="fas fa-users text-green-500"></i>
                    Users Search Results
                </h3>
                <span class="text-sm text-gray-500">Showing search results dynamically</span>
              </div>';
        echo '<div class="overflow-x-auto rounded-xl border">';
        echo '<table class="min-w-full text-sm text-left text-gray-600">';
        
        // HEADER
        echo '<thead class="bg-gray-200 text-black uppercase text-xs tracking-wider sticky top-0">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Department</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
              </thead>';

        echo '<tbody class="divide-y divide-gray-100">';
        while($row = mysqli_fetch_array($query)){
            $id = $row['id'];

            echo "<tr class='hover:bg-gray-50 transition'>";
            echo "<td class='px-4 py-3 font-medium text-gray-800'>".htmlentities($row['name'])."</td>";
            echo "<td class='px-4 py-3'>".htmlentities($row['email_address'])."</td>";
            echo "<td class='px-4 py-3'>".htmlentities($row['user_status'])."</td>";
            echo "<td class='px-4 py-3'>".htmlentities($row['department_name'] ?? 'N/A')."</td>";

            // ACTION DROPDOWN
            echo "<td class='px-4 py-3 text-center'>
                    <div class='relative inline-block'>
                        <button onclick='toggleMenuUser($id)' class='flex items-center justify-center w-8 h-8 rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition duration-200'>
                            <i class='fas fa-ellipsis-h text-sm'></i>
                        </button>

                        <div id='menu-user-$id' class='hidden absolute top-full mt-1 right-0 w-28 bg-white rounded-lg shadow-sm border border-gray-100 z-50
                                   transform scale-95 opacity-0 transition-all duration-150'>
                            <button onclick=\"openEditUser($id)\" class='w-full flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded-t-lg'>
                                <i class='fas fa-edit text-[10px] text-blue-500'></i>
                                Edit
                            </button>
                            <a href='delete_user.php?id=$id' class='w-full flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded-b-lg'
                               onclick=\"return confirm('Delete this user?')\">
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
        echo "<div class='mt-6 text-center text-gray-500'>No users found.</div>";
    }
}
?>