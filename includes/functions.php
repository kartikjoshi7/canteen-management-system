<?php
/**
 * Helper Functions for GTU Canteen System
 */

// Function to get the number of items in the cart
function get_cart_count() {
    if (isset($_SESSION['cart'])) {
        return count($_SESSION['cart']);
    } else {
        return 0; // Cart is empty
    }
}

// Function to get total price (Optional, but useful)
function get_cart_total($menu_items) {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id) {
            if (isset($menu_items[$id])) {
                $total += $menu_items[$id]['price'];
            }
        }
    }
    return $total;
}

// Function to get the count of a SPECIFIC item (e.g., How many Burgers?)
function get_item_count($item_id) {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $counts = array_count_values($_SESSION['cart']);
        return isset($counts[$item_id]) ? $counts[$item_id] : 0;
    }
    return 0;
}
?>