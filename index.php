<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página con Menú Ocultable</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Encabezado amarillo */
        .ENCABE2A-DO {
            background: #FFD700;
            color: #000000;
            padding: 1rem;
            font-size: 2rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        /* Botón hamburguesa */
        .menu-toggle {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            padding: 0.5rem;
        }

        .hamburger {
            display: block;
            width: 30px;
            height: 3px;
            background: #000;
            position: relative;
            transition: all 0.3s;
        }

        .hamburger::before,
        .hamburger::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 3px;
            background: #000;
            transition: all 0.3s;
        }

        .hamburger::before { top: -8px; }
        .hamburger::after { top: 8px; }

        /* Menú lateral oculto */
        .MENU {
            background: #1E90FF;
            width: 250px;
            height: calc(100vh - 62px);
            position: fixed;
            left: -250px;
            top: 62px;
            transition: all 0.3s;
            z-index: 1;
            padding: 1rem;
        }

        .MENU.active {
            left: 0;
        }

        .MENU ul {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            list-style: none;
        }

        .MENU a {
            color: white;
            text-decoration: none;
            padding: 1rem;
            display: block;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .MENU a:hover {
            background: #000080;
        }

        /* Contenido principal */
        .CONTENIDO {
            padding: 2rem;
            min-height: calc(100vh - 62px);
            background: #f0f8ff;
            transition: margin 0.3s;
        }

        .CONTENIDO.active {
            margin-left: 250px;
        }
    </style>
</head>
<body>
    <?php include('conexion.php'); // Incluye la conexión aquí ?>
    <header class="ENCABE2A-DO">
        <button class="menu-toggle" onclick="toggleMenu()">
            <span class="hamburger"></span>
        </button>
        CineGest
    </header>

    <nav class="MENU" id="menu">
        <ul>
            <li><a href="index.php">INICIO</a></li>
            <li><a href="servicios.php">CARTELERA</a></li>
            <li><a href="#">PRODUCTOS</a></li>
            <li><a href="#">CONTACTO</a></li>
        </ul>
    </nav>

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