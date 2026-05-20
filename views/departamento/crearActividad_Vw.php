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
    $_SESSION['rol'] != 'departamento'
){

    die("Acceso denegado");

}

/* =========================================
   CONEXIÓN
========================================= */

require_once "../../modules/login/conexion.php";

/* =========================================
   OBTENER TIPOS ACTIVIDAD
========================================= */

$sql = "SELECT
        idTipoActividadComplementario,
        nombreTipoActComplementaria
        FROM tipoActividad
        ORDER BY nombreTipoActComplementaria";

$stmt = $conexion->query($sql);

$tipoActividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Crear Actividad | TecNM</title>

  <!-- Noto Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet"
  href="../../assets/css/departamento/crearActividad_Vw.css">

</head>

<body>

<div class="page-container">

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

          <p>Departamento</p>

        </div>

      </div>

      <nav class="sidebar-menu">

        <a href="#">
          <i class="fa-solid fa-house"></i>
          <span>Inicio</span>
        </a>

        <a href="#" class="active">
          <i class="fa-solid fa-plus"></i>
          <span>Crear actividad</span>
        </a>

        <a href="#">
          <i class="fa-solid fa-book"></i>
          <span>Mis actividades</span>
        </a>

      </nav>

    </div>

  </aside>

  <!-- MAIN -->

  <main class="main-content">

    <!-- TOPBAR -->

    <header class="topbar">

      <div>

        <h1>
          Crear actividad complementaria
        </h1>

        <p>
          Registro institucional de actividades académicas
        </p>

      </div>

    </header>

    <!-- FORMULARIO -->

    <section class="form-panel">

      <div class="form-header">

        <h2>Información de la actividad</h2>

        <p>
          Complete correctamente los datos requeridos
        </p>

      </div>

      <form action="../../modules/departamento/crearActividad.php" method="POST">

        <!-- GRID -->

        <div class="form-grid">

          <!-- NOMBRE -->

          <div class="input-group full-width">

            <label>
              Nombre de la actividad
            </label>

            <div class="input-wrapper">

              <i class="fa-solid fa-book-open"></i>

              <input
                type="text"
                name="nombreActividad"
                placeholder="Ingrese el nombre de la actividad"
                required
              >

            </div>

          </div>

          <!-- TIPO -->

          <div class="input-group">

            <label>
              Tipo de actividad
            </label>

            <div class="input-wrapper">

              <i class="fa-solid fa-layer-group"></i>

              <select name="tipoActividad" required>

                <option value="">
                  Seleccione tipo de actividad
                </option>

                 <?php foreach($tipoActividades as $tipoActividad): ?>

    <option value="<?= $tipoActividad['idTipoActividadComplementario'] ?>">

      <?= $tipoActividad['nombreTipoActComplementaria'] ?>

    </option>

  <?php endforeach; ?>

              </select>

            </div>

          </div>

          <!-- CREDITOS 

          <div class="input-group">

            <label>
              Créditos asignados
            </label>

            <div class="input-wrapper">

              <i class="fa-solid fa-award"></i>

              <select name="creditos" required>

                <option value="">
                  Seleccione
                </option>

                <option value="0.5">
                  0.5
                </option>

                <option value="1">
                  1.0
                </option>

                <option value="1.5">
                  1.5
                </option>

                <option value="2">
                  2.0
                </option>

              </select>

            </div>

          </div>-->

          <!-- DESCRIPCIÓN -->

          <div class="input-group full-width">

            <label>
              Descripción
            </label>

            <textarea
              name="descripcion"
              placeholder="Describa la actividad complementaria..."
              required
            ></textarea>

          </div>

        </div>

        <!-- BOTONES -->

        <div class="form-actions">

          <button type="button" class="btn-secondary">
            Cancelar
          </button>

          <button type="submit" class="btn-primary">

            <i class="fa-solid fa-paper-plane"></i>

            Enviar a revisión

          </button>

        </div>

      </form>

    </section>

  </main>

</div>

</body>
</html>