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
?>


<!-- departamento_Vw.php -->

<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Departamento | TecNM</title>

  <!-- Noto Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet"
  href="../../assets/css/departamento/departamento_Vw.css">

</head>

<body>

<div class="dashboard-layout">

  <!-- SIDEBAR -->

  <aside class="sidebar">

    <div>

      <div class="sidebar-header">

        <img
          src="https://upload.wikimedia.org/wikipedia/commons/5/55/TecNM_logo.png"
          alt="TecNM"
          class="sidebar-logo"
        >

        <div>

          <h2>TecNM</h2>

          <p>Panel Departamento</p>

        </div>

      </div>

      <!-- MENU -->

      <nav class="sidebar-menu">

        <a href="#" class="active">

          <i class="fa-solid fa-house"></i>

          <span>Inicio</span>

        </a>

        <a href="crearActividad_Vw.php">

          <i class="fa-solid fa-plus"></i>

          <span>Crear actividad</span>

        </a>

        <a href="#">

          <i class="fa-solid fa-book"></i>

          <span>Mis actividades</span>

        </a>

        <a href="#">

          <i class="fa-solid fa-clock"></i>

          <span>Pendientes</span>

        </a>

        <a href="#">

          <i class="fa-solid fa-circle-check"></i>

          <span>Publicadas</span>

        </a>

        <a href="#">

          <i class="fa-solid fa-user-tie"></i>

          <span>Responsables</span>

        </a>

        <a href="#">

          <i class="fa-solid fa-bell"></i>

          <span>Notificaciones</span>

        </a>

      </nav>

    </div>

    <!-- FOOTER -->

    <div class="sidebar-footer">

      <div class="user-box">

        <div class="user-avatar">

          <i class="fa-solid fa-building"></i>

        </div>

        <div>

          <strong>
    <?= ucfirst($_SESSION['rol']) ?>
</strong>

<span>
    <?= $_SESSION['correo'] ?>
</span>

        </div>

      </div>

      <a href="#">

        <i class="fa-solid fa-right-from-bracket"></i>

        <span>Cerrar sesión</span>

      </a>

    </div>

  </aside>

  <!-- MAIN -->

  <main class="main-content">

    <!-- TOPBAR -->

    <header class="topbar">

      <div>

        <h1>
          Panel de Departamento
        </h1>

        <p>
          Gestión de actividades complementarias institucionales
        </p>

      </div>

      <div class="topbar-actions">

        <button class="btn-primary">

          <i class="fa-solid fa-plus"></i>

          Nueva actividad

        </button>

      </div>

    </header>

    <!-- CARDS -->

    <section class="cards-grid">

      <article class="card">

        <div class="card-icon">

          <i class="fa-solid fa-book-open"></i>

        </div>

        <div>

          <h3>12</h3>

          <p>Actividades creadas</p>

        </div>

      </article>

      <article class="card">

        <div class="card-icon">

          <i class="fa-solid fa-clock"></i>

        </div>

        <div>

          <h3>4</h3>

          <p>Pendientes comité</p>

        </div>

      </article>

      <article class="card">

        <div class="card-icon">

          <i class="fa-solid fa-circle-check"></i>

        </div>

        <div>

          <h3>6</h3>

          <p>Publicadas</p>

        </div>

      </article>

      <article class="card">

        <div class="card-icon">

          <i class="fa-solid fa-circle-xmark"></i>

        </div>

        <div>

          <h3>2</h3>

          <p>Rechazadas</p>

        </div>

      </article>

    </section>

    <!-- TABLA -->

    <section class="panel">

      <div class="panel-header">

        <h2>
          Actividades recientes
        </h2>

        <a href="#">
          Ver todas
        </a>

      </div>

      <table>

        <thead>

          <tr>

            <th>Actividad</th>

            <th>Tipo</th>

            <th>Créditos</th>

            <th>Estado</th>

            <th>Fecha</th>

            <th>Acciones</th>

          </tr>

        </thead>

        <tbody>

          <tr>

            <td>Taller de Robótica</td>

            <td>Académica</td>

            <td>1.0</td>

            <td>

              <span class="status pending">
                Pendiente
              </span>

            </td>

            <td>18/05/2026</td>

            <td>

              <button class="table-btn">
                Ver
              </button>

            </td>

          </tr>

          <tr>

            <td>Concurso de Programación</td>

            <td>Académica</td>

            <td>2.0</td>

            <td>

              <span class="status approved">
                Publicada
              </span>

            </td>

            <td>15/05/2026</td>

            <td>

              <button class="table-btn">
                Ver
              </button>

            </td>

          </tr>

          <tr>

            <td>Torneo Deportivo</td>

            <td>Deportiva</td>

            <td>1.0</td>

            <td>

              <span class="status rejected">
                Rechazada
              </span>

            </td>

            <td>10/05/2026</td>

            <td>

              <button class="table-btn">
                Ver
              </button>

            </td>

          </tr>

        </tbody>

      </table>

    </section>

  </main>

</div>

</body>
</html>