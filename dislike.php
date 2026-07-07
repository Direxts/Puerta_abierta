<?php
session_start();
include 'includes/conexion.php';

$emprendimiento_id = intval($_GET['id'] ?? 0);
$usuario_id = $_SESSION['usuario_id'] ?? null;

if ($emprendimiento_id && $usuario_id) {
  $stmt = $pdo->prepare("INSERT INTO likes (emprendimiento_id, usuario_id, tipo) VALUES (?, ?, 'dislike')");
  $stmt->execute([$emprendimiento_id, $usuario_id]);
}
?>
