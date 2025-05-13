<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include('Estilo\header.php'); ?>
</head>
<body>
    <?php include('Estilo\menu.php'); ?>
    
    <main class="CONTENIDO" id="contenido">
        <h2>Cartelera Semanal</h2>
        
        <?php
        $sql = "SELECT F.fecha_hora, P.titulo, S.nombre_sala, F.precio 
                FROM Funcion F
                JOIN Pelicula P ON F.id_pelicula = P.id_pelicula
                JOIN Sala S ON F.id_sala = S.id_sala
                WHERE F.fecha_hora BETWEEN GETDATE() AND DATEADD(day, 7, GETDATE())
                ORDER BY F.fecha_hora";
                
        $result = sqlsrv_query($conn, $sql);
        
        echo "<div class='cartelera'>";
        while($funcion = sqlsrv_fetch_array($result)) {
            $fecha = $funcion['fecha_hora']->format('d/m H:i');
            echo "<div class='funcion'>
                    <h3>{$funcion['titulo']}</h3>
                    <p>Sala: {$funcion['nombre_sala']}</p>
                    <p>Fecha: $fecha</p>
                    <p>Precio: $ {$funcion['precio']}</p>
                  </div>";
        }
        echo "</div>";
        ?>
        
        <style>
            .cartelera {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1rem;
                padding: 1rem;
            }
            .funcion {
                background: white;
                padding: 1rem;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
        </style>
    </main>
</body>
</html>