<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CineGest - Configurar Precios</title>
    <?php include('Estilo/header.php'); ?>
    <link rel="stylesheet" href="Estilo/style.css">
</head>
<body>
<?php include('Estilo/menu.php'); ?>

<div class="config-precios">
    <main class="contenido-precios" id="contenido">
        <form method="POST">
            <div class="container-two-tables">
                <!-- Boletos Regulares -->
                <div class="table-container">
                    <h2>BOLETOS REGULARES</h2>
                    <table class="boletos-regulares">
                        <thead>
                            <tr><th>Tipo de Boleto</th><th>Precio ($)</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $boletos_normales = [];
                            $boletos_3d = [];

                            $sql = "SELECT * FROM Precios ORDER BY 
                                    CASE WHEN tipo_boleto LIKE '%3D%' THEN 1 ELSE 0 END, 
                                    tipo_boleto";
                            $result = sqlsrv_query($conn, $sql);
                            while ($tipo = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                if (strpos($tipo['tipo_boleto'], '3D') !== false) {
                                    $boletos_3d[] = $tipo;
                                } else {
                                    $boletos_normales[] = $tipo;
                                }
                            }

                            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_precios'])) {
                                $actualizados = 0;
                                foreach ($_POST['precios'] as $id_tipo => $precio) {
                                    $sql = "UPDATE Precios SET precio = ? WHERE id_tipo = ?";
                                    $params = array($precio, $id_tipo);
                                    $stmt = sqlsrv_query($conn, $sql, $params);
                                    if ($stmt) $actualizados++;
                                }
                                if ($actualizados > 0) {
                                    echo '<div class="alert success">\u00a1Precios actualizados correctamente!</div>';
                                }
                            }

                            foreach ($boletos_normales as $tipo): ?>
                            <tr>
                                <td><?= htmlspecialchars($tipo['tipo_boleto']) ?></td>
                                <td>
                                    <input type="number" step="0.01" min="0"
                                           name="precios[<?= $tipo['id_tipo'] ?>]"
                                           value="<?= number_format($tipo['precio'], 2) ?>">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Boletos 3D -->
                <div class="table-container">
                    <h2>BOLETOS 3D</h2>
                    <table class="boletos-3d">
                        <thead>
                            <tr><th>Tipo de Boleto</th><th>Precio ($)</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($boletos_3d as $tipo): ?>
                            <tr>
                                <td><?= htmlspecialchars($tipo['tipo_boleto']) ?></td>
                                <td>
                                    <input type="number" step="0.01" min="0"
                                           name="precios[<?= $tipo['id_tipo'] ?>]"
                                           value="<?= number_format($tipo['precio'], 2) ?>">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <button type="submit" name="actualizar_precios">Guardar Cambios</button>
        </form>
    </main>
</div>

</body>
</html>
