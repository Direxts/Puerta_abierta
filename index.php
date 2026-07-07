<?php
include 'includes/conexion.php';
session_start();

$usuario_id = $_SESSION['usuario_id'] ?? null;

// Empresas más recientes
$sql_recientes = "
  SELECT e.*, 
    (SELECT COUNT(*) FROM likes l WHERE l.emprendimiento_id = e.id AND l.tipo = 'like') AS total_likes
  FROM emprendimientos e
  ORDER BY e.id DESC
  LIMIT 3";
$stmt_recientes = $pdo->query($sql_recientes);
$empresas_recientes = $stmt_recientes->fetchAll(PDO::FETCH_ASSOC);

// Empresas favoritas por likes
$sql_favoritas = "
  SELECT e.*, 
    COUNT(l.id) AS total_likes
  FROM emprendimientos e
  LEFT JOIN likes l ON l.emprendimiento_id = e.id AND l.tipo = 'like'
  GROUP BY e.id
  ORDER BY total_likes DESC
  LIMIT 3";
$stmt_favoritas = $pdo->query($sql_favoritas);
$empresas_favoritas = $stmt_favoritas->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="style.css">
<main class="principal">
  <section class="sobre-nosotros contenedor">
    <h2><i class="fas fa-hands-helping"></i> Sobre Nosotros</h2>
    <p>PUERTA ABIERTA es una plataforma que impulsa a emprendedores locales brindándoles visibilidad digital y oportunidades de crecimiento.</p>
    
  </section>

  <section class="empresas-destacadas contenedor">
    <h2><i class="fas fa-star"></i> Empresas Favoritas</h2>
    <div class="grid">
      <?php foreach ($empresas_favoritas as $e): ?>
        <div class="card">
          <?php if (!empty($e['imagen'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($e['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($e['nombre_empresa']); ?>">
          <?php endif; ?>
          <h3><?php echo htmlspecialchars($e['nombre_empresa']); ?></h3>
          <p><?php echo htmlspecialchars(substr($e['descripcion'], 0, 100)); ?>...</p>
          <div class="acciones">
            <button class="btn btn-ver ver-detalle" data-id="<?php echo $e['id']; ?>"><i class="fas fa-eye"></i> Ver más</button>
            <button class="btn-like" onclick="darLike(<?php echo $e['id']; ?>)">
              <i class="fas fa-thumbs-up"></i> Me gusta (<span id="likes-<?php echo $e['id']; ?>"><?php echo $e['total_likes']; ?></span>)
            </button>
            <?php if ($usuario_id): ?>
              <button onclick="darDislike(<?php echo $e['id']; ?>)" class="btn-dislike"><i class="fas fa-thumbs-down"></i> No me gusta</button>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="empresas-destacadas contenedor">
    <h2><i class="fas fa-clock"></i> Empresas Recientes</h2>
    <div class="grid">
      <?php foreach ($empresas_recientes as $e): ?>
        <div class="card">
          <?php if (!empty($e['imagen'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($e['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($e['nombre_empresa']); ?>">
          <?php endif; ?>
          <h3><?php echo htmlspecialchars($e['nombre_empresa']); ?></h3>
          <p><?php echo htmlspecialchars(substr($e['descripcion'], 0, 100)); ?>...</p>
          <div class="acciones">
            <button class="btn btn-ver ver-detalle" data-id="<?php echo $e['id']; ?>"><i class="fas fa-eye"></i> Ver más</button>
            <button class="btn-like" onclick="darLike(<?php echo $e['id']; ?>)">
              <i class="fas fa-thumbs-up"></i> Me gusta (<span id="likes-<?php echo $e['id']; ?>"><?php echo $e['total_likes']; ?></span>)
            </button>
            <?php if ($usuario_id): ?>
              <button onclick="darDislike(<?php echo $e['id']; ?>)" class="btn-dislike"><i class="fas fa-thumbs-down"></i> No me gusta</button>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="cta contenedor">
    <h2>¿Tienes un emprendimiento?</h2>
    <p><a href="registro.php" class="btn">Regístrate</a> y da a conocer tu negocio al mundo.</p>
  </section>
</main>

<!-- Modal Dinámico -->
<div id="modal-detalle" class="modal oculto">
  <div class="modal-contenido">
    <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
    <div id="detalle-emprendimiento" class="contenido-dinamico">
      <!-- Aquí se carga el contenido desde ver_detalle.php -->
    </div>
  </div>
</div>



<script src="js/main.js" defer></script>
<?php include 'includes/footer.php'; ?>
