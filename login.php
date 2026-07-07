<?php
include 'includes/conexion.php';
session_start();
include 'includes/header.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        // Autenticación exitosa
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre']; // corregido: usar misma clave que en dashboard.php
        header("Location: dashboard.php");
        exit;
    } else {
        $mensaje = "❌ Correo o contraseña incorrectos.";
    }
}
?>

<main class="contenedor">
  <h2>Iniciar Sesión</h2>

  <?php if ($mensaje): ?>
    <p class="mensaje"><?php echo $mensaje; ?></p>
  <?php endif; ?>

  <form action="login.php" method="POST" class="formulario" id="form-login">
    <label for="email">Correo electrónico:</label>
    <input type="email" name="email" id="email" placeholder="correo@ejemplo.com" required>

    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" placeholder="Tu contraseña" required>

    <button type="submit" class="btn-formulario">Iniciar sesión</button>
  </form>
</main>

<script src="js/main.js"></script>
<?php include 'includes/footer.php'; ?>
