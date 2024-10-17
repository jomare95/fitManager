<?php
session_start();

// Verificar si el usuario tiene el rol de profesor (rol_id = 3)
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 3) {
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página del Profesor</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Vincula tu archivo CSS -->
</head>
<body>
    <h2>Página del Profesor</h2>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>. Aquí puedes gestionar tus clases, horarios, y más.</p>
    
    <nav>
        <ul>
            <li><a href="mis_clases.php">Mis Clases</a></li>
            <li><a href="mis_horarios.php">Mis Horarios</a></li>
            <li><a href="perfil.php">Editar Perfil</a></li>
        </ul>
    </nav>

    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
