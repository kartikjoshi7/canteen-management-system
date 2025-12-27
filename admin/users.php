<?php
session_start();

include '../data/mock_data.php'; // Get the user data

// 2. SECURITY CHECK
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
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
        <a href="dashboard.php" class="btn" style="background: #666;">&larr; Back</a>
    </div>
    <hr style="margin: 10px 0 30px 0;">

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // 3. DISPLAY LOOP
            // We loop through the hardcoded $users array (Key=Username, Value=Password).
            // We ignore the password here for security (never show passwords!).
            foreach($users as $username => $password): 
            ?>
            <tr>
                <td><strong><?php echo $username; ?></strong></td>
                <td>
                    <?php 
                    // 4. ROLE DISPLAY LOGIC
                    // We check the username to decide what "Badge" to show.
                    // This helps the Admin quickly see who has high-level access.
                    if($username == 'admin'): 
                    ?>
                        <span style="background: #333; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.8rem;">ADMIN</span>
                    <?php else: ?>
                        <span style="background: #007bff; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.8rem;">STUDENT</span>
                    <?php endif; ?>
                </td>
                
                <td style="color: green;">Active</td>
                
                <td>
                    <button class="btn" style="padding: 5px 10px; font-size: 0.8rem; background: #dc3545;">Remove</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<footer style="text-align: center; margin-top: 50px; padding: 20px; background: #333; color: white;">
    <p>&copy; 2025 Canteen Admin System</p>
</footer>

</body>
</html>