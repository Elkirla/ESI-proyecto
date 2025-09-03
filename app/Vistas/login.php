<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="../imagenes/logo.png" type="icon">
    <title>Login</title>
</head>
<body>
<div class="header">
    <a href="../frontend-landing/index.html">
        <img src="/Esi-proyecto/imagenes/regresar.png" alt="regresar boton" class="regresar_flecha">
    </a>
    <img src="/Esi-proyecto/imagenes/logo2.png" alt="logo FENEC" class="logoheader">
    <p class="separador"></p>
</div>

    <div class="login-container">
        <div class="login-column" id="img-login">  

        </div>
        <form class="login-column" id="form-login" action="login.php" method="POST" autocomplete="on">
            <img src="/Esi-proyecto/imagenes/Login-Avatar.png" alt="Avatar" class="avatar-icon">
            <h2>¡Bienvenido de nuevo!</h2>
            <input type="email" placeholder="Email" id="Email" class="input-field" required >
            <input type="password" placeholder="Contraseña" id="password" class="input-field" required>
            <input type="submit" value="Iniciar Sesión" class="input-field login-button">
            <p id="link-registro">Si no tienes una cuenta puedes registrarte <a href="registrar.html">aquí</a>.</p>
        </form>

    </div>

    <div class="footer-container">
    <div class="footer-column">
        <h3>Contacto</h3>
        <p>Email: info@cooperativafenec.com</p>
        <p>Teléfono: +123 456 7890</p>
        <p>Dirección: Calle Principal 123, Ciudad</p>
    </div>

    <div class="footer-column">
        <h3>Enlaces rápidos</h3>
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Novedades</a></li>
            <li><a href="#">Sobre nosotros</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </div>

    <div class="footer-column">
        <h3>Redes sociales</h3>
        <div class="social-icons">
            <a href="https://twitter.com/" target="_blank"><img src="/Esi-proyecto/imagenes/twitter.png" alt="Twitter"></a>
            <a href="https://facebook.com/" target="_blank"><img src="/Esi-proyecto/imagenes/facebook.png" alt="Facebook"></a>
            <a href="https://instagram.com/" target="_blank"><img src="/Esi-proyecto/imagenes/instagram.png" alt="Instagram"></a>
            <a href="https://linkedin.com/" target="_blank"><img src="/Esi-proyecto/imagenes/linkedin.png" alt="LinkedIn"></a>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 FENEC. Todos los derechos reservados.</p>
    </div>

</body>
</html>