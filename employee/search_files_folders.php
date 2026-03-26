<?php
session_start();
require_once("../include/connection.php");

$user_department = $_SESSION['department_id'];

if(isset($_POST['keyword'])){
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    // Main container
    echo '<div class="container mx-auto px-4 py-6">'; // Tailwind container

    echo '<div id="searchResults" class="space-y-6">'; // spacing between sections

    // ===== Folders matching keyword =====
    $folders_query = mysqli_query($conn, "
        SELECT f.folder_id, f.folder_name
        FROM folders f
        JOIN folder_departments fd ON f.folder_id = fd.folder_id
        WHERE fd.department_id = '$user_department'
        AND f.folder_status='Active'
        AND f.folder_name LIKE '%$keyword%'
        ORDER BY f.folder_name ASC
    ");

    if(mysqli_num_rows($folders_query) > 0){
        echo '<h3 class="text-xl font-semibold text-gray-800 mb-2">Folder Results</h3>';
        while($folder = mysqli_fetch_assoc($folders_query)){
            $folder_id = $folder['folder_id'];
            echo '<div class="folder-card p-4 bg-white rounded-xl shadow-md">'; // folder card
            echo '<div class="flex items-center gap-2 mb-2">';
            echo '<i class="fas fa-folder text-yellow-500"></i>';
            echo '<span class="font-medium">'.htmlentities($folder['folder_name']).'</span>';
            echo '</div>';

            // Files inside folder matching keyword
            $files_query = mysqli_query($conn, "
                SELECT uf.*, al.name AS uploader_name,
                       GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') as departments
                FROM upload_files uf
                LEFT JOIN admin_login al ON uf.email = al.id
                LEFT JOIN file_departments fd ON uf.id = fd.file_id
                LEFT JOIN departments d ON fd.department_id = d.department_id
                WHERE uf.folder_id = '$folder_id'
                AND uf.status='Active'
                AND fd.department_id = '$user_department'
                AND uf.name LIKE '%$keyword%'
                GROUP BY uf.id
                ORDER BY uf.id DESC
            ");

            if(mysqli_num_rows($files_query) > 0){
                echo '<div class="overflow-x-auto mt-2 rounded-xl">';
                echo '<table class="min-w-full text-sm border-separate border-spacing-y-2">';
                echo '<thead>
                        <tr class="text-dark-600 text-left">
                            <th class="p-3">Filename</th>
                            <th class="p-3">Departments</th>
                            <th class="p-3">Uploader</th>
                            <th class="p-3">Date</th>
                            <th class="p-3 text-center">Action</th>
                        </tr>
                      </thead><tbody>';

                while($file = mysqli_fetch_assoc($files_query)){
                    $id = $file['id'];
                    $filepath = "../uploads/".$file['file_path'];
                    echo "<tr class='bg-white shadow-sm hover:shadow-md transition rounded-xl'>";
                    echo "<td class='p-3 font-medium text-dark-800'>".htmlentities($file['name'])."</td>";
                    echo "<td class='p-3 text-dark-600'>".htmlentities($file['departments'])."</td>";
                    echo "<td class='p-3 text-dark-600'>".htmlentities($file['uploader_name'])."</td>";
                    echo "<td class='p-3 text-dark-600'>".htmlentities($file['timers'])."</td>";
                    echo "<td class='p-3 text-center'>
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

                echo '</tbody></table></div>';
            } else {
                echo '<div class="text-gray-500 ml-7 mt-1">No matching files in this folder.</div>';
            }

            echo '</div>'; // folder-card
        }
    }

    // ===== Files not in matching folders =====
    $files_query = mysqli_query($conn, "
        SELECT uf.*, f.folder_name, al.name AS uploader_name,
               GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') as departments
        FROM upload_files uf
        LEFT JOIN folders f ON uf.folder_id = f.folder_id
        LEFT JOIN admin_login al ON uf.email = al.id
        LEFT JOIN file_departments fd ON uf.id = fd.file_id
        LEFT JOIN departments d ON fd.department_id = d.department_id
        WHERE uf.status='Active'
        AND fd.department_id = '$user_department'
        AND uf.name LIKE '%$keyword%'
        AND (uf.folder_id IS NULL OR uf.folder_id NOT IN (
            SELECT folder_id FROM folders WHERE folder_name LIKE '%$keyword%'
        ))
        GROUP BY uf.id
        ORDER BY uf.id DESC
    ");

    if(mysqli_num_rows($files_query) > 0){
        echo '<h3 class="text-xl font-semibold text-gray-800 mt-4 mb-2">Other Files</h3>';
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

        while($row = mysqli_fetch_assoc($files_query)){
            $id = $row['id'];
            $filepath = "../uploads/".$row['file_path'];
            echo "<tr class='bg-white shadow-sm hover:shadow-md transition rounded-xl'>";
            echo "<td class='p-3 font-medium text-dark-800'>".htmlentities($row['name'])."</td>";
            echo "<td class='p-3 text-dark-600'>".htmlentities($row['folder_name'])."</td>";
            echo "<td class='p-3 text-dark-600'>".htmlentities($row['departments'])."</td>";
            echo "<td class='p-3 text-dark-600'>".htmlentities($row['uploader_name'])."</td>";
            echo "<td class='p-3 text-dark-600'>".htmlentities($row['timers'])."</td>";
            echo "<td class='p-3 text-center'>
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

        echo '</tbody></table></div>';
    }

    echo '</div>'; // searchResults
    echo '</div>'; // container
}
?>