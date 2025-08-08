 
    // Script para manejar la visualización del nombre del archivo
    document.getElementById('archivoPago').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Ningún archivo seleccionado';
        document.getElementById('file-name').textContent = fileName;
    });

    document.getElementById('btnSeleccionar').addEventListener('click', function() {
        document.getElementById('archivoPago').click();
    });