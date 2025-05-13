<?php
// Nombre del servidor SQL Server (en este caso, tu máquina se llama "ADESS")
$serverName = "LEONARDOJL\MSSQLSERVER2";

// Opciones de conexión necesarias para acceder a SQL Server
$connectionOptions = array(
    // Nombre de la base de datos a la que te quieres conectar
    "Database" => "PuntoVentaCine",

    // Usuario con permisos sobre la base de datos (en este caso, el usuario 'sa')
    "Uid" => "sa",

    // Contraseña del usuario (la que configuraste previamente)
    "PWD" => "LEONARDOJL2301",

    // Especifica el conjunto de caracteres (UTF-8 asegura soporte para tildes, eñes, etc.)
    "CharacterSet" => "UTF-8"
);

// Intentamos establecer la conexión usando sqlsrv_connect()
// Esta función devuelve un recurso de conexión si todo va bien, o false si falla
$conn = sqlsrv_connect($serverName, $connectionOptions);


?>