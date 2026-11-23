<?php
require_once '../../config/core.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT u.id, u.nombre, u.password, r.nombre AS rol_nombre 
                           FROM usuarios u 
                           JOIN roles r ON u.rol_id = r.id 
                           WHERE u.correo = ? AND u.estado = 1");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && $password === $usuario['password']) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol_nombre'];

        header("Location: " . BASE_URL . "views/dashboard.php");
        exit();
    } else {
        header("Location: " . BASE_URL . "index.php?error=1");
        exit();
    }
} else {
    header("Location: " . BASE_URL);
    exit();
}
?>