<?php
require_once '../config/core.php';
require_once '../app/Models/Incidencia.php';
require_once '../app/Models/Equipo.php';

// Proteger la vista
if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL);
    exit();
}

$incidencias = Incidencia::obtenerTodas();
$equipos = Equipo::obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Incidencias - Telecentros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><i class="bi bi-arrow-left"></i> Volver al Dashboard</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Reportes e Incidencias Técnicas</h2>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalNuevaIncidencia">
            <i class="bi bi-exclamation-triangle"></i> Reportar Falla
        </button>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">La incidencia técnica se ha registrado correctamente. El equipo ahora figura 'Con Fallas'.</div>
    <?php endif; ?>

    <?php if(isset($_GET['resuelto'])): ?>
        <div class="alert alert-success">¡Excelente! La falla ha sido marcada como solucionada y el equipo vuelve a estar operativo.</div>
    <?php endif; ?>

    <?php if(isset($_GET['editado'])): ?>
        <div class="alert alert-info"><i class="bi bi-pencil-square"></i> El reporte de incidencia fue actualizado correctamente.</div>
    <?php endif; ?>

    <?php if(isset($_GET['eliminado'])): ?>
        <div class="alert alert-warning"><i class="bi bi-trash"></i> El reporte de falla fue eliminado permanentemente por el personal autorizado.</div>
    <?php endif; ?>
    <?php if(isset($_GET['editado'])): ?>
        <div class="alert alert-info"><i class="bi bi-pencil-square"></i> La incidencia técnica se actualizó correctamente.</div>
    <?php endif; ?>
    <!-- Tabla de Incidencias -->
    <div class="card shadow-sm border-danger">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Fecha Reporte</th>
                        <th>Equipo (QR)</th>
                        <th>Ubicación</th>
                        <th>Descripción de la Falla</th>
                        <th>Reportado por</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($incidencias as $incidencia): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($incidencia['fecha_reporte'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($incidencia['codigo_qr']); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars($incidencia['generacion']); ?></small></td>
                        <td><?php echo htmlspecialchars($incidencia['ambiente']); ?></td>
                        <td class="text-danger"><?php echo htmlspecialchars($incidencia['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['reportador']); ?></td>
                        <td>
                            <?php if($incidencia['estado_incidencia'] == 'Reportada'): ?>
                                <span class="badge bg-danger">Reportada</span>
                            <?php elseif($incidencia['estado_incidencia'] == 'En Revisión'): ?>
                                <span class="badge bg-warning text-dark">En Revisión</span>
                            <?php else: ?>
                                <span class="badge bg-success">Solucionada</span>
                            <?php endif; ?>
                        </td>
                        <td>
                        <td>
    <?php if ($_SESSION['rol'] == 'Administrador' || $_SESSION['rol'] == 'Técnico'): ?>
        <?php 
        $id_inc = $incidencia['id'];
        $id_eq  = $incidencia['equipo_id'];
        $url_resolver = "../app/Controllers/ResolverIncidenciaController.php?id=" . urlencode($id_inc) . "&equipo_id=" . urlencode($id_eq);
        $url_eliminar = "../app/Controllers/EliminarIncidenciaController.php?id=" . urlencode($id_inc) . "&equipo_id=" . urlencode($id_eq);
        ?>

        <?php if($incidencia['estado_incidencia'] != 'Solucionada'): ?>
            <a href="<?php echo htmlspecialchars($url_resolver); ?>" 
               class="btn btn-sm btn-outline-success mb-1 d-block" 
               title="Marcar como Solucionada" 
               onclick="confirmarAccion(event, '¿Solucionar incidencia?', 'El reporte pasará a estado Solucionada y el equipo volverá a estar operativo.', 'Sí, solucionar', '#198754')">
                <i class="bi bi-tools"></i> Resolver
            </a>
        <?php else: ?>
            <span class="text-muted small d-block mb-1"><i class="bi bi-check2-all"></i> Cerrado</span>
        <?php endif; ?>

        <a href="<?php echo htmlspecialchars($url_eliminar); ?>" 
           class="btn btn-sm btn-outline-danger mb-1 d-block" 
           title="Eliminar reporte" 
           onclick="confirmarAccion(event, '¿Eliminar reporte?', 'Esta acción eliminará la incidencia de forma permanente.', 'Sí, eliminar', '#dc3545')">
            <i class="bi bi-trash"></i> Eliminar
        </a>
    <?php endif; ?>
</td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if(empty($incidencias)): ?>
                        <tr><td colspan="7" class="text-center text-muted">No hay incidencias reportadas en el sistema.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Nueva Incidencia -->
<div class="modal fade" id="modalNuevaIncidencia" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content border-danger">
      <form action="../app/Controllers/IncidenciaController.php" method="POST">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Reportar Equipo Dañado</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label>Seleccionar Equipo (Buscar por QR o Especificación)</label>
                  <select name="equipo_id" class="form-select" required>
                      <option value="">Seleccione el equipo afectado...</option>
                      <?php foreach($equipos as $equipo): ?>
                          <option value="<?php echo $equipo['id']; ?>">
                              <?php echo htmlspecialchars($equipo['codigo_qr'] . " - " . $equipo['ambiente'] . " (" . $equipo['procesador'] . ")"); ?>
                          </option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="mb-3">
                  <label>Descripción detallada de la falla</label>
                  <textarea name="descripcion" class="form-control" rows="4" placeholder="Ej: El equipo no enciende, la fuente de poder emite un pitido..." required></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger"><i class="bi bi-send"></i> Enviar Reporte</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarAccion(event, titulo, texto, textoBoton, colorBoton) {
    event.preventDefault();
    const url = event.currentTarget.getAttribute('href');

    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: colorBoton,
        cancelButtonColor: '#6c757d',
        confirmButtonText: textoBoton,
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
<script>
function confirmarAccion(event, titulo, texto, textoBoton, colorBoton) {
    event.preventDefault();
    const url = event.currentTarget.getAttribute('href');

    if (typeof Swal === 'undefined') {
        if (confirm(titulo + '\n' + texto)) {
            window.location.href = url;
        }
        return;
    }

    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: colorBoton,
        cancelButtonColor: '#6c757d',
        confirmButtonText: textoBoton,
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
</body>
</html>