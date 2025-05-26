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
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .cartelera-columnas {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 1rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }
        .columna-salas {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem;
        }
        .columna-funciones {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem;
        }
        .sala-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            background: #1E90FF;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .sala-item:hover, .sala-item.active {
            background: #000080;
            transform: translateX(5px);
        }
        .funcion {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            background: rgba(30, 144, 255, 0.05);
            border-left: 4px solid #1E90FF;
        }
        .funcion-info {
            flex: 1;
        }
        .funcion h3 {
            color: #1E90FF;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }
        .horario {
            font-weight: bold;
            color: #333;
        }
        .funcion-dias {
            background: #1E90FF;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .semana-selector {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 2rem auto;
            align-items: center;
            max-width: 1200px;
            padding: 0 1rem;
        }
        .semana-selector button {
            padding: 0.8rem 1.5rem;
            background: #1E90FF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        .semana-selector button:hover {
            background: #000080;
        }
        .semana-text {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
        .no-funciones {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }
        .titulo-principal {
            text-align: center;
            margin: 1.5rem 0;
            color: #333;
            font-size: 2rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        @media (max-width: 768px) {
            .cartelera-columnas {
                grid-template-columns: 1fr;
            }
            .columna-salas {
                display: flex;
                overflow-x: auto;
                gap: 0.5rem;
                padding-bottom: 1rem;
            }
            .sala-item {
                min-width: 120px;
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <?php include('Estilo\menu.php'); ?>
    
    <main class="CONTENIDO" id="contenido">
        <h1 class="titulo-principal">Cartelera Semanal</h1>
        
        <?php
        // Obtener la fecha seleccionada o usar la actual
        $fechaSeleccionada = isset($_GET['fecha']) ? new DateTime($_GET['fecha']) : new DateTime();
        
        // Ajustar al jueves anterior si no es jueves
        if ($fechaSeleccionada->format('N') != 4) {
            $fechaSeleccionada->modify('last thursday');
        }
        
        // Calcular fechas de la semana (jueves a miércoles)
        $jueves = clone $fechaSeleccionada;
        $miercoles = clone $jueves; $miercoles->modify('+6 days');
        
        // Formatear fechas para mostrar
        $formatoFecha = 'd/m/Y';
        $semanaTexto = "Semana del " . $jueves->format($formatoFecha) . " al " . $miercoles->format($formatoFecha);
        ?>
        
        <div class="semana-selector">
            <button onclick="cambiarSemana(-1)">◄ Anterior</button>
            <div class="semana-text"><?php echo $semanaTexto; ?></div>
            <button onclick="cambiarSemana(1)">Siguiente ►</button>
        </div>
        
        <div class="cartelera-columnas">
            <div class="columna-salas" id="columna-salas">
                <?php
                // Consulta para obtener todas las salas disponibles
                $sqlSalas = "SELECT id_sala, nombre_sala FROM Sala ORDER BY nombre_sala";
                $resultSalas = sqlsrv_query($conn, $sqlSalas);
                
                while($sala = sqlsrv_fetch_array($resultSalas, SQLSRV_FETCH_ASSOC)) {
                    echo '<div class="sala-item" data-sala-id="' . $sala['id_sala'] . '">' . $sala['nombre_sala'] . '</div>';
                }
                ?>
            </div>
            
            <div class="columna-funciones" id="columna-funciones">
                <?php
                // Consulta para obtener las funciones de la semana
                $sqlFunciones = "SELECT 
                                    F.id_sala,
                                    P.titulo, 
                                    CONVERT(VARCHAR(5), F.fecha_hora, 108) AS hora,
                                    COUNT(*) AS num_funciones,
                                    STRING_AGG(CONVERT(VARCHAR(10), CAST(F.fecha_hora AS DATE), 103), ', ') AS dias
                                 FROM Funcion F
                                 JOIN Pelicula P ON F.id_pelicula = P.id_pelicula
                                 WHERE F.fecha_hora BETWEEN ? AND ?
                                 GROUP BY F.id_sala, P.titulo, CONVERT(VARCHAR(5), F.fecha_hora, 108)
                                 ORDER BY F.id_sala, hora, P.titulo";
                
                $params = array(
                    $jueves->format('Y-m-d 00:00:00'),
                    $miercoles->format('Y-m-d 23:59:59')
                );
                
                $resultFunciones = sqlsrv_query($conn, $sqlFunciones, $params);
                
                // Organizar funciones por sala
                $funcionesPorSala = array();
                while($funcion = sqlsrv_fetch_array($resultFunciones, SQLSRV_FETCH_ASSOC)) {
                    $funcionesPorSala[$funcion['id_sala']][] = $funcion;
                }
                
                // Mostrar funciones de la primera sala por defecto
                $primeraSala = true;
                foreach($funcionesPorSala as $idSala => $funciones) {
                    echo '<div class="funciones-sala" id="funciones-sala-' . $idSala . '" ' . ($primeraSala ? '' : 'style="display: none;"') . '>';
                    $primeraSala = false;
                    
                    if(count($funciones) > 0) {
                        foreach($funciones as $funcion) {
                            echo '<div class="funcion">';
                            echo '<div class="funcion-info">';
                            echo '<h3>' . $funcion['titulo'] . '</h3>';
                            echo '<div class="horario">Hora: ' . $funcion['hora'] . '</div>';
                            echo '</div>';
                            echo '<div class="funcion-dias">' . $funcion['num_funciones'] . ' funciones</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="no-funciones">No hay funciones programadas</div>';
                    }
                    
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        
        <script>
            // Cambiar semana
            function cambiarSemana(direccion) {
                const urlParams = new URLSearchParams(window.location.search);
                let fechaActual = urlParams.get('fecha') || new Date().toISOString().split('T')[0];
                
                let fecha = new Date(fechaActual);
                if (fecha.getDay() !== 4) {
                    fecha.setDate(fecha.getDate() - ((fecha.getDay() + 3) % 7));
                }
                
                fecha.setDate(fecha.getDate() + (direccion * 7));
                window.location.href = `?fecha=${fecha.toISOString().split('T')[0]}`;
            }
            
            // Mostrar funciones de la sala seleccionada
            document.querySelectorAll('.sala-item').forEach(item => {
                item.addEventListener('click', function() {
                    // Remover clase active de todos los items
                    document.querySelectorAll('.sala-item').forEach(i => {
                        i.classList.remove('active');
                    });
                    
                    // Añadir clase active al item clickeado
                    this.classList.add('active');
                    
                    // Ocultar todas las funciones
                    document.querySelectorAll('.funciones-sala').forEach(funciones => {
                        funciones.style.display = 'none';
                    });
                    
                    // Mostrar funciones de la sala seleccionada
                    const salaId = this.getAttribute('data-sala-id');
                    document.getElementById('funciones-sala-' + salaId).style.display = 'block';
                });
            });
            
            // Activar la primera sala al cargar
            document.querySelector('.sala-item').click();
        </script>
    </main>
</body>
</html>