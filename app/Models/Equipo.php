<?php
// Usamos __DIR__ para asegurar que siempre encuentre la ruta correcta
require_once __DIR__ . '/../../config/database.php';

class Equipo {
    public static function obtenerTodos() {
        global $pdo;
        $stmt = $pdo->query("SELECT e.*, a.nombre as ambiente 
                             FROM equipos e 
                             JOIN ambientes a ON e.ambiente_id = a.id 
                             ORDER BY e.id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function crear($ambiente_id, $codigo_qr, $generacion, $procesador, $ram, $almacenamiento) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO equipos (ambiente_id, codigo_qr, generacion, procesador, memoria_ram, almacenamiento) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$ambiente_id, $codigo_qr, $generacion, $procesador, $ram, $almacenamiento]);
    }

    public static function obtenerAmbientes() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM ambientes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>