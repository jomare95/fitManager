<?php
// verificar_sesion_cliente.php

// Verificar si la sesión no ha sido iniciada previamente
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función para verificar si la sesión del cliente está activa
function verificarSesionCliente() {
    // Verificar si el usuario tiene el rol de cliente (ajustar según tu lógica de roles)
    if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) { // Suponiendo que '1' es el rol de cliente
        // Redirigir a la página de inicio de sesión o a una página de error
        header("Location: login.php");
        exit();
    }

    // Regenerar el ID de la sesión para mejorar la seguridad
    session_regenerate_id(true);
}
?>
