<?php
include 'conexion.php'; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol_id = $_POST['rol_id']; // Este campo puede ser '1' para cliente, '2' para administrador, etc.

    // Encriptar la contraseña para mayor seguridad
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si el email ya está registrado
    $sql_check = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Este correo ya está registrado.";
    } else {
        // Insertar nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $email, $password_hashed, $rol_id);

        if ($stmt->execute()) {
            $success_message = "Registro exitoso. Ahora puedes iniciar sesión.";
            header("Location: login.php?success=1"); // Redirigir al login tras registrarse
            exit();
        } else {
            $error_message = "Error en el registro: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Fit Manager</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Vincula tu archivo CSS -->
</head>
<body>
    <h2>Registro de Usuarios</h2>

    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="registro.php">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br>

        <label for="rol_id">Rol:</label>
        <select name="rol_id" required>
            <option value="1">Cliente</option>
            <option value="2">Administrador</option>
            <option value="3">Profesor</option>
        </select><br>

        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
