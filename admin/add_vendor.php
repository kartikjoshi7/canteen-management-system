<?php
session_start();
include '../includes/db_connect.php';

// 1. SECURITY CHECK (Only existing Admins can create new Admins)
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = "";
$success = "";

// 2. HANDLE FORM SUBMISSION
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $role = 'admin'; // <--- FORCE ROLE TO ADMIN

    // Duplicate Check
    $check_sql = "SELECT * FROM users WHERE username='$username' OR email='$email' OR phone='$phone'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "User details already exist!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, password, role, email, phone) 
                VALUES ('$username', '$hashed_password', '$role', '$email', '$phone')";

        if (mysqli_query($conn, $sql)) {
            $success = "✅ New Vendor/Admin Added Successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Vendor | SVIT Canteen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <nav>
        <h1>SVIT Admin Panel</h1>
        <ul>
            <li><a href="users.php">Back to Users</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="login-container" style="max-width: 500px; margin-top: 50px;">
    <h2>👨‍🍳 Add New Vendor/Staff</h2>
    <p style="color: #666; margin-bottom: 20px;">Create a new login for canteen staff.</p>

    <?php if($error): ?>
        <p style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if($success): ?>
        <p style="color: green; background: #d4edda; padding: 10px; border-radius: 5px;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required placeholder="e.g. manager_raj">

        <label>Email</label>
        <input type="email" name="email" required placeholder="staff@svit.com">

        <label>Phone</label>
        <input type="number" name="phone" required placeholder="9876543210">

        <label>Password</label>
        <input type="password" name="password" required placeholder="Set a strong password">

        <button type="submit" class="btn" style="width: 100%;">Create Admin Account</button>
    </form>
</div>

<footer style="text-align: center; margin-top: 50px; padding: 20px; background: #333; color: white;">
    <p>&copy; 2026 Canteen Admin System</p>
</footer>

</body>
</html>