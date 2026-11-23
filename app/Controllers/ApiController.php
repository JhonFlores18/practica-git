<?php
// Permitir peticiones desde cualquier origen (CORS) para la futura app móvil
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../../config/database.php';
require_once '../Models/Incidencia.php';
require_once '../Models/Equipo.php';

// Leer el JSON que envía la aplicación móvil
$json_recibido = file_get_contents("php://input");
$datos = json_decode($json_recibido, true);

if (!empty($datos) && isset($datos['incidencias'])) {
    $contador = 0;
    
    foreach ($datos['incidencias'] as $falla) {
        // En un caso real la app móvil genera el UUID offline, aquí usamos el del modelo si no viene
        $equipo_id = $falla['equipo_id'];
        $usuario_id = $falla['usuario_id']; // El técnico logueado en el celular
        $descripcion = $falla['descripcion'] . " (Reportado desde App Móvil Offline)";
        
        // Guardamos usando el modelo que ya creamos
        Incidencia::crear($equipo_id, $usuario_id, $descripcion);
        $contador++;
    }

    // Devolvemos una respuesta de éxito a la aplicación móvil
    echo json_encode([
        "status" => "success",
        "mensaje" => "Sincronización completada. $contador incidencias recibidas.",
        "codigo" => 200
    ]);
} else {
    // Error si mandan datos vacíos
    echo json_encode([
        "status" => "error",
        "mensaje" => "No se recibieron datos JSON válidos.",
        "codigo" => 400
    ]);
}
?>