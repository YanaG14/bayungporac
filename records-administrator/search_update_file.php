<?php
include("../include/connection.php");

$id = intval($_POST['file_id']);
$name = $_POST['file_name'];
$departments = $_POST['departments']; // should be array

// 1. Update file name
mysqli_query($conn, "
    UPDATE upload_files 
    SET name = '$name'
    WHERE id = $id
");

// 2. Remove old department links
mysqli_query($conn, "
    DELETE FROM file_departments 
    WHERE file_id = $id
");

// 3. Insert new departments
if (!empty($departments)) {
    foreach ($departments as $dept_id) {
        $dept_id = intval($dept_id);
        mysqli_query($conn, "
            INSERT INTO file_departments (file_id, department_id)
            VALUES ($id, $dept_id)
        ");
    }
}

echo "<script>
alert('File updated successfully');
window.location.href = 'folder_management.php';
</script>";
?>