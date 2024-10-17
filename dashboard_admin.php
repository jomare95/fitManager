<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'administrador') {
    header("Location: error.php");
    exit();
}

// Regenerar el ID de sesión para mayor seguridad
session_regenerate_id(true);

// Obtener estadísticas en una sola consulta
$sql = "SELECT 
           (SELECT COUNT(*) FROM usuarios) as total_usuarios, 
           (SELECT COUNT(*) FROM clases) as total_clases, 
           (SELECT COUNT(*) FROM horarios) as total_horarios, 
           (SELECT COUNT(*) FROM asistencias WHERE asistio = 1) as total_asistencias";

$result = $conn->query($sql);
if ($result) {
    $stats = $result->fetch_assoc();
    $total_usuarios = $stats['total_usuarios'];
    $total_clases = $stats['total_clases'];
    $total_horarios = $stats['total_horarios'];
    $total_asistencias = $stats['total_asistencias'];
} else {
    die("Error en la consulta: " . $conn->error);
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fit Manager - Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido, Administrador</h2>
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Usuarios</h3>
                <p><?php echo $total_usuarios; ?></p>
            </div>
            <div class="stat-card">
                <h3>Clases</h3>
                <p><?php echo $total_clases; ?></p>
            </div>
            <div class="stat-card">
                <h3>Horarios</h3>
                <p><?php echo $total_horarios; ?></p>
            </div>
            <div class="stat-card">
                <h3>Asistencias</h3>
                <p><?php echo $total_asistencias; ?></p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="gestionar_usuarios.php" class="btn">Gestionar Usuarios</a>
            <a href="clases.php" class="btn">Gestionar Clases</a>
            <a href="ver_reportes.php" class="btn">Ver Reportes</a>
        </div>
        <a href="logout.php" class="btn btn-logout">Cerrar Sesión</a>
    </div>
</body>
</html>
