<?php
session_start();
include 'includes/db_connect.php';

// --- AUTO-REDIRECT IF ALREADY LOGGED IN ---
// If user is already logged in (Session), skip login page
if (isset($_SESSION['user'])) {
    $redirect = ($_SESSION['role'] == 'admin') ? "admin/dashboard.php" : "index.php";
    header("Location: $redirect");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password']) || $password == $row['password']) {
            
            // Fix Plain Text Password (Self-Healing)
            if ($password == $row['password']) {
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $uid = $row['id'];
                mysqli_query($conn, "UPDATE users SET password = '$new_hash' WHERE id = '$uid'");
            }

            // 1. SET SESSION (Standard Login)
            $_SESSION['user'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            // 2. SET COOKIE (Remember Me Feature)
            if (isset($_POST['remember'])) {
                // Cookie lasts for 30 Days (86400 seconds * 30)
                // We store the ID and a Hash of the Username for security
                setcookie('canteen_user', $row['username'], time() + (86400 * 30), "/");
                setcookie('canteen_role', $row['role'], time() + (86400 * 30), "/");
            }

            // 3. REDIRECT
            $redirect_url = ($row['role'] == 'admin') ? "admin/dashboard.php" : "index.php";
            echo "<script>window.location.href = '$redirect_url';</script>";
            exit();

        } else {
            $error = "Incorrect Password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | SVIT Canteen</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="login-container">
    <h2>Login</h2>
    
    <?php if($error): ?>
        <p style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required placeholder="Enter username">

        <label>Password</label>
        <input type="password" name="password" required placeholder="Enter password">

        <div style="text-align: left; margin-bottom: 20px; display: flex; align-items: center;">
            <input type="checkbox" name="remember" id="remember" style="width: auto; margin: 0 10px 0 0;">
            <label for="remember" style="margin: 0; cursor: pointer; font-size: 0.95rem;">Remember me</label>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Login</button>
    </form>

    <p style="margin-top: 20px;">
        Don't have an account? <a href="signup.php" style="color: var(--primary-color); font-weight: bold;">Sign Up Now</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>