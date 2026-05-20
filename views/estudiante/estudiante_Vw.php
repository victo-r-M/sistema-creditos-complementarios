

<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Dashboard | TecNM</title>

  <!-- Noto Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="../../assets/css/estudiante/estudiante_Vw.css">

</head>
<body>

<div class="dashboard-layout">

  <!-- SIDEBAR -->

  <aside class="sidebar">

    <div>

      <div class="sidebar-header">

        <img
          src="https://upload.wikimedia.org/wikipedia/commons/5/55/TecNM_logo.png"
          alt="Logo TecNM"
          class="sidebar-logo"
        >

        <div>

          <h2>TecNM</h2>

          <p>Créditos Complementarios</p>

        </div>

      </div>

      <nav class="sidebar-menu">

        <a href="#" class="active">
          <i class="fa-solid fa-house"></i>
          <span>Inicio</span>
        </a>

        <a href="#">
          <i class="fa-solid fa-book"></i>
          <span>Actividades</span>
        </a>

        <a href="#">
          <i class="fa-solid fa-file-circle-check"></i>
          <span>Mis evidencias</span>
        </a>

        <a href="#">
          <i class="fa-solid fa-chart-column"></i>
          <span>Evaluaciones</span>
        </a>

        <a href="#">
          <i class="fa-solid fa-award"></i>
          <span>Constancias</span>
        </a>

        <a href="#">
          <i class="fa-solid fa-user"></i>
          <span>Perfil</span>
        </a>

      </nav>

    </div>

    <div class="sidebar-footer">

      <div class="user-box">

        <div class="user-avatar">
          <i class="fa-solid fa-user"></i>
        </div>

        <div>


          <span>Estudiante</span>

        </div>

      </div>

     

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

        <h1>Panel Principal</h1>

        <p>
          Bienvenido al sistema institucional TecNM
        </p>

      </div>

      <div class="topbar-actions">

        <button>
          <i class="fa-regular fa-bell"></i>
        </button>

      </div>

    </header>

    <!-- CARDS -->

    <section class="cards-grid">

      <article class="card">

        <div class="card-icon">
          <i class="fa-solid fa-graduation-cap"></i>
        </div>

        <div>

          <h3>2.0</h3>

          <p>Créditos acumulados</p>

        </div>

      </article>

      <article class="card">

        <div class="card-icon">
          <i class="fa-solid fa-book-open"></i>
        </div>

        <div>

          <h3>3</h3>

          <p>Actividades activas</p>

        </div>

      </article>

      <article class="card">

        <div class="card-icon">
          <i class="fa-solid fa-file-circle-exclamation"></i>
        </div>

        <div>

          <h3>1</h3>

          <p>Evidencias pendientes</p>

        </div>

      </article>

      <article class="card">

        <div class="card-icon">
          <i class="fa-solid fa-chart-simple"></i>
        </div>

        <div>

          <h3>Notable</h3>

          <p>Último desempeño</p>

        </div>

      </article>

    </section>

    <!-- CONTENT GRID -->

    <section class="content-grid">

      <!-- ACTIVIDAD -->

      <section class="panel large-panel">

        <div class="panel-header">

          <h2>Actividad reciente</h2>

          <a href="#">Ver más</a>

        </div>

        <table>

          <thead>
            <tr>
              <th>Fecha</th>
              <th>Actividad</th>
              <th>Estado</th>
            </tr>
          </thead>

          <tbody>

            <tr>
              <td>12/05/2026</td>
              <td>Entrega de evidencia</td>
              <td>
                <span class="status approved">
                  Enviado
                </span>
              </td>
            </tr>

            <tr>
              <td>10/05/2026</td>
              <td>Inscripción actividad cultural</td>
              <td>
                <span class="status pending">
                  Pendiente
                </span>
              </td>
            </tr>

            <tr>
              <td>08/05/2026</td>
              <td>Evaluación registrada</td>
              <td>
                <span class="status approved">
                  Completado
                </span>
              </td>
            </tr>

          </tbody>

        </table>

      </section>

      <!-- PANEL DERECHO -->

      <section class="side-panels">

        <article class="panel">

          <div class="panel-header">
            <h2>Acciones rápidas</h2>
          </div>

          <div class="quick-actions">

            <button>
              <i class="fa-solid fa-plus"></i>
              Inscribirse
            </button>

            <button>
              <i class="fa-solid fa-upload"></i>
              Subir evidencia
            </button>

            <button>
              <i class="fa-solid fa-file"></i>
              Ver constancias
            </button>

          </div>

        </article>

        <article class="panel">

          <div class="panel-header">
            <h2>Avisos</h2>
          </div>

          <div class="notice-list">

            <div class="notice-item">
              Periodo de inscripciones abierto.
            </div>

            <div class="notice-item">
              Actualice sus evidencias pendientes.
            </div>

            <div class="notice-item">
              Revise sus evaluaciones recientes.
            </div>

          </div>

        </article>

      </section>

    </section>

  </main>

</div>

</body>
</html>

