<?php
session_start();

// Regenerar el ID de sesión para mayor seguridad
session_regenerate_id(true);

// Verificar si la sesión está iniciada y el usuario tiene el rol de cliente
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'cliente') {
    header("Location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fit Manager - Usuario</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h2>
    <p>Esta es tu pantalla principal.</p>
    <a href="clases.php">Ver Clases</a><br>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
