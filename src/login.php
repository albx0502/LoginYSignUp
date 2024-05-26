<?php
session_start();

$max_attempts = 5;
$lockout_time = 300; // 5 minutos

if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $max_attempts) {
    $remaining_lockout = $_SESSION['last_attempt_time'] + $lockout_time - time();
    if ($remaining_lockout > 0) {
        die("Too many login attempts. Please try again in $remaining_lockout seconds.");
    } else {
        $_SESSION['login_attempts'] = 0;
    }
}

if (isset($_SESSION["user_id"])) {
    header("Location: /LoginY_SingUp/src");
    exit();
}

require "database.php";

$message = "";

if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $records = $conn->prepare("SELECT id, email, password, role FROM users WHERE email = :email");
    $records->bindParam(':email', $_POST["email"]);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    if ($results && password_verify($_POST["password"], $results["password"])) {
        $_SESSION["user_id"] = $results["id"];
        $_SESSION["role"] = $results["role"]; // Asegúrate de almacenar el rol del usuario
        $_SESSION['login_attempts'] = 0; // Reset login attempts
        header("Location: /LoginY_SingUp/src");
        exit();
    } else {
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $_SESSION['last_attempt_time'] = time();
        $message = "Wrong Email or Password";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Login</title>
</head>
<body>
<?php require 'partials/header.php'; ?>
<?php if (!empty($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<h1>Login</h1>
<span>or <a href="signup.php">Sign Up</a></span>
<form action="login.php" method="post">
    <input type="text" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <input type="submit" value="Send">
</form>
</body>
</html>
