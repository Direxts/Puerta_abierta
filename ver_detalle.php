<?php
include 'includes/conexion.php';

if (!isset($_GET['id'])) {
    echo "<p>No se proporcionó un ID válido.</p>";
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM emprendimientos WHERE id = ?");
$stmt->execute([$id]);
$emprendimiento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emprendimiento) {
    echo "<p>No se encontró el emprendimiento.</p>";
    exit;
}
?>

<div class="modal-detalle">
  <?php if (!empty($emprendimiento['imagen'])): ?>
    <img src="uploads/<?php echo htmlspecialchars($emprendimiento['imagen']); ?>" alt="Logo" class="detalle-img" style="width:100%; max-height:250px; object-fit:cover;">
  <?php endif; ?>

  <h2><?php echo htmlspecialchars($emprendimiento['nombre_empresa']); ?></h2>
  <p><strong>Descripción:</strong><br><?php echo nl2br(htmlspecialchars($emprendimiento['descripcion'])); ?></p>

  <?php if (!empty($emprendimiento['categoria'])): ?>
    <p><strong>Categoría:</strong> <?php echo htmlspecialchars($emprendimiento['categoria']); ?></p>
  <?php endif; ?>

  <?php if (!empty($emprendimiento['ubicacion'])): ?>
    <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($emprendimiento['ubicacion']); ?></p>
  <?php endif; ?>

  <?php if (!empty($emprendimiento['email'])): ?>
    <p><strong>Correo:</strong> <a href="mailto:<?php echo htmlspecialchars($emprendimiento['email']); ?>"><?php echo htmlspecialchars($emprendimiento['email']); ?></a></p>
  <?php endif; ?>

  <?php if (!empty($emprendimiento['telefono'])): ?>
    <p><strong>Teléfono:</strong> <a href="tel:<?php echo htmlspecialchars($emprendimiento['telefono']); ?>"><?php echo htmlspecialchars($emprendimiento['telefono']); ?></a></p>
  <?php endif; ?>
</div>
