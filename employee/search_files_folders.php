<?php
session_start();
require_once("../include/connection.php");

$user_department = $_SESSION['department_id'];

if(isset($_POST['keyword'])){
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    // Query only files assigned to the employee's department
    $query = mysqli_query($conn, "
        SELECT uf.*, f.folder_name, al.name AS uploader_name,
               GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') as departments
        FROM upload_files uf
        LEFT JOIN folders f ON uf.folder_id = f.folder_id
        LEFT JOIN login_user al ON uf.email = al.id
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

    echo '<div class="container mx-auto px-4 py-6">';
    
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
                    <div class='flex justify-center gap-2'>
                        <a href='downloads.php?file_id=$id'
                           class='bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-xl shadow hover:scale-105 transition duration-300'
                           title='Download'><i class='fas fa-download'></i></a>
                        <a href='$filepath' target='_blank'
                           class='bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-3 py-2 rounded-xl shadow hover:scale-105 transition duration-300'
                           title='View'><i class='fas fa-eye'></i></a>
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

    echo '</div>'; // container
}
?>