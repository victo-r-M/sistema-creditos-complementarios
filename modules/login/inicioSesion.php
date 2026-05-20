<?php

session_start();

require_once "conexion.php";

if($_SERVER['REQUEST_METHOD'] != 'POST'){

    die("Acceso no permitido");

}

/* =========================================
   RECIBIR DATOS
========================================= */

$correo = trim($_POST['correo']);
$password = $_POST['password'];

/* =========================================
   VALIDACIONES
========================================= */

if(
    empty($correo) ||
    empty($password)
){

    die("Todos los campos son obligatorios");

}

/* =========================================
   BUSCAR USUARIO
========================================= */

$sql = "SELECT
            idUser,
            correoInst,
            password,
            rol,
            activo
        FROM usuarios
        WHERE correoInst = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([$correo]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

/* =========================================
   VALIDAR EXISTENCIA
========================================= */

if(!$usuario){

    die("Correo o contraseña incorrectos");

}

/* =========================================
   VALIDAR USUARIO ACTIVO
========================================= */

if($usuario['activo'] != 'S'){

    die("Usuario inactivo");

}

/* =========================================
   VALIDAR PASSWORD
========================================= */

if(
    !password_verify(
        $password,
        $usuario['password']
    )
){

    die("Correo o contraseña incorrectos");

}

/* =========================================
   CREAR SESIÓN
========================================= */

$_SESSION['idUser'] = $usuario['idUser'];

$_SESSION['correo'] = $usuario['correoInst'];

$_SESSION['rol'] = $usuario['rol'];

/* =========================================
   REDIRECCIONES POR ROL
========================================= */

switch($usuario['rol']){

    case 'estudiante':

        header("Location: ../../views/estudiante/estudiante_Vw.php");
        break;

    case 'departamento':

        header("Location: ../../views/departamento/departamento_Vw.php");
        break;

    case 'comite':

        header("Location: ../../views/comite/comite_Vw.php");
        break;

    case 'director':

        header("Location: ../../views/director/dashboard.php");
        break;

    case 'division_ep':

        header("Location: ../../views/division/dashboard.php");
        break;

    case 'responsable':

        header("Location: ../../views/responsable/dashboard.php");
        break;

    case 'servicios_escolares':

        header("Location: ../../views/servicios/dashboard.php");
        break;

    default:

        session_destroy();

        die("Rol no válido");

}

exit;