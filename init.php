<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function verificarSesionAdmin() {
    if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
        header("Location: error.php");
        exit();
    }
}