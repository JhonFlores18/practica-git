<?php
require_once '../../config/core.php';
require_once '../Models/Equipo.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Técnico')) {
    header("Location: " . BASE_URL);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ambiente_id = $_POST['ambiente_id'];
    $generacion = $_POST['generacion'];
    $procesador = $_POST['procesador'];
    $ram = $_POST['memoria_ram'];
    $almacenamiento = $_POST['almacenamiento'];
    
    // Generar un identificador único para el QR (Ej: TEL-A8F3B9)
    $codigo_qr = 'TEL-' . strtoupper(substr(md5(uniqid()), 0, 6));

    // Guardar en la base de datos
    Equipo::crear($ambiente_id, $codigo_qr, $generacion, $procesador, $ram, $almacenamiento);

    header("Location: " . BASE_URL . "views/inventario.php?success=1");
    exit();
}
?>