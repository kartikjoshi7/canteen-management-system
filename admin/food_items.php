<?php
session_start();

// 1. LOAD DATA FRESH
// Que: "Why not include mock_data.php here?"
// Ans: "We need to read the JSON file directly to get the absolute latest state of the menu. 
// We decode it into an associative array so we can manipulate (delete) items easily."
$file_path = '../data/food_items.json';
$menu_items = json_decode(file_get_contents($file_path), true);

// 2. SECURITY CHECK
// This prevents students or guests from accessing the admin panel by typing the URL directly.
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 3. HANDLE DELETE ACTION
// Logic: We check if the URL contains "?action=delete&id=..."
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    
    $delete_id = $_GET['id'];
    
    // Que: "How do you delete data without SQL?"
    // Ans: "I use the PHP 'unset()' function. It removes the specific key-value pair 
    // from the array effectively deleting the item from memory."
    unset($menu_items[$delete_id]); 

    // SAVE CHANGES
    // After deleting from the array, we must OVERWRITE the JSON file with the new array.
    // JSON_PRETTY_PRINT keeps the text file readable for debugging.
    file_put_contents($file_path, json_encode($menu_items, JSON_PRETTY_PRINT)); 
    
    // Refresh page to show updated list
    header("Location: food_items.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Food Items | SVIT Canteen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 15px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
        img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>

<header>
    <nav>
        <h1>SVIT Admin Panel</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>🍔 Manage Food Menu</h2>
        <div>
            <a href="add_food.php" class="btn" style="background: #28a745; margin-right: 10px;">+ Add New Item</a>
            <a href="dashboard.php" class="btn" style="background: #666;">&larr; Back</a>
        </div>
    </div>
    <hr style="margin: 10px 0 30px 0;">

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // 4. DISPLAY LOOP
            // We loop through the $menu_items array and create a table row (<tr>) for each item.
            foreach($menu_items as $id => $item): 
            ?>
            <tr>
                <td><img src="../assets/images/<?php echo $item['image']; ?>" alt="Food"></td>
                
                <td><?php echo $item['name']; ?></td>
                <td style="color: #28a745; font-weight: bold;">₹<?php echo $item['price']; ?></td>
                <td style="color: #666; font-size: 0.9rem;"><?php echo $item['description']; ?></td>
                
                <td>
                    <a href="edit_food.php?id=<?php echo $id; ?>" class="btn" style="padding: 5px 10px; font-size: 0.8rem; background: #007bff; text-decoration: none;">Edit</a>
                    
                    <a href="food_items.php?action=delete&id=<?php echo $id; ?>" 
                       class="btn" 
                       style="padding: 5px 10px; font-size: 0.8rem; background: #dc3545; text-decoration: none;"
                       onclick="return confirm('Are you sure you want to remove this item?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<footer style="text-align: center; margin-top: 50px; padding: 20px; background: #333; color: white;">
    <p>&copy; 2025 Canteen Admin System</p>
</footer>

</body>
</html>