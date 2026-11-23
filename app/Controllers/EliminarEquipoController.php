<?php
require_once '../../config/core.php';
require_once '../../config/database.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Técnico')) {
    header("Location: " . BASE_URL);
    exit();
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Si tienes incidencias vinculadas a este equipo, elimina primero o asegúrate de tener ON DELETE CASCADE en MySQL
    $stmtInc = $pdo->prepare("DELETE FROM incidencias WHERE equipo_id = ?");
    $stmtInc->execute([$id]);

    $stmt = $pdo->prepare("DELETE FROM equipos WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: " . BASE_URL . "views/inventario.php?eliminado=1");
    exit();
}
?>