<?php
require_once("../include/connection.php");

$folder_id = intval($_GET['folder_id']);

$query = mysqli_query($conn, "
SELECT uf.*, al.name AS uploader_name
FROM upload_files uf
LEFT JOIN admin_login al ON uf.email = al.id
WHERE uf.folder_id='$folder_id' AND uf.status='Archived'
ORDER BY uf.id DESC
");
?>


<div class="flex justify-between items-center p-3">

  <label class="flex items-center gap-2 text-sm">
    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
    Select All
  </label>

  <button onclick="confirmBulkRestore()" 
    class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-sm">
    Restore Selected
  </button>

</div>
<table class="min-w-full table-auto">
<thead class="bg-gray-200 text-black uppercase text-xs sticky top-0">
<tr>
<th class="px-4 py-2 text-center">✔</th>
<th class="px-4 py-2 text-left">Filename</th>
<th class="px-4 py-2 text-left">Size</th>
<th class="px-4 py-2 text-left">Uploader</th>
<th class="px-4 py-2 text-left">Role</th>
<th class="px-4 py-2 text-left">Date Uploaded</th>
<th class="px-4 py-2 text-center">Action</th>
</tr>
</thead>

<tbody class="text-gray-700">
<?php while($f = mysqli_fetch_array($query)){ 
$fid = $f['id'];
$fpath = "../uploads/".$f['file_path'];
?>

<tr class="border-b hover:bg-gray-50 transition">
<td class="px-4 py-2 text-center"><input type="checkbox" class="fileCheckbox" value="<?php echo $fid; ?>"></td>
<td class="px-4 py-2"><?php echo htmlentities($f['name']); ?></td>
<td class="px-4 py-2"><?php echo floor($f['size']/1000).' KB'; ?></td>
<td class="px-4 py-2"><?php echo htmlentities($f['uploader_name']); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($f['admin_status']); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($f['timers']); ?></td>

<td class="px-4 py-2 text-center">
<div class="flex justify-center gap-2">

<!-- VIEW -->
<button onclick="openPreview('<?php echo $fpath; ?>')"
class="text-indigo-500 hover:text-indigo-700 text-sm">
<i class="fas fa-eye"></i>
</button>

<!-- UNARCHIVE -->
<button onclick="confirmUnarchive(<?php echo $fid; ?>)"
class="text-green-500 hover:text-green-700 text-sm">
<i class="fas fa-undo"></i>
</button>

</div>
</td>

</tr>

<?php } ?>
</tbody>
</table>

<script>
function confirmUnarchive(id){
    Swal.fire({
        title: 'Restore File?',
        html: '<p style="font-size:0.9rem;margin:0;">This file will be restored.</p>',
        showCancelButton: true,
        confirmButtonText: 'Restore',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if(result.isConfirmed){
            window.location = "unarchive_file.php?file_id=" + id;
        }
    });
}
</script>

