<?php
session_start();
require 'database.php';

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Eliminar el usuario
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);

    if ($stmt->execute()) {
        header('Location: admin.php');
        exit;
    } else {
        echo "Sorry, there was an issue deleting the user";
    }
}
?>
