document.addEventListener("DOMContentLoaded", () => {

    // =================================================================
    // 1. SELECTORES DE ELEMENTOS
    // =================================================================

    // --- Notificaciones ---
    const notificacionesContainer = document.querySelector('.notificaciones-container');
    const listaNotificaciones = document.getElementById('lista-notificaciones');
    const cerrarNotisBtn = document.getElementById('cerrar-notis');

    // --- Usuarios (Panel Pendientes) ---
    const btnCerrar = document.getElementById("CerrarUsuariosPendientes");
    const panel = document.getElementById("usuariosPendientes-div");
    const contenedorUsuariosPendientes = document.getElementById('usuariosPendientes-div');
    const btnAceptar = document.getElementById("A-Registro");
    const btnRechazar = document.getElementById("R-Registro");
    const listaUsuariosPend = document.getElementById("usuariosPendientes-div");

    // --- Navegación General & Sidebar ---
    const sections = document.querySelectorAll(".section");
    const marcador = document.querySelector(".opcion-div");
    const botones = document.querySelectorAll(".sider button");

    // --- Mi Perfil ---
    const btnEditar = document.getElementById("btn-editar-datos");
    const vistaDatos = document.getElementById("vista-datos");
    const formEditar = document.getElementById("form-editar-datos");
    const nombreDatos = document.getElementById("Nombre-datos");
    const apellidoDatos = document.getElementById("Apellido-datos");
    const telefonoDatos = document.getElementById("Telefono-datos");
    const ciDatos = document.getElementById("ci-datos");
    const inputNombre = document.getElementById("input-nombre");
    const inputApellido = document.getElementById("input-apellido");
    const inputTelefono = document.getElementById("input-telefono");
    const inputCi = document.getElementById("input-ci");
    const btnCancelar = document.getElementById("btn-cancelar");

    // =================================================================
    // 2. CONFIGURACIÓN DE NAVEGACIÓN
    // =================================================================

    const navigationMap = {
        'btn-mi-perfil': 'mi-perfil',
        'btn-Usuarios': 'usuarios',
        'btn-Pagos': 'pagos',
        'btn-ingresar': 'ingresar',
        'btn-Horas': 'horas',
        'btn-Unidades': 'unidades',
        'btn-Config': 'config'
    };

    // =================================================================
    // 3. LÓGICA DE NAVEGACIÓN Y SIDEBAR
    // =================================================================

    // --- Navegación Principal (Tabs) ---
    Object.keys(navigationMap).forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (!btn) return; // Buena práctica: saltar si el botón no existe

        btn.addEventListener("click", () => {
            // Ocultar todas las secciones
            sections.forEach(s => s.style.display = "none");
            
            // Mostrar la sección correspondiente
            const sectionClass = navigationMap[btnId];
            const sectionToShow = document.querySelector("." + sectionClass);
            
            if (sectionToShow) {
                // Caso especial para 'usuarios' que usa flex
                if (sectionClass === 'usuarios') {
                    sectionToShow.style.display = 'flex';
                } else {
                    sectionToShow.style.display = "block";
                }
            }
        });
    });

    // --- Marcador de Sidebar ---
    botones.forEach(boton => {
        boton.addEventListener("click", () => {
            marcador.style.top = (boton.offsetTop - 5) + "px";
        });
    });

    // =================================================================
    // 4. LÓGICA DE NOTIFICACIONES
    // =================================================================

    function agregarNotificacion(mensaje, tipo = "info") {
        const item = document.createElement('li');
        item.textContent = mensaje;
        item.classList.add(tipo);
        
        listaNotificaciones.appendChild(item);
        notificacionesContainer.style.display = 'block';
        
        // Auto-cierre
        setTimeout(() => {
            if (item.parentNode) {
                item.remove();
            }
            if (listaNotificaciones.children.length === 0) {
                notificacionesContainer.style.display = 'none';
            }
        }, 15000);
    }

    function cerrarNotificaciones() {
        notificacionesContainer.style.display = 'none';
        listaNotificaciones.innerHTML = '';
    }

    cerrarNotisBtn.addEventListener('click', cerrarNotificaciones);

    // =================================================================
    // 5. LÓGICA DE "MI PERFIL"
    // =================================================================

    // --- Funciones "Mi Perfil" ---
    
    async function datosUsuario() {
        try {
            const res = await fetch("/usuariodatos");
            const data = await res.json();
            const usuario = data[0];

            if (usuario) {
                nombreDatos.textContent = usuario.nombre;
                apellidoDatos.textContent = usuario.apellido;
                telefonoDatos.textContent = usuario.telefono;
                ciDatos.textContent = usuario.ci;
            }
        } catch(e) {
            console.error("Error en datosUsuario:", e);
            agregarNotificacion("Error al cargar datos del usuario", "error");
        }
    }

    function activarEdicion(modo) {
        if (modo) {
            // Sincronizar inputs con la vista actual
            inputNombre.value = nombreDatos.textContent;
            inputApellido.value = apellidoDatos.textContent;
            inputTelefono.value = telefonoDatos.textContent;
            inputCi.value = ciDatos.textContent;
        }

        vistaDatos.style.display = modo ? "none" : "flex";
        formEditar.style.display = modo ? "block" : "none";
    }

    function actualizarVista() {
        nombreDatos.textContent = inputNombre.value;
        apellidoDatos.textContent = inputApellido.value;
        telefonoDatos.textContent = inputTelefono.value;
        ciDatos.textContent = inputCi.value;
    }

    function mostrarErrores(errores) {
        Object.keys(errores).forEach(key => {
            const el = document.getElementById(`error-${key}`);
            if (el) el.textContent = errores[key];
        });
    }

    function limpiarErrores() {
        document.querySelectorAll(".error-msg").forEach(el => el.textContent = "");
    }

    // --- Event Listeners "Mi Perfil" ---

    btnEditar.addEventListener("click", () => activarEdicion(true));
    btnCancelar.addEventListener("click", () => activarEdicion(false));

    formEditar.addEventListener("submit", async (e) => {
        e.preventDefault();
        limpiarErrores();

        const datos = new FormData();
        datos.append("nombre", inputNombre.value);
        datos.append("apellido", inputApellido.value);
        datos.append("telefono", inputTelefono.value);
        datos.append("ci", inputCi.value);

        try {
            const response = await fetch("/actualizar-DatosUsuario", {
                method: "POST",
                body: datos
            });

            const result = await response.json();

            if (result.success) {
                actualizarVista();
                activarEdicion(false);
                agregarNotificacion(result.success, "success");
            } else if (result.errores) {
                mostrarErrores(result.errores);
            } else {
                agregarNotificacion(result.error, "error");
            }
        } catch {
            agregarNotificacion("Error de conexión con el servidor", "error");
        }
    });

    // =================================================================
    // 6. LÓGICA DE "USUARIOS" (PENDIENTES)
    // =================================================================
    
    // --- Funciones "Usuarios" ---

