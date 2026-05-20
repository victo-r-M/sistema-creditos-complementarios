<?php

session_start();

require_once "../login/conexion.php";

/* =========================================
   VALIDAR SESIÓN
========================================= */

if(
    !isset($_SESSION['idUser'])
){

    die("Sesión no válida");

}

/* =========================================
   VALIDAR ROL
========================================= */

if(
    $_SESSION['rol'] != 'director'
){

    die("Acceso denegado");

}

/* =========================================
   VALIDAR MÉTODO
========================================= */

if($_SERVER['REQUEST_METHOD'] != 'POST'){

    die("Acceso no permitido");

}

/* =========================================
   RECIBIR DATOS
========================================= */

$idActividad = $_POST['idActividad'];

$idRevision = $_POST['idRevision'];

$accion = $_POST['accion'];

$observacion = trim($_POST['observaciones']);

/* =========================================
   OBTENER ID DIRECTOR
========================================= */

$sql = "SELECT
        idDirector
        FROM director
        WHERE idUser = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    $_SESSION['idUser']
]);

$director = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$director){

    die("Director no encontrado");

}

$idDirector = $director['idDirector'];

/* =========================================
   DEFINIR ESTADOS
========================================= */

if($accion == 'aprobar'){

    $estadoDictamen = 'A';

    /*
        A = Aprobada/Publicada
    */

    $nuevoEstadoActividad = 'A';

}elseif($accion == 'rechazar'){

    $estadoDictamen = 'X';

    /*
        X = Rechazada
    */

    $nuevoEstadoActividad = 'X';

}else{

    die("Acción inválida");

}

/* =========================================
   TRANSACCIÓN
========================================= */

try{

    $conexion->beginTransaction();

    /* =====================================
       INSERTAR DICTAMEN
    ===================================== */

    $sql = "INSERT INTO dictamenActividad
    (
        idDirector,
        idRevisionActividadComplementaria,
        fechaDictamen,
        observacion,
        estadoDictamen
    )

    VALUES
    (
        ?, ?, GETDATE(), ?, ?
    )";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        $idDirector,
        $idRevision,

        $observacion,
        $estadoDictamen

    ]);

    /* =====================================
       ACTUALIZAR ACTIVIDAD
    ===================================== */

    $sql = "UPDATE actividadComplementaria
            SET estadoActividad = ?
            WHERE idActividadComplementarias = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        $nuevoEstadoActividad,
        $idActividad

    ]);

    /* =====================================
   ACTUALIZAR REVISIÓN
===================================== */

$sql = "UPDATE ActividadComplementariaRevisada
        SET estadoRevision = ?
        WHERE idRevisionActividadComplementaria = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([

    $nuevoEstadoActividad,
    $idRevision

]);
/* =====================================
   PUBLICAR EN CATÁLOGO
===================================== */

if($accion == 'aprobar'){

    $sql = "INSERT INTO catalogoActividadComplementaria
    (
        idActividadComplementarias,
        fechaPublicacion,
        activo
    )

    VALUES
    (
        ?, GETDATE(), 'S'
    )";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        $idActividad

    ]);

}
    /* =====================================
       CONFIRMAR
    ===================================== */

    $conexion->commit();

    header(
        "Location: ../../views/director/director_Vw.php"
    );

    exit;

}catch(PDOException $e){

    $conexion->rollBack();

    die(
        "Error al emitir dictamen: "
        . $e->getMessage()
    );

}
?>