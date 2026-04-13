<?php
require_once("../include/connection.php");

$folder_id = intval($_GET['folder_id']);

// Fetch archived letters
$query = mysqli_query($conn, "
SELECT l.*, al.name AS uploader_name
FROM letters l
LEFT JOIN admin_login al ON l.email = al.id
WHERE l.folder_id='$folder_id' AND l.status='Archived'
ORDER BY l.id DESC
");
?>

<div class="flex justify-between items-center p-3">
  <label class="flex items-center gap-2 text-sm">
    <input type="checkbox" id="selectAllLetters" onclick="toggleSelectAllLetters(this)">
    Select All
  </label>

  <button onclick="confirmBulkRestoreLetters()" 
    class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-sm">
    Restore Selected
  </button>
</div>

<table class="min-w-full table-auto">
<thead class="bg-gray-200 text-black uppercase text-xs sticky top-0">
<tr>
<th class="px-4 py-2 text-center">✔</th>
<th class="px-4 py-2 text-left">Reference No.</th>
<th class="px-4 py-2 text-left">Subject</th>
<th class="px-4 py-2 text-left">Sender</th>
<th class="px-4 py-2 text-left">Uploader</th>
<th class="px-4 py-2 text-left">Date Received</th>
<th class="px-4 py-2 text-center">Action</th>
</tr>
</thead>

<tbody class="text-gray-700">
<?php while($l = mysqli_fetch_array($query)){ 
$lid = $l['id'];
$letterFile = "../letters/".$l['file_path']; // assuming letters are in this folder
?>

<tr class="border-b hover:bg-gray-50 transition">
<td class="px-4 py-2 text-center"><input type="checkbox" class="letterCheckbox" value="<?php echo $lid; ?>"></td>
<td class="px-4 py-2"><?php echo htmlentities($l['reference_no']); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($l['subject']); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($l['sender']); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($l['uploader_name']); ?></td>
<td class="px-4 py-2"><?php echo htmlentities($l['date_received']); ?></td>

<td class="px-4 py-2 text-center">
<div class="flex justify-center gap-2">

<!-- VIEW LETTER -->
<button onclick="openPreview('<?php echo $letterFile; ?>')"
class="text-indigo-500 hover:text-indigo-700 text-sm">
<i class="fas fa-eye"></i>
</button>

<!-- UNARCHIVE LETTER -->
<button onclick="confirmUnarchiveLetter(<?php echo $lid; ?>)"
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
// Restore single letter
function confirmUnarchiveLetter(id){
    Swal.fire({
        title: 'Restore Letter?',
        html: '<p style="font-size:0.9rem;margin:0;">This letter will be restored.</p>',
        showCancelButton: true,
        confirmButtonText: 'Restore',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if(result.isConfirmed){
            window.location = "unarchive_letter.php?letter_id=" + id;
        }
    });
}

// Toggle all letters
function toggleSelectAllLetters(source) {
    document.querySelectorAll('.letterCheckbox').forEach(cb => {
        cb.checked = source.checked;
    });
}

// Bulk restore letters
function confirmBulkRestoreLetters() {
    let selected = [];
    document.querySelectorAll('.letterCheckbox:checked').forEach(cb => selected.push(cb.value));

    if (selected.length === 0) {
        Swal.fire('No letters selected');
        return;
    }

    Swal.fire({
        title: 'Restore selected letters?',
        text: selected.length + ' letter(s) will be restored.',
        showCancelButton: true,
        confirmButtonText: 'Restore',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280'
    }).then(result => {
        if(result.isConfirmed){
            // Send via GET to bulk unarchive script
            window.location = "bulk_unarchive_letters.php?ids=" + selected.join(',');
        }
    });
}
</script>