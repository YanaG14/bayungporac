<?php
session_start();
require_once("../include/connection.php");

$folder_id = intval($_GET['folder_id']);
$user_department = $_SESSION['department_id'];

$stmt = $conn->prepare("
    SELECT uf.id, uf.name, uf.file_path, uf.timers, al.name AS uploader_name
    FROM upload_files uf
    LEFT JOIN admin_login al ON uf.email = al.id
    WHERE uf.folder_id = ? AND uf.status='Active'
    ORDER BY uf.id DESC
");
$stmt->bind_param("i", $folder_id);
$stmt->execute();
$files = $stmt->get_result();
?>

<div class="space-y-2">

<?php while($file = $files->fetch_assoc()): ?>
  <div class="flex justify-between items-center border p-3 rounded-lg">

    <div>
      <p class="font-medium"><?php echo htmlentities($file['name']); ?></p>
      <p class="text-xs text-gray-500"><?php echo $file['uploader_name']; ?></p>
    </div>

    <div class="flex gap-3">
      <a href="downloads.php?file_id=<?php echo $file['id']; ?>" class="text-blue-600">
        <i class="fas fa-download"></i>
      </a>

      <a href="uploads/<?php echo $file['file_path']; ?>" target="_blank" class="text-green-600">
        <i class="fas fa-eye"></i>
      </a>
    </div>

  </div>
<?php endwhile; ?>

</div>