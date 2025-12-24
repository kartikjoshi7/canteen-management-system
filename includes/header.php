<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// INCLUDE THE FUNCTIONS FILE
// __DIR__ ensures it looks in the 'includes' folder, no matter which page loads this header.
include __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <nav>
        <h1>GTU Canteen</h1>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="my_orders.php">My Orders</a></li>
            
            <li>
                <a href="cart.php">
                    Cart (<?php echo get_cart_count(); ?>)
                </a>
            </li>
            
            <?php if(isset($_SESSION['user'])): ?>
                <li><a href="logout.php" style="color: #ff9900;">Logout (<?php echo $_SESSION['user']; ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="container">