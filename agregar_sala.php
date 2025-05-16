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
                $fecha_hora_input = $_POST['fecha_hora'];

                // Validar formato de fecha
                $fecha_hora = DateTime::createFromFormat('d/m/Y H:i', $fecha_hora_input);
                
                if (!$fecha_hora) {
                    die("<div class='alert error'>❌ Formato incorrecto. Use: DD/MM/AAAA HH:MM</div>");
                }
                
                $errors = array();
                
                // Insertar funciones para 7 días consecutivos
                for ($i = 0; $i < 7; $i++) {
                    $current_date = clone $fecha_hora;
                    $current_date->modify("+$i days");
                    $fecha_hora_sql = $current_date->format('Y-m-d H:i:s');
                    
                    $sql = "INSERT INTO Funcion (id_pelicula, id_sala, fecha_hora) 
                            VALUES (?, ?, ?)";
                    $params = array($id_pelicula, $id_sala, $fecha_hora_sql);
                    
                    $stmt = sqlsrv_query($conn, $sql, $params);
                    
                    if (!$stmt) {
                        $error = sqlsrv_errors()[0]['message'];
                        $errors[] = "Fecha $fecha_hora_sql: $error";
                    }
                }
                
                if (empty($errors)) {
                    echo "<div class='alert success'>✅ Funciones programadas para toda la semana</div>";
                } else {
                    echo "<div class='alert error'>❌ Errores encontrados:<br>" . implode('<br>', $errors) . "</div>";
                }
            }

            // Obtener películas y salas
            $peliculas = sqlsrv_query($conn, "SELECT id_pelicula, titulo FROM Pelicula");
            $salas = sqlsrv_query($conn, "SELECT id_sala, nombre_sala FROM Sala");
        ?>
        
        <form method="POST">
            <label>Película:</label>
            <select name="pelicula" required>
                <?php while($p = sqlsrv_fetch_array($peliculas, SQLSRV_FETCH_ASSOC)): ?>
                    <option value="<?= $p['id_pelicula'] ?>"><?= $p['titulo'] ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Sala:</label>
            <select name="sala" required>
                <?php while($s = sqlsrv_fetch_array($salas, SQLSRV_FETCH_ASSOC)): ?>
                    <option value="<?= $s['id_sala'] ?>"><?= $s['nombre_sala'] ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Fecha y Hora Inicial:
                <small>Ejemplo: 12/05/2023 15:30</small>
            </label>
            <input 
                type="text" 
                name="fecha_hora" 
                placeholder="DD/MM/AAAA HH:MM" 
                pattern="\d{2}/\d{2}/\d{4} \d{2}:\d{2}" 
                required
            >
            
            <button type="submit">Programar Funciones</button>
        </form>
    </main>
</body>
</html>