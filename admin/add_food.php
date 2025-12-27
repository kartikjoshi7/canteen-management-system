<?php
session_start();

// 1. SECURITY CHECK
// Que: "Why check session again?"
// Ans: "To ensure that only a logged-in Admin can add items. We don't want students adding fake food items."
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. HANDLE ADD NEW ITEM LOGIC
if (isset($_POST['add_btn'])) {
    $file_path = '../data/food_items.json';
    // Read current database to get the list of existing items
    $menu_items = json_decode(file_get_contents($file_path), true);

    // AUTO-INCREMENT ID LOGIC (Crucial!)
    // Que: "How do you generate a Primary Key without SQL?"
    // Ans: "I extract all the existing Keys (IDs) from the array, find the highest number (max), 
    // and add 1 to it. So if the last ID was 5, the new one becomes 6."
    $new_id = max(array_keys($menu_items)) + 1;

    // 3. IMAGE UPLOAD LOGIC
    $image_name = "default.jpg"; // Fallback image if user forgets to upload one
    
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        // Get the filename (e.g., "pizza.jpg")
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        // Que: "What is tmp_name?"
        // ANSWER: "When a file is uploaded, PHP saves it in a temporary system folder. 
        // We must use move_uploaded_file() to physically move it to our project folder."
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        
        $image_name = basename($_FILES["image"]["name"]);
    }

    // 4. CREATE NEW ITEM ARRAY
    // We bundle all the form data into a neat array structure.
    $new_item = [
        "name" => $_POST['name'],
        "price" => $_POST['price'],
        "description" => $_POST['description'],
        "image" => $image_name
    ];

    // 5. SAVE TO DATABASE
    // We insert the new item into the main array using the new ID we calculated.
    $menu_items[$new_id] = $new_item;

    // Write the updated array back to the JSON file
    file_put_contents($file_path, json_encode($menu_items, JSON_PRETTY_PRINT));
    
    // Redirect to the list to show success
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