document.addEventListener('DOMContentLoaded', function() {
  const enviarBtn = document.getElementById('btn-pagar');
  const form = document.getElementById('form-pago'); 

  enviarBtn.addEventListener('click', async function(e) {
    e.preventDefault();

    const formData = new FormData(form);
    const respuesta = await fetch('/pago', {
      method: "POST",
      body: formData
    });

    const resultado = await respuesta.json();

    if (resultado.success) {
      alert("Pago exitoso ✅");
    } else {
      alert("Error al registrar pago ❌");
    }
  });
});
