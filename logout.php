<?php
// 1. RESUME SESSION
// Que: "Why start the session if you are logging out?"
// ANSWER: You cannot destroy a session unless you are connected to it first.
// We must "find" the active session to have permission to delete it.
session_start();

// 2. CLEAR VARIABLES
// This removes specific data (like 'user', 'cart') from the memory.
// It is like emptying the papers out of a folder.
session_unset();

// 3. DESTROY SESSION STORAGE
// This completely deletes the Session File on the server side.
// After this line, the user's login token is invalid.
session_destroy(); 

// 4. REDIRECT
// Send the user back to the home/login page.
// Using exit() is important to ensure no other code runs after the redirect.
header("Location: index.php");
exit();
?>