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

    // --- Navegación General & Sidebar ---
    const botonesConfig = document.querySelectorAll(".btn-config");
    const seccionesConfig = document.querySelectorAll(".config-section");

    const sections = document.querySelectorAll(".section");
    const marcador = document.querySelector(".opcion-div");
    const botones = document.querySelectorAll(".sider button");


    const tabConfig = document.querySelectorAll(".tab-btn");
    const tabContents = document.querySelectorAll(".tab-content");
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
 
const formCrearUsuario = document.getElementById('formCrearUsuario'); 
const formEliminarUsuario = document.getElementById("formEliminarUsuario");
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
                // Caso especial para 'ingresar' que usa flex
                if (sectionClass === 'ingresar') {
                    sectionToShow.style.display = 'flex';
                } else {
                    sectionToShow.style.display = "block";
                }
                    // Si mostramos la sección de configuración, deshabilitar el scroll del body
                    if (sectionClass === 'config') {
                        document.body.classList.add('no-scroll');
                    } else {
                        document.body.classList.remove('no-scroll');
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

 
    function CargarDatos() {
        cargarUsuariosPendientes();
        datosUsuario();  
        CargarUsuarios();
        cargarTablaPagosUsuarios();
        cargarHorasPrincipales();
        cargarUnidades();
        cargarConfiguracion();
    }



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
        if (el) {
            const msg = Array.isArray(errores[key]) 
                ? errores[key].join(', ')
                : errores[key];
            el.textContent = msg;
        }
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
    // 6. LÓGICA DE "USUARIOS"  
    // =================================================================
    
    // --- Funciones "Usuarios" ---

async function cargarUsuariosPendientes() {
    try {
        const response = await fetch('/usuariospendientes');
        if (!response.ok) throw new Error('Error en la respuesta del servidor');

        const usuarios = await response.json(); 

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


    // --- ELEMENTOS DEL DOM ---
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabs = document.querySelectorAll(".tab");

    // --- FUNCIÓN PARA CAMBIAR DE PESTAÑA ---
    function cambiarPestaña(tabSeleccionada) {
        // Remover clase activa de todos los botones
        tabButtons.forEach(btn => btn.classList.remove("active"));

        // Remover clase activa de todos los contenidos
        tabs.forEach(tab => tab.classList.remove("active"));

        // Activar la pestaña seleccionada
        const botonActivo = document.querySelector(`.tab-button[data-tab="${tabSeleccionada}"]`);
        const tabActiva = document.querySelector(`#tab-${tabSeleccionada}`);

        if (botonActivo && tabActiva) {
            botonActivo.classList.add("active");
            tabActiva.classList.add("active");
        }
    }

 
 
async function handleFormSubmit(e) {
    e.preventDefault();

    const formData = new FormData(this);
 
    const pais = formData.get('pais') || '+598'; 
    const telefono = formData.get('telefono');
    const telefonoCompleto = pais + telefono;

    formData.set('telefono', telefonoCompleto);

    try {
        const response = await fetch('/registro', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

  if (!result.success) {
    mostrarErrores(result.errors); 

    Swal.fire({
        icon: "error",
        title: "Errores en el formulario",
        html: Object.keys(result.errors)
            .map(k => `<b>${k}:</b> ${
                Array.isArray(result.errors[k]) ? result.errors[k].join("<br>") : result.errors[k]
            }`)
            .join("<br>"),
    });

    return;
}else {
            Swal.fire({
                icon: 'success',
                title: 'Usuario Creado',
                text: 'El nuevo usuario ha sido registrado exitosamente.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                formCrearUsuario.reset();
            });
                CargarUsuarios();
        }
    } catch (error) {
        console.error('Error al enviar el formulario:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            text: 'No se pudo completar la solicitud. Intente más tarde.',
            confirmButtonText: 'Aceptar'
        });
    }
}

if (formCrearUsuario) {
        formCrearUsuario.addEventListener('submit', handleFormSubmit);
}

function CargarUsuarios() {
    fetch("/ObtenerUsuariosBackoffice")
        .then(res => res.json())
        .then(data => {
 
            if ($.fn.DataTable.isDataTable("#tablaUsuarios")) {
                $("#tablaUsuarios").DataTable().clear().destroy();
            }
 
            $("#tablaUsuarios").DataTable({
                data: data,
                columns: [
                    { data: "usuario" },
                    { data: "unidad" },
                    { data: "correo" },
                    { data: "telefono" },
                    { data: "ci" }
                ],
                language: {
                    url: "/public/js/dataTables/es-ES-1.13.6.json"
                },
                responsive: true,
                pageLength: 10
            });

        })
        .catch(err => {
            console.error("Error cargando usuarios:", err);
        });

}


    // --- EVENTOS DE CLIC EN LAS PESTAÑAS ---
    tabButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const tabSeleccionada = btn.dataset.tab;
            cambiarPestaña(tabSeleccionada);
        });
    });

    // --- PESTAÑA INICIAL ---
    cambiarPestaña("listar");


 function activarClicksPendientes() {
    document.querySelectorAll('.usuario-pendiente').forEach(item => {
        item.addEventListener('click', async () => {

            document.querySelectorAll('.usuario-pendiente')
                .forEach(u => u.classList.remove('seleccionado'));

            item.classList.add('seleccionado');

            const id = item.dataset.id;
            window.usuarioActual = id;  

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
    e.preventDefault(); 

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
        CargarDatos();
        formRegistro.reset();  
        window.usuarioActual = null;

    } catch (err) {
        console.error(err);
        agregarNotificacion("Error al aprobar usuario ❌", "error");
    }
});


btnRechazar.addEventListener("click", async (e) => {
    e.preventDefault();  

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

        cargarUsuariosPendientes();  
        formRegistro.reset();
        window.usuarioActual = null;

    } catch (err) {
        console.error(err);
        agregarNotificacion("Error al rechazar usuario", "error");
    }
});
 
$('#tablaUsuarios tbody').on('click', 'tr', function () {
    let data = $('#tablaUsuarios').DataTable().row(this).data();
    if (!data) return;

    let ci = data.ci;
 
    CargarUsuarioSeleccionado(ci);
});

function CargarUsuarioSeleccionado(ci) {

    let formData = new FormData();
    formData.append("ci", ci);

    fetch("/usuarioPorID", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(usuario => {
        if (usuario.error) {
            console.error(usuario.error);
            return;
        }
 
        CargarModificarUsuario(usuario);
        CargarEliminarUsuario(usuario); 
        limpiarErrores();

        // Cambiar pestaña automáticamente
        document.querySelector(".tab-button[data-tab='modificar']").click();
    })
    .catch(err => console.error("Error al obtener usuario:", err));
} 
const formModificar = document.getElementById("formModificarUsuario");

function limpiarErroresAdmin() {
    // Busca los errores del formulario de modificación
    document.querySelectorAll("#formModificarUsuario .error-msg")
        .forEach(el => el.textContent = "");
}
async function cargarConfiguracion() {
    try {
        const response = await fetch('/obtenerTodasConfig');
        const data = await response.json();

        // Convertimos el array [{"clave":"x","valor":"y"}] en un objeto { x: y }
        const configMap = {};
        data.forEach(item => {
            configMap[item.clave] = item.valor;
        });

        // Rellenar inputs automáticamente
        const form = document.getElementById("form-configuracion");
        Object.keys(configMap).forEach(clave => {
            const input = form.querySelector(`[name="${clave}"]`);
            if (input) {
                input.value = configMap[clave];
            }
        });

    } catch (error) {
        console.error("Error cargando la configuración:", error);
    }
}

    botonesConfig.forEach(btn => {
        btn.addEventListener("click", () => {

            // Quitar 'active' a todos
            botonesConfig.forEach(b => b.classList.remove("active"));
            seccionesConfig.forEach(sec => sec.classList.remove("active"));

            // Activar botón presionado
            btn.classList.add("active");

            // Mostrar la sección correspondiente
            const objetivo = btn.dataset.target;
            document.getElementById(objetivo).classList.add("active");
        });
    }); 
    tabConfig.forEach(btn => {
        btn.addEventListener("click", () => {

            // Desactivar todos los tabs (.tab-btn), antes se usaba la colección equivocada
            tabConfig.forEach(b => b.classList.remove("active"));
            tabContents.forEach(c => c.classList.remove("active"));

            // Activar el tab seleccionado
            btn.classList.add("active");

            // Mostrar contenido asociado
            const tabObjetivo = btn.dataset.tab;
            document.getElementById(tabObjetivo).classList.add("active");
        });
    });
document.getElementById("form-configuracion").addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = e.target;
    const datos = new FormData(form);

    try {
        const response = await fetch("/editarConfig", {
            method: "POST",
            body: datos
        });

        const result = await response.json();

        // ❌ Si hay errores del backend
        if (!result.success) {
            let mensajeErrores = "";

            Object.keys(result.errores).forEach(clave => {
                mensajeErrores += `• ${clave}: ${result.errores[clave]}<br>`;
            });

            Swal.fire({
                icon: "error",
                title: "Errores en la configuración",
                html: mensajeErrores,
                confirmButtonText: "Entendido"
            });

            return;
        }

        // ✅ Si todo salió bien
        Swal.fire({
            icon: "success",
            title: "Configuraciones actualizadas",
            text: result.mensaje,
            timer: 2000,
            showConfirmButton: false
        });

    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error inesperado",
            text: "No se pudo conectar con el servidor."
        });
        console.error("Error FETCH:", error);
    }
});


