<?php
require_once '../../config/core.php';
require_once '../../config/database.php';

// CONTROL DE SEGURIDAD: Solo Administrador y Técnico pueden eliminar
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Técnico')) {
    header("Location: " . BASE_URL);
    exit();
}

$incidencia_id = isset($_GET['id']) ? trim($_GET['id']) : '';
$equipo_id     = isset($_GET['equipo_id']) ? trim($_GET['equipo_id']) : '';

if ($incidencia_id !== '') {
    $stmt1 = $pdo->prepare("DELETE FROM incidencias WHERE id = ?");
    $stmt1->execute([$incidencia_id]);

    if ($equipo_id !== '') {
        $stmt2 = $pdo->prepare("UPDATE equipos SET estado_operativo = 'Operativo' WHERE id = ?");
        $stmt2->execute([$equipo_id]);
    }

    header("Location: " . BASE_URL . "views/incidencias.php?eliminado=1");
    exit();
}

header("Location: " . BASE_URL . "views/incidencias.php");
exit();