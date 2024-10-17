<?php
session_start();

// Verificar si la sesión está iniciada y el usuario tiene el rol de cliente (rol_id = 1)
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: error.php");
    exit();
}

// Regenerar el ID de sesión para mayor seguridad
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página del Cliente</title>
    <link rel="stylesheet" href="assets/css/cliente-style.css"> <!-- Vincula tu archivo CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!-- jsPDF -->
</head>
<body>
    <h2>Bienvenido, <?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario'; ?>. Esta es tu página de cliente.</h2>
    <p>Aquí puedes ver tus clases, historial, y más.</p>
    <a href="clases.php">Revisa aquí tus clases</a>

    <!-- BMI Calculation Start -->
    <div class="container-fluid position-relative bmi mt-5" style="margin-bottom: 90px;">
        <div class="container">
            <div class="row px-3 align-items-center">
                <div class="col-md-6">
                    <div class="pr-md-3 d-none d-md-block">
                        <h4 class="text-primary">Body Mass Index</h4>
                        <h4 class="display-4 text-white font-weight-bold mb-4">¿Qué es el Índice de Masa Corporal?</h4>
                        <p class="m-0 text-white">El Índice de Masa Corporal (IMC) es una medida que utiliza tu peso y altura para determinar si estás en un rango de peso saludable.</p>
                    </div>
                </div>
                <div class="col-md-6 bg-secondary py-5">
                    <div class="py-5 px-3">
                        <h1 class="mb-4 text-white">Calcula tu Índice de Masa Corporal</h1>
                        <form>
                            <div class="form-row">
                                <div class="col form-group">
                                    <input type="text" id="peso" class="form-control form-control-lg bg-dark text-white" placeholder="Peso (KG)">
                                </div>
                                <div class="col form-group">
                                    <input type="text" id="altura" class="form-control form-control-lg bg-dark text-white" placeholder="Altura (CM)">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col form-group">
                                    <input type="text" id="edad" class="form-control form-control-lg bg-dark text-white" placeholder="Edad">
                                </div>
                                <div class="col form-group">
                                    <select id="genero" class="custom-select custom-select-lg bg-dark text-muted">
                                        <option value="" disabled selected>Género</option>
                                        <option value="Hombre">Hombre</option>
                                        <option value="Mujer">Mujer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input type="button" class="btn btn-lg btn-block btn-dark border-light" value="Calcular">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BMI Calculation End -->

    <!-- Script de cálculo de IMC -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function calcularIMC() {
                // Obtener valores de los campos
                const peso = parseFloat(document.getElementById("peso").value);
                const altura = parseFloat(document.getElementById("altura").value) / 100; // Convertir cm a metros
                const edad = parseInt(document.getElementById("edad").value);
                const genero = document.getElementById("genero").value;

                // Validación básica de los datos ingresados
                if (isNaN(peso) || isNaN(altura) || isNaN(edad) || !genero) {
                    alert("Por favor, ingresa todos los campos correctamente.");
                    return;
                }

                // Cálculo del IMC
                const imc = (peso / (altura * altura)).toFixed(2);

                // Determinar la categoría del IMC
                let categoria = "";
                if (imc < 18.5) {
                    categoria = "Bajo peso";
                } else if (imc >= 18.5 && imc < 24.9) {
                    categoria = "Peso normal";
                } else if (imc >= 25 && imc < 29.9) {
                    categoria = "Sobrepeso";
                } else {
                    categoria = "Obesidad";
                }

                // Mostrar el resultado en una ventana emergente
                alert(`Tu IMC es ${imc}. Categoría: ${categoria}.`);
            }

            // Asociar el evento click del botón con la función calcularIMC
            document.querySelector('input[type="button"]').addEventListener('click', calcularIMC);
        });
    </script>

    <!-- Sección para seleccionar la rutina de ejercicios -->
    <h3>Genera tu rutina semanal de ejercicios</h3>
    <form id="rutinaForm">
        <div>
            <label for="lunes">Lunes:</label>
            <input type="text" id="lunes" placeholder="Ejercicio para Lunes">
        </div>
        <div>
            <label for="martes">Martes:</label>
            <input type="text" id="martes" placeholder="Ejercicio para Martes">
        </div>
        <div>
            <label for="miercoles">Miércoles:</label>
            <input type="text" id="miercoles" placeholder="Ejercicio para Miércoles">
        </div>
        <div>
            <label for="jueves">Jueves:</label>
            <input type="text" id="jueves" placeholder="Ejercicio para Jueves">
        </div>
        <div>
            <label for="viernes">Viernes:</label>
            <input type="text" id="viernes" placeholder="Ejercicio para Viernes">
        </div>
        <div>
            <label for="sabado">Sábado:</label>
            <input type="text" id="sabado" placeholder="Ejercicio para Sábado">
        </div>
        <div>
            <label for="domingo">Domingo:</label>
            <input type="text" id="domingo" placeholder="Ejercicio para Domingo">
        </div>
        <button type="button" onclick="descargarPDF()">Descargar Rutina en PDF</button> 
    </form><br><br>

    <!-- Script para generar el PDF con jsPDF -->
    <script>
        function descargarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Obtener los valores del formulario
            const lunes = document.getElementById('lunes').value || 'Sin asignar';
            const martes = document.getElementById('martes').value || 'Sin asignar';
            const miercoles = document.getElementById('miercoles').value || 'Sin asignar';
            const jueves = document.getElementById('jueves').value || 'Sin asignar';
            const viernes = document.getElementById('viernes').value || 'Sin asignar';
            const sabado = document.getElementById('sabado').value || 'Sin asignar';
            const domingo = document.getElementById('domingo').value || 'Sin asignar';

            // Añadir contenido al PDF
            doc.setFontSize(16);
            doc.text('Rutina Semanal de Ejercicio', 20, 20);
            
            doc.setFontSize(12);
            doc.text(`Lunes: ${lunes}`, 20, 40);
            doc.text(`Martes: ${martes}`, 20, 50);
            doc.text(`Miércoles: ${miercoles}`, 20, 60);
            doc.text(`Jueves: ${jueves}`, 20, 70);
            doc.text(`Viernes: ${viernes}`, 20, 80);
            doc.text(`Sábado: ${sabado}`, 20, 90);
            doc.text(`Domingo: ${domingo}`, 20, 100);

            // Descargar el PDF
            doc.save('rutina_ejercicio.pdf');
        }
    </script>

    <a href="logout.php">Cerrar Sesión</a>

</body>
</html>
