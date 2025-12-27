<?php

// 1. LOAD FOOD ITEMS FROM JSON
// TIP: We use __DIR__ to get the absolute path of the current folder.
// This ensures that PHP can always find 'food_items.json' no matter where we include this file from.
$food_file = __DIR__ . '/food_items.json';
$menu_items = [];

// Error Handling: Check if the file actually exists before trying to read it.
if (file_exists($food_file)) {
    
    // Step A: Read the raw text content from the JSON file.
    $json_content = file_get_contents($food_file);
    
    // Step B: Convert the JSON string into a PHP Associative Array.
    // The 'true' parameter is crucial: it tells PHP to give us an Array (easy to use) 
    // instead of an Object.
    $menu_items = json_decode($json_content, true);
}

// 2. HARDCODED USERS (Authentication)
// Que: "Why didn't you put users in a JSON file too?"
// Ans: "For this prototype, I kept users simple to demonstrate the Login Logic clearly.
// In a real production app, I would store these in a Database with hashed passwords."
$users = [
    "student" => "1234",  // Key = Username, Value = Password
    "admin" => "admin"
];
?>