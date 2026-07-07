<?php
include 'includes/conexion.php';
include 'includes/header.php';

$filtro_nombre = $_GET['nombre'] ?? '';
$filtro_categoria = $_GET['categoria'] ?? '';
$filtro_ubicacion = $_GET['ubicacion'] ?? '';
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$limite = 6;
$offset = ($pagina_actual - 1) * $limite;

// Condiciones dinámicas
$condiciones = [];
$parametros = [];

if (!empty($filtro_nombre)) {
    $condiciones[] = "nombre_empresa LIKE ?";
    $parametros[] = "%$filtro_nombre%";
}
if (!empty($filtro_categoria)) {
    $condiciones[] = "categoria = ?";
    $parametros[] = $filtro_categoria;
}
if (!empty($filtro_ubicacion)) {
    $condiciones[] = "ubicacion LIKE ?";
    $parametros[] = "%$filtro_ubicacion%";
}

$sql_base = "FROM emprendimientos";
if ($condiciones) {
    $sql_base .= " WHERE " . implode(" AND ", $condiciones);
}

// Total de registros
$stmt_total = $pdo->prepare("SELECT COUNT(*) $sql_base");
$stmt_total->execute($parametros);
$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $limite);

// Obtener resultados paginados
$stmt = $pdo->prepare("SELECT * $sql_base ORDER BY id DESC LIMIT $limite OFFSET $offset");
$stmt->execute($parametros);
$emprendimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="contenedor">
  <h2>Explora Emprendimientos</h2>

  <form method="GET" class="form-filtros" style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
    <input type="text" name="nombre" placeholder="Buscar por nombre..." value="<?php echo htmlspecialchars($filtro_nombre); ?>">

    <!-- Categoría dinámica -->
    <select name="categoria">
      <option value="">-- Todas las categorías --</option>
      <?php
      $sql_categorias = "SELECT DISTINCT categoria FROM emprendimientos ORDER BY categoria ASC";
      $stmt_cat = $pdo->query($sql_categorias);
      foreach ($stmt_cat->fetchAll(PDO::FETCH_COLUMN) as $cat):
        $seleccionado = ($filtro_categoria === $cat) ? 'selected' : '';
      ?>
        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $seleccionado; ?>>
          <?php echo htmlspecialchars($cat); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <input type="text" name="ubicacion" placeholder="Buscar por ubicación..." value="<?php echo htmlspecialchars($filtro_ubicacion); ?>">

    <button type="submit" class="btn-formulario">Buscar</button>
  </form>

  <?php if ($emprendimientos): ?>
    <div class="grid">
      <?php foreach ($emprendimientos as $emp): ?>
        <div class="card">
          <?php if ($emp['imagen']): ?>
            <img src="uploads/<?php echo htmlspecialchars($emp['imagen']); ?>" alt="Imagen del negocio">
          <?php endif; ?>
          <h3><?php echo htmlspecialchars($emp['nombre_empresa']); ?></h3>
          <p><?php echo htmlspecialchars($emp['descripcion']); ?></p>
          <small><?php echo htmlspecialchars($emp['categoria']); ?> | <?php echo htmlspecialchars($emp['ubicacion']); ?></small>
          <div class="acciones-card">
            <button class="btn-accion like"><i class="fas fa-thumbs-up"></i></button>
            <button class="btn-accion dislike"><i class="fas fa-thumbs-down"></i></button>
            <a href="#" class="btn-accion ver-detalle">Ver detalle</a>
</div>

        </div>
      <?php endforeach; ?>
    </div>

    <!-- Paginación -->
    <div class="paginacion" style="text-align:center; margin-top: 2rem;">
      <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>"
           class="btn <?php echo ($i === $pagina_actual) ? 'btn-formulario' : ''; ?>">
          <?php echo $i; ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php else: ?>
    <p>No se encontraron emprendimientos con esos criterios.</p>
  <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
