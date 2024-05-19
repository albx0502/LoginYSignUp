<?php
session_start();

require "database.php";

if (isset($_SESSION["user_id"])){
    $records = $conn->prepare("SELECT id,email,password FROM users WHERE id = :id");
    $records->bindParam(':id', $_SESSION["user_id"]);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0){
        $user = $results;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Welcome to your App</title>
</head>
<body>
    <?php require 'partials/header.php'; ?>

    <?php if (!empty($user)): ?>
    <br>Welcome: <?= $user["email"]; ?>
    <br>You are Successfully Logged In
    <a href="logout.php">
        Logout
    </a>
    <?php else: ?>

    <h1>Please Log in or SignUp</h1>

    <a href="login.php">Login</a> or
    <a href="signup.php">Signup</a>
    <?php endif; ?>
</body>
</html>