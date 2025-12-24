<?php
session_start();

// Security Check
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// HANDLE ADD NEW ITEM
if (isset($_POST['add_btn'])) {
    $file_path = '../data/food_items.json';
    $menu_items = json_decode(file_get_contents($file_path), true);

    // Generate a new ID (highest ID + 1)
    $new_id = max(array_keys($menu_items)) + 1;

    // Handle Image Upload
    $image_name = "default.jpg"; // Fallback
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image_name = basename($_FILES["image"]["name"]);
    }

    // Create New Item Array
    $new_item = [
        "name" => $_POST['name'],
        "price" => $_POST['price'],
        "description" => $_POST['description'],
        "image" => $image_name
    ];

    // Add to main array
    $menu_items[$new_id] = $new_item;

    // Save
    file_put_contents($file_path, json_encode($menu_items, JSON_PRETTY_PRINT));
    
    header("Location: food_items.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Item</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h2>Add New Food Item</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Item Name:</label>
        <input type="text" name="name" class="form-control" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
        
        <label>Price (₹):</label>
        <input type="number" name="price" class="form-control" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
        
        <label>Description:</label>
        <textarea name="description" class="form-control" required style="width: 100%; padding: 8px; margin-bottom: 10px;"></textarea>
        
        <label>Upload Image:</label>
        <input type="file" name="image" required style="margin-bottom: 20px;">
        
        <button type="submit" name="add_btn" class="btn" style="width: 100%; background: #28a745;">Add Item</button>
        <a href="food_items.php" style="display: block; text-align: center; margin-top: 10px;">Cancel</a>
    </form>
</div>
</body>
</html>