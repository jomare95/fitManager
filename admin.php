<?php
session_start();

// Función para verificar la sesión y el rol del usuario
function verificarSesionAdmin() {
    if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
        header("Location: error.php");
        exit();
    }
}

// Verificar la sesión al inicio del script
verificarSesionAdmin();

// Regenerar el ID de sesión para mayor seguridad
if (!isset($_SESSION['regenerated'])) {
    session_regenerate_id(true);
    $_SESSION['regenerated'] = true;
}

// Función para escapar la salida HTML
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - FITMANAGER</title>
    <link rel="stylesheet" href="assets/css/admin-style.css">
</head>
<body>
    <header>
        <h1>Panel de Administración - FITMANAGER</h1>
        <nav>
            <ul>
                <li><a href="usuarios.php">Gestión de Usuarios</a></li>
                <li><a href="clases.php">Gestión de Clases</a></li>
                <li><a href="facturacion.php">Facturación</a></li> <!-- New link to billing page -->
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Bienvenido, <?php echo e($_SESSION['nombre'] ?? 'Administrador'); ?></h2>
        <p>Aquí puedes gestionar los usuarios, clases y más aspectos de FITMANAGER.</p>
        
        <!-- Aquí puedes agregar más contenido del panel, como resúmenes o accesos rápidos -->
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> FITMANAGER - Todos los derechos reservados</p>
    </footer>

    <script src="assets/js/admin.js"></script>
</body>
</html>