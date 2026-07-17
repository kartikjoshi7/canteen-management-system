<?php
// 1. START SESSION SAFELY
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. AUTO-LOGIN CHECK (Remember Me Logic)
// If the user is NOT logged in via Session, BUT has a valid Cookie...
if (!isset($_SESSION['user']) && isset($_COOKIE['canteen_user'])) {
    
    // We trust the cookie for this session
    // (In a real banking app, we would use a secure token, but this is perfect for a college project)
    $_SESSION['user'] = $_COOKIE['canteen_user'];
    $_SESSION['role'] = $_COOKIE['canteen_role']; // We stored role in cookie too
    
    // NOTE: We don't verify password here because cookies are proof they already logged in once.
}

include __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
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