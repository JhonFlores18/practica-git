<?php
require_once '../../config/core.php';
require_once '../../config/database.php';

// Solo Administrador puede generar copias de seguridad
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'Administrador') {
    header("Location: " . BASE_URL . "views/dashboard.php?error=permisos");
    exit();
}

$backupDir = __DIR__ . '/../../backups';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$fecha = date('Y-m-d_H-i-s');
$nombreArchivo = "backup_{$dbname}_{$fecha}.sql";
$rutaArchivo = $backupDir . DIRECTORY_SEPARATOR . $nombreArchivo;

function exportarConPDO(PDO $pdo, string $dbName): string {
    $sql = "-- Respaldo generado por Sistema Hibrido\n";
    $sql .= "-- Base de datos: `{$dbName}`\n";
    $sql .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";
    $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    $tablas = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tablas as $tabla) {
        $createStmt = $pdo->query("SHOW CREATE TABLE `{$tabla}`")->fetch(PDO::FETCH_ASSOC);
        $createSql = $createStmt['Create Table'] ?? '';
        $sql .= "-- Estructura de tabla `{$tabla}`\n";
        $sql .= "DROP TABLE IF EXISTS `{$tabla}`;\n";
        $sql .= $createSql . ";\n\n";

        $filas = $pdo->query("SELECT * FROM `{$tabla}`")->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($filas)) {
            $sql .= "-- Datos de tabla `{$tabla}`\n";
            foreach ($filas as $fila) {
                $valores = array_map(function ($val) use ($pdo) {
                    if ($val === null) {
                        return 'NULL';
                    }
                    return $pdo->quote((string) $val);
                }, array_values($fila));

                $columnas = array_map(fn($col) => "`{$col}`", array_keys($fila));
                $sql .= "INSERT INTO `{$tabla}` (" . implode(', ', $columnas) . ") VALUES (" . implode(', ', $valores) . ");\n";
            }
            $sql .= "\n";
        }
    }

    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    return $sql;
}

$exportado = false;

// Intentar primero con mysqldump si existe
$candidatosMysqldump = [
    'C:\\xampp\\mysql\\bin\\mysqldump.exe',
    '/usr/bin/mysqldump',
    'mysqldump'
];

$binDump = null;
foreach ($candidatosMysqldump as $bin) {
    if ($bin === 'mysqldump' || file_exists($bin)) {
        $binDump = $bin;
        break;
    }
}

if ($binDump !== null && function_exists('exec')) {
    $cmd = escapeshellarg($binDump)
        . " --host=" . escapeshellarg($host)
        . " --user=" . escapeshellarg($user);

    if ($pass !== '') {
        $cmd .= " --password=" . escapeshellarg($pass);
    }

    $cmd .= " " . escapeshellarg($dbname) . " > " . escapeshellarg($rutaArchivo);
    $output = [];
    $code = 1;
    @exec($cmd, $output, $code);

    if ($code === 0 && file_exists($rutaArchivo) && filesize($rutaArchivo) > 0) {
        $exportado = true;
    }
}

// Respaldo limpio mediante PDO si mysqldump no aplicó
if (!$exportado) {
    $contenidoSql = exportarConPDO($pdo, $dbname);
    if (file_put_contents($rutaArchivo, $contenidoSql) !== false) {
        $exportado = true;
    }
}

if ($exportado && file_exists($rutaArchivo)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($nombreArchivo) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($rutaArchivo));
    readfile($rutaArchivo);
    exit();
}

header("Location: " . BASE_URL . "views/dashboard.php?backup=error");
exit();