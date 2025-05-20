<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CineGest - Configurar Descuentos</title>
    <?php include('Estilo/header.php'); ?>
    <link rel="stylesheet" href="Estilo/estilo_descuentos.css"> <!-- Estilo exclusivo -->
</head>
<body>
<?php include('Estilo/menu.php'); ?>

<div class="descuentos-box">
    <h2>CONFIGURACIÓN DE DESCUENTOS</h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
        $folio = $_POST['folio'];
        $nombre = $_POST['nombre_descuento'];
        $porcentaje = $_POST['porcentaje'];
        $inicio = $_POST['fecha_inicio'];
        $fin = $_POST['fecha_fin'];

        $sql = "INSERT INTO Descuento (folio, nombre_descuento, porcentaje, fecha_inicio, fecha_fin)
                VALUES (?, ?, ?, ?, ?)";
        $params = [$folio, $nombre, $porcentaje, $inicio, $fin];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt) echo "<div class='alert success'>¡Descuento agregado correctamente!</div>";
    }

    if (isset($_POST['guardar_cambios'])) {
        foreach ($_POST['descuentos'] as $id => $datos) {
            $sql = "UPDATE Descuento SET folio = ?, nombre_descuento = ?, porcentaje = ?, fecha_inicio = ?, fecha_fin = ? WHERE id_descuento = ?";
            $params = [$datos['folio'], $datos['nombre'], $datos['porcentaje'], $datos['inicio'], $datos['fin'], $id];
            sqlsrv_query($conn, $sql, $params);
        }
        echo "<div class='alert success'>¡Cambios guardados correctamente!</div>";
    }

    $sql = "SELECT * FROM Descuento ORDER BY fecha_inicio DESC";
    $result = sqlsrv_query($conn, $sql);
    ?>

    <form method="POST">
        <table class="tabla-descuentos">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Nombre</th>
                    <th>%</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) : ?>
                <tr>
                    <td><input type="text" name="descuentos[<?= $row['id_descuento'] ?>][folio]" value="<?= htmlspecialchars($row['folio']) ?>"></td>
                    <td><input type="text" name="descuentos[<?= $row['id_descuento'] ?>][nombre]" value="<?= htmlspecialchars($row['nombre_descuento']) ?>"></td>
                    <td><input type="number" name="descuentos[<?= $row['id_descuento'] ?>][porcentaje]" value="<?= $row['porcentaje'] ?>" min="0" max="100"></td>
                    <td><input type="date" name="descuentos[<?= $row['id_descuento'] ?>][inicio]" value="<?= $row['fecha_inicio']->format('Y-m-d') ?>"></td>
                    <td><input type="date" name="descuentos[<?= $row['id_descuento'] ?>][fin]" value="<?= $row['fecha_fin']->format('Y-m-d') ?>"></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button class="btn-descuento" type="submit" name="guardar_cambios">Guardar Cambios</button>
    </form>

    <h3>Agregar Nuevo Descuento</h3>
    <form method="POST" class="form-agregar">
        <input type="text" name="folio" placeholder="Folio" required>
        <input type="text" name="nombre_descuento" placeholder="Nombre del Descuento" required>
        <input type="number" name="porcentaje" placeholder="Porcentaje (%)" required min="0" max="100">
        <input type="date" name="fecha_inicio" required>
        <input type="date" name="fecha_fin" required>
        <button class="btn-descuento" type="submit" name="agregar">Agregar Descuento</button>
    </form>
</div>
</body>
</html>
