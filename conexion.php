<?php
$serverName = "LEONARDOJL\MSSQLSERVER2";
$connectionOptions = array(
    "Database" => "PuntoVentaCine",
    "Uid" => "sa",
    "PWD" => "LEONARDOJL2301",
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);


?>