document.addEventListener('DOMContentLoaded', function() { 
    cargardatos();

    document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'none';
    });
    document.querySelector('.inicio').style.display = 'block';
 
    const mapping = {
        'btn-inicio': 'inicio',
        'btn-mi-perfil': 'mi-perfil',
        'btn-pagos': 'pagos',
        'btn-horas': 'horas',
        'btn-unidad': 'unidad',
        'btn-mensajes': 'mensajes',
        'btn-soporte': 'soporte'
    };

    Object.keys(mapping).forEach(btnId => {
        document.getElementById(btnId).addEventListener('click', function() {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });
            // Show the selected section
            document.querySelector('.' + mapping[btnId]).style.display = 'block';
        });
    });

async function cargardatos() {
    // Cargar datos del usuario
    const respuesta = await fetch("/usuariodatos", { method: "GET" });
    const texto = await respuesta.text();

    try {
        const data = JSON.parse(texto);
        document.getElementById("nombre_usuario").innerText = data.nombre;
        document.getElementById("Nombre-datos").innerText = "Nombre: " + data.nombre;
        document.getElementById("Apellido-datos").innerText = "Apellido: " + data.apellido;
        document.getElementById("Telefono-datos").innerText = "Telefono: " + data.telefono;
        document.getElementById("Correo-datos").innerText = "Correo: " + data.email;
    } catch (e) {
        console.log("No se pudo parsear JSON:", e);
    }

    const hoy = new Date();

    const dia = String(hoy.getDate()).padStart(2, '0');
    const mes = String(hoy.getMonth() + 1).padStart(2, '0');
    const anioCorto = String(hoy.getFullYear()).slice(-2); 
    const fechaFormateada = `${dia}/${mes}/${anioCorto}`;
    document.getElementById("fecha-horas").innerText = fechaFormateada;

    const anioCompleto = hoy.getFullYear();
    const fecha_actual = `${anioCompleto}-${mes}-${dia}`;
    document.getElementById("fecha-horas").dataset.mysql = fecha_actual; 
}
document.getElementById("regresar_icon").addEventListener("click", function() {
    document.getElementById("Cambio-contraseña").style.display = "none";
});

document.getElementById("btn-cambiarcontraseña").addEventListener("click", function() {
    document.getElementById("Cambio-contraseña").style.display = "block";
});

const enviarBtn = document.getElementById("subirhoras");

enviarBtn.addEventListener('click', async function(e) { 
const fecha_actual = document.getElementById("fecha-horas").dataset.mysql;
const horas = document.getElementById("hr").value;
    e.preventDefault();

    if (!horas || isNaN(horas) || Number(horas) <= 0) {
        alert("Debes ingresar un número válido de horas trabajadas" + horas);
        return;
    }

    const formData = new FormData();
    formData.append("fecha", fecha_actual); // enviamos en formato MySQL
    formData.append("horas", horas);

    try {
        const respuesta = await fetch('/horas', {
            method: "POST",
            body: formData
        });
        const resultado = await respuesta.json();

        if (resultado.success) {
            alert("Horas registradas ✅");
            document.getElementById("hr").value = "";
            cargardatos(); // refrescar datos
        } else {
            alert("Error ❌ " + (resultado.error || ""));
            console.log("Error details:", resultado);
        }
    } catch (err) {
        alert("Error en la conexión ❌ " + err.message);
    }
});



});