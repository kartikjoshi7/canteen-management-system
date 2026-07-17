<?php
// data/mock_data.php
// NOW CONNECTED TO MYSQL DATABASE

include __DIR__ . '/../includes/db_connect.php';

// 1. FETCH FOOD ITEMS FROM SQL
$menu_items = [];
$sql = "SELECT * FROM food_items";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // We use the ID as the array key to keep existing logic working
        $menu_items[$row['id']] = $row;
    }
}

// 2. FETCH USERS (Optional, usually we query this directly in login.php)
// We leave this empty because login.php will now query the DB directly.
$users = []; 
?>