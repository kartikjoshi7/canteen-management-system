<?php
// 1. SESSION MANAGEMENT
// Que: "Why do we check session_status()?"
// Ans: "If a session is already running (e.g., from a previous page redirect), 
// starting it again causes an error. This check ensures we only start it if one doesn't exist."
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. INCLUDE HELPER FUNCTIONS
// TIP: We include functions.php here so that EVERY page on the website 
// automatically gets access to the 'get_cart_count()' function without writing extra code.
// '__DIR__' makes sure the path is always correct, even if we include this header from a subfolder.
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
        <h1>SVIT Canteen</h1>
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