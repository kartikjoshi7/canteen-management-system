<?php
include 'includes/header.php';
include 'includes/db_connect.php'; // Connect to SQL to get prices

// 1. FETCH FOOD DETAILS FROM DATABASE
// We need to know that ID 1 is "Burger" and costs 50.
// We pull all items from the database and create our own "$menu_items" array.
$menu_items = [];
$sql = "SELECT * FROM food_items";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // We set the Array Key to be the ID (e.g., $menu_items[1] = ...data...)
        // This makes the rest of the code work exactly like before!
        $menu_items[$row['id']] = $row;
    }
}

// --- LOGIC 1: HANDLE ADD / INCREASE / DECREASE ---
if (isset($_REQUEST['action']) && isset($_REQUEST['id'])) {
    
    $action = $_REQUEST['action'];
    $id = $_REQUEST['id'];

    // CASE A: Add or Increase Item
    if ($action == 'add' || $action == 'increase') {
        $_SESSION['cart'][] = $id;
    }

    // CASE B: Decrease Item
    if ($action == 'decrease') {
        // Find one instance of this ID
        $key = array_search($id, $_SESSION['cart']);
        
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            // Re-index array so we don't have gaps
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }

    // SMART REDIRECT
    if (isset($_REQUEST['from']) && $_REQUEST['from'] == 'menu') {
        echo "<script>window.location.href='menu.php#item-$id';</script>";
    } else {
        echo "<script>window.location.href='cart.php';</script>";
    }
    exit();
}

// --- LOGIC 2: HANDLE CLEAR CART ---
if (isset($_GET['action']) && $_GET['action'] == 'clear') {
    unset($_SESSION['cart']); 
    echo "<script>window.location.href='cart.php';</script>";
    exit();
}
?>

<div class="cart-container" style="max-width: 800px; margin: auto;">
    <h2>Your Food Cart</h2>
    <hr style="margin-bottom: 20px;">

    <?php if (empty($_SESSION['cart'])): ?>
        
        <div style="text-align: center; padding: 50px;">
            <p style="font-size: 1.2rem; color: #666;">Your cart is empty.</p>
            <div style="margin-top: 20px;">
                <a href="menu.php" class="btn">Go to Menu</a>
            </div>
        </div>

    <?php else: ?>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr style="background: #eee; text-align: left;">
                <th style="padding: 10px;">Item Name</th>
                <th style="padding: 10px; text-align: center;">Quantity</th>
                <th style="padding: 10px;">Subtotal</th>
            </tr>

            <?php 
            $total_price = 0;
            
            // 1. COUNT DUPLICATES (e.g., [1 => 2, 2 => 1])
            $cart_counts = array_count_values($_SESSION['cart']);

            // 2. DISPLAY TABLE ROWS
            foreach ($cart_counts as $id => $quantity) {
                
                // Ensure the item exists in our Database array
                if (isset($menu_items[$id])) {
                    $item = $menu_items[$id];
                    
                    // Math: Price x Quantity
                    $subtotal = $item['price'] * $quantity;
                    $total_price += $subtotal;
            ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <strong><?php echo $item['name']; ?></strong><br>
                            <span style="font-size: 0.85rem; color: #666;">@ ₹<?php echo $item['price']; ?> each</span>
                        </td>
                        
                        <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">
                            <a href="cart.php?action=decrease&id=<?php echo $id; ?>" 
                               style="text-decoration: none; background: #dc3545; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold;">-</a>
                            
                            <span style="margin: 0 10px; font-weight: bold;"><?php echo $quantity; ?></span>
                            
                            <a href="cart.php?action=increase&id=<?php echo $id; ?>" 
                               style="text-decoration: none; background: #28a745; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold;">+</a>
                        </td>

                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">₹<?php echo $subtotal; ?></td>
                    </tr>
            <?php 
                }
            }
            ?>
            
            <tr style="font-weight: bold; font-size: 1.2rem; background: #f9f9f9;">
                <td colspan="2" style="padding: 10px; text-align: right;">Total Bill:</td>
                <td style="padding: 10px;">₹<?php echo $total_price; ?></td>
            </tr>
        </table>

        <div style="text-align: right;">
            <a href="cart.php?action=clear" style="color: red; margin-right: 20px; text-decoration: none;">Clear Cart</a>
            <a href="checkout.php" class="btn" style="background: #28a745;">Proceed to Checkout</a>
        </div>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>