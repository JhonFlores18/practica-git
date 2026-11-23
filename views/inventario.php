<?php
require_once '../config/core.php';
require_once '../app/Models/Equipo.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Técnico')) {
    header("Location: " . BASE_URL);
    exit();
}

$equipos = Equipo::obtenerTodos();
$ambientes = Equipo::obtenerAmbientes();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Telecentros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><i class="bi bi-arrow-left"></i> Volver al Dashboard</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Inventario de Equipos</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoEquipo">
            <i class="bi bi-plus-circle"></i> Registrar Equipo
        </button>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">Equipo registrado y Código QR generado exitosamente.</div>
    <?php endif; ?>

    <?php if(isset($_GET['editado'])): ?>
        <div class="alert alert-info"><i class="bi bi-pencil-square"></i> Los datos del equipo se actualizaron correctamente.</div>
    <?php endif; ?>

    <?php if(isset($_GET['eliminado'])): ?>
        <div class="alert alert-warning"><i class="bi bi-trash"></i> El equipo fue eliminado del inventario.</div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Ambiente</th>
                        <th>Generación</th>
                        <th>Especificaciones</th>
                        <th>Estado</th>
                        <th>Código QR</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($equipos as $equipo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($equipo['id']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['ambiente']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($equipo['generacion']); ?></span></td>
                        <td>
                            <small>
                                <b>CPU:</b> <?php echo htmlspecialchars($equipo['procesador']); ?><br>
                                <b>RAM:</b> <?php echo htmlspecialchars($equipo['memoria_ram']); ?> | <b>Disco:</b> <?php echo htmlspecialchars($equipo['almacenamiento']); ?>
                            </small>
                        </td>
                        <td>
                            <?php if($equipo['estado_operativo'] == 'Con Fallas'): ?>
                                <span class="badge bg-danger"><?php echo htmlspecialchars($equipo['estado_operativo']); ?></span>
                            <?php else: ?>
                                <span class="badge bg-success"><?php echo htmlspecialchars($equipo['estado_operativo']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-dark" onclick="mostrarQR('<?php echo htmlspecialchars($equipo['codigo_qr']); ?>')">
                                <i class="bi bi-qr-code"></i> Ver QR
                            </button>
                        </td>
                        <td>
                        <a href="editar_equipo.php?id=<?php echo urlencode($equipo['id']); ?>" 
   class="btn btn-sm btn-outline-primary mb-1"
   title="Editar equipo"
   onclick="confirmarAccion(event, '¿Editar equipo?', 'Se abrirá el formulario para modificar las especificaciones de este equipo.', 'Continuar', '#0d6efd')">
    <i class="bi bi-pencil-square"></i> Editar
</a>

<?php if ($_SESSION['rol'] == 'Administrador' || $_SESSION['rol'] == 'Técnico'): ?>
    <a href="../app/Controllers/EliminarEquipoController.php?id=<?php echo urlencode($equipo['id']); ?>" 
       class="btn btn-sm btn-outline-danger mb-1"
       title="Eliminar equipo"
       onclick="confirmarAccion(event, '¿Eliminar equipo?', 'Se eliminará este equipo del inventario de forma permanente.', 'Sí, eliminar', '#dc3545')">
        <i class="bi bi-trash"></i> Eliminar
    </a>
<?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if(empty($equipos)): ?>
                        <tr><td colspan="7" class="text-center text-muted">No hay equipos registrados en el inventario.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Nuevo Equipo -->
<div class="modal fade" id="modalNuevoEquipo" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../app/Controllers/EquipoController.php" method="POST">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Registrar Computadora</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label>Ambiente (Aula)</label>
                  <select name="ambiente_id" class="form-select" required>
                      <option value="">Seleccione un aula...</option>
                      <?php foreach($ambientes as $amb): ?>
                          <option value="<?php echo $amb['id']; ?>"><?php echo htmlspecialchars($amb['nombre']); ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="mb-3">
                  <label>Generación del Equipo</label>
                  <select name="generacion" class="form-select" required>
                      <option value="4ta Generación">4ta Generación</option>
                      <option value="5ta Generación">5ta Generación</option>
                      <option value="10ma Generación">10ma Generación</option>
                      <option value="11va Generación">11va Generación</option>
                  </select>
              </div>
              <div class="mb-3">
                  <label>Procesador (Ej: Core i5)</label>
                  <input type="text" name="procesador" class="form-control" required>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label>Memoria RAM (Ej: 8GB)</label>
                      <input type="text" name="memoria_ram" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label>Almacenamiento (Ej: 500GB SSD)</label>
                      <input type="text" name="almacenamiento" class="form-control" required>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar y Generar QR</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para Mostrar el QR -->
<div class="modal fade" id="modalQR" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content text-center">
      <div class="modal-header">
        <h5 class="modal-title">Etiqueta QR</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <img id="imgQR" src="" alt="Código QR" class="img-fluid border p-2 mb-2">
        <p class="text-muted small">ID: <span id="textQRId" class="fw-bold"></span></p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function mostrarQR(codigo) {
    document.getElementById('imgQR').src = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" + encodeURIComponent(codigo);
    document.getElementById('textQRId').innerText = codigo;
    var myModal = new bootstrap.Modal(document.getElementById('modalQR'));
    myModal.show();
}
</script>
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
</body>
</html>