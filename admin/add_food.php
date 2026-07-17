<?php
session_start();

// CONNECT TO DATABASE
include '../includes/db_connect.php';

// 1. SECURITY CHECK
// We check if the user is logged in AND if their role is 'admin'.
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. HANDLE ADD NEW ITEM LOGIC
if (isset($_POST['add_btn'])) {

    // SANITIZE INPUTS
    // NOTE: We use mysqli_real_escape_string to escape special characters.
    // This prevents SQL Injection attacks (e.g., if a user types "Burger's").
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // 3. IMAGE UPLOAD LOGIC
    $image_name = "default.jpg"; // Fallback image
    
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        // Move the uploaded file from temporary storage to our folder
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_name = basename($_FILES["image"]["name"]);
        }
    }

    // 4. INSERT INTO DATABASE (SQL)
    // NOTE: We do not need to generate an ID manually anymore.
    // MySQL 'AUTO_INCREMENT' will automatically assign the next ID (e.g., 5, 6, 7).
    $sql = "INSERT INTO food_items (name, price, description, image) 
            VALUES ('$name', '$price', '$description', '$image_name')";

    if (mysqli_query($conn, $sql)) {
        // Success: Redirect back to the list
        header("Location: food_items.php");
        exit();
    } else {
        // Error: Show what went wrong
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Item | SVIT Canteen</title>
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