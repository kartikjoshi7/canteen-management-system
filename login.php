<?php
include 'includes/header.php';
// We include mock_data.php because it acts as our "Database". 
// It contains the list of valid usernames (admin/student) and their passwords.
include 'data/mock_data.php';

$error = "";

// 1. HANDLE LOGIN LOGIC
// We use 'isset' to check if the button was actually clicked. 
// This prevents the login logic from running when the page just loads for the first time.
if (isset($_POST['login_btn'])) {
    
    // Capture the data entered by the user in the form fields
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Check if username exists in our "fake database" and password matches
    // isset($users[$user]) checks if the username is in our array.
    // $users[$user] == $pass checks if the password matches the key value.
    if (isset($users[$user]) && $users[$user] == $pass) {
        
        // Success! Save user to session
        // TIP: This is the most important line. Storing the name in $_SESSION 
        // tells the server to "remember" this user as they browse other pages (like the cart).
        $_SESSION['user'] = $user;
        
        // --- UPDATED LOGIC STARTS HERE ---
        
        // If the user is 'admin', send them strictly to the Dashboard
        // TIP: This is called "Role-Based Redirection". We check who the user is
        // and send them to their specific area (Admin Panel vs Student Menu).
        if ($user === 'admin') {
            echo "<script>window.location.href='admin/dashboard.php';</script>";
        } 
        // If it is a normal student, send them to the Home Page
        else {
            echo "<script>window.location.href='index.php';</script>";
        }
        
        exit(); // Stop the script here so no more code runs after redirect
        // --- UPDATED LOGIC ENDS HERE ---

    } else {
        // If authentication fails, we store an error message to display it in HTML below.
        $error = "Invalid Username or Password!";
    }
}
?>

<div class="login-container" style="max-width: 400px; margin: 50px auto; padding: 20px; background: #fff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    
    <h2 style="text-align: center; margin-bottom: 20px;">Login</h2>

    <?php if($error): ?>
        <p style="color: red; text-align: center; background: #ffe6e6; padding: 10px; border-radius: 3px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 15px;">
            <label>Username</label>
            <input type="text" name="username" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label>Password</label>
            <input type="password" name="password" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
        </div>

        <button type="submit" name="login_btn" class="btn" style="width: 100%;">Login</button>
    </form>
    
    <p style="margin-top: 15px; font-size: 0.9rem; color: #666; text-align: center;">
        <strong>Hint:</strong><br> 
        Student: <b>student</b> / <b>1234</b><br>
        Admin: <b>admin</b> / <b>admin</b>
    </p>

</div>

<?php include 'includes/footer.php'; ?>