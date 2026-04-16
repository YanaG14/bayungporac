<?php
require_once("../include/connection.php");

if(isset($_POST['keyword'])){

    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    /* =======================
       FOLDERS QUERY
    ======================= */
    $folderQuery = mysqli_query($conn, "
        SELECT 
            f.folder_id,
            f.folder_name,
            f.created_at,
            GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') as departments
        FROM folders f
        LEFT JOIN folder_departments fd ON f.folder_id = fd.folder_id
        LEFT JOIN departments d ON fd.department_id = d.department_id
        WHERE f.folder_status='Active'
        AND (
            f.folder_name LIKE '%$keyword%'
            OR d.department_name LIKE '%$keyword%'
        )
        GROUP BY f.folder_id
    ");

    /* =======================
       FILES QUERY
    ======================= */
 $fileQuery = mysqli_query($conn, "
    SELECT 
        uf.*,
        f.folder_id,
        f.folder_name,
        al.name AS uploader_name,
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

    /* =======================
       OUTPUT
    ======================= */

    /* =======================
       FOLDERS SECTION
    ======================= */
    if(mysqli_num_rows($folderQuery) > 0){

        echo "
        <tr>
            <td colspan='4' class='bg-green-100 font-bold text-green-800 px-3 py-2'>
                📁 Folders
            </td>
        </tr>
        ";

     while($row = mysqli_fetch_array($folderQuery)){

    echo "
    <tr class='hover:bg-gray-50/50 transition'>

        <!-- Folder Name -->
        <td class='px-3 py-3'>
            <a href='add_document.php?folder_id={$row['folder_id']}' 
               class='flex items-center gap-2 text-gray-800 hover:text-green-700'>
                <i class='fas fa-folder text-yellow-500'></i>
                <b>{$row['folder_name']}</b>
            </a>
        </td>

        <!-- Departments -->
        <td class='px-3 py-3 hidden md:table-cell'>
            {$row['departments']}
        </td>

        <!-- Date -->
        <td class='px-3 py-3'>
            ".date('M j, Y', strtotime($row['created_at']))."
        </td>

        <!-- ACTION BUTTONS (COPIED FROM MAIN UI) -->
        <td class='px-3 py-3 align-middle'>
            <div class='flex justify-center relative'>

                <button onclick='toggleMenu({$row['folder_id']})'
                        class='text-gray-500 hover:text-gray-800 text-xl px-2'>
                    <i class='fas fa-ellipsis-h text-sm'></i>
                </button>

                <!-- DROPDOWN -->
                <div id='menu-{$row['folder_id']}'
                     class='hidden absolute top-full mt-1.5 right-0 w-28 sm:w-32 bg-white rounded-lg shadow-lg border z-50'>

                    <button onclick='openEditModal({$row['folder_id']});'
                            class='w-full flex items-center gap-2 px-3 py-2 text-xs hover:bg-gray-100'>
                        <i class='fas fa-edit text-blue-500'></i> Edit
                    </button>

                    <button onclick='confirmArchive({$row['folder_id']});'
                            class='w-full flex items-center gap-2 px-3 py-2 text-xs hover:bg-gray-100'>
                        <i class='fas fa-archive text-yellow-500'></i> Archive
                    </button>

                    <a href='download_folder.php?folder_id={$row['folder_id']}'
                       class='w-full flex items-center gap-2 px-3 py-2 text-xs hover:bg-gray-100 block'>
                        <i class='fas fa-download text-green-600'></i> Download
                    </a>

                </div>
            </div>
        </td>

    </tr>";
}
    }
    /* =======================
       FILES SECTION
    ======================= */
   if(mysqli_num_rows($fileQuery) > 0){

    echo "
    <tr>
        <td colspan='5' class='bg-blue-100 font-bold text-blue-800 px-3 py-2'>
            📄 Files
        </td>
    </tr>

    <tr class='bg-gray-100 font-semibold text-sm'>
        <td class='px-3 py-2'>File Name</td>
        <td class='px-3 py-2'>Folder</td>
        <td class='px-3 py-2'>Department</td>
        <td class='px-3 py-2'>Author</td>
        <td class='px-3 py-2 text-center'>Action</td>
    </tr>
    ";

    while($row = mysqli_fetch_array($fileQuery)){

        $id = $row['id'];
        $filePath = "uploads/" . $row['file_path'];

        echo "
        <tr class='hover:bg-blue-50 transition'>

            <!-- File Name -->
            <td class='px-3 py-3'>
                <i class='fas fa-file text-blue-500'></i>
                {$row['name']}
            </td>

            <!-- Folder -->
            <td class='px-3 py-3'>
                {$row['folder_name']}
            </td>

            <!-- Department -->
            <td class='px-3 py-3'>
                {$row['departments']}
            </td>

            <!-- Author -->
            <td class='px-3 py-3'>
                {$row['uploader_name']}
            </td>

            <!-- ACTION -->
            <td class='px-3 py-3'>
                <div class='flex justify-center relative'>

                    <button onclick='toggleMenuFileSearch({$id})'
                        class='text-gray-500 hover:text-gray-800 text-xl px-2'>
                        <i class='fas fa-ellipsis-h text-sm'></i>
                    </button>

                    <div id='menu-file-search-{$id}'
                         class='hidden absolute top-full mt-1 right-0 w-40 bg-white rounded-lg shadow-lg border z-50'>

                        <a href='download_file.php?id={$id}'
                           class='w-full flex items-center gap-2 px-3 py-2 text-xs hover:bg-gray-100 block'>
                            <i class='fas fa-download text-green-600'></i> Download
                        </a>
 <button onclick='openEditModalFile({$row['folder_id']});'
                            class='w-full flex items-center gap-2 px-3 py-2 text-xs hover:bg-gray-100'>
                        <i class='fas fa-edit text-blue-500'></i> Edit
                    </button>
                        <a href='{$filePath}' target='_blank'
                           class='w-full flex items-center gap-2 px-3 py-2 text-xs hover:bg-gray-100 block'>
                            <i class='fas fa-eye text-blue-600'></i> View
                        </a>

                        <a href='archive_file.php?file_id={$id}'
                           class='w-full flex items-center gap-2 px-3 py-2 text-xs hover:bg-gray-100 block'>
                            <i class='fas fa-archive text-yellow-500'></i> Archive
                        </a>

                    </div>
                </div>
            </td>

        </tr>
        ";
    }
}
    /* =======================
       NO RESULT
    ======================= */
    if(mysqli_num_rows($folderQuery) == 0 && mysqli_num_rows($fileQuery) == 0){
        echo "
        <tr>
            <td colspan='4' class='text-center text-gray-500 py-6'>
                No folders or files found.
            </td>
        </tr>
        ";
    }
}
?>