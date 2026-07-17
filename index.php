<?php
// INCLUDE HEADER (Starts Session & Navigation)
include 'includes/header.php';
?>

<div class="home-content" style="text-align: center; padding: 100px 20px;">
    
    <h1 style="font-size: 3rem; margin-bottom: 10px;">Welcome to SVIT Canteen</h1>
    
    <?php if(isset($_SESSION['user'])): ?>
        <p style="font-size: 1.2rem; color: #28a745; margin-bottom: 30px;">
            Hello, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>! Hungry today?
        </p>
    <?php else: ?>
        <p style="font-size: 1.2rem; color: #666; margin-bottom: 30px;">
            Fresh Food. Fast Service. Good Mood.
        </p>
    <?php endif; ?>
    
    <a href="menu.php" class="btn" style="
        background: #ff6600; 
        color: white; 
        padding: 15px 30px; 
        text-decoration: none; 
        border-radius: 50px; 
        font-weight: bold; 
        font-size: 1.2rem;
        box-shadow: 0 4px 15px rgba(255, 102, 0, 0.3);
        transition: transform 0.2s;
    " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        Order Food Now 🍔
    </a>

</div>

<?php include 'includes/footer.php'; ?>