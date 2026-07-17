<?php
session_start();
include '../includes/db_connect.php'; // Connect to SQL

// 1. SECURITY CHECK
// We strictly check if the user is logged in AND is an admin.
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. FETCH ALL ORDERS FROM DATABASE
// We order by 'created_at DESC' so the latest orders appear at the top.
$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
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
                <th>Token</th>
                <th>Order ID</th>
                <th>Student Name</th>
                <th>Ordered Items</th>
                <th>Total Bill</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) == 0): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No orders found in database.</td>
                </tr>
            <?php else: ?>
                
                <?php while($order = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><strong style="color: #007bff; font-size: 1.1rem;">#<?php echo $order['token_number']; ?></strong></td>
                    <td style="color: #666; font-size: 0.8rem;">ID: <?php echo $order['id']; ?></td>
                    <td><strong><?php echo $order['student_name']; ?></strong></td>
                    <td><?php echo $order['items']; ?></td>
                    <td style="font-weight: bold; color: #28a745;">₹<?php echo $order['total_price']; ?></td>
                    <td>
                        <?php echo date("d M Y, h:i A", strtotime($order['created_at'])); ?>
                    </td>
                    <td>
                        <?php 
                            // Determine CSS class based on status text
                            $status_lower = strtolower($order['status']);
                            $status_class = "status-" . $status_lower;
                        ?>
                        <span class="badge <?php echo $status_class; ?>">
                            <?php echo $order['status']; ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>

            <?php endif; ?>
        </tbody>
    </table>

</div>

<footer style="text-align: center; margin-top: 50px; padding: 20px; background: #333; color: white;">
    <p>&copy; 2026 Canteen Admin System</p>
</footer>

</body>
</html>