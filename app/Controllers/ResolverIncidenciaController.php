<?php
require_once '../../config/core.php';
require_once '../../config/database.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Técnico')) {
    header("Location: " . BASE_URL);
    exit();
}

if (isset($_GET['id']) && isset($_GET['equipo_id'])) {
    $incidencia_id = $_GET['id'];
    $equipo_id = $_GET['equipo_id'];

    // 1. Actualizar la incidencia a 'Solucionada'
    $stmt1 = $pdo->prepare("UPDATE incidencias SET estado_incidencia = 'Solucionada' WHERE id = ?");
    $stmt1->execute([$incidencia_id]);

    // 2. Volver a poner el equipo como 'Operativo'
    $stmt2 = $pdo->prepare("UPDATE equipos SET estado_operativo = 'Operativo' WHERE id = ?");
    $stmt2->execute([$equipo_id]);

    header("Location: " . BASE_URL . "views/incidencias.php?resuelto=1");
    exit();
}
?>