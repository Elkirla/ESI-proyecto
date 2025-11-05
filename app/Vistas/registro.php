<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/registro.css">
    <link rel="icon" href="public/imagenes/logo.png" type="icon">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <title>Registro</title>
</head>
<body> 
  
<div class="errores-container">
    <div class="Errores">  
        <h2>Error al registrar</h2>
        <p>Debe cumplir con los siguientes requisitos:</p>
        <div id="mensaje-error"></div> 
        <button>Aceptar</button>
    </div>
</div>   

<div class="regresar-container">
    <a href="/">
    <img src="public/imagenes/flecha.png" alt="flecha_icon">
    </a>
</div>

<div class="yaregistrado-container">
    <h2>¿Ya registrado?</h2>
    <img src="public/imagenes/key.png" alt="flecha_icon">
    <p>Ingresa a tu cuenta de manera rápida, segura y sencilla.</p>
    <a href="/login"><button>Ingresar</button></a> 
</div>
<div class="registrar-container">


<h1>Solicita una cuenta</h1>
<form method="post" class="registro-column" action="/registro" id="form-registro" autocomplete="on">  

  <input type="text" placeholder="Nombre(s)" id="nombre" name="nombre" class="input-field" required>

  <input type="text" placeholder="Apellido(s)" id="apellido" name="apellido" class="input-field" required>

  <input type="number" id="ci" name="ci" placeholder="Cédula de Identidad" class="input-field" maxlength="15" pattern="[0-9]+" required>
 
  <div class="telefono-container">
<select id="pais" name="pais" class="select-pais" required>
  <option value="+598" selected>🇺🇾 +598 </option>
  <option value="+54">🇦🇷 +54 </option>
  <option value="+55">🇧🇷 +55 </option>
  <option value="+56">🇨🇱 +56  </option>
  <option value="+57">🇨🇴 +57 </option>
  <option value="+34">🇪🇸 +34 </option>
  <option value="+1">🇺🇸 +1 </option>
  <option value="+52">🇲🇽 +52  </option>
</select>

<input 
  type="number" 
  id="telefono" 
  name="telefono" 
  placeholder="Teléfono" 
  class="input-field telefono-input" 
  min="100000" 
  max="999999999999999" 
  required>

  </div>

  <input type="email" placeholder="Email" id="email" name="email" class="input-field" required>

  <input type="password" placeholder="Contraseña" id="password" name="password" class="input-field"  >

  <input type="password" placeholder="Confirmar contraseña" id="confirm_password" name="confirm_password" class="input-field"  >
  
  <input type="submit" value="Registrarse" class="input-field registro-button"> 

</form>

</div> 
</div>
<script src="public/js/registro.js"></script>
</body>
</html>