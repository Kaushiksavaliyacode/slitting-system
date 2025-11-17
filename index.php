<?php
session_start();
if(isset($_SESSION['department'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Slitting System</title>
    <style>
        body {font-family: Arial; background: #1a5276; margin:0; padding:20px; display:flex; justify-content:center; align-items:center; min-height:100vh;}
        .login {background:white; padding:30px; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.3); width:90%; max-width:400px; text-align:center;}
        input {width:100%; height:50px; margin:10px 0; padding:0 15px; border:1px solid #ddd; border-radius:8px; font-size:16px;}
        button {width:100%; height:50px; background:#27ae60; color:white; border:none; border-radius:8px; font-size:18px; cursor:pointer;}
        h2 {color:#2c3e50; margin-bottom:20px;}
        .error {color:red; margin:10px 0;}
    </style>
</head>
<body>
<div class="login">
    <h2>Department Login</h2>
    <?php if(isset($_GET['error'])) echo "<p class='error'>Wrong username or password!</p>"; ?>
    <?php if(isset($_GET['logout'])) echo "<p style='color:green;'>Logged out!</p>"; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">LOGIN</button>
    </form>
    <br><small>Try: admin / 12345<br>slitting / 12345<br>production / 12345</small>
</div>

<?php
if(isset($_POST['login'])){
    require 'db.php';
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['department'] = $user['department'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: index.php?error=1");
        exit();
    }
}
?>
</body>
</html>