async function cargarUsuariosPendientes() {
    try {
        const response = await fetch('/usuariosPendientes');
        if (!response.ok) throw new Error('Error en la respuesta del servidor');

        const usuarios = await response.json();
        console.log("Usuarios pendientes recibidos:", usuarios);

        // Limpiar elementos previos
        contenedorUsuariosPendientes.querySelectorAll('.usuario-pendiente').forEach(el => el.remove());

        usuarios.forEach(user => {
            const div = document.createElement('div');
            div.classList.add('usuario-pendiente');
            div.dataset.id = user.id;
            div.innerHTML = `<p>${user.nombre} ${user.apellido}</p>`;
            contenedorUsuariosPendientes.appendChild(div);
        });

        activarClicksPendientes();

    } catch (err) {
        console.error('Error al cargar pendientes: ', err);
        agregarNotificacion("Error al cargar usuarios pendientes", "error");
    }
}

 function activarClicksPendientes() {
    document.querySelectorAll('.usuario-pendiente').forEach(item => {
        item.addEventListener('click', async () => {

            document.querySelectorAll('.usuario-pendiente')
                .forEach(u => u.classList.remove('seleccionado'));

            item.classList.add('seleccionado');

            const id = item.dataset.id;
            window.usuarioActual = id; 
            console.log("Usuario seleccionado ID:", id);

            try {
                const response = await fetch('/usuario-por-id', {
                    method: 'POST',
                    body: new URLSearchParams({ id })
                });

                if (!response.ok) throw new Error('Error al buscar usuario por ID');

                const resultado = await response.json();
                const usuario = resultado[0];

                if (!usuario) return;

                document.getElementById("Nombre-Registro").value = usuario.nombre;
                document.getElementById("Apellido-Registro").value = usuario.apellido;
                document.getElementById("Telefono-Registro").value = usuario.telefono;
                document.getElementById("Correo-Registro").value = usuario.email;
                document.getElementById("CI-Registro").value = usuario.ci;

            } catch (error) {
                console.error("Error al traer usuario: ", error);
                agregarNotificacion("Error al cargar detalles del usuario", "error");
            }
        });
    });
}
    
