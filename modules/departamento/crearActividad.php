<?php

session_start();

require_once "../login/conexion.php";

/* =========================================
   VALIDAR SESIÓN
========================================= */

if(!isset($_SESSION['idUser'])){

    die("Sesión no válida");

}

/* =========================================
   VALIDAR MÉTODO
========================================= */

if($_SERVER['REQUEST_METHOD'] !== 'POST'){

    die("Acceso no permitido");

}

/* =========================================
   RECIBIR DATOS
========================================= */

$nombreActividad = trim(
    $_POST['nombreActividad']
);

$idTipoActividad = trim(
    $_POST['tipoActividad']
);

$descripcion = trim(
    $_POST['descripcion']
);

/* =========================================
   VALIDACIONES
========================================= */

if(
    empty($nombreActividad) ||
    empty($idTipoActividad) ||
    empty($descripcion)
){

    die("Todos los campos son obligatorios");

}

/* =========================================
   OBTENER ID DEPARTAMENTO
========================================= */

$sql = "SELECT idDepartamento
        FROM departamentos
        WHERE idUser = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    $_SESSION['idUser']
]);

$departamento = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$departamento){

    die("Departamento no encontrado");

}

$idDepartamento = $departamento['idDepartamento'];

/* =========================================
   INSERTAR ACTIVIDAD
========================================= */

try{

    $sql = "INSERT INTO actividadComplementaria
    (
        idDepartamento,
        idTipoActividadComplementaria,
        nombreActividadComplementaria,
        descripcionActividadComplementaria,
        creditosAsignados,
        estadoActividad
    )

    VALUES
    (
        ?, ?, ?, ?, 0, 'R'
    )";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        $idDepartamento,
        $idTipoActividad,

        $nombreActividad,
        $descripcion

    ]);

    header(
        "Location: ../../views/departamento/departamento_Vw.php"
    );

    exit;

}catch(PDOException $e){

    die(
        "Error al crear actividad: "
        . $e->getMessage()
    );

}