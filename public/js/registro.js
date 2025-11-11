document.addEventListener('DOMContentLoaded', function() {
    const errorDiv = document.querySelector('.errores-container');
    const mensajeError = document.getElementById('mensaje-error');
    const aceptarBtn = errorDiv?.querySelector('button');

    // Botón para cerrar la ventana de error
    aceptarBtn.addEventListener('click', function() {
        errorDiv.style.display = 'none';
        mensajeError.innerHTML = ''; // limpiamos mensajes
    });

    // Manejamos el submit del formulario
 document.getElementById('form-registro').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    
    // Unir el prefijo del país con el número de teléfono
    const pais = formData.get('pais');
    const telefono = formData.get('telefono');
    const telefonoCompleto = pais + telefono;

    formData.set('telefono', telefonoCompleto);

    const response = await fetch('/registro', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    if (!result.success) {
        let html = '';
        for (const campo in result.errors) {
            result.errors[campo].forEach(err => {
                html += `<p>${err}</p>`;
            });
        }

        mensajeError.innerHTML = html;
        errorDiv.style.display = 'flex';
    } else {
        window.location.href = "/exitoregistro";
    }
});

});

 