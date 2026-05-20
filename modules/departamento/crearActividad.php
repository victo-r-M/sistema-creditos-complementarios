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

$cupoMax = trim(
    $_POST['cupoMax']
);

/* =========================================
   VALIDACIONES
========================================= */

if(
    empty($nombreActividad) ||
    empty($idTipoActividad) ||
    empty($descripcion) ||
    empty($cupoMax)
){

    die("Todos los campos son obligatorios");

}

/* =========================================
   VALIDAR CUPO
========================================= */

if(
    $cupoMax < 1 ||
    $cupoMax > 500
){

    die("Cupo máximo inválido");

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
    estadoActividad,
    cupoMax
)

VALUES
(
    ?, ?, ?, ?, 0, 'R', ?
)";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

    $idDepartamento,
    $idTipoActividad,

    $nombreActividad,
    $descripcion,

    $cupoMax

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