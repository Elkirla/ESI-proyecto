document.addEventListener("DOMContentLoaded", () => {
    const notificacionesContainer = document.querySelector('.notificaciones-container');
    const listaNotificaciones = document.getElementById('lista-notificaciones');
    const cerrarNotisBtn = document.getElementById('cerrar-notis');

    // ======== NAVEGACIÓN GENERAL ========
    const sections = document.querySelectorAll(".section");

    const navigationMap = {
        'btn-mi-perfil': 'mi-perfil',
        'btn-Usuarios': 'usuarios',
        'btn-Pagos': 'pagos',
        'btn-ingresar': 'ingresar',
        'btn-Horas': 'horas',
        'btn-Unidades': 'unidades',
        'btn-Config': 'config'
    };

    sections.forEach(s => s.style.display = "none");
    document.querySelector(".mi-perfil").style.display = "block";

    Object.keys(navigationMap).forEach(btnId => {
        const btn = document.getElementById(btnId);

        btn.addEventListener("click", () => {
            sections.forEach(s => s.style.display = "none");
            document.querySelector("." + navigationMap[btnId]).style.display = "block";
        });
    });

    const marcador = document.querySelector(".opcion-div");
    const botones = document.querySelectorAll(".sider button");

    botones.forEach(boton => {
        boton.addEventListener("click", () => {
            marcador.style.top = (boton.offsetTop - 5) + "px";
        });
    });

        function agregarNotificacion(mensaje, tipo = "info") {
        const item = document.createElement('li');
        item.textContent = mensaje;
        item.classList.add(tipo);
        
        listaNotificaciones.appendChild(item);
        notificacionesContainer.style.display = 'block';
        
        setTimeout(() => {
            item.remove();
            if (listaNotificaciones.children.length === 0) {
                notificacionesContainer.style.display = 'none';
            }
        }, 15000);
    }
    cerrarNotisBtn.addEventListener('click', cerrarNotificaciones);
    function cerrarNotificaciones() {
        notificacionesContainer.style.display = 'none';
        listaNotificaciones.innerHTML = '';
    }

    // ==================================
    // ✅ LÓGICA DE “MI PERFIL”
    // ==================================

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

    datosUsuario();  


    // === EVENTOS ===
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


    // === FUNCIONES ===
    async function datosUsuario() {
        try {
            const res = await fetch("/usuariodatos");
            const data = await res.json();
            const usuario = data[0];

            nombreDatos.textContent = usuario.nombre;
            apellidoDatos.textContent = usuario.apellido;
            telefonoDatos.textContent = usuario.telefono;
            ciDatos.textContent = usuario.ci;
        } catch {
            agregarNotificacion("Error al cargar datos del usuario", "error");
        }
    }

    function activarEdicion(modo) {
        if (modo) {
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
});
