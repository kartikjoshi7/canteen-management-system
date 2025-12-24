<?php
session_start();

// Security Check
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$file_path = '../data/food_items.json';
$menu_items = json_decode(file_get_contents($file_path), true);
$id = $_GET['id'];
$item = $menu_items[$id];

// HANDLE FORM SUBMISSION
if (isset($_POST['update_btn'])) {
    // Update text fields
    $menu_items[$id]['name'] = $_POST['name'];
    $menu_items[$id]['price'] = $_POST['price'];
    $menu_items[$id]['description'] = $_POST['description'];

    // Update Image (Only if a new one is uploaded)
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $menu_items[$id]['image'] = basename($_FILES["image"]["name"]);
    }

    // Save back to JSON
    file_put_contents($file_path, json_encode($menu_items, JSON_PRETTY_PRINT));
    
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