<?php
session_start();
include '../includes/db_connect.php'; // Connect to SQL

// Security Check
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// HANDLE DELETE ACTION (SQL Version)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // SQL Delete Query
    $sql = "DELETE FROM food_items WHERE id = '$delete_id'";
    if(mysqli_query($conn, $sql)) {
        header("Location: food_items.php"); // Refresh
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// FETCH ITEMS FROM SQL
$menu_items = [];
$sql = "SELECT * FROM food_items";
$result = mysqli_query($conn, $sql);
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
            <?php while($item = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>
                    <?php 
                        $img_file = "../assets/images/" . $item['image'];
                        if (!file_exists($img_file) || empty($item['image'])) {
                            $img_file = "../assets/images/default.jpg"; 
                        }
                    ?>
                    <img src="<?php echo $img_file; ?>" alt="Food">
                </td>
                
                <td><?php echo $item['name']; ?></td>
                <td style="color: #28a745; font-weight: bold;">₹<?php echo $item['price']; ?></td>
                <td style="color: #666; font-size: 0.9rem;"><?php echo $item['description']; ?></td>
                <td>
                    <a href="edit_food.php?id=<?php echo $item['id']; ?>" class="btn" style="padding: 5px 10px; font-size: 0.8rem; background: #007bff; text-decoration: none;">Edit</a>
                    
                    <a href="food_items.php?action=delete&id=<?php echo $item['id']; ?>" 
                       class="btn" 
                       style="padding: 5px 10px; font-size: 0.8rem; background: #dc3545; text-decoration: none;"
                       onclick="return confirm('Are you sure you want to remove this item?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer style="text-align: center; margin-top: 50px; padding: 20px; background: #333; color: white;">
    <p>&copy; 2026 Canteen Admin System</p>
</footer>

</body>
</html>