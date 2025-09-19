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
    const respuesta = await fetch("/usuariodatos", { method: "GET" });

    const texto = await respuesta.text(); 
 

    try {
        const data = JSON.parse(texto); // intentamos parsear
      document.getElementById("nombre_usuario").innerText = data.nombre;

      document.getElementById("Nombre-datos").innerText ="Nombre: "+ data.nombre;
      document.getElementById("Apellido-datos").innerText ="Apellido: "+ data.apellido;
      document.getElementById("Telefono-datos").innerText ="Telefono: "+ data.telefono;
      document.getElementById("Correo-datos").innerText ="Correo: "+ data.email;
    } catch (e) {
        console.log("No se pudo parsear JSON:", e);
    }

    const hoy = new Date();

    const dia = String(hoy.getDate()).padStart(2, '0');
    const mes = String(hoy.getMonth() + 1).padStart(2, '0'); 
    const anio = String(hoy.getFullYear()).slice(-2); 

    const fechaFormateada = `${dia}/${mes}/${anio}`;

    document.getElementById("fecha-horas").innerText = fechaFormateada;

}
document.getElementById("regresar_icon").addEventListener("click", function() {
    document.getElementById("Cambio-contraseña").style.display = "none";
});

document.getElementById("btn-cambiarcontraseña").addEventListener("click", function() {
    document.getElementById("Cambio-contraseña").style.display = "block";
});


});