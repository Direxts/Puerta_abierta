function mostrarModal(id) {
  fetch('ver_detalles.php?id=' + id)
    .then(response => response.text())
    .then(data => {
      document.getElementById('contenido-modal').innerHTML = data;
      document.getElementById('modal-emprendimiento').style.display = 'block';
    });
}

function cerrarModal() {
  document.getElementById('modal-emprendimiento').style.display = 'none';
}
