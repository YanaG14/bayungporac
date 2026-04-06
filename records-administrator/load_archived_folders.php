<?php
require_once("../include/connection.php");
?>

<div class="w-full px-4 py-6">

  <!-- Card Container -->
  <div class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl p-6 border border-gray-200">

    <!-- Header 
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Archived Folders</h2>
      <span class="text-sm text-gray-500">Manage and restore archived records</span>
    </div> -->

    <!-- Table Wrapper -->
    <div class="overflow-x-auto">

      <table class="w-full text-sm text-left text-gray-600">

        <!-- Table Head -->
        <thead class="text-xs uppercase bg-gray-100 text-gray-700">
          <tr>
            <th class="px-6 py-3 rounded-l-xl">Folder Name</th>
            <th class="px-6 py-3">Departments</th>
            <th class="px-6 py-3">Date Created</th>
            <th class="px-6 py-3 rounded-r-xl text-center">Action</th>
          </tr>
        </thead>

        <!-- Table Body -->
        <tbody class="divide-y">

        <?php
        $query = mysqli_query($conn,"
        SELECT 
        f.folder_id,
        f.folder_name,
        f.created_at,
        GROUP_CONCAT(d.department_name SEPARATOR ', ') as departments
        FROM folders f
        LEFT JOIN folder_departments fd ON f.folder_id = fd.folder_id
        LEFT JOIN departments d ON fd.department_id = d.department_id
        WHERE f.folder_status='Archived'
        GROUP BY f.folder_id
        ORDER BY f.folder_name ASC
        ");

        while($row=mysqli_fetch_array($query)){
        ?>

          <tr class="hover:bg-gray-50 transition">

            <!-- Folder Name -->
            <td class="px-6 py-4 font-medium text-gray-900">
              <?php echo htmlentities($row['folder_name']); ?>
            </td>

            <!-- Departments -->
            <td class="px-6 py-4">
              <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
                <?php echo $row['departments'] ? htmlentities($row['departments']) : 'No Department'; ?>
              </span>
            </td>

            <!-- Date -->
            <td class="px-6 py-4 text-gray-500">
              <?php echo date("F d, Y", strtotime($row['created_at'])); ?>
            </td>

            <!-- Action -->
            <td class="px-6 py-4 text-center">

              <a href="unarchive_folder.php?id=<?php echo $row['folder_id']; ?>"
              onclick="confirmRestore(event, this)"
                 class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg shadow hover:bg-green-700 hover:scale-105 transition-all duration-200">

                <!-- Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-4 w-4" 
                     fill="none" 
                     viewBox="0 0 24 24" 
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582M20 20v-5h-.581M5.05 9A7 7 0 0119 12a7 7 0 01-13.95 3" />
                </svg>

                Unarchive
              </a>

            </td>

          </tr>

        <?php } ?>

        </tbody>
      </table>

    </div>

    <!-- Empty State -->
    <?php if(mysqli_num_rows($query) == 0){ ?>
      <div class="text-center py-10 text-gray-500">
        No archived folders found.
      </div>
    <?php } ?>

  </div>

</div>

<script>
function confirmRestore(e, el){
    e.preventDefault(); // stop default action

    Swal.fire({
        title: 'Restore this folder?',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, restore',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // continue original action
            window.location.href = el.href;
        }
    });
}
</script>