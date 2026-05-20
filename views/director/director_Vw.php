<?php

session_start();

/* =========================================
   VALIDAR SESIÓN
========================================= */

if(
    !isset($_SESSION['idUser'])
){

    header("Location: ../../index.html");
    exit;

}

/* =========================================
   VALIDAR ROL
========================================= */

if(
    $_SESSION['rol'] != 'director'
){

    die("Acceso denegado");

}

require_once "../../modules/login/conexion.php";

/* =========================================
   ACTIVIDADES APROBADAS POR COMITÉ
========================================= */

$sql = "SELECT

        ac.idActividadComplementarias,

        ac.nombreActividadComplementaria,
        ac.descripcionActividadComplementaria,

        ac.creditosAsignados,
        ac.cupoMax,

        ta.nombreTipoActComplementaria,

        arc.idRevisionActividadComplementaria,
        arc.observacion,
        arc.estadoRevision

        FROM actividadComplementaria ac

        INNER JOIN tipoActividad ta
        ON ac.idTipoActividadComplementaria =
        ta.idTipoActividadComplementario

        INNER JOIN ActividadComplementariaRevisada arc
        ON ac.idActividadComplementarias =
        arc.idActividadComplementarias

        WHERE ac.estadoActividad = 'D'

        ORDER BY ac.idActividadComplementarias DESC";

$stmt = $conexion->query($sql);

$actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
/* =========================================
   ACTIVIDADES PUBLICADAS HOY
========================================= */

$sql = "SELECT COUNT(*) AS totalPublicadas
        FROM catalogoActividadComplementaria
        WHERE fechaPublicacion = CAST(GETDATE() AS DATE)
        AND activo = 'S'";

$stmt = $conexion->query($sql);

$publicadasHoy = $stmt->fetch(PDO::FETCH_ASSOC);

/* =========================================
   ACTIVIDAD SELECCIONADA
========================================= */

$actividadSeleccionada = null;

if(isset($_GET['id'])){

    $idActividad = $_GET['id'];

   $sql = "SELECT

        ac.*,

        ta.nombreTipoActComplementaria,

        arc.idRevisionActividadComplementaria,
        arc.observacion,
        arc.estadoRevision

        FROM actividadComplementaria ac

        INNER JOIN tipoActividad ta
        ON ac.idTipoActividadComplementaria =
        ta.idTipoActividadComplementario

        INNER JOIN ActividadComplementariaRevisada arc
        ON ac.idActividadComplementarias =
        arc.idActividadComplementarias

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

  <meta name="viewport"
  content="width=device-width, initial-scale=1.0">

  <title>Director | TecNM</title>

  <!-- GOOGLE FONTS -->

  <link rel="preconnect"
  href="https://fonts.googleapis.com">

  <link rel="preconnect"
  href="https://fonts.gstatic.com"
  crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap"
  rel="stylesheet">

  <!-- FONT AWESOME -->

  <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- CSS -->

  <link rel="stylesheet"
  href="../../assets/css/director/director_Vw.css">

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

          <p>Dirección</p>

        </div>

      </div>

      <!-- MENU -->

      <nav class="sidebar-menu">

        <a href="director_Vw.php" class="active">

          <i class="fa-solid fa-house"></i>

          <span>Inicio</span>

        </a>

        <a href="#">

          <i class="fa-solid fa-file-circle-check"></i>

          <span>Pendientes autorización</span>

        </a>

        <a href="#">

          <i class="fa-solid fa-circle-check"></i>

          <span>Publicadas</span>

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

          <strong>
            Director
          </strong>

          <span>
            <?= $_SESSION['correo'] ?>
          </span>

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
          Autorización final
        </h1>

        <p>
          Revisión institucional de actividades complementarias
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

          <p>
            Pendientes autorización
          </p>

        </div>

      </article>

      <article class="card">

        <div class="card-icon">
          <i class="fa-solid fa-circle-check"></i>
        </div>

        <div>

        <h3>
            <?= $publicadasHoy['totalPublicadas'] ?>
        </h3>
          <p>
            Publicadas hoy
          </p>

        </div>

      </article>

    </section>

    <!-- TABLA -->

    <section class="panel">

      <div class="panel-header">

        <h2>
          Actividades aprobadas por comité
        </h2>

      </div>

      <table>

        <thead>

          <tr>

            <th>Actividad</th>
            <th>Tipo</th>
            <th>Créditos</th>
            <th>Cupo</th>
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
              <?= $actividad['creditosAsignados'] ?>
            </td>

            <td>
              <?= $actividad['cupoMax'] ?>
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

      <!-- PANEL REVISIÓN -->

      <?php if($actividadSeleccionada): ?>

      <section class="review-panel">

        <div class="panel-header">

          <h2>
            Dictamen final
          </h2>

        </div>

        <form
          action="../../modules/director/revisarDirector.php"
          method="POST"
        >

          <input
            type="hidden"
            name="idActividad"
            value="<?= $actividadSeleccionada['idActividadComplementarias'] ?>"
          >

          <input
            type="hidden"
            name="idRevision"
            value="<?= $actividadSeleccionada['idRevisionActividadComplementaria'] ?>"
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

          <!-- CRÉDITOS -->

          <div class="review-group">

            <label>
              Créditos asignados
            </label>

            <input
              type="text"
              value="<?= $actividadSeleccionada['creditosAsignados'] ?>"
              disabled
            >

          </div>

          <!-- CUPO -->

          <div class="review-group">

            <label>
              Cupo máximo
            </label>

            <input
              type="text"
              value="<?= $actividadSeleccionada['cupoMax'] ?>"
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

          <!-- OBSERVACIONES -->

          <div class="review-group">

            <label>
              Observaciones dirección
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
              Publicar actividad
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