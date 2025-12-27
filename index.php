<?php
// MODULARIZATION / DRY PRINCIPLE (Don't Repeat Yourself)
// Instead of writing the Navbar HTML on every single page (Login, Menu, Cart),
// we write it once in 'header.php' and just include it here.
// This also starts the Session automatically.
include 'includes/header.php';
?>

<div class="home-content" style="text-align: center; padding: 50px 0;">
    
    <h1>Welcome to SVIT Canteen</h1>
    <p>Fresh Food. Fast Service.</p>
    
    <a href="menu.php" class="btn">Order Food Now</a>

</div>

<?php
// Reusing the footer code (Copyright text, closing HTML tags)
include 'includes/footer.php';
?>