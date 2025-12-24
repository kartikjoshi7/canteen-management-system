<?php
include 'includes/header.php';
include 'data/mock_data.php';

// --- LOGIC 1: HANDLE ADD / INCREASE / DECREASE ---
// We use $_REQUEST so it works for both links (GET) and forms (POST)
if (isset($_REQUEST['action']) && isset($_REQUEST['id'])) {
    $action = $_REQUEST['action'];
    $id = $_REQUEST['id'];

    // CASE A: Add or Increase Item (Same logic)
    if ($action == 'add' || $action == 'increase') {
        $_SESSION['cart'][] = $id;
    }

    // CASE B: Decrease Item
    if ($action == 'decrease') {
        // Find one instance of this ID and remove it
        $key = array_search($id, $_SESSION['cart']);
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            // Re-index array so there are no empty gaps
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }

    // --- SMART REDIRECT LOGIC ---
    // Check if the user clicked this from the "Menu" page
    if (isset($_REQUEST['from']) && $_REQUEST['from'] == 'menu') {
        // Send them back to the Menu (and jump to the specific item)
        echo "<script>window.location.href='menu.php#item-$id';</script>";
    } else {
        // Otherwise, refresh the Cart page
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
            
            // 1. Count how many times each ID appears (e.g., ID 1 => 2 times)
            $cart_counts = array_count_values($_SESSION['cart']);

            // 2. Loop through the UNIQUE items
            foreach ($cart_counts as $id => $quantity) {
                if (isset($menu_items[$id])) {
                    $item = $menu_items[$id];
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