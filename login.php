<?php
include 'conexion.php'; // Incluir la conexión a la base de datos
session_start();

// Redirigir si ya está autenticado
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['rol_id'] == 1) {
        header("Location: cliente.php");
    } elseif ($_SESSION['rol_id'] == 2) {
        header("Location: admin.php");
    } elseif ($_SESSION['rol_id'] == 3) {
        header("Location: profesor.php");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar el usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Establecer la sesión de usuario
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol_id'] = $user['rol_id'];
            $_SESSION['nombre'] = $user['nombre'];

            // Redirigir según el rol del usuario
            if ($user['rol_id'] == 1) {
                header("Location: cliente.php"); // Página para clientes
            } elseif ($user['rol_id'] == 2) {
                header("Location: admin.php"); // Página para administradores
            } elseif ($user['rol_id'] == 3) {
                header("Location: profesor.php"); // Página para profesores
            }
            exit();
        } else {
            header("Location: index.php?error=" . urlencode("Contraseña incorrecta."));
            exit();
        }
    } else {
        header("Location: index.php?error=" . urlencode("No se encontró una cuenta con este correo."));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Fit Manager</title>
    <link rel="stylesheet" href="assets/css/login-style.css"> <!-- Enlaza tu archivo CSS aquí -->
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Iniciar Sesión</button><br>
    </form>

    <p>¿No tienes una cuenta? <a href="registro.php"><br>Regístrate aquí</a></p><br><br>
    <p><a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a></p>
</body>
</html>