function mostrarErroresAdmin(errores) {
    Object.keys(errores).forEach(key => {
        // Busca el elemento de error con el nuevo prefijo (ej: mod-error-ci)
        const el = document.getElementById(`mod-error-${key}`);
        if (el) el.textContent = errores[key];
    });
}
const btnBuscar = document.getElementById("btnBuscarUsuario");
const inputBuscar = document.getElementById("buscadorUsuario");

// Instancias DataTables (se crean cuando realmente existan)
let tablaPagos = null;
let tablaHoras = null;
let tablaDeudasM = null;
let tablaDeudasS = null;

// Crear DataTables cuando el DOM esté cargado (si las tablas están visibles)
document.addEventListener("DOMContentLoaded", () => {

    if ($("#tablaPagosMensuales").length) {
        tablaPagos = $('#tablaPagosMensuales').DataTable();
    }

    if ($("#tablaHorasUsuario").length) {
        tablaHoras = $('#tablaHorasUsuario').DataTable();
    }

    if ($("#tablaDeudasMensuales").length) {
        tablaDeudasM = $('#tablaDeudasMensuales').DataTable();
    }

    if ($("#tablaDeudasSemanales").length) {
        tablaDeudasS = $('#tablaDeudasSemanales').DataTable();
    }
});

// Evento del botón Buscar
btnBuscar.addEventListener("click", function(e) {
    e.preventDefault();

    const userInfo = inputBuscar.value.trim();
    if (userInfo === "") {
        Swal.fire("Error", "Ingrese CI o correo", "warning");
        return;
    }

    buscarDatosUsuario(userInfo);
});


