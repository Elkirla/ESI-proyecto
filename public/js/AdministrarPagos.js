document.addEventListener("DOMContentLoaded", () => {

    let tabla;
    let pagoSeleccionado = null;

    const btnAceptar = document.querySelector("#btnAceptar");
    const btnRechazar = document.querySelector("#btnRechazar");
    const filtroTipo = document.querySelector("#filtroTipo");
    const detalleUsuario = document.querySelector("#detalleUsuario");
    const visorArchivo = document.querySelector("#visorArchivo");

    btnAceptar.addEventListener("click", () => procesarPago("aprobar"));
    btnRechazar.addEventListener("click", () => procesarPago("rechazar"));


    function cargarPagos(endpoint) {
        fetch(endpoint)
            .then(res => res.json())
            .then(async data => {
                // Pedir datos completos del usuario por cada fila
                for (const pago of data) {
                    try {
                        const res = await fetch("/usuario-por-id", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: `id=${encodeURIComponent(pago.usuario_id)}`
                        });
                
                        const userArr = await res.json();
                        const user = userArr[0];
                
                        pago.nombreCompleto = user
                            ? `${user.nombre} ${user.apellido}`
                            : `Usuario #${pago.usuario_id}`;
                    } catch {
                        pago.nombreCompleto = `Usuario #${pago.usuario_id}`;
                    }
                }


                if ($.fn.DataTable.isDataTable("#tablaPagos")) {
                    tabla.clear().destroy();
                }

                tabla = $("#tablaPagos").DataTable({
                    data: data,
                    columns: [
                        { data: "nombreCompleto" },
                        { data: "fecha", render: fecha => new Date(fecha).toLocaleDateString("es-UY") },
                        { data: null, render: row => row.mes ? "Mensual" : "Compensatorio" }
                    ],
                    order: [[1, "desc"]],
                    paging: true,
                    searching: true,
                    lengthChange: false,
                    info: false
                });

                $("#tablaPagos tbody").off("click").on("click", "tr", function () {
                    const data = tabla.row(this).data();
                    if (!data) return;

                    pagoSeleccionado = data;

                    $("#tablaPagos tbody tr").removeClass("fila-seleccionada");
                    $(this).addClass("fila-seleccionada");

                    mostrarDatosBasicos(data);
                    mostrarComprobante(data.archivo_url);
                    activarBotones();
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: "error",
                    title: "Error al cargar los pagos"
                });
            });
    }

    filtroTipo.addEventListener("change", (e) => {
        cargarPagos(
            e.target.value === "mensual"
                ? "/pagosadmin"
                : "/pagos-compensatorios-admin"
        );
    });

    function activarBotones() {
        btnAceptar.disabled = false;
        btnRechazar.disabled = false;
    }

    function mostrarDatosBasicos(data) {
        const esMensual = !!data.mes;

        detalleUsuario.innerHTML = `
            <h3>Detalles del Pago 📄</h3>
            <p><b>Usuario:</b> ${data.nombreCompleto}</p>
            <p><b>Monto:</b> ${data.monto} UYU</p>
            <p><b>Fecha:</b> ${new Date(data.fecha).toLocaleDateString("es-UY")}</p>
            ${esMensual 
                ? `<p><b>Mes:</b> ${data.mes}</p><p><b>Tipo:</b> Mensual</p>`
                : `<p><b>Tipo:</b> Compensatorio</p>`
            }
        `;

        detalleUsuario.classList.remove("oculto");
    }

    function mostrarComprobante(ruta) {
        visorArchivo.classList.remove("oculto");

        if (!ruta) {
            visorArchivo.innerHTML = `<p>No hay comprobante disponible.</p>`;
            return;
        }

        let path = String(ruta).replace(/\\+/g, "");

        // Normalizar rutas - eliminar duplicados y asegurar ruta correcta
        if (!/^https?:\/\//i.test(path)) {
            // Remover cualquier prefijo duplicado
            path = path.replace(/^\/+/, ""); // Quitar slash inicial
            path = path.replace(/^public\/uploads\//, "uploads/"); // Normalizar si ya tiene public/
            path = path.replace(/^uploads\//, "uploads/"); // Asegurar formato consistente
            
            // Si no empieza con uploads/, agregarlo
            if (!path.startsWith("uploads/")) {
                path = "uploads/" + path;
            }
            
            // Agregar prefijo public/ solo si no está presente
            if (!path.startsWith("public/")) {
                path = "public/" + path;
            }
        }

        const ext = path.split(".").pop().toLowerCase();

        visorArchivo.innerHTML =
            ext === "pdf"
                ? `<iframe src="${path}" class="visor" allowfullscreen></iframe>`
                : `<img src="${path}" class="visor" alt="Comprobante" onerror="this.onerror=null;this.src='public/imagenes/usuario.png'">`;
    }

    function procesarPago(accion) {
        if (!pagoSeleccionado) return;

        const esCompensatorio = !pagoSeleccionado.mes;

        const endpoint = esCompensatorio
            ? (accion === "aprobar" ? "/aprobar-pago-compensatorio" : "/rechazar-pago-compensatorio")
            : (accion === "aprobar" ? "/aprobar-pago" : "/rechazar-pago");

        Swal.fire({
            title: accion === "aprobar" ? "¿Aprobar este pago?" : "¿Rechazar este pago?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí"
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch(endpoint, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `pago_id=${encodeURIComponent(pagoSeleccionado.id)}`
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) throw new Error(data.error);

                    Swal.fire({
                        icon: "success",
                        title: accion === "aprobar" ? "Pago aprobado ✅" : "Pago rechazado ❌",
                        timer: 1500,
                        showConfirmButton: false
                    });

                // ✅ Reset visual y funcional a estado inicial
                pagoSeleccionado = null;
                
                // ✅ Reset visual
                $("#tablaPagos tbody tr").removeClass("fila-seleccionada");
                detalleUsuario.innerHTML = `<p class="placeholder-detalle">Selecciona un pago para ver más información...</p>`;
                detalleUsuario.classList.add("oculto");
                
                visorArchivo.innerHTML = "";
                visorArchivo.classList.add("oculto");
                
                // ✅ Deshabilitar botones
                btnAceptar.disabled = true;
                btnRechazar.disabled = true;
                
                // ✅ Recargar tabla limpia
                cargarPagos(
                    filtroTipo.value === "mensual"
                        ? "/pagosadmin"
                        : "/pagos-compensatorios-admin"
                );

                })
                .catch(err => {
                    Swal.fire({ icon: "error", title: "Oops...", text: err.message });
                });
        });
    }

    cargarPagos("/pagosadmin"); // ✅ Cargar por defecto al iniciar
});