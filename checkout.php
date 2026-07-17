<?php
include 'includes/header.php';
include 'includes/db_connect.php'; // Connect to SQL

// 1. SECURITY CHECK
if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href='menu.php';</script>";
    exit();
}

// 2. FETCH MENU ITEMS
$menu_items = [];
$sql = "SELECT * FROM food_items";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $menu_items[$row['id']] = $row;
    }
}

// 3. PROCESS ORDER
$order_items = [];
$total_price = 0;
$cart_counts = array_count_values($_SESSION['cart']);

foreach ($cart_counts as $id => $quantity) {
    if (isset($menu_items[$id])) {
        $item_name = $menu_items[$id]['name'];
        $item_price = $menu_items[$id]['price'];
        $subtotal = $item_price * $quantity;
        
        $order_items[] = "$item_name (x$quantity)";
        $total_price += $subtotal;
    }
}

$items_string = implode(", ", $order_items);
$student_name = isset($_SESSION['user']) ? $_SESSION['user'] : "Guest";

// --- RANDOM TOKEN GENERATION ---
$token_number = rand(1000, 9999); // Generates a random 4-digit number

// --- INSERT INTO SQL ---
// We now insert the random 'token_number' into the database
$insert_sql = "INSERT INTO orders (token_number, student_name, items, total_price) 
               VALUES ('$token_number', '$student_name', '$items_string', '$total_price')";

if (mysqli_query($conn, $insert_sql)) {
    // Clear cart on success
    unset($_SESSION['cart']);
} else {
    die("Error placing order: " . mysqli_error($conn));
}

// Timezone for display
date_default_timezone_set('Asia/Kolkata'); 
$current_time = date("h:i A, d M Y"); 
?>

<div class="container" style="text-align: center; padding: 50px;">
    
    <div style="background: #d4edda; color: #155724; display: inline-block; padding: 20px 40px; border-radius: 10px; border: 1px solid #c3e6cb;">
        <h1 style="margin: 0;">🎉 Order Placed!</h1>
        <p>Your order has been sent to the Canteen Manager.</p>
    </div>

    <div style="margin-top: 30px; border: 2px dashed #ccc; display: inline-block; padding: 20px; border-radius: 10px;">
        <h3>Your Token Number:</h3>
        <h1 style="font-size: 4rem; color: #007bff; margin: 10px 0;">#<?php echo $token_number; ?></h1>
        <p>Please show this token at the counter.</p>
        <p style="color: #666; margin-top: 10px;">Time: <?php echo $current_time; ?></p>
    </div>

    <br><br>
    <a href="menu.php" class="btn">Order More</a>
    <a href="logout.php" class="btn" style="background: #dc3545;">Logout</a>

</div>

<?php include 'includes/footer.php'; ?>