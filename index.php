<?php
require_once 'config/core.php';

if (!isset($_SESSION['usuario_id'])) {
    require_once 'views/login.php';
} else {
    require_once 'views/dashboard.php';
}
?>