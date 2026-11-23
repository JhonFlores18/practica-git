<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Telecentros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm border-primary border-top border-4">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4 text-primary"><i class="bi bi-pc-display"></i> Sistema Híbrido</h4>
                    
                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger text-center small py-2">Credenciales incorrectas</div>
                    <?php endif; ?>

                    <!-- SOLUCIÓN AL ERROR 404: Ruta absoluta forzada hacia tu proyecto -->
                    <form action="/SistemaHibrido/app/Controllers/AuthController.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" value="tecnico@telecentro.edu" required>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label text-muted small fw-bold">Contraseña</label>
                                <!-- Enlace de recuperación -->
                                <a href="#" class="text-decoration-none small" data-bs-toggle="modal" data-bs-target="#modalRecuperar">¿Olvidaste la contraseña?</a>
                            </div>
                            <!-- Input de contraseña con botón para mostrar/ocultar -->
                            <div class="input-group">
                                <input type="password" name="password" id="inputPassword" class="form-control" value="TecPassword2026" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="iconoPassword"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 fw-bold mt-3">Iniciar Sesión</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Recuperar Contraseña -->
<div class="modal fade" id="modalRecuperar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title text-primary"><i class="bi bi-shield-lock"></i> Recuperar Acceso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <h1 class="display-4 text-secondary mb-3"><i class="bi bi-headset"></i></h1>
                <p class="mb-4 text-muted">Para restablecer tu contraseña o solicitar nuevas credenciales de acceso, por favor comunícate con el Administrador del sistema mediante los siguientes canales:</p>
                
                <div class="d-grid gap-3 col-10 mx-auto">
                    <!-- Botón de WhatsApp con enlace directo -->
                    <a href="https://wa.me/59171517850" target="_blank" class="btn btn-success text-white fw-bold">
                        <i class="bi bi-whatsapp"></i> WhatsApp: 71517850
                    </a>
                    <!-- Correo de soporte -->
                    <a href="mailto:soporte.admin@telecentro.edu" class="btn btn-outline-primary fw-bold">
                        <i class="bi bi-envelope"></i> soporte.admin@telecentro.edu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Función para alternar la visibilidad de la contraseña
function togglePassword() {
    var input = document.getElementById("inputPassword");
    var icono = document.getElementById("iconoPassword");
    
    if (input.type === "password") {
        input.type = "text";
        icono.classList.remove("bi-eye");
        icono.classList.add("bi-eye-slash");
    } else {
        input.type = "password";
        icono.classList.remove("bi-eye-slash");
        icono.classList.add("bi-eye");
    }
}
</script>
</body>
</html>