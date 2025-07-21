document.addEventListener('DOMContentLoaded', function() {
    animacionh2();
    animarElementos();
    iniciarSlider();
});
const menucontainer = document.getElementById("menucontainer");
const menuAbrir = document.getElementById('menuabrir');
const menuCerrar = document.getElementById('menucerrar'); 
const menucontactos = document.getElementById('menucontactos'); 
const menubotones = document.getElementById('menubotones'); 

const h2 = [ 
    "Unidos por el cambio", 
    "Juntos somos más fuertes",
    "Unidos por un futuro mejor",
    "Construyendo un futuro juntos",
    
];

async function animacionh2() {
    const elementoH2 = document.getElementById('h2');
    
    while (true) {  
        for (const texto of h2) {
            // Efecto de máquina de escribir 
            await escribirTexto(elementoH2, texto);
            
            // Espera 2 segundos con el texto completo
            await esperar(12);
            
            // Efecto de borrado 
            await borrarTexto(elementoH2);
             
            await esperar(0.5);
        }
    }
}

async function escribirTexto(elemento, texto) {
    let caracteresEscritos = 0;
    while (caracteresEscritos < texto.length) {
        elemento.textContent = texto.substring(0, caracteresEscritos + 1);
        caracteresEscritos++;
        await esperar(0.03); 
    }
}


async function borrarTexto(elemento) {
    let textoActual = elemento.textContent;
    while (textoActual.length > 0) {
        textoActual = textoActual.substring(0, textoActual.length - 1);
        elemento.textContent = textoActual;
        await esperar(0.05); 
    }
}

async function moverElemento(elemento, posI, posF, tiempo, eje, opacidadInicial = 0, opacidadFinal = 1) {
    const distancia = posF - posI;
    const pasos = 100;
    const intervalo = tiempo / pasos;

    // Inicializar propiedades
    elemento.style.opacity = opacidadInicial;
    elemento.style.transform = eje === 'x' 
        ? `translateX(${posI}px)` 
        : `translateY(${posI}px)`;

    // Animación paso a paso
    for (let i = 0; i <= pasos; i++) {
        const progreso = i / pasos;
        const posicionActual = posI + (distancia * progreso);
        const opacidadActual = opacidadInicial + (opacidadFinal - opacidadInicial) * progreso;

        elemento.style.transform = eje === 'x' 
            ? `translateX(${posicionActual}px)` 
            : `translateY(${posicionActual}px)`;
        
        elemento.style.opacity = opacidadActual;
        await esperar(intervalo);
    }
}

async function animarElementos() {
    const h1 = document.getElementById('h1');
    moverElemento(h1, -40, 0, 0.01, 'x', 0, 1);

    // Animación de los botones (todos a la vez)
    const botones = document.querySelectorAll('.header button');
    const animaciones = Array.from(botones).map(boton => 
        moverElemento(boton, -50, 0, 0.6, 'y', 0, 1)
    );
    await Promise.all(animaciones); // Espera a que todas las animaciones terminen
}
function iniciarSlider() {
    const slides = document.querySelectorAll('.slider-item');
    const dotsContainer = document.querySelector('.slider-dots');
    let currentIndex = 0;

    // Mostrar el primer slide inmediatamente
    slides[0].classList.add('active');
    
    // Crear puntos de navegación
    slides.forEach((_, i) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => cambiarSlide(i));
        dotsContainer.appendChild(dot);
    });

    // Botones de navegación
    document.querySelector('.slider-btn.next').addEventListener('click', () => {
        cambiarSlide((currentIndex + 1) % slides.length);
    });

    document.querySelector('.slider-btn.prev').addEventListener('click', () => {
        cambiarSlide((currentIndex - 1 + slides.length) % slides.length);
    });

    function cambiarSlide(index) {
        slides[currentIndex].classList.remove('active');
        dotsContainer.children[currentIndex].classList.remove('active');
        
        currentIndex = index;
        
        slides[currentIndex].classList.add('active');
        dotsContainer.children[currentIndex].classList.add('active');
    }
 
    setInterval(() => {
        cambiarSlide((currentIndex + 1) % slides.length);
    }, 5000);
}
 
document.getElementById('menuabrir').addEventListener('click', function() {
    menucontainer.style.display = 'flex';
    menucontainer.style.transform = 'translateX(0)';
    menucontainer.style.opacity = '1';
});

document.getElementById('menucerrar').addEventListener('click', function() {
    menucontainer.style.transform = 'translateX(250px)';
    menucontainer.style.opacity = '0';
    menucontainer.style.display = 'none';
});


function esperar(segundos) {
  return new Promise(resolve => setTimeout(resolve, segundos * 1000));
}

