<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Mismo header que index.php -->
    <?php include('Estilo\header.php'); ?>
</head>
<body>
    <?php include('Estilo\menu.php'); ?>
    <main class="CONTENIDO" id="contenido">
        <h2>Bienvenido a nuestro sitio</h2>
        <?php if($conn): ?>
            <div class="alert success">✅ Conectado a la base de datos</div>
        <?php else: ?>
            <div class="alert error">❌ Error de conexión</div>
        <?php endif; ?>
        
        <p>Gestiona tu cine de forma eficiente:</p>
        <ul>
            <li>Programa funciones</li>
            <li>Controla precios de boletos</li>
            <li>Administra salas</li>
        </ul>

        <!-- Explicación al final -->
        <div class="explicacion" style="margin-top: 2rem;">
            <h3>¿Qué puedes hacer aquí?</h3>
            <p>Este sistema permite gestionar toda la operación de tu cine, desde la cartelera hasta las ventas.</p>
        </div>    
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