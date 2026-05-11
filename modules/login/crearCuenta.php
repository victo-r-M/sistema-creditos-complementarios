<?php
require_once "conexion.php";

if($_SERVER['REQUEST_METHOD'] != 'POST'){

    die("Acceso no permitido");

}

/* =========================================
   RECIBIR DATOS
========================================= */

$nombre = trim($_POST['nombre']);
$primerApellido = trim($_POST['primerApellido']);
$segundoApellido = trim($_POST['segundoApellido']);

$noControl = trim($_POST['noControl']);

$idCarrera = $_POST['idCarrera'];

$correo = trim($_POST['correo']);

$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$semestreActual = $_POST['semestreActual'];



/* =========================================
   VALIDACIONES
========================================= */

/* CAMPOS VACÍOS */

if(
    empty($nombre) ||
    empty($primerApellido) ||
    empty($noControl) ||
    empty($idCarrera) ||
    empty($correo) ||
    empty($password) ||
    empty($confirmPassword)
){

    die("Todos los campos obligatorios deben completarse");

}

/* VALIDAR CONTRASEÑAS */

if($password !== $confirmPassword){

    die("Las contraseñas no coinciden");

}

/* VALIDAR CORREO */

if(
    !str_contains($correo, '@chetumal.tecnm.mx')
){

    die("Debe usar un correo institucional");

}

/* VALIDAR LONGITUD NO CONTROL */

if(strlen($noControl) != 8){

    die("Número de control inválido");

}
/*validar semestre*/
if(
    $semestreActual < 1 ||
    $semestreActual > 12
){

    die("Semestre inválido");

}

/* =========================================
   VALIDAR CORREO REPETIDO
========================================= */

$sql = "SELECT idUser
        FROM usuarios
        WHERE correoInst = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([$correo]);

if($stmt->fetch()){

    die("El correo ya está registrado");

}

/* =========================================
   VALIDAR NO CONTROL REPETIDO
========================================= */

$sql = "SELECT idEstudiante
        FROM estudiante
        WHERE noControl = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([$noControl]);

if($stmt->fetch()){

    die("El número de control ya existe");

}

/* =========================================
   HASH PASSWORD
========================================= */

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

/* =========================================
   TRANSACCIÓN
========================================= */

try{

    $conexion->beginTransaction();

    /* =====================================
       INSERT USUARIO
    ===================================== */

   /* =====================================
   INSERT USUARIO
===================================== */

$sql = "INSERT INTO usuarios
(
    correoInst,
    password,
    rol,
    fechaCreacion,
    activo
)

OUTPUT INSERTED.idUser

VALUES
(
    ?, ?, 'estudiante', GETDATE(), 'S'
)";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    $correo,
    $passwordHash
]);

$idUser = $stmt->fetchColumn();

/* DEBUG */

var_dump($idUser);
// die();
    /* =====================================
       INSERT ESTUDIANTE
    ===================================== */

   $sql = "INSERT INTO estudiante
(
    idCarrera,
    idUser,
    nombre,
    primerApellido,
    segundoApellido,
    noControl,
    cargaAcademicaCreditos
)

OUTPUT INSERTED.idEstudiante

VALUES
(
    ?, ?, ?, ?, ?, ?, 20
)";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

    $idCarrera,
    $idUser,

    $nombre,
    $primerApellido,
    $segundoApellido,

    $noControl

]);

$idEstudiante = $stmt->fetchColumn();


    /* =====================================
       CREAR EXPEDIENTE
    ===================================== */

    $sql = "INSERT INTO expedienteEstudiante
    (
        idEstudiante,
        fechaCreacionExpediente,
        estadoExpediente,
        creditosAcumulados,
        semestreActual
    )
    VALUES
    (
        ?,
        GETDATE(),
        'A',
        0,
        ?
    )";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        $idEstudiante,
        $semestreActual
    ]);

    /* =====================================
       CONFIRMAR TRANSACCIÓN
    ===================================== */

    $conexion->commit();

    header("Location: ../../index.html");
    exit;

}catch(Exception $e){

    $conexion->rollBack();

    die("Error: " . $e->getMessage());

}
   