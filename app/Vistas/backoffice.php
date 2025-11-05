<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/backoffice.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=person" />
    <title>Document</title>
</head>
<body>
<div class="heder"></div>
<div class="sider">
    <div class="Cerrar-sesion">
        <button id="btn-cerrar-sesion">Cerrar sesión</button>

    </div>
    <h2>Coperativa</h2>
    <h3>backoffice</h3>
    <button id="btn-mi-perfil">Mi perfil</button>
    <button id="btn-usuarios">Usuarios</button>
    <button id="btn-pagos">Pagos</button>
    <button id="btn-ingresar">Ingresar</button>
    <button id="btn-horas">Horas</button>
</div>
<div class="info">
    <div class="mi-perfil section">
        <div class="texto-icono">
            <span class="material-symbols-outlined">person</span>
            <span>Mi perfil</span>
        </div>
        <div class="datos">
            <input type="text" placeholder="Nombre">
            <input type="text" placeholder="Apellido">
            <input type="text" placeholder="Telefono">
            <input type="email" placeholder="Correo electronico">
        </div>
        <button id="btn-guardar" class="but">Cambiar contraseña</button>
    </div>

    <div class="usuarios section">
        <h2>Usuario</h2>
        <div class="user">
            <label for="buscar">Buscar por</label>
            <select id="buscar">
            <option value="cedula">Cedula</option>
            <option value="nombre">Nombre</option>
            <option value="Correo electronico">Correo</option>
            <option value="telefono">Telefono</option>
            </select>
            <input type="text" class="in">
            <button class="bu">Aplicar</button>
        </div>
        <div class="Filtro">
            <h3>Filtro</h3>
            <button class="bp">Pagos</button>
            <button class="bh">Horas</button>
        </div>
        <table>
            <tr>
                <th>Cedula</th>
                <th>Nombre</th>
                <th>Correo electronico</th>
                <th>Telefono</th>
                <th>Unidad</th>
            </tr>
            <tr>
                <td>1231231</td>
                <td>Jorge</td>
                <td>Jorge@gmail.com</td>
                <td>099 123 456</td>
                <td>1</td>
            </tr>
            <tr>
                <td>1122334</td>
                <td>Pedro</td>
                <td>Pedro@gmail.com</td>
                <td>099 111 222</td>
                <td>2</td>
            </tr>
                <tr>
                <td>1231231</td>
                <td>Jorge</td>
                <td>Jorge@gmail.com</td>
                <td>099 123 456</td>
                <td>1</td>
            </tr>
            <tr>
                <td>1122334</td>
                <td>Pedro</td>
                <td>Pedro@gmail.com</td>
                <td>099 111 222</td>
                <td>2</td>
            </tr>
                        <tr>
                <td>1231231</td>
                <td>Jorge</td>
                <td>Jorge@gmail.com</td>
                <td>099 123 456</td>
                <td>1</td>
            </tr>
            <tr>
                <td>1122334</td>
                <td>Pedro</td>
                <td>Pedro@gmail.com</td>
                <td>099 111 222</td>
                <td>2</td>
            </tr>
        </table>
    </div>


    <div class="pagos section">
        <h2>Pagos</h2>
        <label for="buscar">Buscar por</label>
        <select id="buscar">
            <option value="Cedula">Cedula</option>
            <option value="Nombre">Nombre</option>
        </select>
        <input type="text" class="in">
        <button class="aplicar">Aplicar</button>
        <table>
            <tr>
                <th>Cedula</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Comprobante</th>
                <th></th>
            </tr>
            <tr>
                <td>1231231</td>
                <td>Jorge</td>
                <td>12/4</td>
                <td>$20.000</td>
                <td>Comprobante.pdf</td>
                <td> <input type="checkbox"></td>
            </tr>
            <tr>
                <td>1122334</td>
                <td>Pedro</td>
                <td>12/4</td>
                <td>$20.000</td>
                <td>Comprovante.pdf</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>1231231</td>
                <td>Jorge</td>
                <td>16/3</td>
                <td>$20.000</td>
                <td>Comprobante.pdf</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>1122334</td>
                <td>Pedro</td>
                <td>10/3</td>
                <td>$20.000</td>
                <td>Comprovante.pdf</td>
                <td> <input type="checkbox"></td>
            </tr>
            <tr>
                <td>1231231</td>
                <td>Jorge</td>
                <td>11/2</td>
                <td>$20.000</td>
                <td>Comprobante.pdf</td>
                <td> <input type="checkbox"></td>
            </tr>
            <tr>
                <td>1122334</td>
                <td>Pedro</td>
                <td>9/2</td>
                <td>$20.000</td>
                <td>Comprovante.pdf</td>
                <td> <input type="checkbox"></td>
            </tr>
            </table>
                <button id="ba" class="bverde">Aceptar</button>
                <button id="br" class="brojo">Rechazar</button>
    </div>

    <div class="ingresar section">
        <h2>Ingresar usuarios</h2>
        <label for="buscar1">Buscar por</label>
        <select id="buscar1">
            <option value="Cedula">Cedula</option>
            <option value="Nombre">Nombre</option>
            <option value="Apellido">Apellido</option>
            <option value="Telefono">Telefono</option>
            <option value="Correo">Correo</option>
        </select>
        <input type="text" class="in">
        <button class="aplicar">Aplicar</button>
        <table>
            <tr>
                <th>Cedula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th></th>
            </tr>
            <tr>
                <td>1231231</td>
                <td>Jorge</td>
                <td>Vias</td>
                <td>099 011 908</td>
                <td>Jorge@gmail.com</td>
                <td> <input type="checkbox"></td>
            </tr>
            <tr>
                <td>1122334</td>
                <td>Pedro</td>
                <td>Jose</td>
                <td>099 111 222</td>
                <td>Pedro@gmail.com</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>1231231</td>
                <td>Maria</td>
                <td>Martines</td>
                <td>099 555 666</td>
                <td>Maria@gmail.com</td>
                <td><input type="checkbox"></td>
            </tr>
              <tr>
                <td>1231231</td>
                <td>Jorge</td>
                <td>Vias</td>
                <td>099 011 908</td>
                <td>Jorge@gmail.com</td>
                <td> <input type="checkbox"></td>
            </tr>
            <tr>
                <td>1122334</td>
                <td>Pedro</td>
                <td>Jose</td>
                <td>099 111 222</td>
                <td>Pedro@gmail.com</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>1231231</td>
                <td>Maria</td>
                <td>Martines</td>
                <td>099 555 666</td>
                <td>Maria@gmail.com</td>
                <td><input type="checkbox"></td>
            </tr>

            </table>
                <button id="ba" class="bverde">Aceptar</button>
                <button id="br" class="brojo">Rechazar</button>

    </div>
    <div class="horas section" >
        <h2>Manejo de horas</h2>
        <div class="user">
            <label for="b">Buscar por</label>
            <select id="b">
                <option value="Cedula">Cedula</option>
                <option value="Nombre">Nombre</option>
            </select>
            <input type="text" class="in">
            <button class="aplicar">Aplicar</button>
        </div>
    <div class="dos">
        <div class="t">
            <table>
                <tr>
                    <th>Cedula</th>
                    <th>Nombre</th>
                    <th>Horas inasistidas</th>
                    <th></th>
                </tr>
                <tr>
                    <td>1231231</td>
                    <td>Jorge</td>
                    <td>5</td>
                    <td><input type="checkbox"></td> 
                </tr>
                <tr>
                    <td>1122334</td>
                    <td>Pedro</td>
                    <td>12</td>
                    <td><input type="checkbox"></td>  
                </tr>
                <tr>
                    <td>1231231</td>
                    <td>Jorge</td>
                    <td>5</td>
                    <td><input type="checkbox"></td>         
                </tr>
                <tr>
                    <td>1122334</td>
                    <td>Pedro</td>
                    <td>12</td>
                    <td> <input type="checkbox"></td>    
                </tr>
                <tr>
                    <td>1231231</td>
                    <td>Jorge</td>
                    <td>5</td>
                    <td><input type="checkbox"></td>
                </tr>
                <tr>
                    <td>1122334</td>
                    <td>Pedro</td>
                    <td>12</td>
                    <td><input type="checkbox"></td>
                </tr>
            </table>
            <button class="bverde">Aceptar</button>
            <button class="brojo">Rechazar</button>
        </div>
        <div class="jus">
            <h3>Justificativo</h3>
            <textarea name="just" id="just" cols="30" rows="10" placeholder="Escriba el justificativo aqui..."></textarea>
            <input type="file" placeholder="Fotodelcertificado.png">
            <button id="enviar">Enviar</button>
        </div>
    </div>

</div>

<script src="../../public/js/backoffice.js"></script>
</body>
</html>
