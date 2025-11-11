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
            .then(data => {
                if ($.fn.DataTable.isDataTable("#tablaPagos")) {
                    tabla.clear().destroy();
                }

                tabla = $("#tablaPagos").DataTable({
                    data: data,
                    columns: [
                        { data: null, render: row => `Usuario #${row.usuario_id}` },
                        { data: "fecha", render: fecha => new Date(fecha).toLocaleDateString("es-UY") },
                        {
                            data: null,
                            render: row => row.mes ? "Mensual" : "Compensatorio"
                        }

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
            .catch(err => console.error("Error al cargar pagos:", err));
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
        <p><b>ID Usuario:</b> ${data.usuario_id}</p>
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

        if (!/^https?:\/\//i.test(path)) {
            path = path.replace(/^\/+/, "");
            if (!path.startsWith("public/")) path = "public/" + path;
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

    const confirmMsg = accion === "aprobar"
        ? "¿Seguro que deseas APROBAR este pago?"
        : "¿Seguro que deseas RECHAZAR este pago?";

    if (!confirm(confirmMsg)) return;

    fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `pago_id=${encodeURIComponent(pagoSeleccionado.id)}`
    })
    .then(res => res.text())
    .then(texto => {
        console.log("📌 Respuesta RAW del servidor:", texto);

        let data;
        try {
            data = JSON.parse(texto);
        } catch (jsonErr) {
            throw new Error("Respuesta inválida del servidor. No es JSON.");
        }

        if (!data.success) throw new Error(data.error || "Error al procesar el pago");

        Swal.fire({
            icon: "success",
            title: accion === "aprobar" ? "Pago aprobado ✅" : "Pago rechazado ❌",
            timer: 2000,
            showConfirmButton: false
        });

        pagoSeleccionado = null;
        detalleUsuario.classList.add("oculto");
        visorArchivo.classList.add("oculto");
        btnAceptar.disabled = true;
        btnRechazar.disabled = true;

        cargarPagos(
            filtroTipo.value === "mensual"
                ? "/pagosadmin"
                : "/pagos-compensatorios-admin"
        );
    })
    .catch(err => {
        console.error("⚠ Error:", err);
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: err.message
        });
    });
}



    cargarPagos("/pagosadmin"); // ✅ Cargar por defecto al iniciar
});