/* --------------------------------------------------------------
   FUNCIÓN PRINCIPAL — Solicitar datos al backend
---------------------------------------------------------------- */
function buscarDatosUsuario(userInfo) {

    const formData = new FormData();
    formData.append("UserInfo", userInfo);
 

    fetch("/ListarDatosUsuarios", {  
        method: "POST",
        body: formData
    })
    .then(async response => {  
        // Leer respuesta RAW (sin parsear)
        const rawText = await response.text();
 
        // Intentar parsear JSON
        try {
            const parsed = JSON.parse(rawText); 
            return parsed;
        } catch (parseError) {
            console.error("❌ ERROR AL PARSEAR JSON:", parseError);
            Swal.fire("Error", "El servidor devolvió una respuesta inválida.", "error");
            return null;
        }
    })
    .then(data => {

        if (!data) return;

        if (data.error) {
            Swal.fire("Error", data.error, "error");
            return;
        }

        if (data.status !== "ok") {
            Swal.fire("Error", "No se pudo cargar la información.", "error");
            return;
        }

        // Cargar tablas
        cargarPagos(data.pagos);
        cargarHoras(data.horas);
        cargarDeudasMensuales(data.deudas_mensuales);
        cargarDeudasSemanales(data.deudas_semanales);

        Swal.fire("Éxito", "Datos cargados correctamente.", "success");
    })
    .catch(err => {
        console.error("🔥 ERROR FATAL EN FETCH:", err);
        Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
    });
}



/* --------------------------------------------------------------
   CARGADORES DE TABLAS
---------------------------------------------------------------- */

// Crear DataTable si no existe
function asegurarTabla(ref, selector) {
    if (!ref) {
        console.warn(`⚠ Inicializando DataTable tardíamente: ${selector}`);
        return $(selector).DataTable();
    }
    return ref;
}

// PAGOS MENSUALES
function cargarPagos(pagos) {

    tablaPagos = asegurarTabla(tablaPagos, "#tablaPagosMensuales");

    tablaPagos.clear();

    pagos.forEach(p => {
        tablaPagos.row.add([
            p.mes,
            p.monto,
            p.fecha
        ]);
    });

    tablaPagos.draw();
}


