<?php
require 'database.php';
require 'csrf.php';
generateCsrfToken();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    if (!empty($_POST["email"])) {
        // Envía un correo electrónico con un enlace de restablecimiento de contraseña
        // (Implementar lógica de correo)
        $message = "Password reset link sent to your email";
    } else {
        $message = "Email is required";
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
    <title>Forgot Password</title>
</head>
<body>
<?php require 'partials/header.php'; ?>
<?php if (!empty($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<h1>Forgot Password</h1>
<form action="forgot_password.php" method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    <input type="text" name="email" placeholder="Enter your email" required>
    <input type="submit" value="Send">
</form>
</body>
</html>
