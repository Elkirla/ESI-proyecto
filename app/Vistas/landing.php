<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa-FENEC</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;700&family=Montserrat&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="icon" href="public/imagenes/logo.png" type="icon">
</head>
<body>

<div class="header">
    <img src="public/imagenes/logo2.png" alt="logo FENEC" class="logoheader">
    <button><a href="#normas">Normas</a></button>
    <button><a href="#comunidadtxt">Sobre nosotros</a></button>
    <button><a href="#footer">Contactanos</a></button>
    <button class="loginbtton"><a href="../frontend-usuarios/login.html">Log in</a></button> 
    <img src="public/imagenes/menu.png" alt="menu hamburguesa" class="menu" id="menuabrir">
    <p class="separador"></p>
</div>

<div class="menu-container" id="menucontainer">
    <img src="public/imagenes/menu.png" alt="menu hamburguesa" class="menu-icon" id="menucerrar">
 <p class="separador-menu"></p>
    <div class="menubotones">
      <button><a href="#logrado">Novedades</a></button>
      <button><a href="#footer">Contactanos</a></button>
      <button><a href="#comunidadtxt">Sobre nosotros</a></button>
      <button class="loginbtton"><a href="../frontend-usuarios/login.html">Log in</a></button>
   </div>    
   <div id="menucontactos">
      <p class="menu-text">Email: info@cooperativafenec.com</p>
      <p class="menu-text">Teléfono: +123 456 7890</p>
      <p class="menu-text">Dirección: Calle Principal 123, Ciudad</p>    
   </div>
</div> 

<div class="hero-container">
    <h1 id="h1">Cooperativa</h1>
    <h2 id="h2"></h2>
    <h2 class="subtitle">Cooperativa FENEC</h2>
    <button class="inicio-hero"><a href="registro.php">Iniciar</a></button>
    <p>Cree su usuario de manera rapida</p>
    <div class="hero"></div>
</div>

<div class="normas" id="normas"> 
    <div class="normasimg1"></div>
    <div class="normasimg2"></div>
<div class="normastexto-container">
    <div class="textonormas">
        <h2 id="normash2">Normas que valoramos</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis, architecto ea. Nobis nostrum molestiae, nesciunt fuga amet porr.</p> 
        <button><a href="normasociales.html">Más información</a></button>
    </div>
    <div class="textocrearcuenta">
        <h2 id="cuentah2">¿Como creo una cuenta?</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis, architecto ea. Nobis nostrum molestiae, nesciunt fuga amet porr.</p> 
        <button><a href=#registroinstrucciones>Más información </a></button>
    </div>
</div>
</div>
    <h2 id="comunidadtxt">Comunidad</h2>

<div class="slider">
    <button class="slider-btn prev">‹</button>
    <div class="slider-container">
        
        <div class="slider-item active">
            <img src="public/imagenes/slider1.png" alt="Slider Image 1">
        </div>
        <div class="slider-item">
            <img src="public/imagenes/slider2.png" alt="Slider Image 2">
        </div>
        <div class="slider-item">
            <img src="public/imagenes/slider3.png" alt="Slider Image 3">
        </div>
        <div class="slider-item">
            <img src="public/imagenes/slider4.png" alt="Slider Image 4">
        </div> 
    </div>
        <button class="slider-btn next">›</button>
     <div class="slider-dots"></div>
</div>

<div class="info-slide" id="infoslide">
    <div class="info-slide-text">
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Est vero incidunt alias quidem animi nulla quod</p>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Est vero incidunt alias quidem animi nulla quod</p>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
</div>

</div>
<div class="logrado-container" id="logrado">
    <div class="conseguidocont" id="logradotxt">
        <h2>Hemos logrado</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut incidunt maiores. </p><br>
       <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut incidunt maiores. </p>
    </div>
    <div class="metas" id="metastxt">
        <h2>Apuntamos a</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio mollitia eligendi a saepe. Hic, numquam. Magni omnis eligendi neque quod cum ex.</p>
    </div>
</div>

<div class="registro-instrucciones" id="registroinstrucciones">
    <h2>Instrucciones para el registro</h2>
    <p>Para unirte a nuestra cooperativa, sigue estos simples pasos:</p> 
    <p>Haz clic en el botón "Registrarse" a continuación.</p>
    <button class="registro-button"><a href="registro.php">Registrarse</a></button>
    <p>Completa el formulario de registro con tus datos personales.</p>
    <p>Acepta los términos y condiciones de la cooperativa.</p>
    <p>Verifica tu correo electrónico para activar tu cuenta.</p>
    <p>Inicia sesión y accede a todos los beneficios exclusivos.</p>
    <p>Si tienes alguna duda, consulta nuestras <a href="#">preguntas frecuentes</a> o contáctanos.</p>
</div>

<div class="footer-container" id="footer">
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
            <a href="https://x.com/" target="_blank"><img src="public/imagenes/twitter.png" alt="Twitter"></a>
            <a href="https://facebook.com/" target="_blank"><img src="public/imagenes/facebook.png" alt="Facebook"></a>
            <a href="https://instagram.com/" target="_blank"><img src="public/imagenes/instagram.png" alt="Instagram"></a>
            <a href="https://linkedin.com/" target="_blank"><img src="public/imagenes/linkedin.png" alt="LinkedIn"></a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 FENEC. Todos los derechos reservados.</p>
    </div>
</div>

    <script src="public/js/script.js"></script>
</body>
</html>