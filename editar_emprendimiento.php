<?php
include 'includes/conexion.php';
session_start();
include 'includes/header.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener ID del emprendimiento
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<p>ID de emprendimiento no válido.</p>";
    exit;
}

// Obtener datos del emprendimiento
$stmt = $pdo->prepare("SELECT * FROM emprendimientos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $_SESSION['usuario_id']]);
$emprendimiento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emprendimiento) {
    echo "<p>Emprendimiento no encontrado o no autorizado.</p>";
    exit;
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_empresa = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    
    $imagen = $emprendimiento['imagen'];
    if (!empty($_FILES['imagen']['name'])) {
        $nombre_imagen = basename($_FILES["imagen"]["name"]);
        $ruta_imagen = "uploads/" . $nombre_imagen;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_imagen);
        $imagen = $nombre_imagen;
    }

    // Actualizar datos
    $sql = "UPDATE emprendimientos SET nombre_empresa = ?, descripcion = ?, categoria = ?, ubicacion = ?, imagen = ? WHERE id = ? AND usuario_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre_empresa, $descripcion, $categoria, $ubicacion, $imagen, $id, $_SESSION['usuario_id']]);

    header("Location: dashboard.php");
    exit;
}
?>

<main class="contenedor">
  <h2><i class="fas fa-edit"></i> Editar Emprendimiento</h2>

  <form action="" method="POST" enctype="multipart/form-data" class="formulario">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($emprendimiento['nombre_empresa']); ?>" required>

    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" id="descripcion" required><?php echo htmlspecialchars($emprendimiento['descripcion']); ?></textarea>

    <label for="categoria">Categoría:</label>
    <input type="text" name="categoria" id="categoria" value="<?php echo htmlspecialchars($emprendimiento['categoria']); ?>" required>

    <label for="ubicacion">Ubicación:</label>
    <input type="text" name="ubicacion" id="ubicacion" value="<?php echo htmlspecialchars($emprendimiento['ubicacion']); ?>" required>

    <label for="imagen">Imagen:</label>
    <input type="file" name="imagen" id="imagen">

    <?php if ($emprendimiento['imagen']): ?>
      <p>Imagen actual:</p>
      <img src="uploads/<?php echo htmlspecialchars($emprendimiento['imagen']); ?>" alt="Imagen actual" width="150">
    <?php endif; ?>

    <button type="submit" class="btn-formulario">Actualizar</button>
  </form>
</main>

<script src="js/main.js"></script>
<?php include 'includes/footer.php'; ?>
