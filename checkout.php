<?php
include 'includes/header.php';
include 'data/mock_data.php';

// 1. SECURITY: If cart is empty, kick them back to menu
// TIP: We check this to prevent "Ghost Orders". 
// If a student types 'checkout.php' directly in the URL bar without buying anything, 
// this code forces them back to the menu.
if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href='menu.php';</script>";
    exit();
}

// 2. PROCESS ORDER (Only run this once)
$order_id = rand(1000, 9999); // Generate random Order ID for the token

// Calculate Order Details
$order_items = [];
$total_price = 0;
// We use array_count_values to convert raw IDs [1, 1, 2] into quantities [1=>2, 2=>1]
$cart_counts = array_count_values($_SESSION['cart']);

foreach ($cart_counts as $id => $quantity) {
    if (isset($menu_items[$id])) {
        $item_name = $menu_items[$id]['name'];
        $subtotal = $menu_items[$id]['price'] * $quantity;
        
        // Add to list (e.g., "Veg Burger x2")
        // We are building a readable string here to save into the JSON file later.
        $order_items[] = "$item_name (x$quantity)";
        $total_price += $subtotal;
    }
}

// Convert items array to a string (e.g., "Burger (x2), Coffee (x1)")
$items_string = implode(", ", $order_items);
$student_name = isset($_SESSION['user']) ? $_SESSION['user'] : "Guest Student";

// 3. SAVE TO JSON FILE
// TIP: This section replaces the SQL "INSERT" query. 
$file_path = 'data/orders.json';

// Get existing data
// Logic: We must READ the old orders first. If we don't, 
// saving this new order would overwrite/delete everyone else's history.
$current_data = file_exists($file_path) ? file_get_contents($file_path) : "[]";

// Decode: Convert the JSON text string into a PHP Array so we can add to it.
$array_data = json_decode($current_data, true);

// --- TIMEZONE FIX START ---
// Logic: Servers usually run on UTC time. We force it to use Indian Standard Time (IST)
// so the dashboard shows the correct order time.
date_default_timezone_set('Asia/Kolkata'); 
$current_time = date("h:i A, d M Y"); // e.g., "02:30 PM, 24 Dec 2025"
// --- TIMEZONE FIX END ---

// Create new order array
// This is the data structure for a single order.
$new_order = [
    "id" => $order_id,
    "student" => $student_name,
    "items" => $items_string,
    "total" => $total_price,
    "status" => "Pending", // Default status is always Pending
    "time" => $current_time
];

// Add new order to the list
// We append the new order to the existing array of orders.
$array_data[] = $new_order;

// Save back to file
// Logic: Now we convert the PHP Array back into a JSON String and save it.
// JSON_PRETTY_PRINT makes the text file human-readable.
file_put_contents($file_path, json_encode($array_data, JSON_PRETTY_PRINT));

// 4. CLEAR CART
// Logic: The order is placed, so we must empty the session cart 
// so the student can start a fresh order next time.
unset($_SESSION['cart']);

?>

<div class="container" style="text-align: center; padding: 50px;">
    
    <div style="background: #d4edda; color: #155724; display: inline-block; padding: 20px 40px; border-radius: 10px; border: 1px solid #c3e6cb;">
        <h1 style="margin: 0;">🎉 Order Placed!</h1>
        <p>Your order has been sent to the Canteen Manager.</p>
    </div>

    <div style="margin-top: 30px; border: 2px dashed #ccc; display: inline-block; padding: 20px; border-radius: 10px;">
        <h3>Your Token Number:</h3>
        <h1 style="font-size: 4rem; color: #007bff; margin: 10px 0;">#<?php echo $order_id; ?></h1>
        <p>Please show this token at the counter.</p>
        <p style="color: #666; margin-top: 10px;">Time: <?php echo $current_time; ?></p>
    </div>

    <br><br>
    <a href="menu.php" class="btn">Order More</a>
    <?php if(file_exists('my_orders.php')): ?>
        <a href="my_orders.php" class="btn" style="background: #17a2b8;">View My Orders</a>
    <?php endif; ?>
    <a href="logout.php" class="btn" style="background: #dc3545;">Logout</a>

</div>

<?php include 'includes/footer.php'; ?>