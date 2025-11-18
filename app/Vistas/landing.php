<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa-FENEC</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;700&family=Montserrat&display=swap" rel="stylesheet"> 

    <!-- ARCHIVOS LOCALES CORREGIDOS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="imagenes/logo.png" type="image/png">
</head>
<body>

<div class="header">
    <img src="imagenes/logo2.png" alt="logo FENEC" class="logoheader">

    <button><a href="#normas">Normas</a></button>
    <button><a href="#comunidadtxt">Sobre nosotros</a></button>
    <button><a href="#footer">Contactanos</a></button>

    <button class="loginbtton"><a href="/login">Log in</a></button>

    <img src="imagenes/menu.png" alt="menu hamburguesa" class="menu" id="menuabrir">
    <p class="separador"></p>
</div>

<div class="menu-container" id="menucontainer">
    <img src="imagenes/menu.png" alt="menu hamburguesa" class="menu-icon" id="menucerrar">
    <p class="separador-menu"></p>

    <div class="menubotones">
        <button><a href="#logrado">Novedades</a></button>
        <button><a href="#footer">Contactanos</a></button>
        <button><a href="#comunidadtxt">Sobre nosotros</a></button>
        <button class="loginbtton"><a href="/login">Log in</a></button>
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

    <button class="inicio-hero"><a href="/registro">Iniciar</a></button>

    <p>Cree su usuario de manera rápida</p>
    <div class="hero"></div>
</div>

<div class="normas" id="normas"> 
    <div class="normasimg1"></div>
    <div class="normasimg2"></div>

    <div class="normastexto-container">
        <div class="textonormas">
            <h2 id="normash2">Normas que valoramos</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis, architecto ea.</p> 
            <button><a href="/normas">Más información</a></button>
        </div>

        <div class="textocrearcuenta">
            <h2 id="cuentah2">¿Cómo creo una cuenta?</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis, architecto ea.</p> 
            <button><a href="#registroinstrucciones">Más información</a></button>
        </div>
    </div>
</div>

<h2 id="comunidadtxt">Comunidad</h2>

<div class="slider">
    <button class="slider-btn prev">‹</button>
    <div class="slider-container">
        <div class="slider-item active"><img src="imagenes/slider1.png" alt="Slider Image 1"></div>
        <div class="slider-item"><img src="imagenes/slider2.png" alt="Slider Image 2"></div>
        <div class="slider-item"><img src="imagenes/slider3.png" alt="Slider Image 3"></div>
        <div class="slider-item"><img src="imagenes/slider4.png" alt="Slider Image 4"></div>
    </div>
    <button class="slider-btn next">›</button>
    <div class="slider-dots"></div>
</div>

<div class="info-slide" id="infoslide">
    <div class="info-slide-text">
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
    </div>
</div>

<div class="logrado-container" id="logrado">
    <div class="conseguidocont" id="logradotxt">
        <h2>Hemos logrado</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
    </div>

    <div class="metas" id="metastxt">
        <h2>Apuntamos a</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
    </div>
</div>

<div class="registro-instrucciones" id="registroinstrucciones">
    <h2>Instrucciones para el registro</h2>
    <p>Para unirte a nuestra cooperativa, sigue estos simples pasos:</p> 
    <button class="registro-button"><a href="/registro">Registrarse</a></button>
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
            <a href="https://x.com/" target="_blank"><img src="imagenes/twitter.png" alt="Twitter"></a>
            <a href="https://facebook.com/" target="_blank"><img src="imagenes/facebook.png" alt="Facebook"></a>
            <a href="https://instagram.com/" target="_blank"><img src="imagenes/instagram.png" alt="Instagram"></a>
            <a href="https://linkedin.com/" target="_blank"><img src="imagenes/linkedin.png" alt="LinkedIn"></a>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 FENEC. Todos los derechos reservados.</p>
    </div>
</div>

<!-- JS CORREGIDO -->
<script src="js/script.js"></script>

</body>
</html>
