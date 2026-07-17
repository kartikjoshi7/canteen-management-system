<?php
session_start();
include '../includes/db_connect.php'; // Connect to SQL

// 1. SECURITY CHECK
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. HANDLE DELETE USER
// This allows the admin to remove a student account permanently.
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // SAFETY CHECK: Prevent Admin from deleting themselves!
    $check_sql = "SELECT role FROM users WHERE id = '$delete_id'";
    $check_result = mysqli_query($conn, $check_sql);
    $user_to_delete = mysqli_fetch_assoc($check_result);

    // Only delete if the user is NOT an admin
    if ($user_to_delete && $user_to_delete['role'] != 'admin') {
        $sql = "DELETE FROM users WHERE id = '$delete_id'";
        mysqli_query($conn, $sql);
    }
    
    header("Location: users.php");
    exit();
}

// 3. FETCH USERS FROM DATABASE
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | SVIT Canteen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 15px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
    </style>
</head>
<body>

<header>
    <nav>
        <h1>SVIT Admin Panel</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>👥 Registered Users</h2>
    <div>
        <a href="add_vendor.php" class="btn" style="background: #28a745; margin-right: 10px;">+ Add Vendor</a>
        <a href="dashboard.php" class="btn" style="background: #666;">&larr; Back</a>
    </div>
</div>
    <hr style="margin: 10px 0 30px 0;">

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // LOOP THROUGH DATABASE RESULTS
            while($row = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
                <td><strong><?php echo $row['username']; ?></strong></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                
                <td>
                    <?php 
                    // ROLE BADGE LOGIC
                    if($row['role'] == 'admin'): 
                    ?>
                        <span style="background: #333; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.8rem;">ADMIN</span>
                    <?php else: ?>
                        <span style="background: #007bff; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.8rem;">STUDENT</span>
                    <?php endif; ?>
                </td>
                
                <td>
                    <?php if($row['role'] != 'admin'): ?>
                        <a href="users.php?action=delete&id=<?php echo $row['id']; ?>" 
                           class="btn" 
                           style="padding: 5px 10px; font-size: 0.8rem; background: #dc3545; text-decoration: none;"
                           onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                           Remove
                        </a>
                    <?php else: ?>
                        <span style="color: #999; font-size: 0.8rem;">(Protected)</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer style="text-align: center; margin-top: 50px; padding: 20px; background: #333; color: white;">
    <p>&copy; 2026 Canteen Admin System</p>
</footer>

</body>
</html>