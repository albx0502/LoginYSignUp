<?php
session_start();

require "database.php";

$user = null;

if (isset($_SESSION["user_id"])) {
    $records = $conn->prepare("SELECT id, email, password, role FROM users WHERE id = :id");
    $records->bindParam(':id', $_SESSION["user_id"]);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    if ($results && count($results) > 0) {
        $user = $results;
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
    <title>Welcome to your App</title>
</head>
<body>
<?php require 'partials/header.php'; ?>

<?php if (!empty($user)): ?>
    <br>Welcome: <?= htmlspecialchars($user["email"]); ?>
    <br>You are Successfully Logged In
<?php else: ?>
    <h1>Please Log in or SignUp</h1>
<?php endif; ?>
</body>
</html>
