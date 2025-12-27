<?php
/**
 * Helper Functions for Canteen System
 * TIP: Created this file to follow the "DRY" Principle (Don't Repeat Yourself).
 * These functions are used on the Menu, Cart, and Header to calculate totals.
 */

// Function to get the number of items in the cart
// Usage: Used in the Navbar to show "Cart (3)"
function get_cart_count() {
    // Check if the session variable 'cart' exists
    if (isset($_SESSION['cart'])) {
        // count() returns the total number of elements in the array.
        // e.g., if cart is [1, 1, 2], it returns 3.
        return count($_SESSION['cart']);
    } else {
        return 0; // Cart is empty
    }
}

// Function to get total price
// Usage: Helps verify totals if needed
function get_cart_total($menu_items) {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        // Loop through every Item ID stored in the session
        foreach ($_SESSION['cart'] as $id) {
            // Security Check: Ensure the ID actually exists in our menu database
            if (isset($menu_items[$id])) {
                // Add the price of that item to the running total
                $total += $menu_items[$id]['price'];
            }
        }
    }
    return $total;
}

// Function to get the count of a SPECIFIC item (e.g., How many Burgers?)
// TIP: This is the logic behind the "+ / -" buttons on the Menu page.
function get_item_count($item_id) {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        
        // array_count_values() is a native PHP function.
        // It converts a list like [1, 1, 2] into an associative array: [1 => 2, 2 => 1].
        // This tells us: "ID 1 appears 2 times".
        $counts = array_count_values($_SESSION['cart']);
        
        // Return the count for the specific item we asked for, or 0 if not found.
        return isset($counts[$item_id]) ? $counts[$item_id] : 0;
    }
    return 0;
}
?>