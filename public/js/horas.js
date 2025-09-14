document.addEventListener('DOMContentLoaded', function() {
    const enviarBtn = document.getElementById('btn-enviar');

    enviarBtn.addEventListener('click', async function(e){
        e.preventDefault();

        const formData = new FormData(this);
        const respuesta = await fetch('/horas', {
            method: "POST",
            body: formData
        });

        const resultado = await respuesta.json();

        if(resultado.success){
            // Mostrar "Horas registradas con extito"
        } else {
            // Mostrar errores
        }
    });
});