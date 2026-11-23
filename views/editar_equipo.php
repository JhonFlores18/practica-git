<?php
require_once '../config/core.php';
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Técnico')) {
    header("Location: " . BASE_URL);
    exit();
}

$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if ($id === '') {
    header("Location: " . BASE_URL . "views/inventario.php");
    exit();
}

$stmt = $pdo->prepare("SELECT e.*, a.nombre AS ambiente FROM equipos e LEFT JOIN ambientes a ON e.ambiente_id = a.id WHERE e.id = ?");
$stmt->execute([$id]);
$equipo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$equipo) {
    header("Location: " . BASE_URL . "views/inventario.php");
    exit();
}

$ambientes = $pdo->query("SELECT id, nombre FROM ambientes ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Equipo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm col-md-7 mx-auto">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Editar Equipo #<?php echo htmlspecialchars($equipo['id'] ?? ''); ?></h5>
        </div>
        <div class="card-body">
            <form id="formEditarEquipo" action="../app/Controllers/EditarEquipoController.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($equipo['id'] ?? ''); ?>">

                <div class="mb-3">
                    <label class="form-label">Ambiente / Aula</label>
                    <select name="ambiente_id" class="form-select" required>
                        <?php foreach ($ambientes as $amb): ?>
                            <option value="<?php echo htmlspecialchars($amb['id']); ?>"
                                <?php echo (($equipo['ambiente_id'] ?? '') == $amb['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($amb['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Generación</label>
                    <input type="text" name="generacion" class="form-control"
                           value="<?php echo htmlspecialchars($equipo['generacion'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Procesador (CPU)</label>
                    <input type="text" name="procesador" class="form-control"
                           value="<?php echo htmlspecialchars($equipo['procesador'] ?? ($equipo['cpu'] ?? '')); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Memoria RAM</label>
                    <input type="text" name="memoria_ram" class="form-control"
                           value="<?php echo htmlspecialchars($equipo['memoria_ram'] ?? ($equipo['ram'] ?? '')); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Almacenamiento</label>
                    <input type="text" name="almacenamiento" class="form-control"
                           value="<?php echo htmlspecialchars($equipo['almacenamiento'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado Operativo</label>
                    <select name="estado_operativo" class="form-select">
                        <option value="Operativo" <?php echo (($equipo['estado_operativo'] ?? '') == 'Operativo') ? 'selected' : ''; ?>>Operativo</option>
                        <option value="Con Fallas" <?php echo (($equipo['estado_operativo'] ?? '') == 'Con Fallas') ? 'selected' : ''; ?>>Con Fallas</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="inventario.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('formEditarEquipo').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;

    Swal.fire({
        title: '¿Guardar cambios?',
        text: 'Se actualizarán los datos técnicos del equipo en el inventario.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
</body>
</html>