<?php
include 'includes/header.php';
include 'includes/db_connect.php'; // Connect to SQL

// 1. SECURITY CHECK
if (!isset($_SESSION['user'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$current_user = $_SESSION['user'];

// 2. FETCH ORDERS FOR CURRENT USER
// We use 'WHERE student_name' so students only see THEIR own orders.
// We order by 'created_at DESC' to show the newest orders at the top.
$sql = "SELECT * FROM orders WHERE student_name = '$current_user' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container" style="max-width: 800px; margin-top: 50px;">
    <h2>🧾 My Order History</h2>
    <p>Track your past and current orders here.</p>
    <hr style="margin: 10px 0 30px 0;">

    <?php if (mysqli_num_rows($result) > 0): ?>
        
        <div style="display: grid; gap: 20px;">
            <?php while($order = mysqli_fetch_assoc($result)): ?>
                
                <div class="order-card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background: #fff; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    
                    <div>
                        <h3 style="margin: 0; color: #007bff;">
                            Token #<?php echo $order['token_number']; ?>
                        </h3>
                        <p style="color: #666; font-size: 0.9rem; margin: 5px 0;">
                            <?php echo date("d M Y, h:i A", strtotime($order['created_at'])); ?>
                        </p>
                        <p style="margin-top: 10px; font-weight: bold; color: #333;">
                            <?php echo $order['items']; ?>
                        </p>
                    </div>

                    <div style="text-align: right;">
                        <h3 style="margin: 0; color: #28a745;">₹<?php echo $order['total_price']; ?></h3>
                        
                        <div style="margin-top: 10px;">
                            <?php if($order['status'] == 'Pending'): ?>
                                <span style="background: #ffc107; color: #333; padding: 5px 10px; border-radius: 20px; font-size: 0.9rem; font-weight: bold;">
                                    ⏳ Pending
                                </span>
                            <?php elseif($order['status'] == 'Cooking'): ?>
                                <span style="background: #17a2b8; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.9rem; font-weight: bold;">
                                    🔥 Cooking
                                </span>
                            <?php elseif($order['status'] == 'Completed'): ?>
                                <span style="background: #28a745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.9rem; font-weight: bold;">
                                    ✅ Ready to Pickup
                                </span>
                            <?php else: ?>
                                <span style="background: #dc3545; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.9rem; font-weight: bold;">
                                    ❌ Cancelled
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            <?php endwhile; ?>
        </div>

    <?php else: ?>
        
        <div style="text-align: center; padding: 50px; background: #f9f9f9; border-radius: 10px;">
            <p style="color: #666; font-size: 1.2rem;">You haven't placed any orders yet.</p>
            <a href="menu.php" class="btn" style="margin-top: 10px;">Order Food Now</a>
        </div>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>