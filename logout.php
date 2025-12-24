<?php
session_start();
session_unset();
session_destroy(); // Kill the session

// Redirect back to login page
header("Location: index.php");
exit();
?>