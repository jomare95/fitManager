<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; 
$username = "root";        
$password = "";            
$database = "fit_manager"; 

// Activar el reporte de errores de mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $database);

    // Establecer el conjunto de caracteres a utf8 para soportar caracteres especiales
    $conn->set_charset("utf8");
} catch (mysqli_sql_exception $e) {
    // Manejo de errores
    die("Conexión fallida: " . $e->getMessage());
}
?>
