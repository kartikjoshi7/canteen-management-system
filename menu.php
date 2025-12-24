<?php
include 'includes/header.php';
// This line connects to our "Database" file
include 'data/mock_data.php'; 
?>

<h2>Our Menu</h2>
<p>Select your favorite food items below.</p>
<hr style="margin: 10px 0 20px 0;">

<?php if(isset($_GET['added'])): ?>
    <script>
        // Remove the ?added=1 from URL without refreshing, so it looks clean
        window.history.replaceState(null, null, window.location.pathname);
    </script>
<?php endif; ?>

<div class="menu-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
    
    <?php 
    // We check if the variable $menu_items exists (from mock_data.php)
    if(isset($menu_items) && count($menu_items) > 0){
        
        // Loop through every food item
        foreach($menu_items as $id => $item): 
            
            // CRITICAL: Get the quantity of THIS specific item from the session
            // We use the helper function we added to includes/functions.php
            $qty = get_item_count($id);
    ?>
        
        <div id="item-<?php echo $id; ?>" class="food-card" style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; background: #fff;">
            
            <img src="assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 5px; background-color: #eee;">
            
            <h3 style="margin: 10px 0;"><?php echo $item['name']; ?></h3>
            <p style="color: #666; font-size: 0.9rem; min-height: 40px;"><?php echo $item['description']; ?></p>
            <h4 style="color: #28a745; margin: 10px 0;">₹<?php echo $item['price']; ?></h4>
            
            <?php if ($qty == 0): ?>
                
                <a href="cart.php?action=add&id=<?php echo $id; ?>&from=menu" class="btn" style="display: block; width: 100%; text-decoration: none;">
                    Add to Cart
                </a>

            <?php else: ?>

                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; background: #fff3cd; padding: 8px; border-radius: 5px; border: 1px solid #ffeeba;">
                    
                    <a href="cart.php?action=decrease&id=<?php echo $id; ?>&from=menu" 
                       style="background: #dc3545; color: white; width: 30px; height: 30px; line-height: 30px; border-radius: 50%; text-decoration: none; font-weight: bold; font-size: 1.2rem; display: inline-block;">
                       -
                    </a>

                    <span style="font-size: 1.2rem; font-weight: bold; color: #856404; width: 30px;">
                        <?php echo $qty; ?>
                    </span>

                    <a href="cart.php?action=increase&id=<?php echo $id; ?>&from=menu" 
                       style="background: #28a745; color: white; width: 30px; height: 30px; line-height: 30px; border-radius: 50%; text-decoration: none; font-weight: bold; font-size: 1.2rem; display: inline-block;">
                       +
                    </a>
                </div>

            <?php endif; ?>
            </div>

    <?php 
        endforeach; 

    } else {
        echo "<p>No food items found. Please check data/mock_data.php</p>";
    }
    ?>

</div>

<?php if(get_cart_count() > 0): ?>
    <a href="cart.php" style="
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #28a745;
        color: white;
        padding: 15px 25px;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        font-weight: bold;
        font-size: 1.1rem;
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 10px;
    ">
        🛒 Go to Cart (<?php echo get_cart_count(); ?>)
    </a>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>