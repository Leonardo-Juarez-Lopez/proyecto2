<?php
class Cconexion {
    public static function Cconectar() {
        $host = 'localhost\SQLEXPRESS2';
        $dbname = 'PuntoVentaCine';
        $username = 'LEONARDOJL';
        $password = 'LEONARDOJL2301';
        $puerto = 1433;

        try {
            // ODBC Driver 18 requiere parámetros de cifrado, por eso se agregan Encrypt y TrustServerCertificate
            $conn = new PDO(
                "sqlsrv:Server=$host,$puerto;Database=$dbname;Encrypt=no;TrustServerCertificate=yes", 
                $username, 
                $password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Error grave: " . $e->getMessage());
        }
    }
}
?>
