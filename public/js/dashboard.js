document.addEventListener('DOMContentLoaded', function() { 
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


    
});