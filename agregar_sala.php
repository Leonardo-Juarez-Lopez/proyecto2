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
    <?php include('Estilo/header.php'); ?>
</head>
<body>
    <?php include('Estilo/menu.php'); ?>

    <main class="CONTENIDO" id="contenido">
        <h2>Agregar Nueva Sala</h2>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $capacidad = $_POST['capacidad'];
            $tipo = $_POST['tipo_sala'];

            // Iniciar transacción
            sqlsrv_begin_transaction($conn);

            // Usar una subconsulta segura para obtener el siguiente número de sala
            $query_next = "SELECT ISNULL(MAX(CAST(nombre_sala AS INT)), 0) + 1 AS siguiente
                           FROM Sala
                           WHERE ISNUMERIC(nombre_sala) = 1";

            $stmt_next = sqlsrv_query($conn, $query_next);

            if (!$stmt_next) {
                sqlsrv_rollback($conn);
                echo "<div class='alert error'>❌ Error al obtener el número de sala</div>";
            } else {
                $row_next = sqlsrv_fetch_array($stmt_next, SQLSRV_FETCH_ASSOC);
                $nombre = $row_next['siguiente'];

                $sql = "INSERT INTO Sala (nombre_sala, capacidad, tipo_sala) VALUES (?, ?, ?)";
                $params = array((string)$nombre, $capacidad, $tipo);
                $stmt = sqlsrv_query($conn, $sql, $params);

                if ($stmt) {
                    sqlsrv_commit($conn);
                    echo "<div class='alert success'>✅ Sala número $nombre agregada correctamente</div>";
                } else {
                    sqlsrv_rollback($conn);
                    echo "<div class='alert error'>❌ Error al agregar la sala</div>";
                }
            }
        }
        ?>

        <form method="POST">
            <label>Capacidad:</label>
            <input type="number" name="capacidad" required>

            <label>Tipo de sala:</label>
            <select name="tipo_sala" required>
                <option value="2D">2D Estándar</option>
                <option value="3D">3D</option>
            </select>

            <button type="submit">Guardar Sala</button>
        </form>
    </main>
</body>
</html>
