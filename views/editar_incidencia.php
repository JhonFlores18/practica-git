<?php
require_once '../config/core.php';
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Técnico')) {
    header("Location: " . BASE_URL);
    exit();
}

$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if ($id === '') {
    header("Location: " . BASE_URL . "views/incidencias.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM incidencias WHERE id = ?");
$stmt->execute([$id]);
$incidencia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$incidencia) {
    header("Location: " . BASE_URL . "views/incidencias.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Incidencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm col-md-7 mx-auto border-danger">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Editar Incidencia #<?php echo htmlspecialchars($incidencia['id']); ?></h5>
        </div>
        <div class="card-body">
            <form action="../app/Controllers/EditarIncidenciaController.php" method="POST"
                  onsubmit="return confirm('¿Deseas guardar los cambios en esta incidencia?');">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($incidencia['id']); ?>">
                <input type="hidden" name="equipo_id" value="<?php echo htmlspecialchars($incidencia['equipo_id']); ?>">

                <div class="mb-3">
                    <label class="form-label">Descripción de la falla</label>
                    <textarea name="descripcion" rows="4" class="form-control" required><?php echo htmlspecialchars($incidencia['descripcion']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado de la incidencia</label>
                    <select name="estado_incidencia" class="form-select">
                        <option value="Reportada" <?php echo ($incidencia['estado_incidencia'] == 'Reportada') ? 'selected' : ''; ?>>Reportada</option>
                        <option value="En Revisión" <?php echo ($incidencia['estado_incidencia'] == 'En Revisión') ? 'selected' : ''; ?>>En Revisión</option>
                        <option value="Solucionada" <?php echo ($incidencia['estado_incidencia'] == 'Solucionada') ? 'selected' : ''; ?>>Solucionada</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="incidencias.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-danger">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>