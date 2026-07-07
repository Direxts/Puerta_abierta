<?php
session_start();
session_unset();  // Limpia todas las variables de sesión
session_destroy();  // Destruye la sesión

// Redirige al login o página de inicio
header("Location: login.php");
exit;
