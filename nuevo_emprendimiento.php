<?php
include 'includes/conexion.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include 'includes/header.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $imagen_nombre = null;

    if ($nombre && $descripcion && $categoria && $ubicacion) {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $dir_subida = 'uploads/';
            $imagen_nombre = time() . "_" . basename($_FILES['imagen']['name']);
            $ruta_completa = $dir_subida . $imagen_nombre;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa);
        }

        $sql = "INSERT INTO emprendimientos 
            (usuario_id, nombre_empresa, descripcion, categoria, ubicacion, email, telefono, imagen)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_SESSION['usuario_id'],
            $nombre,
            $descripcion,
            $categoria,
            $ubicacion,
            $email,
            $telefono,
            $imagen_nombre
        ]);

        $mensaje = "Emprendimiento registrado con éxito.";
    } else {
        $mensaje = "Por favor completa todos los campos requeridos.";
    }
}
?>

<main class="contenedor">
  <h2><i class="fas fa-store"></i> Registrar nuevo emprendimiento</h2>

  <?php if ($mensaje): ?>
    <p class="mensaje-exito"><?php echo htmlspecialchars($mensaje); ?></p>
  <?php endif; ?>

  <form action="nuevo_emprendimiento.php" method="POST" enctype="multipart/form-data" class="formulario" id="form-emprendimiento">
    <label for="nombre"><i class="fas fa-briefcase"></i> Nombre del negocio:</label>
    <input type="text" name="nombre" id="nombre" placeholder="Ej: Café Creativo" required>

    <label for="descripcion"><i class="fas fa-align-left"></i> Descripción:</label>
    <textarea name="descripcion" id="descripcion" placeholder="Describe tu emprendimiento..." required></textarea>

    <label for="categoria"><i class="fas fa-tags"></i> Categoría:</label>
    <input type="text" name="categoria" id="categoria" placeholder="Ej: Alimentos, Educación, Tecnología..." required>

    <label for="ubicacion"><i class="fas fa-map-marker-alt"></i> Ubicación:</label>
    <input type="text" name="ubicacion" id="ubicacion" placeholder="Ej: Distrito Nacional" required>

    <label for="email"><i class="fas fa-envelope"></i> Correo electrónico:</label>
    <input type="email" name="email" id="email" placeholder="ejemplo@correo.com">

    <label for="telefono"><i class="fas fa-phone"></i> Teléfono:</label>
    <input type="tel" name="telefono" id="telefono" placeholder="809-000-0000">

    <label for="imagen"><i class="fas fa-image"></i> Imagen del negocio:</label>
    <input type="file" name="imagen" id="imagen" accept="image/*">

    <button type="submit" class="btn-formulario"><i class="fas fa-save"></i> Registrar</button>
  </form>
</main>

<script src="js/main.js"></script>
<?php include 'includes/footer.php'; ?>
