<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="imagenes/logo.png" type="icon">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <title>Login</title>
</head>
<body> 

<div class="login-container"> 

  <div class="regresar-container">
    <a href="/">
    <img src="imagenes/flecha.png" alt="flecha_icon">
    </a>
  </div>

  <h1>Ingrese con su usuario</h1>
  <form class="login-column" id="form-login" autocomplete="on"> 
    <input name="email" placeholder="Email" id="Email" class="input-field">
    <input type="password" name="password" placeholder="Contraseña" id="password" class="input-field">
    <label class="Error"></label>
    <input type="submit" value="Iniciar Sesión" class="boton-login">
  </form>
</div>
 
<div class="Lado-derecho-container">
  <h2>¿Sin cuenta?</h2>
  <img src="imagenes/persona-mas.png" alt="usuario_icon">
  <p>Crea una solicitud para participar de nuestra cooperativa.</p>
  <a href="/registro"><button>Registrar</button></a>
</div>

<script src="js/login.js"></script>
</body>
</html>
