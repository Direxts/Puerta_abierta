<?php
include 'includes/conexion.php';
session_start();

$usuario_id = $_SESSION['usuario_id'] ?? null;
$emprendimiento_id = intval($_GET['id']);
$tipo = $_GET['tipo'] ?? 'like';

if (!$usuario_id) {
    echo "Debes iniciar sesión.";
    exit;
}

if (!$emprendimiento_id || !in_array($tipo, ['like', 'dislike'])) {
    echo "Parámetros inválidos.";
    exit;
}

// Verificar si ya ha votado
$stmt = $pdo->prepare("SELECT tipo FROM likes WHERE usuario_id = ? AND emprendimiento_id = ?");
$stmt->execute([$usuario_id, $emprendimiento_id]);
$existe = $stmt->fetch();

if ($existe) {
    echo "Ya has votado.";
    exit;
}

// Registrar nuevo voto
$stmt = $pdo->prepare("INSERT INTO likes (usuario_id, emprendimiento_id, tipo) VALUES (?, ?, ?)");
$stmt->execute([$usuario_id, $emprendimiento_id, $tipo]);

echo "¡Gracias por tu voto!";
