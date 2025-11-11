<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/AdministrarPagos.css">
    <link rel="icon" href="public/imagenes/logo.png" type="icon">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <title>Administrar Pagos</title>
</head>
<body> 

<div class="admin-pagos-container">
    
    <!-- Columna izquierda -->
    <div class="panel-lista">
        <h1 class="titulo-seccion">Pagos Pendientes</h1>

        <!-- Filtros -->
        <div class="filtros">
            <select id="filtroTipo"> 
                <option value="mensual">Mensual</option>
                <option value="compensatorio">Compensatorio</option>
            </select> 
        </div>

        <!-- DataTable de usuarios que enviaron pago -->
        <table id="tablaPagos" class="display">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
                <!-- Se llena dinámicamente con Ajax -->
            </tbody>
        </table>

        <!-- Botones de acción -->
        <div class="acciones">
            <button id="btnAceptar" disabled>Aceptar</button>
            <button id="btnRechazar" disabled>Rechazar</button>
        </div>
    </div>


    <!-- Columna derecha -->
    <div class="panel-detalle">
        
        <!-- Información del usuario -->
        <div id="detalleUsuario" class="detalle-usuario">
            <h3>Detalles del Usuario</h3>
            <p>Selecciona un pago para ver más información...</p>
        </div>

        <!-- Visor PDF/Imagen -->
        <div id="visorArchivo" class="visor-archivo oculto">
            <!-- Aquí cargaremos el comprobante -->
        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <script src="public/js/AdministrarPagos.js"></script>

</body>
</html>