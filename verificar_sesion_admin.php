<?php
session_start();

// Incluir el archivo donde está definida la función verificarSesionAdmin
require_once 'verificar_sesion_admin.php'; // Asegúrate de ajustar el nombre del archivo según corresponda

// Verificar la sesión de administrador
verificarSesionAdmin();



// Función para verificar si el usuario es administrador (rol_id = 2)
function verificarSesionAdmin() {
    if (!isset($_SESSION['rol_id'])) {
        echo "No está configurado rol_id en la sesión.";
        header("Location: error.php");
        exit();
    }

    if ($_SESSION['rol_id'] != 2) {
        echo "rol_id en la sesión es: " . $_SESSION['rol_id'];
        header("Location: error.php");
        exit();
    }
}

// Llamar a la función de verificación de sesión
verificarSesionAdmin();
?>
