document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('form-login');

  form.addEventListener('submit', async function(e) {
    e.preventDefault(); 
    const formData = new FormData(form);
    
    // Añadir indicador de carga
    const submitBtn = form.querySelector('input[type="submit"]');
    const originalText = submitBtn.value;
    submitBtn.value = "Cargando...";
    submitBtn.disabled = true;

    try {
      const respuesta = await fetch('/login', {
        method: "POST",
        body: formData
      });

      // Verificar si la respuesta es JSON
      const contentType = respuesta.headers.get("content-type");
      if (!contentType || !contentType.includes("application/json")) {
        throw new TypeError("La respuesta del servidor no es JSON");
      }

      const resultado = await respuesta.json();

      if (resultado.success) {

        // ✅ redirigir según el rol
        if (resultado.rol === "administrador") {
        window.location.href = "/dashboard-admin";
        alert("Bienido de vuelta");

        } else {
        window.location.href = "/dashboard-usuario";
        alert("Bienido de vuelta");
        }

      } else {
        alert("Error: " + (resultado.error || "Credenciales inválidas ❌"));
      }
    } catch (err) {
      console.error("Error en la petición:", err);
      alert("Hubo un problema en el servidor ❌");
    } finally {
      // Restaurar botón
      submitBtn.value = originalText;
      submitBtn.disabled = false;
    }
  });
});