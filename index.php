<?php include('conexion.php'); ?>
<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"] !== "sa") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include('Estilo\header.php'); ?>
</head>
<body>
<?php include('Estilo\menu.php'); ?>
<main class="CONTENIDO" id="contenido">
    <h2>Bienvenido al Sistema de Gestión de Cine</h2>

    <?php if($conn): ?>
        <div class="alert success">✅ Conectado a la base de datos</div>
    <?php else: ?>
        <div class="alert error">❌ Error de conexión</div>
    <?php endif; ?>

    <section style="margin-top: 2rem;">
        <h3>🎯 Propósito del Sistema</h3>
        <p>
            Este sistema está diseñado para apoyar al personal administrativo en la operación interna de un cine. 
            Su propósito es facilitar el registro, organización y mantenimiento de funciones, películas, salas, precios, descuentos y usuarios desde un solo lugar de manera clara y eficiente.
        </p>
    </section>

    <section style="margin-top: 2rem;">
        <h3>🛠 Funcionalidades Principales</h3>
        <ul>
            <li><strong>Inicio:</strong> Página de bienvenida que verifica la conexión a la base de datos y presenta una introducción general al sistema.</li>
            <li><strong>Cartelera:</strong> Visualiza las funciones programadas, permitiendo consultar qué películas se proyectan, en qué salas y horarios.</li>
            <li><strong>Agregar Sala:</strong> Permite registrar nuevas salas, indicando su número e información de capacidad. Estas salas se utilizarán en las funciones programadas.</li>
            <li><strong>Agregar Película:</strong> Registra nuevas películas incluyendo título, duración y clasificación oficial. Esta información estará disponible para programarlas en la cartelera.</li>
            <li><strong>Programar Función:</strong> Crea funciones asignando una película, sala, fecha y hora. Es el módulo que vincula los recursos audiovisuales y físicos del cine.</li>
            <li><strong>Configuración de Precios:</strong> Establece o modifica los precios de los boletos según tipo de cliente (adulto, niño, estudiante, tercera edad), permitiendo ajustes dinámicos.</li>
            <li><strong>Configuración de Descuentos:</strong> Administra promociones especiales, ya sea por fechas específicas, tipo de cliente u otras políticas internas.</li>
            <li><strong>Configuración de Usuarios:</strong> Gestiona el acceso al sistema: creación de cuentas, cambios de contraseña y activación/desactivación de usuarios.</li>
        </ul>
    </section>

    <section style="margin-top: 2rem;">
        <h3>🔌 Conectividad y Base de Datos</h3>
        <p>
            Todas las funcionalidades se respaldan con una base de datos SQL. La conexión está centralizada mediante un archivo llamado <code>conexion.php</code>,
            lo cual garantiza consistencia y mantenimiento eficiente del sistema.
        </p>
    </section>

    <section style="margin-top: 2rem;">
        <h3>📌 Consideraciones Finales</h3>
        <p>
            Este sistema representa una solución administrativa moderna, modular y escalable. Aunque actualmente está orientado a la gestión interna,
            su arquitectura permite la integración futura con módulos de venta de boletos, análisis de asistencia, reportes financieros e incluso aplicaciones para el cliente final.
        </p>
        <p>
            Se recomienda que el personal autorizado reciba una inducción básica sobre el uso de cada sección para garantizar una operación sin errores y una mejor experiencia para los usuarios del cine.
        </p>
    </section>
</main>

<script>
    function toggleMenu() {
        const menu = document.getElementById('menu');
        const contenido = document.getElementById('contenido');
        const hamburger = document.querySelector('.hamburger');
        
        menu.classList.toggle('active');
        contenido.classList.toggle('active');
        hamburger.classList.toggle('active');
    }
</script>
</body>
</html>
