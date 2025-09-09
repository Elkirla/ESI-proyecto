document.addEventListener('DOMContentLoaded', function() { 
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
});