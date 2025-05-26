<?php
ob_start();
include('conexion.php'); ?>
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
    <meta charset="UTF-8">
    <title>Administración de Usuarios SQL Server</title>
    <?php include('Estilo/header.php'); ?>
</head>
<body>
<?php include('Estilo/menu.php'); ?>

<div class="usuarios-sql">
    <div class="descuentos-box">
        <h2>Usuarios del Servidor SQL</h2>

        <!-- Tabla de usuarios existentes -->
        <table class="tabla-descuentos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Habilitado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT name, is_disabled FROM sys.sql_logins WHERE name NOT LIKE '##%'";
            $result = sqlsrv_query($conn, $query);

            $usuarios = [];
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                $usuarios[] = $row;
            }

            foreach ($usuarios as $row) {
                $nombre = $row['name'];
                $estado_actual = (int)$row['is_disabled'];
                $estado_texto = $estado_actual ? 'No' : 'Sí';
                $boton_texto = $estado_actual ? 'Habilitar' : 'Deshabilitar';

                echo "<tr>
                        <td>{$nombre}</td>
                        <td>{$estado_texto}</td>
                        <td>";

                if (strtolower($nombre) !== 'sa') {
                    echo "<form method='POST'>
                            <input type='hidden' name='usuario' value='{$nombre}'>
                            <input type='hidden' name='estado_actual' value='{$estado_actual}'>
                            <button type='submit' name='toggle_estado' class='btn-descuento'>{$boton_texto}</button>
                        </form>
                        <form method='POST' style='margin-top: 0.5rem;'>
                            <input type='hidden' name='usuario' value='{$nombre}'>
                            <input type='password' name='nueva_pass' placeholder='Nueva contraseña' required>
                            <button type='submit' name='cambiar_password' class='btn-descuento'>Cambiar Contraseña</button>
                        </form>";
                } else {
                    echo "<em>Protegido</em>";
                }

                echo "</td></tr>";
            }
            ?>
            </tbody>
        </table>

        <!-- Agregar nuevo login -->
        <h3>Crear Nuevo Usuario SQL Server</h3>
        <form method="POST" class="form-agregar">
            <input type="text" name="nuevo_usuario" placeholder="Usuario SQL" required>
            <input type="password" name="nueva_contrasena" placeholder="Contraseña" required>
            <select name="rol" required>
                <option value="login">Login</option>
            </select>
            <button type="submit" name="crear_usuario" class="btn-descuento">Crear Usuario</button>
        </form>

        <?php
        // Alternar estado del login
        if (isset($_POST['toggle_estado'])) {
            $usuario = $_POST['usuario'];
            $estado_actual = (int)$_POST['estado_actual'];

            if (strtolower($usuario) !== 'sa') {
                $sql_toggle = ($estado_actual === 1)
                    ? "ALTER LOGIN [$usuario] ENABLE"
                    : "ALTER LOGIN [$usuario] DISABLE";

                sqlsrv_query($conn, $sql_toggle);
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Cambiar contraseña del login
        if (isset($_POST['cambiar_password'])) {
            $usuario = $_POST['usuario'];
            $nueva_pass = $_POST['nueva_pass'];

            $usuario_sanitizado = preg_replace('/[^a-zA-Z0-9_]/', '', $usuario);
            $pass_sanitizado = str_replace("'", "''", $nueva_pass);

            $sql_pass = "ALTER LOGIN [$usuario_sanitizado] WITH PASSWORD = '$pass_sanitizado'";
            sqlsrv_query($conn, $sql_pass);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Crear nuevo login
        if (isset($_POST['crear_usuario'])) {
            $nuevo = $_POST['nuevo_usuario'];
            $pass = $_POST['nueva_contrasena'];

            $usuario_sanitizado = preg_replace('/[^a-zA-Z0-9_]/', '', $nuevo);
            $pass_sanitizado = str_replace("'", "''", $pass);

            $sql_create = "CREATE LOGIN [$usuario_sanitizado] WITH PASSWORD = '$pass_sanitizado', CHECK_POLICY = OFF";
            $stmt = sqlsrv_query($conn, $sql_create);

            if ($stmt) {
                echo "<div class='alert success'>¡Usuario creado correctamente!</div>";
            } else {
                $errors = sqlsrv_errors();
                foreach ($errors as $error) {
                    echo "<div class='alert error'>Error SQL: " . htmlspecialchars($error['message']) . "</div>";
                }
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        ?>
    </div>
</div>

<?php ob_end_flush(); ?>
</body>
</html>