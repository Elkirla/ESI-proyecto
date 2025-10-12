document.addEventListener('DOMContentLoaded', function() { 
    cargardatos();
    function cargardatos(){
        usuariospendientesList();
    }
    // Oculta todas las secciones
    document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'none';
    });
    // Muestra solo "Mi perfil" al inicio
    document.querySelector('.mi-perfil').style.display = 'block';

    const mapping = {
        'btn-mi-perfil': 'mi-perfil',
        'btn-usuarios': 'usuarios',
        'btn-pagos': 'pagos',
        'btn-ingresar': 'ingresar',
        'btn-horas': 'horas'
    };

    Object.keys(mapping).forEach(btnId => {
        document.getElementById(btnId).addEventListener('click', function() {
            // Oculta todas las secciones
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });
            // Muestra la sección seleccionada
            document.querySelector('.' + mapping[btnId]).style.display = 'block';
        });
    });

const tablapendientes = document.getElementById('tablapendientes');
const template = document.getElementById('usuario-template');

async function usuariospendientesList() {
    try {
        const respuesta = await fetch("/usuariospendientes", { method: "GET" });
        const datos = await respuesta.json();

        tablapendientes.innerHTML = ""; // limpiar contenedor

        datos.forEach(usuario => {
            // clonar el template
            const clone = template.content.cloneNode(true);
            const tarjeta = clone.querySelector(".tarjeta-usuario");
            const nombre = clone.querySelector(".nombre");
            const cerrar = clone.querySelector(".cerrar");

            // setear datos
            tarjeta.dataset.id = usuario.id;
            nombre.textContent = `${usuario.nombre} ${usuario.apellido}`;

            // eventos
            tarjeta.addEventListener("click", () => {
                console.log("Click tarjeta -> ID:", usuario.id);
            });

            cerrar.addEventListener("click", (e) => {
                e.stopPropagation();
                console.log("Click cerrar -> ID:", usuario.id);
            });

            tablapendientes.appendChild(clone);
        });
    } catch (error) {
        console.error("Error al cargar usuarios pendientes:", error);
    }
}
 

// 1. Seleccionar la lista
const lista = document.getElementById("listaUsuarios");

// 2. Delegar el click a los <li>
lista.addEventListener("click", async (e) => {
  const li = e.target.closest("li"); // el li en el que se hizo click
  if (!li) return;

  const userId = li.getAttribute("data-id"); // leer el id del usuario
  console.log("Usuario clickeado con id:", userId);

  try {
    // 3. Hacer fetch al backend
    const response = await fetch("/usuario-por-id", {
      method: "POST", // o GET si tu API lo permite
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ id: userId })
    });

    const data = await response.json();

    if (data.error) {
      console.error("Error en el servidor:", data.error);
      return;
    }

    // 4. Guardar en variables (ejemplo)
    const nombre   = data.nombre;
    const apellido = data.apellido;
    const telefono = data.telefono;
    const email    = data.email;
    const ci       = data.ci;

    console.log("Datos recibidos:");
    console.log({ nombre, apellido, telefono, email, ci });

    // (luego acá vos los adaptás a tu programa, ej: mostrarlos en inputs)
  } catch (err) {
    console.error("Error en la petición:", err);
  }
});

/*
USA ESTO EN EL HTML PARA LOS USUARIOS PENDIENTES

<div id="tablapendientes"></div>

<template id="usuario-template">
  <div class="tarjeta-usuario" data-id="">
    <span class="nombre"></span>
    <span class="cerrar">x</span>
  </div>
</template>
*/



});