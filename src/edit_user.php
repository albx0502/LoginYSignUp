<?php
session_start();
require 'database.php';

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$message = '';
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Obtener la información del usuario
    $records = $conn->prepare("SELECT id, email, role FROM users WHERE id = :id");
    $records->bindParam(':id', $user_id);
    $records->execute();
    $user = $records->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $message = 'User not found';
    }
}

if (!empty($_POST)) {
    $email = $_POST['email'];
    $role = $_POST['role'];
    $user_id = $_POST['id'];

    // Actualizar la información del usuario
    $stmt = $conn->prepare("UPDATE users SET email = :email, role = :role WHERE id = :id");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $user_id);

    if ($stmt->execute()) {
        $message = 'User updated successfully';
        header('Location: admin.php');
        exit;
    } else {
        $message = 'Sorry, there was an issue updating the user';
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
    <title>Edit User</title>
</head>
<body>
<?php require 'partials/header.php'; ?>
<h1>Edit User</h1>
<?php if (!empty($message)): ?>
    <p><?= htmlspecialchars($message); ?></p>
<?php endif; ?>
<?php if ($user): ?>
    <form action="edit_user.php?id=<?= htmlspecialchars($user['id']); ?>" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
        <input type="text" name="email" value="<?= htmlspecialchars($user['email']); ?>" placeholder="Email">
        <select name="role">
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
        </select>
        <input type="submit" value="Update">
    </form>
<?php endif; ?>
</body>
</html>
