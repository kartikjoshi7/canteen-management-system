<?php
// FILE: data/mock_data.php

// 1. Load Food Items from JSON
$food_file = __DIR__ . '/food_items.json';
$menu_items = [];

if (file_exists($food_file)) {
    $json_content = file_get_contents($food_file);
    $menu_items = json_decode($json_content, true);
}

// 2. Keep Users Hardcoded (For simplicity)
$users = [
    "student" => "1234",
    "admin" => "admin"
];
?>