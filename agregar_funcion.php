<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include('Estilo\header.php'); ?>
</head>
<body>
    <?php include('Estilo\menu.php'); ?>
    
    <main class="CONTENIDO" id="contenido">
        <h2>Programar Función</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pelicula = $_POST['pelicula'];
            $id_sala = $_POST['sala'];
            $fecha_hora = $_POST['fecha_hora'];
            $precio = $_POST['precio'];
            
            $sql = "INSERT INTO Funcion (id_pelicula, id_sala, fecha_hora, precio)
                    VALUES (?, ?, ?, ?)";
            $params = array($id_pelicula, $id_sala, $fecha_hora, $precio);
            $stmt = sqlsrv_query($conn, $sql, $params);
            
            if($stmt) {
                echo "<div class='alert success'>✅ Función programada</div>";
            } else {
                echo "<div class='alert error'>❌ Error al programar</div>";
            }
        }
        
        // Obtener películas y salas para los selects
        $peliculas = sqlsrv_query($conn, "SELECT id_pelicula, titulo FROM Pelicula");
        $salas = sqlsrv_query($conn, "SELECT id_sala, nombre_sala FROM Sala");
        ?>
        
        <form method="POST">
            <label>Película:</label>
            <select name="pelicula" required>
                <?php while($p = sqlsrv_fetch_array($peliculas)): ?>
                    <option value="<?= $p['id_pelicula'] ?>"><?= $p['titulo'] ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Sala:</label>
            <select name="sala" required>
                <?php while($s = sqlsrv_fetch_array($salas)): ?>
                    <option value="<?= $s['id_sala'] ?>"><?= $s['nombre_sala'] ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Fecha y Hora:</label>
            <input type="datetime-local" name="fecha_hora" required>
            
            <label>Precio base:</label>
            <input type="number" step="0.01" name="precio" required>
            
            <button type="submit">Programar Función</button>
        </form>
    </main>
</body>
</html>