<?php
include 'includes/conexion.php';
include 'includes/header.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql_check = "SELECT * FROM usuarios WHERE email = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$email]);

        if ($stmt_check->rowCount() > 0) {
            $mensaje = "Este correo ya está registrado.";
        } else {
            $sql_insert = "INSERT INTO usuarios (nombre, email, telefono, password) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql_insert);
            if ($stmt->execute([$nombre, $email, $telefono, $password_hash])) {
                $mensaje = "Registro exitoso. Ya puedes iniciar sesión.";
            } else {
                $mensaje = "Ocurrió un error al registrar.";
            }
        }
    }
}
?>

<main class="contenedor">
  <h2>Registro de Usuario</h2>

  <?php if ($mensaje): ?>
    <p class="mensaje-error"><?php echo $mensaje; ?></p>
  <?php endif; ?>

  <form action="registro.php" method="POST">
    <label for="nombre">Nombre completo:</label>
    <input type="text" name="nombre" id="nombre" required minlength="3" maxlength="100">

    <label for="email">Correo electrónico:</label>
    <input type="email" name="email" id="email" required>

    <label for="telefono">Teléfono:</label>
    <input type="tel" name="telefono" id="telefono" required pattern="[0-9]{7,15}" placeholder="Ej. 8091234567">

    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" required minlength="6">
    <small class="ayuda-campo">Mínimo 6 caracteres, una mayúscula, una minúscula y un número.</small>


    <label for="password_confirm">Confirmar contraseña:</label>
    <input type="password" name="password_confirm" id="password_confirm" required>

    <button type="submit">Registrarse</button>
  </form>
</main>

<?php include 'includes/footer.php'; ?>
