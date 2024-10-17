<?php
session_start();
require 'verificar_sesion_admin.php'; // This file will contain the session verification logic

// Regenerate session ID for added security
if (!isset($_SESSION['regenerated'])) {
    session_regenerate_id(true);
    $_SESSION['regenerated'] = true;
}

// HTML and logic for billing
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturación - FITMANAGER</title>
    <link rel="stylesheet" href="assets/css/facturacion-style.css">
</head>
<body>
    <header>
        <h1>Facturación - FITMANAGER</h1>
        <nav>
            <ul>
                <li><a href="admin.php">Volver al Panel de Administración</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Generar Facturas</h2>
        <!-- Facturación content goes here -->
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> FITMANAGER - Todos los derechos reservados</p>
    </footer>

    <script src="assets/js/facturacion.js"></script>
</body>
</html>