// =================================================================
// ACEPTAR / RECHAZAR USUARIO
// =================================================================
btnAceptar.addEventListener("click", async (e) => {
    e.preventDefault(); // ✅ Evita recargar la página

    if (!window.usuarioActual) {
        agregarNotificacion("Selecciona un usuario primero", "info");
        return;
    }

    try {
        const resp = await fetch('/aprobar-usuario', {
            method: 'POST',
            body: new URLSearchParams({ id: window.usuarioActual })
        });

        if (!resp.ok) throw new Error('Error al aprobar usuario');

        agregarNotificacion("Usuario aprobado correctamente ✅", "success");

        cargarUsuariosPendientes(); // ✅ Se actualiza la lista
        formRegistro.reset(); // ✅ Vaciamos la hoja
        window.usuarioActual = null;

    } catch (err) {
        console.error(err);
        agregarNotificacion("Error al aprobar usuario ❌", "error");
    }
});


btnRechazar.addEventListener("click", async (e) => {
    e.preventDefault(); // ✅ Evita recargar la página

    if (!window.usuarioActual) {
        agregarNotificacion("Selecciona un usuario primero", "info");
        return;
    }

    try {
        const resp = await fetch('/rechazar-usuario', {
            method: 'POST',
            body: new URLSearchParams({ id: window.usuarioActual })
        });

        if (!resp.ok) throw new Error('Error al rechazar usuario');

        agregarNotificacion("Usuario rechazado ❌", "warning");

        cargarUsuariosPendientes(); // ✅ Actualizamos lista
        formRegistro.reset();
        window.usuarioActual = null;

    } catch (err) {
        console.error(err);
        agregarNotificacion("Error al rechazar usuario", "error");
    }
});

    // --- Event Listeners "Usuarios" ---

    btnCerrar.addEventListener("click", () => {
        panel.classList.toggle("cerrado");
        // Cambiar indicador del botón
        btnCerrar.textContent = panel.classList.contains("cerrado") ? ">" : "<";
    });
    
     

    // Función que agrupa la carga de datos inicial
    function CargarDatos() {
        cargarUsuariosPendientes();
        datosUsuario(); // Cargar datos de "Mi Perfil"
    }

    // --- Estado Inicial de la UI ---
    sections.forEach(s => s.style.display = "none");
    document.querySelector(".mi-perfil").style.display = "block"; // Mostrar "Mi Perfil" por defecto

    // --- Carga de Datos ---
    CargarDatos();

}); // Fin de DOMContentLoaded