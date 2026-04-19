<?php
include("../include/connection.php");

$keyword = mysqli_real_escape_string($conn, $_POST['keyword'] ?? '');

$output = "";

/* =========================
   FOLDER SEARCH
========================= */
$folder_output = "";

$folders = mysqli_query($conn, "
SELECT 
    f.folder_id, 
    f.folder_name,
    f.created_at,
    GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') AS departments
FROM folders f
LEFT JOIN folder_departments fd ON f.folder_id = fd.folder_id
LEFT JOIN departments d ON fd.department_id = d.department_id
WHERE f.folder_status='Active'
AND (
    f.folder_name LIKE '%$keyword%'
    OR d.department_name LIKE '%$keyword%'
    OR f.created_at LIKE '%$keyword%'
)
GROUP BY f.folder_id
");
/* =========================
   FILE SEARCH
========================= */
$file_output = "";

$files = mysqli_query($conn, "
SELECT 
    uf.id,
    uf.name,
    uf.timers,
    f.folder_name,
    GROUP_CONCAT(DISTINCT d.department_name SEPARATOR ', ') AS departments,
    al.name AS uploader
FROM upload_files uf
LEFT JOIN folders f ON uf.folder_id = f.folder_id
LEFT JOIN folder_departments fd ON f.folder_id = fd.folder_id
LEFT JOIN departments d ON fd.department_id = d.department_id
LEFT JOIN admin_login al ON uf.email = al.id
WHERE uf.status='Active'
AND (
    uf.name LIKE '%$keyword%'
    OR al.name LIKE '%$keyword%'
    OR uf.timers LIKE '%$keyword%'
    OR d.department_name LIKE '%$keyword%'
)
GROUP BY uf.id
");

/* =========================
   FOLDER ROWS
========================= */


while($f = mysqli_fetch_assoc($folders)){
    $folder_output .= "
    <tr class='hover:bg-gray-50'>
        <td class='px-3 py-2'>📁 <b>{$f['folder_name']}</b></td>
        <td>{$f['departments']}</td>
        <td>{$f['created_at']}</td>
   <td class='text-center relative'>

    <button onclick='toggleFolderMenu({$f['folder_id']})'
        class='text-gray-500 hover:text-gray-800 text-lg px-2'>
        <i class='fas fa-ellipsis-h'></i>
    </button>

    <!-- DROPDOWN -->
    <div id='folder-menu-{$f['folder_id']}'
         class='hidden absolute right-0 mt-2 w-32 bg-white border rounded-lg shadow-lg z-50'>

        <!-- EDIT -->
        <button onclick='openEditModal({$f['folder_id']})'
            class='block w-full text-left px-3 py-2 text-sm hover:bg-gray-100'>
            Edit
        </button>

        <!-- ARCHIVE -->
        <button onclick='confirmArchive({$f['folder_id']})'
            class='block w-full text-left px-3 py-2 text-sm hover:bg-gray-100'>
            Archive
        </button>

        <!-- DOWNLOAD -->
        <a href='download_folder.php?folder_id={$f['folder_id']}'
            class='block px-3 py-2 text-sm hover:bg-gray-100'>
            Download
        </a>

    </div>

</td>
    </tr>";
}
/* =========================
   FILE ROWS
========================= */

while($file = mysqli_fetch_assoc($files)){
    $file_output .= "
    <tr class='hover:bg-gray-50 border-b'>

        <!-- FILE NAME -->
        <td class='px-3 py-3'>
            <div class='flex flex-col'>
                <span class='flex items-center gap-2'>
                    <i class='fas fa-file text-blue-500'></i>
                    {$file['name']}
                </span>

                <span class='text-xs text-gray-500 ml-6'>
                    Folder: {$file['folder_name']}
                </span>
            </div>
        </td>

        <!-- DEPARTMENTS -->
        <td class='hidden md:table-cell'>
            {$file['departments']}
        </td>

        <!-- UPLOADER -->
        <td>
            {$file['uploader']}
        </td>

        <!-- DATE -->
        <td>
            {$file['timers']}
        </td>

        <!-- ACTION -->
     <td class='text-center relative'>

    <button onclick='toggleFileMenu({$file['id']})'
        class='text-gray-500 hover:text-gray-800 text-lg px-2'>
        <i class='fas fa-ellipsis-h'></i>
    </button>

    <div id='file-menu-{$file['id']}'
         class='hidden absolute right-0 mt-2 w-36 bg-white border rounded-lg shadow-lg z-50'>

        <!-- VIEW -->
        <a href='view_file.php?id={$file['id']}'
           class='block px-3 py-2 text-sm hover:bg-gray-100'>
            View
        </a>

        <!-- DOWNLOAD -->
        <a href='search_download_file.php?id={$file['id']}'
           class='block px-3 py-2 text-sm hover:bg-gray-100'>
            Download
        </a>

        <!-- EDIT -->
        <button onclick='openEditFileModal({$file['id']})'
            class='block w-full text-left px-3 py-2 text-sm hover:bg-gray-100'>
            Edit
        </button>

        <!-- ARCHIVE -->
        <button onclick='confirmArchiveFile({$file['id']})'
            class='block w-full text-left px-3 py-2 text-sm hover:bg-gray-100'>
            Archive
        </button>

    </div>

</td>
    </tr>";
}

echo json_encode([
    "folders" => $folder_output,
    "files" => $file_output
]);


?>
