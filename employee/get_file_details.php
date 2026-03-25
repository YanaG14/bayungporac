<?php
require_once("../include/connection.php");

$id = intval($_GET['id']);

$user_department = $_SESSION['department_id'];

// GET FILE
$query = mysqli_query($conn, "
SELECT uf.* 
FROM upload_files uf
JOIN file_departments fd ON uf.id = fd.file_id
WHERE uf.id='$id'
AND fd.department_id = '$user_department'
");
$file = mysqli_fetch_array($query);

// FILE NAME SPLIT
$file_parts = pathinfo($file['name']);
$filename_no_ext = $file_parts['filename'];
$extension = $file_parts['extension'];
?>  

<form method="POST" action="edit_file.php">
    <input type="hidden" name="file_id" value="<?php echo $id; ?>">
    <input type="hidden" name="folder_id" value="<?php echo $file['folder_id']; ?>">

    <!-- FILE NAME -->
    <label>File Name</label>
    <input type="text" name="file_name"
        class="w-full border p-2 rounded mt-2"
        value="<?php echo htmlentities($filename_no_ext); ?>" required>

    <span class="text-gray-500 text-sm">.<?php echo $extension; ?></span>

    <br><br>

    <!-- DEPARTMENTS -->
    <label>Assign Departments</label>

    <?php
    // ALL DEPARTMENTS
    $departments = mysqli_query($conn,"
        SELECT * FROM departments WHERE department_status='Active'
    ");

    // CURRENT ASSIGNED
    $assigned = mysqli_query($conn,"
        SELECT department_id FROM file_departments WHERE file_id='$id'
    ");

    $assigned_dept = [];
    while($ad = mysqli_fetch_array($assigned)){
        $assigned_dept[] = $ad['department_id'];
    }

    // DISPLAY CHECKBOXES
    while($d = mysqli_fetch_array($departments)){
    ?>
        <div class="mt-2">
            <input type="checkbox" name="departments[]"
                value="<?php echo $d['department_id']; ?>"
                <?php echo in_array($d['department_id'], $assigned_dept) ? 'checked' : ''; ?>>

            <?php echo htmlentities($d['department_name']); ?>
        </div>
    <?php } ?>

    <!-- SAVE -->
    <div class="mt-4 text-right">
        <button name="edit_file"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Save Changes
        </button>
    </div>
</form>