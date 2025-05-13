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
        <h2>Agregar Nueva Sala</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre_sala'];
            $capacidad = $_POST['capacidad'];
            $tipo = $_POST['tipo_sala'];
            
            $sql = "INSERT INTO Sala (nombre_sala, capacidad, tipo_sala) 
                    VALUES (?, ?, ?)";
            $params = array($nombre, $capacidad, $tipo);
            $stmt = sqlsrv_query($conn, $sql, $params);
            
            if($stmt) {
                echo "<div class='alert success'>✅ Sala agregada correctamente</div>";
            } else {
                echo "<div class='alert error'>❌ Error al agregar sala</div>";
            }
        }
        ?>
        
        <form method="POST">
            <label>Nombre de la sala:</label>
            <input type="text" name="nombre_sala" required>
            
            <label>Capacidad:</label>
            <input type="number" name="capacidad" required>
            
            <label>Tipo de sala:</label>
            <select name="tipo_sala" required>
                <option value="2D">2D Estándar</option>
                <option value="3D">3D</option>
                <option value="VIP">VIP</option>
            </select>
            
            <button type="submit">Guardar Sala</button>
        </form>
    </main>
</body>
</html>