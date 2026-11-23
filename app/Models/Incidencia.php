<?php
require_once __DIR__ . '/../../config/database.php';

class Incidencia {
    // Generador de UUID v4 (Identificador universal para evitar choques offline/online)
    public static function generarUUID() {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function obtenerTodas() {
        global $pdo;
        // SOLUCIÓN: Agregamos i.equipo_id a la consulta SELECT
        $stmt = $pdo->query("SELECT i.id, i.equipo_id, i.descripcion, i.estado_incidencia, i.fecha_reporte, 
                                    e.codigo_qr, e.generacion, a.nombre as ambiente, u.nombre as reportador
                             FROM incidencias i 
                             JOIN equipos e ON i.equipo_id = e.id 
                             JOIN ambientes a ON e.ambiente_id = a.id
                             JOIN usuarios u ON i.usuario_id = u.id
                             ORDER BY i.fecha_reporte DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function crear($equipo_id, $usuario_id, $descripcion) {
        global $pdo;
        $uuid = self::generarUUID();
        $fecha_actual = date('Y-m-d H:i:s');
        
        $stmt = $pdo->prepare("INSERT INTO incidencias (id, equipo_id, usuario_id, descripcion, estado_incidencia, fecha_reporte) 
                               VALUES (?, ?, ?, ?, 'Reportada', ?)");
        
        // Al reportar una falla, cambiamos automáticamente el estado del equipo a 'Con Fallas'
        $stmtEquipo = $pdo->prepare("UPDATE equipos SET estado_operativo = 'Con Fallas' WHERE id = ?");
        $stmtEquipo->execute([$equipo_id]);

        return $stmt->execute([$uuid, $equipo_id, $usuario_id, $descripcion, $fecha_actual]);
    }
}
?>