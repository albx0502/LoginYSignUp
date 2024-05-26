<?php
require 'database.php';
require 'csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

generateCsrfToken();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    if (!empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm_password"])) {
        if ($_POST["password"] !== $_POST["confirm_password"]) {
            $message = "Passwords do not match";
        } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $_POST["password"])) {
        $message = "Password must be at least 8 characters long, contain at least one number and one uppercase letter";
        } else {
            $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $_POST["email"]);
            $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password);

            $role = 'user';
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_POST['role'])) {
                $role = $_POST['role'];
            }
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                $message = "Signup Successful";
                // Optionally log the user in and redirect to appropriate page
                $_SESSION["user_id"] = $conn->lastInsertId();
                $_SESSION["role"] = $role;
                header("Location: " . ($role === 'admin' ? 'admin.php' : 'index.php'));
                exit();
            } else {
                $message = "Signup Failed";
            }
        }
    } else {
        $message = "All fields are required";
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
    <title>Sign Up</title>
</head>
<body>
<?php require 'partials/header.php'; ?>
<?php if (!empty($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<h1>Sign Up</h1>
<span>or <a href="login.php">Log in</a></span>
<form action="signup.php" method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    <input type="text" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <input type="password" name="confirm_password" placeholder="Confirm your password" required>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    <?php endif; ?>
    <input type="submit" value="Send">
</form>
<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        var password = document.querySelector('input[name="password"]').value;
        var confirmPassword = document.querySelector('input[name="confirm_password"]').value;

        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            event.preventDefault();
        }
    });
</script>
</body>
</html>
