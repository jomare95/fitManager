<?php
// clases.php
//session_start();
require_once 'conexion.php';
require_once 'init.php';
require_once 'cliente.php'; // Archivo con funciones auxiliares
require_once 'admin.php';

// Verificar si la sesión está iniciada y el usuario tiene el rol de administrador
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: error.php");
    exit();
}



// Verificar si la sesión está iniciada y el usuario tiene el rol de administrador
verificarSesionAdmin();

// Escapar la salida HTML
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Variable de búsqueda
$busqueda = trim($_GET['busqueda'] ?? '');

// Consulta para contar el total de clases
$sql_total = "SELECT COUNT(*) as total FROM clases c 
              JOIN instructores i ON c.instructor_id = i.id";
$params = [];
if ($busqueda) {
    $sql_total .= " WHERE c.nombre LIKE ? OR i.nombre LIKE ?";
    $busqueda_param = "%$busqueda%";
    $params = [$busqueda_param, $busqueda_param];
}

$stmt_total = $conn->prepare($sql_total);
if (!empty($params)) {
    $stmt_total->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_clases = $total_result->fetch_assoc()['total'];

// Paginación
$clases_por_pagina = 10;
$total_paginas = ceil($total_clases / $clases_por_pagina);
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina_actual - 1) * $clases_por_pagina;

// Consulta para obtener clases con limit y offset
$sql_clases = "SELECT c.id, c.nombre, c.descripcion, c.precio, c.duracion, i.nombre as instructor 
               FROM clases c 
               JOIN instructores i ON c.instructor_id = i.id";
if ($busqueda) {
    $sql_clases .= " WHERE c.nombre LIKE ? OR i.nombre LIKE ?";
}
$sql_clases .= " LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql_clases);
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)) . 'ii', ...[...$params, $clases_por_pagina, $offset]);
} else {
    $stmt->bind_param('ii', $clases_por_pagina, $offset);
}
$stmt->execute();
$result_clases = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fit Manager - Gestión de Clases</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Clases</h1>
        <form action="" method="GET" class="search-form">
            <input type="text" name="busqueda" placeholder="Buscar por nombre o instructor" value="<?= e($busqueda) ?>">
            <button type="submit">Buscar</button>
        </form>
        <a href="agregar_clase.php" class="btn">Agregar Nueva Clase</a>
        <table class="clases-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Duración (min)</th>
                    <th>Instructor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_clases->num_rows > 0): ?>
                    <?php while ($clase = $result_clases->fetch_assoc()): ?>
                        <tr>
                            <td><?= e($clase['nombre']) ?></td>
                            <td><?= e($clase['descripcion']) ?></td>
                            <td>$<?= number_format($clase['precio'], 2) ?></td>
                            <td><?= e($clase['duracion']) ?></td>
                            <td><?= e($clase['instructor']) ?></td>
                            <td>
                                <a href="editar_clase.php?id=<?= $clase['id'] ?>" class="btn btn-small">Editar</a>
                                <a href="eliminar_clase.php?id=<?= $clase['id'] ?>" class="btn btn-small btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar esta clase?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No se encontraron clases.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Paginación -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?= $i ?>&busqueda=<?= e($busqueda) ?>" <?= $i == $pagina_actual ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
        </div>
        
        <a href="dashboard_admin.php" class="btn">Volver al Dashboard</a>
    </div>
    <script src="assets/js/clases.js"></script>
</body>
</html>
