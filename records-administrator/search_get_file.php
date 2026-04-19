<?php
include("../include/connection.php");

$id = intval($_GET['id']);

$query = mysqli_query($conn, "
SELECT 
    uf.id,
    uf.name,
    GROUP_CONCAT(DISTINCT d.department_id) AS department_ids,
    GROUP_CONCAT(DISTINCT d.department_name) AS departments
FROM upload_files uf
LEFT JOIN file_departments fd ON uf.id = fd.file_id
LEFT JOIN departments d ON fd.department_id = d.department_id
WHERE uf.id = $id
GROUP BY uf.id
");

$row = mysqli_fetch_assoc($query);

// SAFE RESPONSE (prevents JS crash)
if (!$row) {
    echo json_encode([
        "id" => null,
        "name" => "",
        "department_ids" => "",
        "departments" => ""
    ]);
    exit;
}

echo json_encode($row);
?>