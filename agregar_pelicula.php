<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include('Estilo/header.php'); ?>
</head>
<body>
    <?php include('Estilo/menu.php'); ?>
    
    <main class="CONTENIDO" id="contenido">
        <h2>Agregar Película</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $duracion = $_POST['duracion'];
            $clasificacion = $_POST['clasificacion'];
            $genero = $_POST['genero'];
            $sinopsis = $_POST['sinopsis'];
            
            $sql = "INSERT INTO Pelicula (titulo, duracion_minutos, clasificacion, genero, sinopsis)
                    VALUES (?, ?, ?, ?, ?)";
            $params = array($titulo, $duracion, $clasificacion, $genero, $sinopsis);
            $stmt = sqlsrv_query($conn, $sql, $params);
            
            if($stmt) {
                echo "<div class='alert success'>✅ Película agregada</div>";
            } else {
                echo "<div class='alert error'>❌ Error al agregar</div>";
            }
        }
        ?>
        
        <form method="POST">
            <label>Título:</label>
            <input type="text" name="titulo" required>
            
            <label>Duración (minutos):</label>
            <input type="number" name="duracion" required>
            
            <label>Clasificación:</label>
            <input type="text" name="clasificacion" required>
            
            <label>Género:</label>
            <input type="text" name="genero" required>
            
            <label>Sinopsis:</label>
            <textarea name="sinopsis" rows="4" required></textarea>
            
            <button type="submit">Guardar Película</button>
        </form>
    </main>
</body>
</html>