<?php

session_start();

/* =========================================
   VALIDAR SESIÓN
========================================= */

if(!isset($_SESSION['idUser'])){

    header("Location: ../../index.html");
    exit;

}

/* =========================================
   VALIDAR ROL
========================================= */

if($_SESSION['rol'] != 'comite'){

    die("Acceso denegado");

}

require_once "../../modules/login/conexion.php";

/* =========================================
   ACTIVIDADES EN REVISIÓN
========================================= */

$sql = "SELECT
        ac.idActividadComplementarias,
        ac.nombreActividadComplementaria,
        ac.descripcionActividadComplementaria,
        ac.creditosAsignados,
        ac.estadoActividad,
        ta.nombreTipoActComplementaria
        FROM actividadComplementaria ac
        INNER JOIN tipoActividad ta
        ON ac.idTipoActividadComplementaria =
           ta.idTipoActividadComplementario
        WHERE ac.estadoActividad = 'R'
        ORDER BY ac.idActividadComplementarias DESC";

$stmt = $conexion->query($sql);

$actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================================
   ACTIVIDAD SELECCIONADA
========================================= */

$actividadSeleccionada = null;

if(isset($_GET['id'])){

    $idActividad = $_GET['id'];

    $sql = "SELECT
            ac.*,
            ta.nombreTipoActComplementaria
            FROM actividadComplementaria ac
            INNER JOIN tipoActividad ta
            ON ac.idTipoActividadComplementaria =
               ta.idTipoActividadComplementario
            WHERE ac.idActividadComplementarias = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([$idActividad]);

    $actividadSeleccionada =
        $stmt->fetch(PDO::FETCH_ASSOC);

}

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Comité Académico | TecNM</title>

<!-- Noto Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- CSS -->
<link rel="stylesheet"
href="../../assets/css/comite/comite_Vw.css">

</head>

<body>

<div class="dashboard-layout">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div>

            <div class="sidebar-header">

                <img
                    src="https://upload.wikimedia.org/wikipedia/commons/5/55/TecNM_logo.png"
                    class="sidebar-logo"
                    alt="TecNM"
                >

                <div>

                    <h2>TecNM</h2>
                    <p>Comité Académico</p>

                </div>

            </div>

            <!-- MENU -->

            <nav class="sidebar-menu">

                <a href="#" class="active">

                    <i class="fa-solid fa-house"></i>
                    <span>Inicio</span>

                </a>

                <a href="#">

                    <i class="fa-solid fa-list-check"></i>
                    <span>Actividades revisión</span>

                </a>

                <a href="#">

                    <i class="fa-solid fa-award"></i>
                    <span>Asignar créditos</span>

                </a>

                <a href="#">

                    <i class="fa-solid fa-circle-check"></i>
                    <span>Aprobadas</span>

                </a>

                <a href="#">

                    <i class="fa-solid fa-circle-xmark"></i>
                    <span>Rechazadas</span>

                </a>

            </nav>

        </div>

        <!-- FOOTER -->

        <div class="sidebar-footer">

            <div class="user-box">

                <div class="user-avatar">

                    <i class="fa-solid fa-user-tie"></i>

                </div>

                <div>

                    <strong><?= ucfirst($_SESSION['rol']) ?></strong>
                    <span> <?= $_SESSION['correo'] ?></span>

                </div>

            </div>

        </div>

    </aside>

    <!-- MAIN -->

    <main class="main-content">

        <!-- TOPBAR -->

        <header class="topbar">

            <div>

                <h1>
                    Revisión de actividades
                </h1>

                <p>
                    Asignación de créditos complementarios
                </p>

            </div>

        </header>

        <!-- CARDS -->

        <section class="cards-grid">

            <article class="card">

                <div class="card-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>

                <div>

                    <h3>
                        <?= count($actividades) ?>
                    </h3>

                    <p>Pendientes revisión</p>

                </div>

            </article>

            <article class="card">

                <div class="card-icon">
                    <i class="fa-solid fa-award"></i>
                </div>

                <div>

                    <h3>0</h3>

                    <p>Créditos asignados hoy</p>

                </div>

            </article>

        </section>

        <!-- TABLA -->

        <section class="panel">

            <div class="panel-header">

                <h2>
                    Actividades pendientes
                </h2>

            </div>

            <table>

                <thead>

                    <tr>

                        <th>Actividad</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach($actividades as $actividad): ?>

                    <tr>

                        <td>
                            <?= $actividad['nombreActividadComplementaria'] ?>
                        </td>

                        <td>
                            <?= $actividad['nombreTipoActComplementaria'] ?>
                        </td>

                        <td>

                            <span class="status pending">
                                En revisión
                            </span>

                        </td>

                        <td>

                            <a
href="?id=<?= $actividad['idActividadComplementarias'] ?>"
                            class="table-btn"
                            >
                                Revisar
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

            <?php if($actividadSeleccionada): ?>

<section class="review-panel">

    <div class="panel-header">

        <h2>
            Revisar actividad
        </h2>

    </div>

    <form
        action="../../modules/comite/revisarActividad.php"
        method="POST"
    >

        <input
            type="hidden"
            name="idActividad"
            value="<?= $actividadSeleccionada['idActividadComplementarias'] ?>"
        >

        <!-- NOMBRE -->

        <div class="review-group">

            <label>
                Nombre actividad
            </label>

            <input
                type="text"
                value="<?= $actividadSeleccionada['nombreActividadComplementaria'] ?>"
                disabled
            >

        </div>

        <!-- TIPO -->

        <div class="review-group">

            <label>
                Tipo actividad
            </label>

            <input
                type="text"
                value="<?= $actividadSeleccionada['nombreTipoActComplementaria'] ?>"
                disabled
            >

        </div>

        <!-- DESCRIPCIÓN -->

        <div class="review-group">

            <label>
                Descripción
            </label>

            <textarea disabled><?= $actividadSeleccionada['descripcionActividadComplementaria'] ?></textarea>

        </div>

        <!-- CREDITOS -->

        <div class="review-group">

            <label>
                Asignar créditos
            </label>

            <select
                name="creditosAsignados"
                required
            >

                <option value="">
                    Seleccione créditos
                </option>

                <option value="0.5">0.5</option>
                <option value="1">1.0</option>
                <option value="1.5">1.5</option>
                <option value="2">2.0</option>

            </select>

        </div>

        <!-- OBSERVACIONES -->

        <div class="review-group">

            <label>
                Observaciones
            </label>

            <textarea
                name="observaciones"
                placeholder="Ingrese observaciones..."
            ></textarea>

        </div>

        <!-- BOTONES -->

        <div class="review-actions">

            <button
                type="submit"
                name="accion"
                value="aprobar"
                class="btn-approve"
            >

                Aprobar

            </button>

            <button
                type="submit"
                name="accion"
                value="rechazar"
                class="btn-reject"
            >

                Rechazar

            </button>

        </div>

    </form>

</section>

<?php endif; ?>

        </section>

    </main>

</div>

</body>
</html>