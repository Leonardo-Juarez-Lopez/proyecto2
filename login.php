<?php
session_start();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    if ($usuario === "sa") {
        $serverName = "LEONARDOJL\\MSSQLSERVER2";
        $connectionInfo = array("UID" => $usuario, "PWD" => $contrasena, "Database" => "PuntoVentaCine");

        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            $_SESSION["usuario"] = $usuario;
            header("Location: index.php");
            exit();
        } else {
            $mensaje = "❌ Usuario o contraseña incorrectos, o no se pudo conectar.";
        }
    } else {
        $mensaje = "❌ Solo se permite el usuario 'sa'.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('favicon.png') no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem 2.5rem;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.25);
            max-width: 400px;
            width: 90%;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #444;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f5faff;
        }

        button {
            width: 100%;
            background-color: #ff8c00;
            color: white;
            padding: 0.9rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #cc5500;
            transform: scale(1.02);
        }

        .alert {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 5px;
            text-align: center;
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Iniciar sesión</h2>
        <?php if ($mensaje): ?>
            <div class="alert"><?= $mensaje ?></div>
        <?php endif; ?>

        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
