document.addEventListener("DOMContentLoaded", () => {

    // Todas las secciones del contenido
    const sections = document.querySelectorAll(".section");

    // Navegación entre secciones existentes
    const navigationMap = {
        'btn-mi-perfil': 'mi-perfil',
        'btn-Usuarios': 'usuarios',
        'btn-Pagos': 'pagos',
        'btn-ingresar': 'ingresar',
        'btn-Horas': 'horas',
        'btn-Unidades': 'unidades',
        'btn-Config': 'config'
    };

    // Ocultamos todas y mostramos "mi perfil" por defecto
    sections.forEach(s => s.style.display = "none");
    document.querySelector(".mi-perfil").style.display = "block";

    Object.keys(navigationMap).forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (!btn) return; // Evita error si el botón no existe aún

        btn.addEventListener("click", () => {
            sections.forEach(s => s.style.display = "none");
            document.querySelector("." + navigationMap[btnId]).style.display = "block";
        });
    });

    // Movimiento del marcador azul en el sider
    const marcador = document.querySelector(".opcion-div");
    const botones = document.querySelectorAll(".sider button");

    botones.forEach(boton => {
        boton.addEventListener("click", () => {
            marcador.style.top = (boton.offsetTop - 5) + "px";
        });
    });

    // Cerrar notificaciones 
});
