<?php
session_start();
include('db.php');

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$msg = "";

if(isset($_POST['register'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0) {
        $msg = "<p class='error'>That email is already registered. <a href='login.php'>Login here</a></p>";
    } else {
        $sql = "INSERT INTO users (full_name, email, password) VALUES ('$name', '$email', '$pass')";
        if(mysqli_query($conn, $sql)) {
            $msg = "<p class='success'>Account created! <a href='login.php'>Login here</a></p>";
        } else {
            $msg = "<p class='error'>Something went wrong. Please try again.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - CarRental Pro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .card { background: white; padding: 35px; border-radius: 12px; width: 360px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; font-size: 1em; }
        button { width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1em; font-weight: bold; margin-top: 10px; }
        button:hover { background: #219a52; }
        .error { color: red; font-size: 0.9em; margin-bottom: 10px; }
        .success { color: green; font-size: 0.9em; margin-bottom: 10px; }
        a { color: #e67e22; text-decoration: none; }
        .login-link { display: block; margin-top: 15px; color: #555; }
    </style>
</head>
<body>
<div class="card">
    <h2>🚗 Create Account</h2>
    <?php echo $msg; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="pass" placeholder="Create Password" required>
        <button type="submit" name="register">Sign Up</button>
    </form>
    <a href="login.php" class="login-link">Already have an account? Login</a>
</div>
</body>
</html>
