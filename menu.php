<?php
include 'includes/header.php';
// This line connects to our "Database" file.
// In the future, this is where we would connect to MySQL. 
// For now, it loads the array of food items from a file.
include 'data/mock_data.php'; 
?>

<h2>Our Menu</h2>
<p>Select your favorite food items below.</p>
<hr style="margin: 10px 0 20px 0;">

<?php 
// CLEAN URL TRICK:
// When an item is added, the URL becomes 'menu.php?added=1'.
// This Javascript snippet silently removes that '?added=1' part
// so if the user refreshes the page, it doesn't try to add the item again.
if(isset($_GET['added'])): 
?>
    <script>
        window.history.replaceState(null, null, window.location.pathname);
    </script>
<?php endif; ?>

<div class="menu-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
    
    <?php 
    // TIP: Always check if data exists before looping to prevent errors.
    if(isset($menu_items) && count($menu_items) > 0){
        
        // LOOP START:
        // This foreach loop goes through every single item in our 'database'
        // and generates the HTML card for it automatically.
        foreach($menu_items as $id => $item): 
            
            // CRITICAL LOGIC: Smart Cart Button
            // We check how many of THIS specific item are already in the session cart.
            // If the user has 2 burgers, $qty will be 2.
            $qty = get_item_count($id);
    ?>
        
        <div id="item-<?php echo $id; ?>" class="food-card" style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; background: #fff;">
            
            <img src="assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 5px; background-color: #eee;">
            <h3 style="margin: 10px 0;"><?php echo $item['name']; ?></h3>
            <p style="color: #666; font-size: 0.9rem; min-height: 40px;"><?php echo $item['description']; ?></p>
            <h4 style="color: #28a745; margin: 10px 0;">₹<?php echo $item['price']; ?></h4>
            
            <?php 
            // LOGIC FOR BUTTON SWITCHING:
            // Case 1: If the user has NOT bought this item yet ($qty == 0),
            // show the simple "Add to Cart" button.
            if ($qty == 0): 
            ?>
                
                <a href="cart.php?action=add&id=<?php echo $id; ?>&from=menu" class="btn" style="display: block; width: 100%; text-decoration: none;">
                    Add to Cart
                </a>

            <?php 
            // Case 2: If the user ALREADY has this item in cart,
            // show the "+ / -" controls so they can edit quantity directly.
            else: 
            ?>

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
        // Fallback message if the database is empty
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