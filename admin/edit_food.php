<?php
session_start();
include '../includes/db_connect.php'; // Connect to SQL

// 1. SECURITY CHECK
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. CHECK ID PARAMETER
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // FETCH EXISTING DATA
    // We need to pre-fill the form with the current details of the burger/pizza.
    $sql = "SELECT * FROM food_items WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $item = mysqli_fetch_assoc($result);

    if (!$item) {
        echo "Item not found!";
        exit();
    }
} else {
    header("Location: food_items.php");
    exit();
}

// 3. HANDLE UPDATE LOGIC
if (isset($_POST['update_btn'])) {
    
    // Sanitize Inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // 4. IMAGE UPDATE LOGIC
    // We assume the user keeps the old image...
    $image_query_part = ""; 

    // ...unless they uploaded a NEW one.
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $new_image = basename($_FILES["image"]["name"]);
            // If new image uploaded, we add this to our SQL query
            $image_query_part = ", image = '$new_image'";
        }
    }

    // 5. RUN UPDATE QUERY
    // Note how we append '$image_query_part'. 
    // If no new image, that variable is empty, so the SQL doesn't touch the image column.
    $update_sql = "UPDATE food_items 
                   SET name = '$name', 
                       price = '$price', 
                       description = '$description' 
                       $image_query_part 
                   WHERE id = '$id'";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: food_items.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Item | SVIT Canteen</title>
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
        <br>
        <img src="../assets/images/<?php echo $item['image']; ?>" style="width: 100px; height: 60px; object-fit: cover; margin: 10px 0; border-radius: 5px;">
        <br>
        <input type="file" name="image" style="margin-bottom: 20px;">
        
        <button type="submit" name="update_btn" class="btn" style="width: 100%;">Update Item</button>
        <a href="food_items.php" style="display: block; text-align: center; margin-top: 10px;">Cancel</a>
    
    </form>
</div>
</body>
</html>