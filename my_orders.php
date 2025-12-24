<?php
include 'includes/header.php';

// 1. Check if logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// 2. Load all orders
$file_path = 'data/orders.json';
$my_orders = [];

if (file_exists($file_path)) {
    $all_orders = json_decode(file_get_contents($file_path), true);
    
    // Filter: Only keep orders that belong to THIS logged-in user
    if (!empty($all_orders)) {
        foreach($all_orders as $order) {
            // Check if 'student' matches the session user
            if ($order['student'] == $_SESSION['user']) {
                $my_orders[] = $order;
            }
        }
        // Show newest first
        $my_orders = array_reverse($my_orders);
    }
}
?>

<div class="container">
    <h2>My Order History</h2>
    <hr style="margin: 10px 0 30px 0;">

    <?php if (empty($my_orders)): ?>
        <p style="text-align: center; color: #666;">You haven't placed any orders yet.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="menu.php" class="btn">Order Food</a>
        </div>
    <?php else: ?>
        
        <div style="display: grid; gap: 20px;">
            <?php foreach($my_orders as $order): ?>
                <div style="border: 1px solid #ddd; padding: 20px; border-radius: 10px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h3 style="margin: 0; color: #333;">Token #<?php echo $order['id']; ?></h3>
                        
                        <?php 
                            $status_color = "#ff9900"; // Pending (Orange)
                            if($order['status'] == 'Cooking') $status_color = "#17a2b8"; // Blue
                            if($order['status'] == 'Completed') $status_color = "#28a745"; // Green
                            if($order['status'] == 'Cancelled') $status_color = "#dc3545"; // Red
                        ?>
                        <span style="background: <?php echo $status_color; ?>; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                            <?php echo $order['status']; ?>
                        </span>
                    </div>
                    
                    <p><strong>Items:</strong> <?php echo $order['items']; ?></p>
                    <p><strong>Total:</strong> ₹<?php echo $order['total']; ?></p>
                    <p style="color: #888; font-size: 0.85rem; margin-top: 5px;">Ordered at: <?php echo isset($order['time']) ? $order['time'] : 'N/A'; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>