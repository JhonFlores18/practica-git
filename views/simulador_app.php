<?php
require_once '../config/core.php';
require_once '../app/Models/Equipo.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL);
    exit();
}

$equipos = Equipo::obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Simulador App Móvil - Telecentros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-dark text-white d-flex align-items-center vh-100">

<!-- Simulamos la pantalla de un celular -->
<div class="container" style="max-width: 400px;">
    <div class="card bg-light text-dark shadow-lg" style="border-radius: 30px; border: 8px solid #333;">
        <div class="card-header bg-primary text-white text-center rounded-top" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <i class="bi bi-wifi"></i> App Técnico Offline
        </div>
        <div class="card-body text-center p-4">
            <h1 class="display-1 text-warning mb-3"><i class="bi bi-phone-vibrate"></i></h1>
            <h5 class="card-title">Sincronización Diferida</h5>
            <p class="card-text text-muted small mb-4">El sistema ha detectado conexión a la red LAN de la Unidad Educativa. Hay reportes pendientes de envío.</p>
            
            <div class="mb-3 text-start">
                <label class="form-label small fw-bold">Seleccionar Equipo (Escaneo QR Simulado):</label>
                <select id="equipoOffline" class="form-select form-select-sm">
                    <?php foreach($equipos as $equipo): ?>
                        <option value="<?php echo $equipo['id']; ?>"><?php echo $equipo['codigo_qr']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button id="btnSincronizar" class="btn btn-success w-100 btn-lg rounded-pill" onclick="sincronizarDatos()">
                <i class="bi bi-cloud-arrow-up"></i> Sincronizar Ahora
            </button>
            
            <div id="mensajeApi" class="alert mt-3 d-none small"></div>
        </div>
        <div class="card-footer text-center bg-white rounded-bottom">
            <a href="dashboard.php" class="text-decoration-none text-muted small">Volver al PC (Dashboard)</a>
        </div>
    </div>
</div>

<script>
function sincronizarDatos() {
    let btn = document.getElementById('btnSincronizar');
    let msj = document.getElementById('mensajeApi');
    let equipoId = document.getElementById('equipoOffline').value;
    
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
    btn.disabled = true;

    // Construimos el paquete JSON que enviaría Kotlin
    let paqueteJSON = {
        "incidencias": [
            {
                "equipo_id": equipoId,
                "usuario_id": <?php echo $_SESSION['usuario_id']; ?>,
                "descripcion": "El monitor parpadea constantemente y no da imagen clara."
            }
        ]
    };

    // Enviamos el JSON mediante Fetch (AJAX) a nuestra API
    fetch('../app/Controllers/ApiController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(paqueteJSON)
    })
    .then(response => response.json())
    .then(data => {
        msj.classList.remove('d-none', 'alert-danger');
        msj.classList.add('alert-success');
        msj.innerHTML = '<i class="bi bi-check-circle"></i> ' + data.mensaje;
        btn.innerHTML = '<i class="bi bi-cloud-arrow-up"></i> Sincronizar Ahora';
        btn.disabled = false;
    })
    .catch(error => {
        msj.classList.remove('d-none', 'alert-success');
        msj.classList.add('alert-danger');
        msj.innerHTML = 'Error de conexión al servidor local.';
        btn.innerHTML = '<i class="bi bi-cloud-arrow-up"></i> Reintentar';
        btn.disabled = false;
    });
}
</script>
</body>
</html>