<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Fit Manager</title>
    <link rel="stylesheet" > <!-- Asegúrate de tener un archivo CSS para estilos básicos -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            color: #d9534f; /* Color del encabezado de error */
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px; /* Tamaño de la fuente del mensaje */
            color: #333;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            background-color: #0275d8; /* Color del botón */
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px; /* Esquinas redondeadas */
        }
        a:hover {
            background-color: #025aa5; /* Color al pasar el ratón */
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Acceso Denegado</h1>
        <p>No tienes permiso para acceder a esta página.</p>
        <a href="login.php">Volver a la pantalla anterior</a> <!-- Enlace al inicio de sesión -->
    </div>
</body>
</html>
