<?php
session_start();
include('db.php');

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if(isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with that email!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - CarRental Pro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .card { background: white; padding: 35px; border-radius: 12px; width: 340px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; margin-bottom: 20px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; font-size: 1em; }
        button { width: 100%; padding: 12px; background: #2c3e50; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1em; font-weight: bold; margin-top: 10px; }
        button:hover { background: #34495e; }
        .error { color: red; font-size: 0.9em; margin-bottom: 10px; text-align: center; }
        p { text-align: center; margin-top: 15px; color: #555; }
        a { color: #e67e22; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h2>🚗 Login</h2>
    <?php if($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login_btn">Login</button>
    </form>
    <p>New user? <a href="signup.php">Register here</a></p>
</div>
</body>
</html>
