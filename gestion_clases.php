<?php
session_start();
include 'conexion.php';

// Verificar si el usuario tiene el rol de administrador
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'administrador') {
    header("Location: error.php");
    exit();
}

// Manejar la eliminación de una clase
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Verificar si la clase existe antes de eliminarla
    $sql_check = "SELECT * FROM clases WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        // Proceder con la eliminación
        $sql_delete = "DELETE FROM clases WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);
        if ($stmt_delete->execute()) {
            $_SESSION['message'] = "Clase eliminada con éxito.";
        } else {
            $_SESSION['error'] = "Error al eliminar la clase.";
        }
    } else {
        $_SESSION['error'] = "La clase no existe.";
    }
    header("Location: gestion_clases.php");
    exit();
}

// Manejar la creación y actualización de clases
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $precio = floatval($_POST['precio']);
    $duracion = intval($_POST['duracion']);
    $instructor_id = intval($_POST['instructor_id']);
    
    // Validación básica de entrada
    if (!is_numeric($precio) || !is_numeric($duracion)) {
        $_SESSION['error'] = "El precio y la duración deben ser números válidos.";
        header("Location: gestion_clases.php");
        exit();
    }

    // Verificación de duplicado
    $sql_check = "SELECT * FROM clases WHERE nombre = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $nombre);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0 && !isset($_POST['id'])) {
        $_SESSION['error'] = "Ya existe una clase con este nombre.";
        header("Location: gestion_clases.php");
        exit();
    }

    if (isset($_POST['id'])) { // Modificación
        $id = intval($_POST['id']);
        $sql_update = "UPDATE clases SET nombre = ?, descripcion = ?, precio = ?, duracion = ?, instructor_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssdiii", $nombre, $descripcion, $precio, $duracion, $instructor_id, $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Clase actualizada con éxito.";
        } else {
            error_log("Error al actualizar la clase: " . $stmt->error);
            $_SESSION['error'] = "Error al actualizar la clase.";
        }
    } else { // Alta
        $sql_insert = "INSERT INTO clases (nombre, descripcion, precio, duracion, instructor_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $duracion, $instructor_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Clase creada con éxito.";
        } else {
            error_log("Error al crear la clase: " . $stmt->error);
            $_SESSION['error'] = "Error al crear la clase.";
        }
    }

    header("Location: gestion_clases.php");
    exit();
}

// Consultar clases
$sql = "SELECT c.*, i.nombre as instructor_nombre FROM clases c JOIN instructores i ON c.instructor_id = i.id ORDER BY c.nombre ASC";
$result = $conn->query($sql);

// Consultar instructores para el formulario
$sql_instructores = "SELECT id, nombre FROM instructores ORDER BY nombre ASC";
$result_instructores = $conn->query($sql_instructores);

// Manejar la edición de clases
$edit_clase = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql_edit = "SELECT * FROM clases WHERE id = ?";
    $stmt = $conn->prepare($sql_edit);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    $edit_clase = $result_edit->fetch_assoc();
}
?>

<!DOCTYPE
