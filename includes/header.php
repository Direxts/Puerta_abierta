<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>PUERTA ABIERTA</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- CSS Principal -->
  <link rel="stylesheet" href="css/style.css">

  <!-- Íconos Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header class="header-con-fondo">
  <div class="overlay">
    <div class="contenido-header">
      <a href="index.php">
        <img src="https://cdn-icons-png.flaticon.com/512/4467/4467338.png" alt="PUERTA ABIERTA" class="logo-img">
      </a>
      <h1>PUERTA ABIERTA</h1>
      <p>Conectamos y visibilizamos emprendedores locales</p>

      <nav class="navegacion-principal">
        <ul class="menu">
          <li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
          <li><a href="emprendimientos.php"><i class="fas fa-store"></i> Emprendimientos</a></li>
          <li><a href="nosotros.php"><i class="fas fa-users"></i> Nosotros</a></li>
          <li><a href="contacto.php"><i class="fas fa-envelope"></i> Contacto</a></li>

          <?php if (isset($_SESSION['usuario_id'])): ?>
            <li><a href="dashboard.php"><i class="fas fa-user-circle"></i> Mi Panel</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
          <?php else: ?>
            <li><a href="registro.php"><i class="fas fa-user-plus"></i> Registrarse</a></li>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</header>
