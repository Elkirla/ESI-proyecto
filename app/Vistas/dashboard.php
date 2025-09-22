<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=person" />
    <link rel="icon" href="public/imagenes/logo.png" type="icon">
    <title>Página usuario</title>
</head>
<body>

<div class="Cambio-contraseña" id="Cambio-contraseña">
    <div class="Form-cambiarcontraseña">
        <div class="flecha">
            <img src="public/imagenes/regresar.png" alt="regresar_icon" id="regresar_icon">
            <h2>Cambiar Contraseña</h2>
        </div>
        <form action="" class="form">
            <div class="input">
                <label for="contraseña-actual">Contraseña Actual:</label>
                <input type="password" id="contraseña-actual" name="contraseña-actual" required>
            </div>
            <div class="input">
                <label for="nueva-contraseña">Nueva Contraseña:</label>
                <input type="password" id="nueva-contraseña" name="nueva-contraseña" required>
            </div>
            <div class="input">
                <label for="confirmar-contraseña">Confirmar Nueva Contraseña:</label>
                <input type="password" id="confirmar-contraseña" name="confirmar-contraseña" required>
            </div>
            <button type="submit" id="btn-guardar-contraseña">Confirmar</button>
        </form>
    </div>
</div>

<div class="notificaciónes-container">
    <div class="notificaciónes" id="notificaciónes">
        <h3>Notificaciones</h3>
        <p id="cerrar-notis">X</p>
        <ul id="lista-notificaciones">
        </ul>
    </div>
</div>

     <div class="heder"></div>
    <div class="sider">
        <button id="btn-inicio">Inicio</button>
        <button id="btn-mi-perfil">Mi perfil</button>
        <button id="btn-pagos">Pagos</button>
        <button id="btn-horas">Horas</button>
        <button id="btn-unidad">Unidad</button>
        <button id="btn-mensajes">Mensajes</button>
        <button id="btn-soporte">Soporte</button>
    </div>
<div class="info">
    <div class="inicio section">
        <div class="h2">
            <h2>Bienvenido</h2>
            <h2 id="nombre_usuario">nombre</h2>
        </div>
        <div class="estado">
            <h3>Estado de pagos </h3> <h3>Al dia</h3>
        </div>
        <div class="h">
        <h3>Horas Trabajadas </h3> <h3>Horas</h3>
        </div>
        <div class="u">
        <h3>Unidad Habitacional </h3> <h3>Cimientos</h3>
        </div>   
    </div>

    <div class="mi-perfil section">
        <div class="texto-icono">
            <span class="material-symbols-outlined">person</span>
            <span>Mi perfil</span>
        </div>
        <div class="datos"> 
            <p id="Nombre-datos"></p>
            <p id="Apellido-datos"></p>
            <p id="Telefono-datos"></p>
            <p id="Correo-datos"></p>
        </div>
        <button id="btn-cambiarcontraseña" class="but">Cambiar Contraseña</button>
    </div>
 
<div class="pagos section"> 
    <h2>Mis Pagos</h2>
    <div class="fila">
        <h3>Debiendo  |  0</h3> 
        <h3> Proximo pago  | 10 dias</h3>
    </div>
    <label for="filtro">Filtro</label>
    <select id="filtro" class="color">
        <option value="todos">Todos</option>
        <option value="pendientes">Pendientes</option>
        <option value="aprobados">Aprobados</option>
        <option value="atrasados">Atrasados</option>
    </select>
    <table>
    <tr>
       <th>Mes</th>
       <th>Dia</th>
       <th>Monto</th>
       <th>Estado</th>
    </tr>
    <tr>
        <td>Mayo</td>
        <td>12</td>
        <td>$20.000</td>
        <td class="pendiente">Pendiente</td>          
    </tr>
    <tr>
        <td>Abril</td>
        <td>14</td>
        <td>$20.000</td>
        <td class="aprovado">Aprobado</td>          
    </tr>
    <tr>
        <td>Maarzo</td>
        <td>21</td>
        <td>$20.000</td>
        <td class="atrasado">Atrasado</td>          
    </tr>
     <tr>
        <td>Febrero</td>
        <td>14</td>
        <td>$20.000</td>
        <td class="atrasado">Aprovado</td>          
    </tr>
</table>
    <h2>Subir comprobantes</h2>
<div class="comprovante">
    <form action="" class="form-pago" id="form-pago" enctype="multipart/form-data">
        <div class="form-group">
            <label for="archivo" class="file-label">
                <span>📎 Seleccionar comprobante</span>
                <input type="file" name="archivo" id="archivo" accept=".jpg,.jpeg,.png,.pdf">
            </label>
            <small>Formatos permitidos: JPG, PNG, PDF (Máx. 5MB)</small>
        </div>
        
        <div class="form-group">
            <label for="mes">Mes del pago:</label>
            <select id="mes" name="mes" class="select-mes">
                <option value="">Seleccione un mes</option>
                <option value="01">Enero</option>
                <option value="02">Febrero</option>
                <option value="03">Marzo</option>
                <option value="04">Abril</option>
                <option value="05">Mayo</option>
                <option value="06">Junio</option>
                <option value="07">Julio</option>
                <option value="08">Agosto</option>
                <option value="09">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
        </div>
        
        <button type="submit" id="btn-pagar" class="but">Enviar Comprobante</button>
    </form>
</div>

</div>
    <div class="horas section">
       <div> <h2>Horas Registradas</h2></div>
        <div>
|           <label for="fil">Filtro</label>
            <select id="fil" class="color">
                <option value="todos">Todos</option>
                <option value="pendientes">Pendientes</option>
                <option value="aprobados">Aprobados</option>
                <option value="atrasados">Rechazados</option>
            </select>
        </div>
        <div class="conjunto">
        <div class="t">
            <table>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
            </table>
        </div>
        <div class="con">

            <div class="jus1">
                <h3>Registrar horas</h3>
                <label for="hr" id="fecha-horas"></label>
                <input type="text" id="hr" placeholder="Horas trabajadas">
                <button id="subirhoras">Subir</button>
            </div>

            <div class="jus">
                <h3>Justificativo</h3>
                <textarea name="just" id="just" cols="30" rows="10" placeholder="Escriba el justificativo aqui..."></textarea>
                <input type="file" placeholder="Fotodelcertificado.png">
                <button id="enviar">Enviar</button>
                </div>
        </div>
    </div>
</div>

    <div class="unidad section">
        <h2>Mi unidad habitacional</h2>
        <div class="f1">
            <h3>Numero de unidad</h3> <h3>12</h3>
        </div>
        <div class="f2"> 
            <h3>Estado</h3> <h3>Cimientos</h3>
        </div>
    </div>

    <div class="mensajes section">
        <h2>Mensajes</h2>
        <textarea id="Recordatorio" placeholder="Recordatorio-se acerca la fecha de pago"></textarea>
    </div>

    <div class="soporte section">
        <h2>Soporte</h2>
        <h3>No dude de hacernos saber si ocurre algun inconveniente</h3>
        <p>- Correo@gmail.com</p>
        <p>- +099.123.456</p>
        <p>- +099.222.333</p>
    </div>
</div>

<script src="public/js/dashboard.js"></script>
</body>
</html>