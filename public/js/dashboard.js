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

function cargardatos() {
    datosusuario();
    cargarhora();
    cargarhoralista();
}
async function cargarhoralista() {
    const respuesta = await fetch("/horasusuario", { method: "GET" });
    const datos = await respuesta.json();

    const tabla = document.querySelector(".t table");

    // limpiar filas anteriores (menos la cabecera)
    tabla.querySelectorAll("tr:not(:first-child)").forEach(tr => tr.remove());

    // agregar filas
    datos.forEach(item => {
        const fila = document.createElement("tr");

        const tdFecha = document.createElement("td");
        tdFecha.textContent = item.fecha; 

        const tdHoras = document.createElement("td");
        tdHoras.textContent = item.horas;

        fila.appendChild(tdFecha);
        fila.appendChild(tdHoras);

        tabla.appendChild(fila);
    });
}


async function datosusuario(){
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
}
function cargarhora(){
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

const enviarhorasBtn = document.getElementById("subirhoras");

enviarhorasBtn.addEventListener('click', async function(e) { 
const fecha_actual = document.getElementById("fecha-horas").dataset.mysql;
const horas = document.getElementById("hr").value;
    e.preventDefault();

    if (!horas || isNaN(horas) || Number(horas) <= 0) {
        agregarNotificacion("Por favor ingresa un número válido de horas.");
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
        agregarNotificacion("Horas registradas con exito") 

        document.getElementById("hr").value = "";
        cargarhoralista(); // refrescar datos

        } else {
            agregarNotificacion(resultado.error);
        }

    } catch (err) {
        alert("Error en la conexión ❌ " + err.message);
    }
});

function agregarNotificacion(mensaje, tipo = "info") {
    const container = document.querySelector('.notificaciónes-container');
    const lista = document.getElementById("lista-notificaciones"); 

    const item = document.createElement('li');
    item.textContent = mensaje;
    item.classList.add(tipo); 

    lista.appendChild(item);
    container.style.display = 'block';

    setTimeout(() => {
        item.remove();
        if (lista.children.length === 0) {
            container.style.display = 'none';
        }
    }, 15000);

}


document.getElementById("cerrar-notis").addEventListener("click", function() {
    document.querySelector('.notificaciónes-container').style.display = 'none';
    document.getElementById("lista-notificaciones").innerHTML = '';
});

});