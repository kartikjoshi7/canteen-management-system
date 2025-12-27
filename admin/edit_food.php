<?php
session_start();

// 1. ADMIN SECURITY CHECK
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. LOAD CURRENT DATA
$file_path = '../data/food_items.json';
// Read the JSON file and convert it into a PHP Array so we can edit it.
$menu_items = json_decode(file_get_contents($file_path), true);

// 3. GET THE SPECIFIC ITEM
// We get the ID from the URL (e.g., edit_food.php?id=0).
// We use this ID to pull the specific food details from the array.
$id = $_GET['id'];
$item = $menu_items[$id];

// 4. HANDLE FORM SUBMISSION (UPDATE LOGIC)
if (isset($_POST['update_btn'])) {
    
    // Update the text fields in the array with new values from the form
    $menu_items[$id]['name'] = $_POST['name'];
    $menu_items[$id]['price'] = $_POST['price'];
    $menu_items[$id]['description'] = $_POST['description'];

    // 5. IMAGE UPLOAD LOGIC
    // Check if the user selected a NEW image. If not, we keep the old one.
    if (!empty($_FILES['image']['name'])) {
        
        $target_dir = "../assets/images/";
        // basename() ensures we just get the filename "burger.jpg", not the full computer path.
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        // Que: "What does move_uploaded_file do?"
        // Ans: "When a file is uploaded, PHP stores it in a temporary 'Trash' folder first. 
        // We must move it to our permanent 'assets/images' folder to keep it."
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        
        // Update the image name in the database array
        $menu_items[$id]['image'] = basename($_FILES["image"]["name"]);
    }

    // 6. SAVE CHANGES
    // Convert the updated Array back to JSON String and overwrite the file.
    file_put_contents($file_path, json_encode($menu_items, JSON_PRETTY_PRINT));
    
    // Redirect back to the list
    header("Location: food_items.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Item</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h2>Edit Food Item</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <label>Item Name:</label>
        <input type="text" name="name" value="<?php echo $item['name']; ?>" class="form-control" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
        
        <label>Price (₹):</label>
        <input type="number" name="price" value="<?php echo $item['price']; ?>" class="form-control" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
        
        <label>Description:</label>
        <textarea name="description" class="form-control" required style="width: 100%; padding: 8px; margin-bottom: 10px;"><?php echo $item['description']; ?></textarea>
        
        <label>Change Image (Optional):</label>
        <input type="file" name="image" style="margin-bottom: 20px;">
        
        <button type="submit" name="update_btn" class="btn" style="width: 100%;">Update Item</button>
        <a href="food_items.php" style="display: block; text-align: center; margin-top: 10px;">Cancel</a>
    </form>
</div>
</body>
</html>