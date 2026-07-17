<?php
session_start();

// 1. CLEAR SESSION
session_unset();
session_destroy();

// 2. CLEAR COOKIES (Critical for "Remember Me")
// We set the expiration time to the past (time() - 3600), which forces the browser to delete it.
if (isset($_COOKIE['canteen_user'])) {
    setcookie('canteen_user', '', time() - 3600, "/");
}
if (isset($_COOKIE['canteen_role'])) {
    setcookie('canteen_role', '', time() - 3600, "/");
}

// 3. REDIRECT TO HOME
header("Location: index.php");
exit();
?>