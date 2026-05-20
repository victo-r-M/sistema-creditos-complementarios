<?php

session_start();

require_once "../login/conexion.php";

/* =========================================
   VALIDAR SESIÓN
========================================= */

if(
    !isset($_SESSION['idUser']) ||
    $_SESSION['rol'] != 'comite'
){

    die("Acceso denegado");

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

$idActividad = $_POST['idActividad'];
$accion = $_POST['accion'];

$creditos = $_POST['creditosAsignados'] ?? 0;

$observacion = trim(
    $_POST['observacion'] ?? ''
);

/* =========================================
   VALIDACIONES
========================================= */

if(
    empty($idActividad) ||
    empty($accion)
){

    die("Datos incompletos");

}

/* =========================================
   OBTENER ID COMITÉ
========================================= */

$sql = "SELECT
        idComiteAcademico
        FROM comiteAcademico
        WHERE idUser = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    $_SESSION['idUser']
]);

$comite = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$comite){

    die("Comité no encontrado");

}

$idComite = $comite['idComiteAcademico'];

/* =========================================
   VALIDAR ACTIVIDAD EXISTE
========================================= */

$sql = "SELECT
        idActividadComplementarias
        FROM actividadComplementaria
        WHERE idActividadComplementarias = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([$idActividad]);

if(!$stmt->fetch()){

    die("Actividad no encontrada");

}

/* =========================================
   TRANSACCIÓN
========================================= */

try{

    $conexion->beginTransaction();

    /* =====================================
       APROBAR ACTIVIDAD
    ===================================== */

    if($accion == 'aprobar'){

        /* VALIDAR CRÉDITOS */

        if(
            $creditos <= 0
        ){

            die("Debe asignar créditos");

        }

        /* =================================
           ACTUALIZAR ACTIVIDAD
        ================================= */

        $sql = "UPDATE actividadComplementaria
                SET
                    creditosAsignados = ?,
                    estadoActividad = 'D'
                WHERE idActividadComplementarias = ?";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            $creditos,
            $idActividad
        ]);

        /* =================================
           INSERTAR REVISIÓN
        ================================= */

        $sql = "INSERT INTO actividadComplementariaRevisada
        (
            
            idActividadComplementarias,
            idComiteAcademico,
            observacion,
            fechaRevision,
            estadoRevision
        )

        VALUES
        (
            ?, ?, ?, GETDATE(), 'D'
        )";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([

            $idActividad,
            $idComite,
            $observacion

        ]);

    }

    /* =====================================
       RECHAZAR ACTIVIDAD
    ===================================== */

    else if($accion == 'rechazar'){

        /* =================================
           ACTUALIZAR ACTIVIDAD
        ================================= */

        $sql = "UPDATE actividadComplementaria
                SET
                    estadoActividad = 'X'
                WHERE idActividadComplementarias = ?";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            $idActividad
        ]);

        /* =================================
           INSERTAR REVISIÓN
        ================================= */

        $sql = "INSERT INTO actividadComplementariaRevisada
        (
            idActividadComplementarias,
            idComiteAcademico,
            observacion,
            fechaRevision,
            resultadoRevision
        )

        VALUES
        (
            ?, ?, ?, GETDATE(), 'R'
        )";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([

            $idActividad,
            $idComite,
            $observacion

        ]);

    }

    else{

        die("Acción inválida");

    }

    /* =====================================
       CONFIRMAR
    ===================================== */

    $conexion->commit();

    header(
        "Location: ../../views/comite/comite_Vw.php"
    );

    exit;

}catch(PDOException $e){

    $conexion->rollBack();

    die(
        "Error al revisar actividad: "
        . $e->getMessage()
    );

}