// HORAS TRABAJADAS
function cargarHoras(horas) {

    tablaHoras = asegurarTabla(tablaHoras, "#tablaHorasUsuario");

    tablaHoras.clear();

    horas.forEach(h => {
        tablaHoras.row.add([
            h.fecha,
            h.horas
        ]);
    });

    tablaHoras.draw();
}


// DEUDAS MENSUALES
function cargarDeudasMensuales(deudas) {

    tablaDeudasM = asegurarTabla(tablaDeudasM, "#tablaDeudasMensuales");

    tablaDeudasM.clear();

    deudas.forEach(d => {
        tablaDeudasM.row.add([
            d.mes,
            d.monto,
            d.adeudado
        ]);
    });

    tablaDeudasM.draw();
}


// DEUDAS SEMANALES
function cargarDeudasSemanales(deudas) {

    tablaDeudasS = asegurarTabla(tablaDeudasS, "#tablaDeudasSemanales");

    tablaDeudasS.clear();

    deudas.forEach(d => {
        tablaDeudasS.row.add([
            `${d.fecha_inicio} → ${d.fecha_fin}`,
            d.horas_trabajadas,
            d.horas_faltantes,
            d.horas_justificadas,
            d.horas_compensadas
        ]);
    });

    tablaDeudasS.draw();
}


formModificar.addEventListener("submit", async (e) => {
    e.preventDefault();
    limpiarErroresAdmin();
 
    const datos = new FormData(e.target); 

    try {
        const res = await fetch("/usuario-modificar", {
            method: "POST",
            body: datos
        });

        let resp;
        const text = await res.text(); 

        try {
            resp = JSON.parse(text);
        } catch {
            console.error("❌ No es JSON válido");
            Swal.fire("Error", "El servidor devolvió una respuesta inválida", "error");
            return;
        }

        if (resp.success) {
            Swal.fire("Éxito", "Usuario modificado correctamente", "success");
            CargarUsuarios();  


        } else if (resp.errores) {
            mostrarErroresAdmin(resp.errores);

        } else {
            Swal.fire("Error", resp.error || "Error desconocido", "error");
        }

    } catch (err) {
        console.log("Error al modificar usuario:", err);
        Swal.fire("Error", "No se pudo conectar con el servidor", "error");
    }
});


function CargarModificarUsuario(u) {
    if (!u || !u[0]) return;
    const usr = u[0];

    document.getElementById("mod-id").value = usr.id;
    document.getElementById("mod-nombre").value = usr.nombre;
    document.getElementById("mod-apellido").value = usr.apellido;
    document.getElementById("mod-telefono").value = usr.telefono;
    document.getElementById("mod-ci").value = usr.ci;
}

function CargarEliminarUsuario(u) {
    if (!u || !u[0]) return;

    const usr = u[0];

    document.getElementById("elim-id").value = usr.id;   
    document.getElementById("elim-nombre").value = usr.nombre;
    document.getElementById("elim-apellido").value = usr.apellido;
    document.getElementById("elim-correo").value = usr.email;
    document.getElementById("elim-ci").value = usr.ci;
}

formEliminarUsuario.addEventListener("submit", async (e) => {
    e.preventDefault();

    const id = document.getElementById("elim-id").value;
    if (!id) return Swal.fire("Error", "No se seleccionó ningún usuario", "error");

    // Confirmación antes de eliminar
    const confirm = await Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    });

    if (!confirm.isConfirmed) return;

    const datos = new FormData();
    datos.append("id", id);

    try {
        const resp = await fetch("/eliminarUsuario", {
            method: "POST",
            body: datos
        });
        const json = await resp.json();

        if (json.success) {
            Swal.fire("Eliminado", "El usuario fue eliminado con éxito.", "success");
            formEliminarUsuario.reset(); 
            CargarUsuarios();
        } else {
            Swal.fire("Error", json.error || "No se pudo eliminar el usuario", "error");
        }
    } catch (err) {
        console.error(err);
        Swal.fire("Error", "Ocurrió un error inesperado", "error");
    }
});
// Ejecutar cuando se muestra la sección o al cargar la página
async function cargarHorasPrincipales() {
    try {
        const resp = await fetch("/horasadmin");
        const data = await resp.json();
  
        if (!data.success) {
            console.warn("No se pudo cargar horas.");
            return;
        }

        // Rango de semana
        document.getElementById("semanaInicio").textContent = data.semana.inicio;
        document.getElementById("semanaFin").textContent   = data.semana.fin;

        // Crear / recrear DataTable
        if ($.fn.DataTable.isDataTable("#tablaHoras")) {
            $("#tablaHoras").DataTable().clear().destroy(); 
        }

        $("#tablaHoras").DataTable({
            data: data.data,
            responsive: true,
            destroy: true,
            language: {
                url: "/public/js/dataTables/es-ES-1.13.4.json"
            },
            columns: [
                { data: "usuario" },
                { data: "email" },
                { data: "telefono" },
                { data: "horas_trabajadas" }
            ]
        });

    } catch (e) {
        console.error("❌ Error cargando horas:", e);
    }
}

 
document.getElementById("btnActualizarHoras").addEventListener("click", () => {
    cargarHorasPrincipales();
});
 


