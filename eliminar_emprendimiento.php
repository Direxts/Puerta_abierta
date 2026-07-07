<?php
include 'includes/conexion.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Validar ID recibido
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$emprendimiento_id = $_GET['id'];

// Obtener la imagen para eliminarla también del servidor
$sql_imagen = "SELECT imagen FROM emprendimientos WHERE id = ? AND usuario_id = ?";
$stmt_imagen = $pdo->prepare($sql_imagen);
$stmt_imagen->execute([$emprendimiento_id, $usuario_id]);
$emprendimiento = $stmt_imagen->fetch(PDO::FETCH_ASSOC);

if ($emprendimiento) {
    // Eliminar archivo de imagen si existe
    if (!empty($emprendimiento['imagen']) && file_exists('uploads/' . $emprendimiento['imagen'])) {
        unlink('uploads/' . $emprendimiento['imagen']);
    }

    // Eliminar emprendimiento
    $sql = "DELETE FROM emprendimientos WHERE id = ? AND usuario_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$emprendimiento_id, $usuario_id]);

    echo "<script>
        alert('Emprendimiento eliminado correctamente.');
        window.location.href = 'dashboard.php';
    </script>";
    exit;
} else {
    // No existe o no pertenece al usuario
    header("Location: dashboard.php");
    exit;
}
?>
