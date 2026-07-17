<?php
session_start();
include 'includes/db_connect.php';

$error = "";
$success = "";

// FIX: Use REQUEST_METHOD check so it works even if script.js disables the button
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. SANITIZE INPUTS
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $role = 'student'; // Default role

    // 2. DUPLICATE CHECK (Spam Prevention)
    $check_sql = "SELECT * FROM users WHERE username='$username' OR email='$email' OR phone='$phone'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $existing_user = mysqli_fetch_assoc($check_result);
        if ($existing_user['username'] == $username) {
            $error = "Username already taken! Please choose another.";
        } elseif ($existing_user['email'] == $email) {
            $error = "This Email is already registered!";
        } else {
            $error = "This Phone Number is already registered!";
        }
    } else {
        // 3. CREATE NEW USER
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password, role, email, phone) 
                VALUES ('$username', '$hashed_password', '$role', '$email', '$phone')";

        if (mysqli_query($conn, $sql)) {
            $success = "Registration Successful! Redirecting to Login...";
            // Javascript Redirect after 1.5 seconds
            echo "<script>
                setTimeout(function(){ window.location.href='login.php'; }, 1500);
            </script>";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up | SVIT Canteen</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        // --- CLIENT-SIDE MOCK OTP LOGIC ---
        function sendMockOTP() {
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            
            // Basic Validation
            if(email === "" || phone === "" || phone.length !== 10) {
                alert("Please enter a valid Email and 10-digit Phone Number first.");
                return;
            }

            // Simulate Network Delay
            const verifyBtn = document.getElementById('verifyBtn');
            verifyBtn.innerHTML = "Sending...";
            verifyBtn.disabled = true;

            setTimeout(() => {
                // Generate Random 4-Digit Code
                const otp = Math.floor(1000 + Math.random() * 9000);
                
                // Show it in a popup (Simulating an SMS/Email)
                alert(" [SIMULATION] \n Your Verification OTP is: " + otp + "\n\n (In a real app, this would be sent to your phone/email)");

                // Ask user to enter it
                const userEntered = prompt("Enter the 4-digit OTP sent to your phone:");

                if (userEntered == otp) {
                    alert("✅ Verification Successful!");
                    
                    // Unlock the Signup Button
                    const signupBtn = document.getElementById('signupBtn');
                    signupBtn.disabled = false;
                    signupBtn.innerHTML = "Sign Up";
                    signupBtn.style.opacity = "1";
                    signupBtn.style.cursor = "pointer";
                    
                    // Lock the inputs so they can't change them after verifying
                    document.getElementById('email').readOnly = true;
                    document.getElementById('phone').readOnly = true;
                    verifyBtn.style.display = 'none'; // Hide verify button
                    
                    // Add a visual indicator
                    document.getElementById('verified-badge').style.display = 'block';
                } else {
                    alert("❌ Incorrect OTP. Please try again.");
                    verifyBtn.innerHTML = "Verify Contact";
                    verifyBtn.disabled = false;
                }
            }, 1000); // 1 second delay
        }
    </script>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="login-container" style="max-width: 450px;">
    <h2>Create Account</h2>
    
    <?php if($error): ?>
        <p style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin-bottom: 15px;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if($success): ?>
        <p style="color: green; background: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">
        
        <label>Username</label>
        <input type="text" name="username" required placeholder="Choose a username">

        <label>Password</label>
        <input type="password" name="password" required placeholder="Create a password">

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

        <label>Email Address</label>
        <input type="email" id="email" name="email" required placeholder="student@example.com">

        <label>Phone Number (10 Digits)</label>
        <input type="number" id="phone" name="phone" required placeholder="9999999999" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);">

        <div id="verified-badge" style="display: none; color: green; font-weight: bold; margin-bottom: 15px; border: 1px solid green; padding: 5px; border-radius: 5px; text-align: center; background: #e8f5e9;">
            ✅ Contact Verified
        </div>

        <button type="button" id="verifyBtn" onclick="sendMockOTP()" class="btn" style="width: 100%; background: #6c757d; margin-bottom: 15px;">
            Verify Phone & Email
        </button>

        <button type="submit" id="signupBtn" class="btn" style="width: 100%; opacity: 0.5; cursor: not-allowed;" disabled>
            Sign Up
        </button>

    </form>

    <p style="margin-top: 20px;">
        Already have an account? <a href="login.php" style="color: var(--primary-color); font-weight: bold;">Login</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>