async function cargarTablaPagosUsuarios() {
    
    try {
        const response = await fetch('/EstadoPagosUsuarios');
        const result = await response.json();

        if (!result.success) {
            console.error(result.error);
            return;
        }

        const tabla = $('#tabla-pagos').DataTable({
            destroy: true,  
            data: result.data,
            columns: [
                { data: 'nombre' },
                { data: 'apellido' },
                { data: 'telefono' },
                { data: 'email' },
                {
                    data: 'estado_pago',
                    render: function (data) {
                        const clase = data === 'Al día' ? 'estado-verde' : 'estado-rojo';
                        return `<span class="${clase}">${data}</span>`;
                    }
                }, 
            ]
        });

const alDia = result.data.filter(u => u.estado_pago === 'Al día').length;
const atrasados = result.data.length - alDia;

document.getElementById('usuariosAlDia').textContent = alDia;
document.getElementById('usuariosAtrasados').textContent = atrasados;

    } catch (error) {
        console.error("Error cargando tabla:", error);
    }
}

    

    // --- Event Listeners "Usuarios" ---

    btnCerrar.addEventListener("click", () => {
        panel.classList.toggle("cerrado");
        // Cambiar indicador del botón
        btnCerrar.textContent = panel.classList.contains("cerrado") ? ">" : "<";
    }); 

    // =================================================================
    // 7. LÓGICA DE "UNIDADES"
    // =================================================================

    // --- SELECTORES DE UNIDADES ---
    const tabButtonsUnidades = document.querySelectorAll(".unidades .tab-button");
    const tabsUnidades = document.querySelectorAll(".unidades .tab");
    const formCrearUnidad = document.getElementById("formCrearUnidad");
    const formModificarUnidad = document.getElementById("formModificarUnidad");
    const formEliminarUnidad = document.getElementById("formEliminarUnidad");

    // --- FUNCIÓN PARA CAMBIAR PESTAÑA (Unidades) ---
    function cambiarPestañaUnidades(tabSeleccionada) {
        tabButtonsUnidades.forEach(btn => btn.classList.remove("active"));
        tabsUnidades.forEach(tab => tab.classList.remove("active"));

        const botonActivo = document.querySelector(`.unidades .tab-button[data-tab="${tabSeleccionada}"]`);
        const tabActiva = document.querySelector(`.unidades #tab-${tabSeleccionada}`);

        if (botonActivo && tabActiva) {
            botonActivo.classList.add("active");
            tabActiva.classList.add("active");
        }
    }

    // --- CARGAR UNIDADES EN TABLA ---
    async function cargarUnidades() {
        try {
            const response = await fetch("/obtenerTodasUnidades");
            const data = await response.json();

            if ($.fn.DataTable.isDataTable("#tablaUnidades")) {
                $("#tablaUnidades").DataTable().clear().destroy();
            }

            // El backend devuelve directamente un array
            const unidades = Array.isArray(data) ? data : (data.data || []);

            $("#tablaUnidades").DataTable({
                data: unidades,
                columns: [
                    { data: "codigo" },
                    { data: "estado" }
                ],
                language: {
                    url: "/public/js/dataTables/es-ES-1.13.6.json"
                },
                responsive: true,
                pageLength: 10
            });

        } catch (err) {
            console.error("Error cargando unidades:", err);
            agregarNotificacion("Error al cargar unidades", "error");
        }
    }

    // --- EVENTO DE CLICK EN TABLA (Seleccionar Unidad) ---
    document.addEventListener("click", function(e) {
        if (e.target.closest("#tablaUnidades tbody tr")) {
            const tabla = $("#tablaUnidades").DataTable();
            const fila = e.target.closest("tr");
            const data = tabla.row(fila).data();

            if (!data) return;

            cargarUnidadSeleccionada(data);
        }
    });

    // --- CARGAR UNIDAD SELECCIONADA EN FORMULARIOS ---
    function cargarUnidadSeleccionada(unidad) {
        // Modificar
        document.getElementById("mod-unidad-id").value = unidad.id;
        document.getElementById("mod-codigo-unidad").value = unidad.codigo;
        document.getElementById("mod-estado-unidad").value = unidad.estado;

        // Eliminar
        document.getElementById("elim-unidad-id").value = unidad.id;
        document.getElementById("elim-codigo-unidad").value = unidad.codigo;
        document.getElementById("elim-estado-unidad").value = unidad.estado;

        // Si tiene información de usuarios asignados
        if (unidad.usuarios_asignados !== undefined) {
            document.getElementById("elim-usuarios-unidad").value = unidad.usuarios_asignados || "0";
        }
    }

    // --- CREAR UNIDAD ---
    if (formCrearUnidad) {
        formCrearUnidad.addEventListener("submit", async (e) => {
            e.preventDefault();

            const datos = new FormData(formCrearUnidad);

            try {
                const response = await fetch("/CrearUnidad", {
                    method: "POST",
                    body: datos
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const text = await response.text();
                if (!text) {
                    throw new Error("Respuesta vacía del servidor");
                }

                const result = JSON.parse(text);

                if (result.success) {
                    Swal.fire("Éxito", "Unidad creada exitosamente", "success");
                    formCrearUnidad.reset();
                    cargarUnidades();
                    cambiarPestañaUnidades("listar-unidades");
                } else {
                    Swal.fire("Error", result.error || "Error al crear unidad", "error");
                }
            } catch (error) {
                console.error("Error:", error);
                Swal.fire("Error", "Error de conexión: " + error.message, "error");
            }
        });
    }

    // --- MODIFICAR UNIDAD ---
    if (formModificarUnidad) {
        formModificarUnidad.addEventListener("submit", async (e) => {
            e.preventDefault();

            const datos = new FormData(formModificarUnidad);

            try {
                const response = await fetch("/CambiarEstadoUnidad", {
                    method: "POST",
                    body: datos
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const text = await response.text();
                if (!text) {
                    throw new Error("Respuesta vacía del servidor");
                }

                const result = JSON.parse(text);

                if (result.success) {
                    Swal.fire("Éxito", "Unidad actualizada exitosamente", "success");
                    cargarUnidades();
                    cambiarPestañaUnidades("listar-unidades");
                } else {
                    Swal.fire("Error", result.error || "Error al actualizar unidad", "error");
                }
            } catch (error) {
                console.error("Error:", error);
                Swal.fire("Error", "Error de conexión: " + error.message, "error");
            }
        });
    }

    // --- ELIMINAR UNIDAD ---
    if (formEliminarUnidad) {
        formEliminarUnidad.addEventListener("submit", async (e) => {
            e.preventDefault();

            const idUnidad = document.getElementById("elim-unidad-id").value;
            if (!idUnidad) {
                Swal.fire("Advertencia", "Selecciona una unidad primero", "warning");
                return;
            }

            // Confirmación
            const confirm = await Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            });

            if (!confirm.isConfirmed) return;

            const datos = new FormData();
            datos.append("idUnidad", idUnidad);

            try {
                const response = await fetch("/EliminarUnidad", {
                    method: "POST",
                    body: datos
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const text = await response.text();
                if (!text) {
                    throw new Error("Respuesta vacía del servidor");
                }

                const result = JSON.parse(text);

                if (result.success) {
                    Swal.fire("Éxito", "Unidad eliminada exitosamente", "success");
                    cargarUnidades();
                    cambiarPestañaUnidades("listar-unidades");
                } else {
                    Swal.fire("Error", result.error || "Error al eliminar unidad", "error");
                }
            } catch (error) {
                console.error("Error:", error);
                Swal.fire("Error", "Error de conexión: " + error.message, "error");
            }
        });
    }

    // --- EVENTOS DE PESTAÑAS (Unidades) ---
    tabButtonsUnidades.forEach(btn => {
        btn.addEventListener("click", () => {
            const tabSeleccionada = btn.dataset.tab;
            cambiarPestañaUnidades(tabSeleccionada);
        });
    });

    // --- Estado Inicial de la UI ---
    sections.forEach(s => s.style.display = "none");
    document.querySelector(".mi-perfil").style.display = "block"; // Mostrar "Mi Perfil" por defecto
 
    // --- Carga de Datos ---
    CargarDatos();
 
}); // Fin de DOMContentLoaded