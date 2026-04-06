<?php
session_start();
require_once("../include/connection.php");

$id = intval($_GET['id']);

// GET FILE DETAILS
$query = mysqli_query($conn, "SELECT * FROM upload_files WHERE id='$id'");
$file = mysqli_fetch_array($query);

// SPLIT FILE NAME AND EXTENSION
$file_parts = pathinfo($file['name']);
$filename_no_ext = $file_parts['filename'];
$extension = $file_parts['extension'];
?>

<form method="POST" action="edit_file.php" class="space-y-4">
    <input type="hidden" name="file_id" value="<?php echo $id; ?>">
    <input type="hidden" name="folder_id" value="<?php echo $file['folder_id']; ?>">

    <!-- FILE NAME -->
    <div>
        <label class="block font-medium text-gray-700">File Name</label>
        <div class="flex items-center gap-2 mt-2">
            <input type="text" name="file_name" value="<?php echo htmlentities($filename_no_ext); ?>"
                class="w-full border p-2 rounded" required>
            <span class="text-gray-500">.<?php echo $extension; ?></span>
        </div>
    </div>

    <!-- DEPARTMENTS -->
    <div>
        <label class="block font-medium text-gray-700">Assign Departments</label>
        <div class="mt-2 space-y-1">
        <?php
        // GET ALL ACTIVE DEPARTMENTS
        $departments = mysqli_query($conn,"SELECT * FROM departments WHERE department_status='Active' ORDER BY department_name");

        // GET CURRENTLY ASSIGNED DEPARTMENTS
        $assigned = mysqli_query($conn,"SELECT department_id FROM file_departments WHERE file_id='$id'");
        $assigned_dept = [];
        while($ad = mysqli_fetch_array($assigned)){
            $assigned_dept[] = $ad['department_id'];
        }

        // DISPLAY CHECKBOXES
        while($d = mysqli_fetch_array($departments)){
            $checked = in_array($d['department_id'], $assigned_dept) ? 'checked' : '';
            echo '<div class="flex items-center gap-2">';
            echo '<input type="checkbox" name="departments[]" value="'.$d['department_id'].'" '.$checked.' class="h-4 w-4 text-green-600 border-gray-300 rounded">';
            echo '<span class="text-gray-700">'.htmlentities($d['department_name']).'</span>';
            echo '</div>';
        }
        ?>
        </div>
    </div>

    <!-- SAVE BUTTON -->
    <div class="text-right mt-4">
        <button type="submit" name="edit_file"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Save Changes
        </button>
    </div>
</form>