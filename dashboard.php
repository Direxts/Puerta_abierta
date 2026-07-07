<?php
include 'includes/conexion.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include 'includes/header.php';

// Obtener los emprendimientos del usuario
$stmt = $pdo->prepare("SELECT * FROM emprendimientos WHERE usuario_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$emprendimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="contenedor">
  <h2 class="titulo-bienvenida">
    <i class="fas fa-briefcase"></i> Bienvenido/a, 
    <span class="nombre-usuario">
      <?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario'; ?>
    </span>
  </h2>

  <a href="nuevo_emprendimiento.php" class="btn" style="margin-bottom: 2rem;">
    <i class="fas fa-plus-circle"></i> Registrar nuevo emprendimiento
  </a>

  <?php if (count($emprendimientos) > 0): ?>
    <div class="grid">
      <?php foreach ($emprendimientos as $e): ?>
        <div class="card">
          <?php if (!empty($e['imagen'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($e['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($e['nombre_empresa']); ?>">
          <?php endif; ?>

          <h3><?php echo htmlspecialchars($e['nombre_empresa']); ?></h3>
          <p><?php echo nl2br(htmlspecialchars($e['descripcion'])); ?></p>
          <p><strong>Categoría:</strong> <?php echo htmlspecialchars($e['categoria']); ?></p>
          <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($e['ubicacion']); ?></p>

          <?php if (!empty($e['email'])): ?>
            <p><strong><i class="fas fa-envelope"></i> Correo:</strong> 
              <a href="mailto:<?php echo htmlspecialchars($e['email']); ?>">
                <?php echo htmlspecialchars($e['email']); ?>
              </a>
            </p>
          <?php endif; ?>

          <?php if (!empty($e['telefono'])): ?>
            <p><strong><i class="fas fa-phone"></i> Teléfono:</strong> 
              <a href="tel:<?php echo htmlspecialchars($e['telefono']); ?>">
                <?php echo htmlspecialchars($e['telefono']); ?>
              </a>
            </p>
          <?php endif; ?>

          <div class="acciones">
            <a href="editar_emprendimiento.php?id=<?php echo $e['id']; ?>" class="btn btn-azul">
              <i class="fas fa-edit"></i> Editar
            </a>
            <a href="eliminar_emprendimiento.php?id=<?php echo $e['id']; ?>" class="btn btn-rojo" onclick="return confirm('¿Estás seguro de eliminar este emprendimiento?')">
              <i class="fas fa-trash"></i> Eliminar
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No has registrado ningún emprendimiento aún.</p>
  <?php endif; ?>
</main>

<script src="js/main.js"></script>
<?php include 'includes/footer.php'; ?>
