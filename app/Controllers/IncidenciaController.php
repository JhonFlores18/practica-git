<?php
require_once '../../config/core.php';
require_once '../Models/Incidencia.php';

// Verificamos que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $equipo_id = $_POST['equipo_id'];
    $usuario_id = $_SESSION['usuario_id']; // Quien está logueado reporta la falla
    $descripcion = $_POST['descripcion'];
    
    // Guardamos la incidencia en la base de datos
    Incidencia::crear($equipo_id, $usuario_id, $descripcion);

    // Redirigimos con mensaje de éxito
    header("Location: " . BASE_URL . "views/incidencias.php?success=1");
    exit();
}
?>