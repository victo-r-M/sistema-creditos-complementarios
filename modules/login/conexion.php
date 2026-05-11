<?php

$server = "localhost";
$database = "sistema_creditos_complementarios";
$user = "sa";
$password = "12345";

try {

    $conexion = new PDO(
        "sqlsrv:Server=$server;Database=$database",
        $user,
        $password
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );


} catch(PDOException $e){

    die("Error de conexión: " . $e->getMessage());

}