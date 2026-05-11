<?php

require_once "../../modules/login/conexion.php";

$sql = "SELECT
        idCarrera,
        nombreCarrera
        FROM carrera
        ORDER BY nombreCarrera";

$stmt = $conexion->query($sql);

$carreras = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- register.html -->
<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Crear Cuenta | TecNM</title>

  <!-- Noto Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="../../assets/css/login/crearCuenta.css">

</head>

<body>

  <main class="register-wrapper">

    <!-- HEADER -->

    <header class="topbar">

      <div class="topbar-pattern"></div>

      <div class="topbar-content">

        <div class="logo-section">

          <img
            src="https://upload.wikimedia.org/wikipedia/commons/5/55/TecNM_logo.png"
            alt="Logo TecNM"
            class="logo"
          >

          <div>

            <h1>TecNM</h1>

            <p>
              Sistema de Créditos Complementarios
            </p>

          </div>

        </div>

      </div>

    </header>

    <!-- CONTENIDO -->

    <section class="register-container">

      <!-- PANEL IZQUIERDO -->

      <section class="left-panel">

        <div class="watermark"></div>

        <div class="left-content">

          <span class="badge">
            Registro Institucional Estudiantil
          </span>

          <h2>
            Crea tu cuenta académica
          </h2>

          <p>
            Registra tu acceso institucional para participar en
            actividades complementarias, seguimiento de evidencias
            y liberación de créditos académicos.
          </p>

          <div class="features">

            <div class="feature-item">
              <i class="fa-solid fa-user-graduate"></i>
              <span>Acceso estudiantil institucional</span>
            </div>

            <div class="feature-item">
              <i class="fa-solid fa-shield-halved"></i>
              <span>Información protegida y segura</span>
            </div>

            <div class="feature-item">
              <i class="fa-solid fa-file-circle-check"></i>
              <span>Gestión académica centralizada</span>
            </div>

          </div>

          <a href="../../index.html" class="btn-secondary">
            Volver al inicio de sesión
          </a>

        </div>

      </section>

      <!-- PANEL DERECHO -->

      <section class="right-panel">

        <div class="form-container">

          <div class="form-header">

            <h3>Crear cuenta</h3>

            <p>
              Complete la información requerida
            </p>

          </div>

<form action="../../modules/login/crearCuenta.php" method="POST">

            <!-- GRID -->

            <div class="form-grid">

              <div class="input-group">

                <label>Nombre</label>

                <div class="input-wrapper">

                  <i class="fa-regular fa-user"></i>

                  <input
                    type="text"
                    name="nombre"
                    placeholder="Ingrese su nombre"
                    required
                  >

                </div>

              </div>

              <div class="input-group">

                <label>Primer apellido</label>

                <div class="input-wrapper">

                  <i class="fa-regular fa-user"></i>

                  <input
                    type="text"
                     name="primerApellido"
                    placeholder="Primer apellido"
                    required
                  >

                </div>

              </div>

            </div>

            <div class="form-grid">

              <div class="input-group">

                <label>Segundo apellido</label>

                <div class="input-wrapper">

                  <i class="fa-regular fa-user"></i>

                  <input
                    type="text"
                     name="segundoApellido"
                    placeholder="Segundo apellido"
                  >

                </div>

              </div>

              <div class="input-group">

                <label>No. Control</label>

                <div class="input-wrapper">

                  <i class="fa-solid fa-id-card"></i>

                  <input
                    type="text"
                    name="noControl"
                    placeholder="Ej. 22490001"
                    required
                  >

                </div>

              </div>

            </div>
<div class="input-group">

  <label>Semestre actual</label>

  <div class="input-wrapper">

    <i class="fa-solid fa-book"></i>

    <select name="semestreActual" required>

      <option value="">
        Seleccione semestre
      </option>

      <option value="1">1° Semestre</option>
      <option value="2">2° Semestre</option>
      <option value="3">3° Semestre</option>
      <option value="4">4° Semestre</option>
      <option value="5">5° Semestre</option>
      <option value="6">6° Semestre</option>
      <option value="7">7° Semestre</option>
      <option value="8">8° Semestre</option>
      <option value="9">9° Semestre</option>
      <option value="10">10° Semestre</option>
      <option value="11">11° Semestre</option>
      <option value="12">12° Semestre</option>

    </select>

  </div>

</div>

            <div class="input-group">

              <label>Carrera</label>

              <div class="input-wrapper">

                <i class="fa-solid fa-graduation-cap"></i>

                <select name="idCarrera" required>

  <option value="">
    Seleccione una carrera
  </option>

  <?php foreach($carreras as $carrera): ?>

    <option value="<?= $carrera['idCarrera'] ?>">

      <?= $carrera['nombreCarrera'] ?>

    </option>

  <?php endforeach; ?>

</select>

              </div>

            </div>

             <div class="input-group">

              <label>Correo institucional</label>

              <div class="input-wrapper">

                <i class="fa-regular fa-envelope"></i>

                <input
                  type="email"
                  name="correo" 
                  placeholder="usuario@chetumal.tecnm.mx"
                  required
                >

              </div>

            </div>

           

            <div class="form-grid">

              <div class="input-group">

                <label>Contraseña</label>

                <div class="input-wrapper">

                  <i class="fa-solid fa-lock"></i>

                  <input
                    type="password"
                    name="password"
                    placeholder="Ingrese contraseña"
                    required
                  >

                </div>

              </div>

              <div class="input-group">

                <label>Confirmar contraseña</label>

                <div class="input-wrapper">

                  <i class="fa-solid fa-lock"></i>

                  <input
                    type="password"
                    name="confirmPassword"
                    placeholder="Repita contraseña"
                    required
                  >

                </div>

              </div>

            </div>

            <div class="terms">

              <label>

                <input type="checkbox" required>

                <span>
                  Acepto los términos y condiciones institucionales
                </span>

              </label>

            </div>

            <button type="submit" class="btn-primary">
              Crear cuenta institucional
            </button>

</form>

        </div>

      </section>

    </section>

  </main>

</body>
</html>