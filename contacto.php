<?php include 'includes/header.php'; ?>

<main class="contenedor">
  <h2>Contacto</h2>

  <p>¿Tienes alguna duda, sugerencia o deseas más información? ¡Escríbenos!</p>

  <?php if (isset($_GET['enviado']) && $_GET['enviado'] == 'true'): ?>
    <p class="mensaje-exito">Tu mensaje fue enviado correctamente. Gracias por contactarnos.</p>
  <?php endif; ?>

  <form action="contacto.php?enviado=true" method="POST" id="formContacto">
    <label for="nombre">Nombre completo:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="correo">Correo electrónico:</label>
    <input type="email" id="correo" name="correo" required>

    <label for="telefono">Teléfono:</label>
    <input type="tel" id="telefono" name="telefono" required>

    <label for="mensaje">Mensaje:</label>
    <textarea id="mensaje" name="mensaje" rows="5" required></textarea>

    <button type="submit" class="btn-formulario">Enviar mensaje</button>
  </form>

  <section>
    <h3>Información de contacto</h3>
    <p><strong>Email:</strong> info@puertaabierta.org</p>
    <p><strong>Teléfono:</strong> +1 809-123-4567</p>
    <p><strong>Dirección:</strong> Calle Principal #45, Distrito Nacional, República Dominicana</p>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
