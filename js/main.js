document.addEventListener('DOMContentLoaded', function () {
  // FORMULARIO
  const formularios = document.querySelectorAll('form');

  formularios.forEach(formulario => {
    formulario.addEventListener('submit', function (e) {
      const campos = formulario.querySelectorAll('input[required], textarea[required], select[required]');
      let valido = true;
      let mensaje = "";

      campos.forEach(campo => {
        const tipo = campo.type;
        const valor = campo.value.trim();
        const minLength = campo.getAttribute('minlength') || 3;
        const maxLength = campo.getAttribute('maxlength') || 200;

        if (!valor || valor.length < minLength || valor.length > maxLength) {
          marcarError(campo);
          valido = false;
        } else {
          quitarError(campo);
        }

        if (tipo === 'email' && !validarEmail(valor)) {
          marcarError(campo);
          mensaje = "Correo electrónico no válido.";
          valido = false;
        }

        if (tipo === 'password' && !validarPassword(valor)) {
          marcarError(campo);
          mensaje = "La contraseña debe tener al menos 6 caracteres, una mayúscula, una minúscula y un número.";
          valido = false;
        }

        if (tipo === 'tel' && !validarTelefono(valor)) {
          marcarError(campo);
          mensaje = "Número de teléfono no válido.";
          valido = false;
        }
      });

      const pass = formulario.querySelector('input[name="password"]');
      const confirm = formulario.querySelector('input[name="password_confirm"]');
      if (pass && confirm && pass.value !== confirm.value) {
        marcarError(confirm);
        mensaje = "Las contraseñas no coinciden.";
        valido = false;
      }

      if (!valido) {
        e.preventDefault();
        mostrarAlerta(mensaje || 'Por favor revisa los campos marcados.', formulario);
      }
    });
  });

  // BOTÓN VER DETALLE (Modal)
  document.querySelectorAll('.ver-detalle').forEach(boton => {
    boton.addEventListener('click', function () {
      const id = this.dataset.id;

      fetch(`ver_detalle.php?id=${id}`)
        .then(res => res.text())
        .then(html => {
          const contenedor = document.getElementById('detalle-emprendimiento');
          contenedor.innerHTML = html;

          const modal = document.getElementById('modal-detalle');
          modal.classList.remove('oculto');
          modal.classList.add('visible');
        })
        .catch(err => {
          alert("No se pudo cargar el detalle del emprendimiento.");
          console.error(err);
        });
    });
  });
});

// FUNCIONES AUXILIARES
function validarEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

function validarPassword(password) {
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/;
  return regex.test(password);
}

function validarTelefono(telefono) {
  const regex = /^[0-9]{7,15}$/;
  return regex.test(telefono);
}

function marcarError(campo) {
  campo.classList.add('error-campo');
}

function quitarError(campo) {
  campo.classList.remove('error-campo');
}

function mostrarAlerta(mensaje, contenedor) {
  const anterior = contenedor.querySelector('.mensaje-error');
  if (anterior) anterior.remove();

  const alerta = document.createElement('p');
  alerta.className = 'mensaje-error fade-in';
  alerta.textContent = mensaje;
  contenedor.prepend(alerta);
}

// Cierra el modal
function cerrarModal() {
  const modal = document.getElementById('modal-detalle');
  modal.classList.add('oculto');
  modal.classList.remove('visible');
}

// Like
function darLike(id) {
  fetch(`like.php?id=${id}&tipo=like`)
    .then(res => res.text())
    .then(mensaje => {
      if (mensaje.includes("Gracias")) {
        const contador = document.getElementById(`likes-${id}`);
        if (contador) {
          let actual = parseInt(contador.textContent);
          contador.textContent = actual + 1;
        }
      } else {
        alert(mensaje);
      }
    })
    .catch(err => console.error(err));
}

// Dislike
function darDislike(id) {
  fetch(`like.php?id=${id}&tipo=dislike`)
    .then(res => res.text())
    .then(mensaje => {
      if (!mensaje.includes("Gracias")) {
        alert(mensaje);
      }
    })
    .catch(err => console.error(err));
}
