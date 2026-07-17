<?php
session_start();
include '../includes/db_connect.php'; // Connect to SQL

// 1. SECURITY CHECK
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. HANDLE STATUS UPDATE (SQL Version)
if (isset($_POST['update_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);

    $update_sql = "UPDATE orders SET status='$new_status' WHERE id='$order_id'";
    mysqli_query($conn, $update_sql);
    
    header("Location: dashboard.php"); // Refresh
    exit();
}

// 3. READ ORDERS FROM SQL
// We fetch all orders, sorted by newest first (DESC)
$orders = [];
$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

// 4. CALCULATE STATS (SQL Version)
$total_orders = mysqli_num_rows($result);
$revenue = 0;
$pending_count = 0;

// Loop once to calculate stats
// We store rows in an array so we can reuse them for the table below
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
    $revenue += $row['total_price'];
    if($row['status'] == 'Pending') {
        $pending_count++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | SVIT Canteen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .dashboard-grid { display: grid; grid-template-columns: 1fr 3fr; gap: 20px; }
        .sidebar { background: #333; color: white; padding: 20px; min-height: 80vh; border-radius: 5px; }
        .sidebar a { display: block; color: #ccc; padding: 10px; margin-bottom: 5px; text-decoration: none; }
        .sidebar a:hover { color: white; background: #555; border-radius: 3px; }
        .stat-card { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
        
        select { padding: 5px; border-radius: 4px; border: 1px solid #ccc; }
        .btn-update { background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<header>
    <nav>
        <h1>SVIT Admin Panel</h1>
        <ul>
            <li><a href="#" style="color: #ff9900;">Hello, Admin</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h2>Dashboard Overview</h2>
    <hr style="margin: 10px 0 30px 0;">

    <div class="dashboard-grid">
        <div class="sidebar">
            <h3>Menu</h3>
            <a href="dashboard.php" style="background: #555; color: white;">📦 All Orders</a>
            <a href="food_items.php">🍔 Manage Food Items</a>
            <a href="users.php">👥 Manage Users</a>
        </div>

        <div class="main-content">
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p style="font-size: 2rem; color: #28a745;"><?php echo $total_orders; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Pending</h3>
                    <p style="font-size: 2rem; color: #ff9900;"><?php echo $pending_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Revenue</h3>
                    <p style="font-size: 2rem; color: #007bff;">₹<?php echo $revenue; ?></p>
                </div>
            </div>

            <h3>Recent Incoming Orders</h3>
            <table>
                <thead>
                    <tr>
                        <th>Token</th>
                        <th>Student</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status / Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="5" style="text-align: center;">No orders yet.</td></tr>
                    <?php else: ?>
                        
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <strong style="font-size: 1.2rem; color: #007bff;">#<?php echo $order['token_number']; ?></strong>
                                <br><small style="color: #999;"><?php echo $order['created_at']; ?></small>
                            </td>
                            <td><?php echo $order['student_name']; ?></td>
                            <td><?php echo $order['items']; ?></td>
                            <td style="font-weight: bold;">₹<?php echo $order['total_price']; ?></td>
                            
                            <td>
                                <form method="POST" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    
                                    <select name="new_status" style="
                                        border-color: <?php 
                                            if($order['status']=='Pending') echo 'orange'; 
                                            elseif($order['status']=='Completed') echo 'green'; 
                                            else echo '#ccc';
                                        ?>;">
                                        <option value="Pending" <?php if($order['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Cooking" <?php if($order['status']=='Cooking') echo 'selected'; ?>>Cooking</option>
                                        <option value="Completed" <?php if($order['status']=='Completed') echo 'selected'; ?>>Completed</option>
                                        <option value="Cancelled" <?php if($order['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>
                                    
                                    <button type="submit" name="update_status" class="btn-update">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

</body>
</html>