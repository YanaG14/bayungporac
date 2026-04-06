<?php
session_start();
require_once("../include/connection.php");

$id = intval($_GET['id']);

// GET USER DETAILS
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_array($query);

// GET ALL ACTIVE DEPARTMENTS
$departments = mysqli_query($conn, "SELECT * FROM departments WHERE department_status='Active' ORDER BY department_name");

// GET CURRENTLY ASSIGNED DEPARTMENT (single select for users)
$current_dept_id = $user['department_id'];
?>

<form method="POST" action="edit_user.php" class="space-y-4">
    <input type="hidden" name="user_id" value="<?php echo $id; ?>">

    <!-- NAME -->
    <div>
        <label class="block font-medium text-gray-700">Full Name</label>
        <input type="text" name="name" value="<?php echo htmlentities($user['name']); ?>" 
               class="w-full border p-2 rounded" required>
    </div>

    <!-- EMAIL -->
    <div>
        <label class="block font-medium text-gray-700">Email Address</label>
        <input type="email" name="email_address" value="<?php echo htmlentities($user['email_address']); ?>" 
               class="w-full border p-2 rounded" required>
    </div>

    <!-- STATUS -->
    <div>
        <label class="block font-medium text-gray-700">Status</label>
        <select name="user_status" class="w-full border p-2 rounded" required>
            <option value="Employee" <?php echo $user['user_status']=='Employee' ? 'selected' : ''; ?>>Employee</option>
            <option value="Archived" <?php echo $user['user_status']=='Archived' ? 'selected' : ''; ?>>Archived</option>
        </select>
    </div>

    <!-- DEPARTMENTS -->
    <div>
        <label class="block font-medium text-gray-700">Department</label>
        <select name="department_id" class="w-full border p-2 rounded" required>
            <option value="">-- Select Department --</option>
            <?php while($d = mysqli_fetch_array($departments)): ?>
                <option value="<?php echo $d['department_id']; ?>" 
                    <?php echo $d['department_id'] == $current_dept_id ? 'selected' : ''; ?>>
                    <?php echo htmlentities($d['department_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- SAVE BUTTON -->
    <div class="text-right mt-4">
        <button type="submit" name="edit_user" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Save Changes
        </button>
    </div>
</form>