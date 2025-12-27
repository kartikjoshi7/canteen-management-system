<?php
session_start();

// 1. SECURITY CHECK
// Que: "Why is this check on every admin page?"
// Ans: "Security is layered. Even if someone guesses the URL 'admin/orders.php', 
// this code checks if they are logged in as 'admin'. If not, it kicks them out."
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. READ REAL ORDERS FROM JSON
$file_path = '../data/orders.json';
$all_orders = [];

// Check if database exists
if (file_exists($file_path)) {
    // Read the raw JSON string
    $json_data = file_get_contents($file_path);
    // Decode into a PHP Array to loop through it
    $all_orders = json_decode($json_data, true);
    
    // UX IMPROVEMENT: Show newest orders first
    // Que: "Why array_reverse?"
    // Ans: "By default, new orders are added to the bottom of the JSON file. 
    // I reverse the array so the Admin sees the latest orders at the top of the table."
    if (!empty($all_orders)) {
        $all_orders = array_reverse($all_orders);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders | SVIT Canteen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 15px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        /* Status Badges */
        /* These classes correspond to the status codes below */
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; }
        .status-pending { background: #ffeeba; color: #856404; }   /* Yellow */
        .status-cooking { background: #b8daff; color: #004085; }   /* Blue */
        .status-completed { background: #c3e6cb; color: #155724; } /* Green */
        .status-cancelled { background: #f5c6cb; color: #721c24; } /* Red */
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
        <h2>📦 Manage All Orders</h2>
        <a href="dashboard.php" class="btn" style="background: #666;">&larr; Back to Dashboard</a>
    </div>
    
    <hr style="margin: 10px 0 30px 0;">

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Student Name</th>
                <th>Ordered Items</th>
                <th>Total Bill</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($all_orders)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No orders found.</td>
                </tr>
            <?php else: ?>
                
                <?php foreach($all_orders as $order): ?>
                <tr>
                    <td><strong>#<?php echo $order['id']; ?></strong></td>
                    <td><?php echo $order['student']; ?></td>
                    <td><?php echo $order['items']; ?></td>
                    <td>₹<?php echo $order['total']; ?></td>
                    <td><?php echo isset($order['time']) ? $order['time'] : '-'; ?></td>
                    <td>
                        <?php 
                            $status_lower = strtolower($order['status']);
                            $status_class = "status-" . $status_lower;
                        ?>
                        <span class="badge <?php echo $status_class; ?>">
                            <?php echo $order['status']; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>

            <?php endif; ?>
        </tbody>
    </table>

</div>

<footer style="text-align: center; margin-top: 50px; padding: 20px; background: #333; color: white;">
    <p>&copy; 2025 Canteen Admin System</p>
</footer>

</body>
</html>