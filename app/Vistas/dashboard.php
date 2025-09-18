<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=person" />
    <title>Página usuario</title>
</head>
<body>

<div class="Cambio-contraseña" id="Cambio-contraseña">
    <div class="Form-cambiarcontraseña">
        <img src="public/imagenes/regresar.png" alt="regresar_icon" id="regresar_icon">
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
        <input type="file"">
        <label for="mes">Mes:</label>
        <select id="mes" name="mes">
        <option value="Enero">Enero</option>
        <option value="Febrero">Febrero</option>
        <option value="Marzo">Marzo</option>
        <option value="Abril">Abril</option>
        <option value="Mayo">Mayo</option>
        <option value="Junio">Junio</option>
        <option value="Julio">Julio</option>
        <option value="Agosto">Agosto</option>
        <option value="Septiembre">Septiembre</option>
        <option value="Octubre">Octubre</option>
        <option value="Noviembre">Noviembre</option>
        <option value="Diciembre">Diciembre</option>
    </select>
        <button id="btn-subir" class="but">Subir</button>
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
                <tr>
                    <td>17/5</td>
                    <td>6</td>
                </tr>
                <tr>
                    <td>17/5</td>
                    <td>Ausente</td>
                </tr>
                <tr>
                    <td>14/5</td>
                    <td>Exonerado</td>
                </tr>
                 <tr>
                    <td>17/5</td>
                    <td>Ausente</td>
                </tr>
                <tr>
                    <td>14/5</td>
                    <td>Exonerado</td>
                </tr>

            </table>
        </div>
        <div class="con">
            <div class="jus1">
                <h3>Registrar horas</h3>
                <label for="hr">18/7</label>
                <input type="text" id="hr" placeholder="Horas trabajadas">
                <button>Subir</button>
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

<script src="/public/js/dashboard.js"></script>
</body>
</html>