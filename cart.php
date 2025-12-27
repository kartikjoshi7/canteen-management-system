<?php
include 'includes/header.php';
include 'data/mock_data.php';

// --- LOGIC 1: HANDLE ADD / INCREASE / DECREASE ---
// TIP: We use $_REQUEST instead of $_GET or $_POST because it captures both.
// This allows us to use links (GET) and forms (POST) to trigger actions.
if (isset($_REQUEST['action']) && isset($_REQUEST['id'])) {
    
    $action = $_REQUEST['action'];
    $id = $_REQUEST['id'];

    // CASE A: Add or Increase Item (Same logic)
    // Logic: We simply push the Item ID into the $_SESSION['cart'] array.
    // If the array was [1, 2], and we add 1, it becomes [1, 2, 1].
    if ($action == 'add' || $action == 'increase') {
        $_SESSION['cart'][] = $id;
    }

    // CASE B: Decrease Item
    if ($action == 'decrease') {
        // Logic: We need to find just ONE instance of this ID and remove it.
        // array_search() finds the first index key (e.g., index 0) where this ID exists.
        $key = array_search($id, $_SESSION['cart']);
        
        if ($key !== false) {
            // unset() deletes that specific array slot.
            unset($_SESSION['cart'][$key]);
            
            // CRITICAL STEP: When we delete an item, it leaves a 'gap' in the array keys (0, 2, 3...).
            // array_values() re-indexes the array cleanly (0, 1, 2...).
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }

    // --- SMART REDIRECT LOGIC ---
    // If the user clicked '+' on the Menu page, we want to stay on the Menu page.
    // If they clicked '+' on the Cart page, we want to refresh the Cart page.
    if (isset($_REQUEST['from']) && $_REQUEST['from'] == 'menu') {
        // Jump directly to the item so the user doesn't have to scroll down again.
        echo "<script>window.location.href='menu.php#item-$id';</script>";
    } else {
        // Otherwise, refresh the Cart page to show updated totals.
        echo "<script>window.location.href='cart.php';</script>";
    }
    exit(); // Always exit after a header redirect
}

// --- LOGIC 2: HANDLE CLEAR CART ---
if (isset($_GET['action']) && $_GET['action'] == 'clear') {
    // Simply destroy the specific session variable for the cart.
    unset($_SESSION['cart']); 
    echo "<script>window.location.href='cart.php';</script>";
    exit();
}
?>

<div class="cart-container" style="max-width: 800px; margin: auto;">
    <h2>Your Food Cart</h2>
    <hr style="margin-bottom: 20px;">

    <?php if (empty($_SESSION['cart'])): ?>
        
        <p style="text-align: center; font-size: 1.2rem; color: #666;">Your cart is empty.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="menu.php" class="btn">Go to Menu</a>
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
            
            // 1. COUNT DUPLICATES
            // The session array looks like [1, 1, 2, 1].
            // array_count_values() converts it to: [1 => 3, 2 => 1].
            // This gives us the Item ID and its Quantity automatically.
            $cart_counts = array_count_values($_SESSION['cart']);

            // 2. DISPLAY TABLE ROWS
            foreach ($cart_counts as $id => $quantity) {
                
                // Ensure the item still exists in our mock database
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