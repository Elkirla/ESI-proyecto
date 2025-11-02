document.addEventListener('DOMContentLoaded', function() {
    const enviarPagoBtn = document.getElementById('btn-pagar');
    const formPago = document.getElementById('form-pago');
    const textoError = document.getElementById("mensaje-error");

    async function cargarMonto() {
        const mensualidadResp = await fetch("/obtener-mensualidad");
        const mensualidadData = await mensualidadResp.json();
        const montoMensual = mensualidadData[0].valor;
        document.getElementById("MontoMensual").innerText = `Monto: $${montoMensual}`;
    }

    cargarMonto();
    enviarPagoBtn.addEventListener('click', procesarPago);

  async function procesarPago(e) {
    e.preventDefault();

    textoError.style.display = "none";

    const archivo = document.getElementById("archivo").files[0];
    if (!archivo) {
        mostrarMensaje("Debes seleccionar un comprobante.", "red");
        return;
    }

    enviarPagoBtn.disabled = true;
    enviarPagoBtn.textContent = 'Procesando...';

    try {
        const formData = new FormData(formPago);

        const respuesta = await fetch('/pago', {
            method: 'POST',
            body: formData
        });

        const contentType = respuesta.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('BAD_JSON_FORMAT');
        }

        const resultado = await respuesta.json();

        if (resultado.success) {
            formPago.reset();
            mostrarMensaje(
                resultado.message || "Comprobante enviado correctamente. Espera aprobación.",
                "green"
            );
        } else {
            mostrarMensaje(
                resultado.error || "No se pudo procesar el pago.",
                "red"
            );
        }

    } catch (err) {
        console.error("Error detallado:", err);

        if (err.message === "BAD_JSON_FORMAT") {
            mostrarMensaje("Error en la respuesta del servidor. Intenta más tarde.", "red");
        } else {
            mostrarMensaje("Error de conexión. Revisa tu internet e intenta nuevamente.", "red");
        }

    } finally {
        enviarPagoBtn.disabled = false;
        enviarPagoBtn.textContent = 'Subir Comprobante';
    }
}


    function mostrarMensaje(texto, color) {
        textoError.textContent = texto;
        textoError.style.color = color;
        textoError.style.display = "block";
    }
});
