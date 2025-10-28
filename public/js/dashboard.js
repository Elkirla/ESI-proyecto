document.addEventListener('DOMContentLoaded', function() {
    // ========== VARIABLES DE ELEMENTOS HTML ==========
    // Elementos de secciones
    const sections = document.querySelectorAll('.section');
    const inicioSection = document.querySelector('.inicio');
     
    // Elementos de datos de usuario
    const EstadoInicio= document.getElementById("EstadoPagosID");
    const HorasInicio= document.getElementById("HorasTrabajadasID");
    const UnidadInicio= document.getElementById("UnidadInicioID");

    const nombreUsuario = document.getElementById('nombre_usuario');
    const nombreDatos = document.getElementById('Nombre-datos');
    const apellidoDatos = document.getElementById('Apellido-datos');
    const telefonoDatos = document.getElementById('Telefono-datos');
    const correoDatos = document.getElementById('Correo-datos');
    
    // Elementos de horas
    const fechaHoras = document.getElementById('fecha-horas');
    const inputHoras = document.getElementById('hr');
    const tablaHoras = document.querySelector('.t table');
    const enviarHorasBtn = document.getElementById('subirhoras');
    
    // Elementos de notificaciones
    const notificacionesContainer = document.querySelector('.notificaciónes-container');
    const listaNotificaciones = document.getElementById('lista-notificaciones');
    const cerrarNotisBtn = document.getElementById('cerrar-notis');
    
    // Elementos de pagos
    const enviarPagoBtn = document.getElementById('btn-pagar');
    const formPago = document.getElementById('form-pago');
    const estadopagos = document.getElementById("EstadoPagos-pagos");
    // ========== CONFIGURACIÓN INICIAL ==========
    cargarDatos();
     
    sections.forEach(section => {
        section.style.display = 'none';
    });

    inicioSection.style.display = 'block';
    
    // ========== MAPA DE NAVEGACIÓN ==========
    const navigationMap = {
        'btn-inicio': 'inicio',
        'btn-mi-perfil': 'mi-perfil',
        'btn-pagos': 'pagos',
        'btn-horas': 'horas',
        'btn-deudas': 'deudas',
        'btn-mensajes': 'mensajes',
        'btn-soporte': 'soporte'
    };
    
    // ========== EVENT LISTENERS ==========
    // Navegación entre secciones
    Object.keys(navigationMap).forEach(btnId => {
        document.getElementById(btnId).addEventListener('click', function() {
            // Ocultar todas las secciones
            sections.forEach(section => {
                section.style.display = 'none';
            });
            // Mostrar la sección seleccionada
            document.querySelector('.' + navigationMap[btnId]).style.display = 'block';
        });
    });
    const marcador = document.querySelector(".opcion-div");
    const botones = document.querySelectorAll(".sider button");

    botones.forEach(boton => {
        boton.addEventListener("click", () => {
            // Calcula la posición vertical del botón dentro del sider
            const offsetTop = boton.offsetTop;
            
            // Ajustamos un poco para centrar el marcador
            marcador.style.top = offsetTop - 5 + "px";
        });
    });
    document.querySelector(".filtropagos button").addEventListener("click", () => {
    const filtro = document.getElementById("filtro-pagos").value; // 'todos', 'pendientes', etc.
    const valor = document.querySelector(".filtropagos input").value.toLowerCase();
    const tabla = document.querySelector(".tablaPagos table");
    const filas = tabla.querySelectorAll("tr:not(:first-child)");

    filas.forEach(fila => {
        let texto = "";
        switch (filtro) {
            case "todos": 
                texto = fila.innerText.toLowerCase(); 
                break;
            case "pendientes": 
                texto = fila.cells[1].innerText.toLowerCase(); // columna Monto
                break;
            case "aprobados": 
                texto = fila.cells[2].innerText.toLowerCase(); // columna Envio
                break;
            case "rechazados": 
                texto = fila.cells[3].innerText.toLowerCase(); // columna Estado
                break;
        }
        // Mostrar u ocultar fila según si coincide el valor
        fila.style.display = texto.includes(valor) ? "" : "none";
    });
});

    // Envío de horas
    enviarHorasBtn.addEventListener('click', enviarHoras);
    
    // Cerrar notificaciones
    cerrarNotisBtn.addEventListener('click', cerrarNotificaciones);
    
    // Envío de pagos
    enviarPagoBtn.addEventListener('click', procesarPago);
    
    // ========== FUNCIONES PRINCIPALES ==========
    function cargarDatos() {
        calcularDeudas();
        datosUsuario();
        cargarHora();
        cargarHoraLista();
        cargarDatosInicio();
        cargarpagos();
    }
    
async function calcularDeudas() {
    try { 
        await fetch("/actualizarPagoDeudas", { method: "POST" });
 
        await fetch("/actualizar-deuda-horas", { method: "POST" });

        console.log("Deudas actualizadas correctamente.");
    } catch (error) {
        console.error("Error calculando deudas:", error);
    }
}

// Ejecutar la función
calcularDeudas();

async function cargarpagos() {
    try {
        // 1. Obtener fecha límite
        const fechaResp = await fetch("/fecha-limite");
        const fechaData = await fechaResp.json();
        const fechaLimite = fechaData[0].valor;
        document.getElementById("FechaLimite").innerText = fechaLimite; 

        // 2. Obtener monto mensual
        const mensualidadResp = await fetch("/obtener-mensualidad");
        const mensualidadData = await mensualidadResp.json();
        const montoMensual = mensualidadData[0].valor;
        document.getElementById("MontoMensual").innerText = `Monto: $${montoMensual}`;

        // 3. Obtener mes actual
        const pagosResp = await fetch("/pagosusuario");
        const pagosData = await pagosResp.json();
        if(pagosData.length > 0) {
            document.getElementById("mes").innerText = `Mes: ${pagosData[0].mes}`;
        }

        // 4. Llenar la tabla de pagos
        const tabla = document.querySelector(".tablaPagos table");
        // Limpiar filas anteriores
        tabla.querySelectorAll("tr:not(:first-child)").forEach(row => row.remove());
        pagosData.forEach(pago => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${pago.mes}</td>
                <td>${pago.monto}</td>
                <td>${pago.entrega}</td>
                <td>${pago.estado}</td>
            `;
            tabla.appendChild(fila);
        });

        // 5. Monto semanal compensatorio
        const semanalResp = await fetch("/valor-semanal");
        const semanalData = await semanalResp.json();
        document.getElementById("MontoCompensatorio").innerText = `Monto semanal: $${semanalData[0].valor}`;

        // 6. Horas restantes
        const horasResp = await fetch("/verHorasDeudaSemanal");
        const horasData = await horasResp.json();
        document.getElementById("HorasRestantes").innerText = `Horas restantes: ${horasData.horas_faltantes}`;

        // 7. Monto total a pagar
        const saldoResp = await fetch("/saldo-compensatorio");
        const saldoData = await saldoResp.json();
        document.getElementById("MontoTotal").innerText = `Monto total: $${saldoData.monto}`;

    } catch (error) {
        console.error("Error cargando los pagos:", error);
    }
}

    async function cargarDatosInicio(){
try {
    // Estado de pagos
    fetch("/VerEstadoPagos", { method: "GET" })
        .then(response => response.json())
        .then(data => {
            EstadoInicio.innerText = data.estado;

            // Cambiar color según el estado
            if (data.estado === "Al día") {
                EstadoInicio.style.color = "#54FD32";
                estadopagos.innerHTML = "Al día";
            } else {
                EstadoInicio.style.color = "#e67474ff";
                estadopagos.innerHTML = "Pendiente";
            }
        })
        .catch(error => {
            console.error("Error al consultar estado:", error);
            EstadoInicio.innerText = "Error de conexión";
            EstadoInicio.style.color = "gray";
        });

} catch (error) {
    console.error("Error al cargar datos del usuario:", error);
}

        //Horas trabajadas
        try {
        fetch("/VerHorasTrabajadas", { method: "GET" })
        .then(response => response.json()) 
        .then(data => {
        HorasInicio.innerText = data.horas + " horas registradas";
        })
        }catch (error) {
            console.error("Error al cargar datos del usuario:", error);
        }

        //Unidad habitacional
        try {
        fetch("/obtenerdatosunidad", { method: "GET" })
        .then(response => response.json())
        .then(data => { 
        UnidadInicio.innerText = "Unidad " + data.codigo + " en " + data.estado;  
        })
        }catch (error) {
            console.error("Error al cargar datos del usuario:", error);
        }
    }
    async function cargarHoraLista() {
        try {
            const respuesta = await fetch("/horasusuario", { method: "GET" });
            const datos = await respuesta.json();
            
            // Limpiar filas anteriores (excepto la cabecera)
            tablaHoras.querySelectorAll("tr:not(:first-child)").forEach(tr => tr.remove());
            
            // Agregar nuevas filas
            datos.forEach(item => {
                const fila = document.createElement("tr");
                
                const tdFecha = document.createElement("td");
                tdFecha.textContent = item.fecha;
                
                const tdHoras = document.createElement("td");
                tdHoras.textContent = item.horas;
                
                fila.appendChild(tdFecha);
                fila.appendChild(tdHoras);
                
                tablaHoras.appendChild(fila);
            });
        } catch (error) {
            console.error("Error al cargar la lista de horas:", error);
            agregarNotificacion("Error al cargar las horas registradas", "error");
        }
    }

    async function datosUsuario() {
        try {
            const respuesta = await fetch("/usuariodatos", { method: "GET" });
            const texto = await respuesta.text();
            const data = JSON.parse(texto);
            
            // Como data es un array, tomamos el primer elemento
            const usuario = data[0];
            
            nombreUsuario.innerText = "Bienvenido "+usuario.nombre;
            nombreDatos.innerText = "Nombre: " + usuario.nombre;
            apellidoDatos.innerText = "Apellido: " + usuario.apellido;
            telefonoDatos.innerText = "Telefono: " + usuario.telefono;
            correoDatos.innerText = "Correo: " + usuario.email;
        } catch (error) {
            console.error("Error al cargar datos del usuario:", error);
            agregarNotificacion("Error al cargar los datos del usuario", "error");
        } 
    }
    
    function cargarHora() {
        const hoy = new Date();
        
        const dia = String(hoy.getDate()).padStart(2, '0');
        const mes = String(hoy.getMonth() + 1).padStart(2, '0');
        const anioCorto = String(hoy.getFullYear()).slice(-2);
        const fechaFormateada = `${dia}/${mes}/${anioCorto}`;
        fechaHoras.innerText = fechaFormateada;
        
        const anioCompleto = hoy.getFullYear();
        const fechaActual = `${anioCompleto}-${mes}-${dia}`;
        fechaHoras.dataset.mysql = fechaActual;
    }
    
    async function enviarHoras(e) {
        e.preventDefault();
        
        const fechaActual = fechaHoras.dataset.mysql;
        const horas = inputHoras.value;
        
        if (!horas || isNaN(horas) || Number(horas) <= 0) {
            agregarNotificacion("Por favor ingresa un número válido de horas.");
            return;
        }
        
        const formData = new FormData();
        formData.append("fecha", fechaActual);  
        formData.append("horas", horas);
        
        try {
            const respuesta = await fetch('/horas', {
                method: "POST",
                body: formData
            });
            const resultado = await respuesta.json();
            
            if (resultado.success) {
                agregarNotificacion("Horas registradas con éxito", "success");
                inputHoras.value = "";
                cargarHoraLista(); // refrescar datos
            } else {
                agregarNotificacion(resultado.error, "error");
            }
        } catch (err) {
            console.error("Error al enviar horas:", err);
            agregarNotificacion("Error en la conexión. Intente nuevamente.", "error");
        }
    }
    
    async function procesarPago(e) {
        e.preventDefault();
        
        // Deshabilitar botón para evitar múltiples envíos
        enviarPagoBtn.disabled = true;
        enviarPagoBtn.textContent = 'Procesando...';
        
        try {
            const formData = new FormData(formPago);
            
            const respuesta = await fetch('/pago', {
                method: "POST",
                body: formData
            });
            
            // Verificar si la respuesta es JSON válido
            const contentType = respuesta.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('El servidor respondió con un formato incorrecto');
            }
            
            const resultado = await respuesta.json();
            
            if (resultado.success) {
                agregarNotificacion(resultado.message || "Pago registrado exitosamente", 'success');
                cargarpagos();  // Refrescar datos de pagos       
                formPago.reset();  // Limpiar formulario después de éxito
            } else {
                // Mostrar error específico del servidor
                agregarNotificacion(resultado.error || "Error al procesar el pago", 'error');
            }
        } catch (err) {
            // Mensajes más amigables según el tipo de error
            if (err.name === 'TypeError' && err.message.includes('JSON')) {
                agregarNotificacion("Error en el servidor. Por favor, intente más tarde.", 'error');
            } else if (err.name === 'TypeError') {
                agregarNotificacion("Error de conexión. Verifique su internet e intente nuevamente.", 'error');
            } else {
                agregarNotificacion("Error inesperado. Intente más tarde.", 'error');
            }
            
            console.error('Error detallado (solo desarrollo):', err);
        } finally {
            // Rehabilitar botón siempre
            enviarPagoBtn.disabled = false;
            enviarPagoBtn.textContent = 'Enviar Pago';
        }
    }
    
    // ========== FUNCIONES AUXILIARES ==========
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
    
    function cerrarNotificaciones() {
        notificacionesContainer.style.display = 'none';
        listaNotificaciones.innerHTML = '';
    }
const btnComprobante = document.querySelector('.botones-comp button:first-child');
const btnCompensatorio = document.querySelector('.botones-comp button:nth-child(2)');
const divPago = document.querySelector('.IngresarPago');
const divCompensatorio = document.querySelector('.IngresarCompensatorio');

btnComprobante.addEventListener('click', () => {
  divPago.style.display = 'flex';
  divCompensatorio.style.display = 'none';
  btnComprobante.classList.add('active');
  btnCompensatorio.classList.remove('active');
});

btnCompensatorio.addEventListener('click', () => {
  divPago.style.display = 'none';
  divCompensatorio.style.display = 'flex';
  btnCompensatorio.classList.add('active');
  btnComprobante.classList.remove('active');
});


});