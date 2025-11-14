<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="public/css/registro.css">
  <link rel="icon" href="public/imagenes/logo.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <title>Registro</title>
</head>
<body> 

  <!-- Contenedor de errores -->
  <div class="errores-container">
    <div class="Errores">  
      <h2>Error al registrar</h2>
      <p>Debe cumplir con los siguientes requisitos:</p>
      <div id="mensaje-error"></div> 
      <button id="cerrar-error">Aceptar</button>
    </div>
  </div>   

  <!-- Botón para regresar -->
  <div class="regresar-container">
    <a href="/">
      <img src="public/imagenes/flecha.png" alt="Regresar">
    </a>
  </div>

  <!-- Sección “Ya registrado” -->
  <div class="yaregistrado-container">
    <h2>¿Ya registrado?</h2>
    <img src="public/imagenes/key.png" alt="Icono llave">
    <p>Ingresa a tu cuenta de manera rápida, segura y sencilla.</p>
    <a href="/login"><button>Ingresar</button></a> 
  </div>

  <!-- Formulario de registro -->
  <div class="registrar-container">
    <h1>Solicita una cuenta</h1>

    <form method="post" action="/registro" id="form-registro" class="registro-column" autocomplete="on">  

      <!-- Nombre -->
      <input 
        type="text" 
        id="nombre" 
        name="nombre" 
        placeholder="Nombre(s)" 
        class="input-field" 
        maxlength="50"
        autocomplete="given-name" 
        required
      >

      <!-- Apellido -->
      <input 
        type="text" 
        id="apellido" 
        name="apellido" 
        placeholder="Apellido(s)" 
        class="input-field" 
        maxlength="50"
        autocomplete="family-name" 
        required
      >

      <!-- Cédula -->
      <input 
        type="text" 
        id="ci" 
        name="ci" 
        placeholder="Cédula de Identidad" 
        class="input-field" 
        maxlength="15" 
        pattern="[0-9]+" 
        inputmode="numeric"
        required
      >
      
      <!-- Teléfono -->
      <div class="telefono-container">
        <select id="pais" name="pais" class="select-pais" required aria-label="Seleccionar país">
          <option value="+598" selected>🇺🇾 +598</option>
          <option value="+54">🇦🇷 +54</option>
          <option value="+55">🇧🇷 +55</option>
          <option value="+56">🇨🇱 +56</option>
          <option value="+57">🇨🇴 +57</option>
          <option value="+34">🇪🇸 +34</option>
          <option value="+1">🇺🇸 +1</option>
          <option value="+52">🇲🇽 +52</option>
        </select>

        <input 
          type="tel" 
          id="telefono" 
          name="telefono" 
          placeholder="Teléfono" 
          class="input-field telefono-input" 
          pattern="[0-9]{6,15}" 
          maxlength="6" 
          inputmode="numeric"
          autocomplete="tel-national"
          required
        >
      </div>

      <!-- Email -->
      <input 
        type="email" 
        id="email" 
        name="email" 
        placeholder="Email" 
        class="input-field" 
        autocomplete="email" 
        required
      >

      <!-- Contraseña -->
      <input 
        type="password" 
        id="password" 
        name="password" 
        placeholder="Contraseña" 
        class="input-field" 
        autocomplete="new-password"
        required
      >

      <!-- Confirmar contraseña -->
      <input 
        type="password" 
        id="confirm_password" 
        name="confirm_password" 
        placeholder="Confirmar contraseña" 
        class="input-field" 
        autocomplete="new-password"
        required
      >
      
      <!-- Botón de envío -->
      <input 
        type="submit" 
        value="Registrarse" 
        class="input-field registro-button"
      > 

    </form>
  </div>

  <script src="public/js/registro.js"></script>
</body>
</html>
