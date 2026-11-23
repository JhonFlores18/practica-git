<?php
require_once '../../config/core.php';
require_once '../../config/database.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Técnico')) {
    header("Location: " . BASE_URL);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id               = isset($_POST['id']) ? trim($_POST['id']) : '';
    $ambiente_id      = isset($_POST['ambiente_id']) ? trim($_POST['ambiente_id']) : '';
    $generacion       = isset($_POST['generacion']) ? trim($_POST['generacion']) : '';
    $procesador       = isset($_POST['procesador']) ? trim($_POST['procesador']) : '';
    $memoria_ram      = isset($_POST['memoria_ram']) ? trim($_POST['memoria_ram']) : '';
    $almacenamiento   = isset($_POST['almacenamiento']) ? trim($_POST['almacenamiento']) : '';
    $estado_operativo = isset($_POST['estado_operativo']) ? trim($_POST['estado_operativo']) : 'Operativo';

    if ($id !== '') {
        $sql = "UPDATE equipos
                SET ambiente_id = ?, generacion = ?, procesador = ?, memoria_ram = ?, almacenamiento = ?, estado_operativo = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ambiente_id, $generacion, $procesador, $memoria_ram, $almacenamiento, $estado_operativo, $id]);
    }

    header("Location: " . BASE_URL . "views/inventario.php?editado=1");
    exit();
}

header("Location: " . BASE_URL . "views/inventario.php");
exit();