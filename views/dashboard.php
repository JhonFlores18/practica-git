<?php
require_once '../config/core.php';
require_once '../config/database.php';

// Proteger la vista
if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL);
    exit();
}

// --- NUEVO CÓDIGO (VERSIÓN 1.2): Consultas para las estadísticas ---
$total_equipos = $pdo->query("SELECT COUNT(*) FROM equipos")->fetchColumn();
$equipos_operativos = $pdo->query("SELECT COUNT(*) FROM equipos WHERE estado_operativo = 'Operativo'")->fetchColumn();
$equipos_fallas = $pdo->query("SELECT COUNT(*) FROM equipos WHERE estado_operativo = 'Con Fallas'")->fetchColumn();
// -------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Telecentros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<!-- Tu barra de navegación actual -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="#"><i class="bi bi-pc-display"></i> Sistema Híbrido</a>
        <span class="text-white">Bienvenido, <?php echo $_SESSION['nombre']; ?> (<?php echo $_SESSION['rol']; ?>)</span>
        <a href="../app/Controllers/LogoutController.php" class="btn btn-sm btn-danger">Cerrar Sesión</a>
    </div>
</nav>

<div class="container mt-4">
    
    <!-- NUEVO CÓDIGO (VERSIÓN 1.2): Tarjetas de Estadísticas (KPIs) -->
    <h4 class="mb-3 text-secondary">Resumen del Sistema</h4>
    <div class="row mb-4">
        <!-- Tarjeta Total -->
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-pc"></i> Total de Equipos</h5>
                    <h2 class="display-5 fw-bold"><?php echo $total_equipos; ?></h2>
                </div>
            </div>
        </div>
        <!-- Tarjeta Operativos -->
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-check-circle"></i> Operativos</h5>
                    <h2 class="display-5 fw-bold"><?php echo $equipos_operativos; ?></h2>
                </div>
            </div>
        </div>
        <!-- Tarjeta Con Fallas -->
        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-exclamation-triangle"></i> Con Fallas</h5>
                    <h2 class="display-5 fw-bold"><?php echo $equipos_fallas; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN NUEVO CÓDIGO -->

    <hr>
    <h4 class="mb-3 text-secondary mt-4">Módulos de Gestión</h4>

    <!-- Aquí debajo mantienes los botones/tarjetas grandes que ya tenías para entrar al Inventario y a Incidencias -->
    <div class="row">
         <!-- ... (Tu código existente de los botones de Inventario e Incidencias) ... -->
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<div class="container mt-5">
    <div class="row">
        
        <?php if ($_SESSION['rol'] == 'Administrador' || $_SESSION['rol'] == 'Técnico'): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 text-primary"><i class="bi bi-motherboard"></i></h1>
                    <h5 class="card-title mt-3">Inventario de Equipos</h5>
                    <p class="card-text text-muted">Gestión de computadoras (4ta a 11va generación).</p>
                    <a href="inventario.php" class="btn btn-outline-primary">Gestionar Inventario</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 text-danger"><i class="bi bi-exclamation-triangle"></i></h1>
                    <h5 class="card-title mt-3">Reportes e Incidencias</h5>
                    <p class="card-text text-muted">Registro de fallas en los equipos.</p>
                    <a href="incidencias.php" class="btn btn-outline-danger">Registrar Falla</a>
                </div>
            </div>
        </div>

        <?php if ($_SESSION['rol'] == 'Administrador'): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 text-success"><i class="bi bi-qr-code"></i></h1>
                    <h5 class="card-title mt-3">Panel Gerencial y QR</h5>
                    <p class="card-text text-muted">Estadísticas y generador de etiquetas.</p>
                    <a href="#" class="btn btn-outline-success">Ir al Panel</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php if ($_SESSION['rol'] == 'Administrador'): ?>
    <a href="../app/Controllers/BackupController.php" class="btn btn-outline-dark">
        <i class="bi bi-cloud-arrow-down"></i> Descargar Backup
    </a>
<?php endif; ?>
</body>
</html>