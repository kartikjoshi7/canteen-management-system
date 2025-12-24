<?php
session_start();

// 1. SECURITY CHECK
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$file_path = '../data/orders.json';

// 2. HANDLE STATUS UPDATE (When Admin clicks "Update")
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    if (file_exists($file_path)) {
        $json_data = file_get_contents($file_path);
        $orders = json_decode($json_data, true);

        // Find the specific order and update it
        // We use &$order to modify the original array directly
        foreach ($orders as &$order) {
            if ($order['id'] == $order_id) {
                $order['status'] = $new_status;
                break; // Stop looking once found
            }
        }
        
        // Save back to JSON file
        file_put_contents($file_path, json_encode($orders, JSON_PRETTY_PRINT));
        
        // Refresh page to see changes
        header("Location: dashboard.php");
        exit();
    }
}

// 3. READ ORDERS
$orders = [];
if (file_exists($file_path)) {
    $json_data = file_get_contents($file_path);
    $orders = json_decode($json_data, true);
    if (!empty($orders)) {
        $orders = array_reverse($orders); // Show newest first
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | GTU Canteen</title>
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
        
        /* Dropdown & Button Styling */
        select { padding: 5px; border-radius: 4px; border: 1px solid #ccc; }
        .btn-update { background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
        .btn-update:hover { background: #0056b3; }
    </style>
</head>
<body>

<header>
    <nav>
        <h1>GTU Admin Panel</h1>
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
            <a href="orders.php">📦 All Orders</a>
            <a href="food_items.php">🍔 Manage Food Items</a>
            <a href="users.php">👥 Manage Users</a>
        </div>

        <div class="main-content">
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p style="font-size: 2rem; color: #28a745;"><?php echo count($orders); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Pending</h3>
                    <p style="font-size: 2rem; color: #ff9900;">
                        <?php 
                        // Count how many are 'Pending'
                        $pending_count = 0;
                        foreach($orders as $o) { if($o['status'] == 'Pending') $pending_count++; }
                        echo $pending_count;
                        ?>
                    </p>
                </div>
                <div class="stat-card">
                    <h3>Revenue</h3>
                    <p style="font-size: 2rem; color: #007bff;">
                        <?php 
                        // Sum up the total money
                        $revenue = 0;
                        foreach($orders as $o) { $revenue += $o['total']; }
                        echo "₹" . $revenue;
                        ?>
                    </p>
                </div>
            </div>

            <h3>Recent Incoming Orders</h3>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="4" style="text-align: center;">No orders yet.</td></tr>
                    <?php else: ?>
                        
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <strong>#<?php echo $order['id']; ?></strong><br>
                                <small><?php echo $order['student']; ?></small>
                            </td>
                            <td><?php echo $order['items']; ?></td>
                            <td>₹<?php echo $order['total']; ?></td>
                            
                            <td>
                                <form method="POST" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    
                                    <select name="new_status">
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

<footer style="text-align: center; margin-top: 50px; padding: 20px; background: #333; color: white;">
    <p>&copy; 2025 Canteen Admin System</p>
</footer>

</body>
</html>