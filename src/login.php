<?php

session_start();

if (isset($_SESSION["user_id"])){
    header("location:/LoginY_SingUp/src");
}

require "database.php";

if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $records = $conn->prepare("SELECT id,email,password FROM users WHERE email = :email");
    $records->bindParam(':email', $_POST["email"]);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = "";

    if (count($results) > 0 && password_verify($_POST["password"], $results["password"])){
        $_SESSION["user_id"] = $results["id"];
        header("Location: /LoginY_SingUp/src");
    }else{
        $message = "Wrong Email or Password";
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
    <title>Login</title>
</head>
<body>
    <?php require 'partials/header.php'; ?>
    <?php if (!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>
    <h1>Login</h1>
    <span>or <a href="signup.php">Sign Up</a> </span>

    <form action="login.php" method="post">
        <input type="text" name="email" placeholder="Enter your email">
        <input type="password" name="password" placeholder="Enter your password">
        <input type="submit" value="Send">
    </form>
</body>
</html>
