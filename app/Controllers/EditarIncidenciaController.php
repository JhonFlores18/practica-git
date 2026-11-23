<?php
require_once '../../config/core.php';
require_once '../../config/database.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Técnico')) {
    header("Location: " . BASE_URL);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $equipo_id = isset($_POST['equipo_id']) ? trim($_POST['equipo_id']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
    $estado = isset($_POST['estado_incidencia']) ? trim($_POST['estado_incidencia']) : 'Reportada';

    $estadosPermitidos = ['Reportada', 'En Revisión', 'Solucionada'];
    if (!in_array($estado, $estadosPermitidos, true)) {
        $estado = 'Reportada';
    }

    if ($id !== '' && $descripcion !== '') {
        $stmt = $pdo->prepare("UPDATE incidencias SET descripcion = ?, estado_incidencia = ? WHERE id = ?");
        $stmt->execute([$descripcion, $estado, $id]);

        if ($equipo_id !== '') {
            $nuevoEstadoEquipo = ($estado === 'Solucionada') ? 'Operativo' : 'Con Fallas';
            $stmtEq = $pdo->prepare("UPDATE equipos SET estado_operativo = ? WHERE id = ?");
            $stmtEq->execute([$nuevoEstadoEquipo, $equipo_id]);
        }
    }

    header("Location: " . BASE_URL . "views/incidencias.php?editado=1");
    exit();
}

header("Location: " . BASE_URL . "views/incidencias.php");
exit();