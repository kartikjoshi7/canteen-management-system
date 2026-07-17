<?php
include 'includes/header.php';

// DATABASE CONNECTION
// We include the connection file to talk to MySQL.
include 'includes/db_connect.php';

// FETCH FOOD ITEMS FROM DATABASE (SQL)
$menu_items = [];
$sql = "SELECT * FROM food_items";
$result = mysqli_query($conn, $sql);

// Check if query was successful and has data
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // We use the ID as the key for easy access later
        $menu_items[$row['id']] = $row;
    }
}
?>

<h2>Our Menu</h2>
<p>Select your favorite food items below.</p>
<hr style="margin: 10px 0 20px 0;">

<?php 
// CLEAN URL TRICK:
// Removes '?added=1' from URL to prevent duplicate additions on refresh.
if(isset($_GET['added'])): 
?>
    <script>
        window.history.replaceState(null, null, window.location.pathname);
    </script>
<?php endif; ?>

<div class="menu-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
    
    <?php 
    // Check if we have items to display
    if(count($menu_items) > 0){
        
        // LOOP START:
        foreach($menu_items as $id => $item): 
            
            // CRITICAL LOGIC: Smart Cart Button
            // We check how many of THIS specific item are already in the session cart.
            $qty = get_item_count($id);
    ?>
        
        <div id="item-<?php echo $id; ?>" class="food-card" style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; background: #fff;">
            
            <?php 
                $img_path = "assets/images/" . $item['image'];
                if (!file_exists($img_path) || empty($item['image'])) {
                    $img_path = "assets/images/default.jpg"; 
                }
            ?>
            <img src="<?php echo $img_path; ?>" alt="<?php echo $item['name']; ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 5px; background-color: #eee;">
            
            <h3 style="margin: 10px 0;"><?php echo $item['name']; ?></h3>
            <p style="color: #666; font-size: 0.9rem; min-height: 40px;"><?php echo $item['description']; ?></p>
            <h4 style="color: #28a745; margin: 10px 0;">₹<?php echo $item['price']; ?></h4>
            
            <?php 
            // LOGIC FOR BUTTON SWITCHING:
            // Case 1: Item NOT in cart -> Show "Add to Cart"
            if ($qty == 0): 
            ?>
                
                <a href="cart.php?action=add&id=<?php echo $id; ?>&from=menu" class="btn" style="display: block; width: 100%; text-decoration: none;">
                    Add to Cart
                </a>

            <?php 
            // Case 2: Item IS in cart -> Show "+ / -" buttons
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
        echo "<p style='grid-column: 1/-1; text-align: center; color: #666;'>No food items found in the database.</p>";
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