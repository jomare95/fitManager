<?php
session_start();
include 'conexion.php';

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['rol_id'] != 2) {
    header("Location: error.php");
    exit();
}

// Manejar la eliminación de un usuario
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql_delete = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $mensaje = "Usuario eliminado exitosamente.";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al eliminar el usuario.";
        $tipo_mensaje = "danger";
    }
}

// Manejar la creación y actualización de usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol_id = $_POST['rol_id'];

    if (isset($_POST['id']) && !empty($_POST['id'])) { // Modificación
        $id = $_POST['id'];
        if (!empty($_POST['password'])) { // Cambiar contraseña solo si se proporciona una
            $password_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql_update = "UPDATE usuarios SET nombre = ?, email = ?, password = ?, rol_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("sssii", $nombre, $email, $password_hashed, $rol_id, $id);
        } else { // No cambiar contraseña
            $sql_update = "UPDATE usuarios SET nombre = ?, email = ?, rol_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("ssii", $nombre, $email, $rol_id, $id);
        }
        if ($stmt->execute()) {
            $mensaje = "Usuario actualizado exitosamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al actualizar el usuario.";
            $tipo_mensaje = "danger";
        }
    } else { // Alta
        $password_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql_insert = "INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("sssi", $nombre, $email, $password_hashed, $rol_id);
        if ($stmt->execute()) {
            $mensaje = "Usuario creado exitosamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al crear el usuario.";
            $tipo_mensaje = "danger";
        }
    }
}

// Consultar usuarios
$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);

// Manejar la edición de usuarios
$edit_user = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql_edit = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql_edit);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    $edit_user = $result_edit->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Gestión de Usuarios</h2>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo isset($edit_user) ? 'Editar Usuario' : 'Agregar Usuario'; ?></h5>
                <form method="POST" action="usuarios.php">
                    <input type="hidden" name="id" value="<?php echo isset($edit_user) ? $edit_user['id'] : ''; ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo isset($edit_user) ? htmlspecialchars($edit_user['nombre']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($edit_user) ? htmlspecialchars($edit_user['email']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" <?php echo isset($edit_user) ? '' : 'required'; ?>>
                    </div>
                    <div class="mb-3">
                        <label for="rol_id" class="form-label">Rol:</label>
                        <select class="form-select" id="rol_id" name="rol_id" required>
                            <option value="1" <?php echo (isset($edit_user) && $edit_user['rol_id'] == 1) ? 'selected' : ''; ?>>Cliente</option>
                            <option value="2" <?php echo (isset($edit_user) && $edit_user['rol_id'] == 2) ? 'selected' : ''; ?>>Administrador</option>
                            <option value="3" <?php echo (isset($edit_user) && $edit_user['rol_id'] == 3) ? 'selected' : ''; ?>>Profesor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo isset($edit_user) ? 'Actualizar' : 'Agregar'; ?></button>
                </form>
            </div>
        </div>

        <h3 class="mb-3">Lista de Usuarios</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php
                            switch ($user['rol_id']) {
                                case 1:
                                    echo 'Cliente';
                                    break;
                                case 2:
                                    echo 'Administrador';
                                    break;
                                case 3:
                                    echo 'Profesor';
                                    break;
                                default:
                                    echo 'Desconocido';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="usuarios.php?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                            <a href="usuarios.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="admin.php">volver atras</a>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

   
</body>
</html>