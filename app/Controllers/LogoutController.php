<?php
require_once '../../config/core.php';
session_destroy();
header("Location: " . BASE_URL);
exit();
?>