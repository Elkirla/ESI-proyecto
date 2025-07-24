/**
 * COOPERATIVA FENEC - SCRIPT PRINCIPAL
 * 
 * Este script maneja:
 * - Animaciones de texto (máquina de escribir)
 * - Animaciones al hacer scroll
 * - Funcionalidad del slider
 * - Menú móvil
 */

// ==================== CONFIGURACIONES INICIALES ====================
const elementosAnimados = new Set(); // Para rastrear elementos ya animados
const menuContainer = document.getElementById("menucontainer");
const menuAbrir = document.getElementById('menuabrir');
const menuCerrar = document.getElementById('menucerrar');

// Textos para la animación de máquina de escribir
const h2Textos = [ 
    "Unidos por el cambio", 
    "Juntos somos más fuertes",
    "Unidos por un futuro mejor",
    "Construyendo un futuro juntos"
];

// Configuración de animaciones para elementos específicos al hacer scroll
const animacionesConfig = {
    // ID del elemento: { eje, posición inicial, opacidad inicial, duración }
    "comunidadtxt": { eje: 'x', inicio: -100, opacidad: 0, duracion: 0.6 },
    "infoslide":    { eje: 'x',  inicio: 100,  opacidad: 0, duracion: 0.6 },
    "logradotxt":   { eje: 'y', inicio: 50,   opacidad: 0, duracion: 0.5 },
    "metastxt":     { eje: 'y', inicio: 50,   opacidad: 0, duracion: 0.5 },
    "normash2":     { eje: 'x', inicio: -80,  opacidad: 0, duracion: 0.4 },
    "cuentah2":     { eje: 'x',  inicio: 80,   opacidad: 0, duracion: 0.4 },
    "registroinstrucciones": { eje: 'x', inicio: -80, opacidad: 0, duracion: 0.4 },

};

// ==================== FUNCIONES DE UTILIDAD ====================

 /** 
 * Espera un número determinado de segundos
 * @param {number} segundos - Tiempo a esperar en segundos
 * @returns {Promise} Promesa que se resuelve después del tiempo especificado
*/
function esperar(segundos) {
    return new Promise(resolve => setTimeout(resolve, segundos * 1000));
}

/**
 * Mueve un elemento con efecto de animación
 * @param {HTMLElement} elemento - Elemento DOM a animar
 * @param {number} posI - Posición inicial
 * @param {number} posF - Posición final
 * @param {number} tiempo - Duración de la animación en segundos
 * @param {string} eje - Eje de animación ('x' o 'y')
 * @param {number} [opacidadInicial=0] - Opacidad inicial
 * @param {number} [opacidadFinal=1] - Opacidad final
 * @returns {Promise} Promesa que se resuelve cuando la animación termina
 */
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

// ==================== ANIMACIONES DE TEXTO ====================

/**
 * Efecto de máquina de escribir para el texto del h2
 */
async function animacionh2() {
    const elementoH2 = document.getElementById('h2');
    
    while (true) {  
        for (const texto of h2Textos) {
            // Efecto de escritura
            await escribirTexto(elementoH2, texto);
            await esperar(12);
            
            // Efecto de borrado
            await borrarTexto(elementoH2);
            await esperar(0.5);
        }
    }
}

/**
 * Escribe texto con efecto de máquina de escribir
 * @param {HTMLElement} elemento - Elemento donde escribir
 * @param {string} texto - Texto a escribir
 */
async function escribirTexto(elemento, texto) {
    let caracteresEscritos = 0;
    while (caracteresEscritos < texto.length) {
        elemento.textContent = texto.substring(0, caracteresEscritos + 1);
        caracteresEscritos++;
        await esperar(0.03);
    }
}

/**
 * Borra texto con efecto
 * @param {HTMLElement} elemento - Elemento a borrar
 */
async function borrarTexto(elemento) {
    let textoActual = elemento.textContent;
    while (textoActual.length > 0) {
        textoActual = textoActual.substring(0, textoActual.length - 1);
        elemento.textContent = textoActual;
        await esperar(0.05);
    }
}

// ==================== ANIMACIONES AL SCROLL ====================

/**
 * Verifica si un elemento está visible en el viewport
 * @param {HTMLElement} elemento - Elemento a verificar
 * @param {number} [offset=0] - Margen adicional
 * @returns {boolean} True si el elemento es visible
 */
function estaEnVista(elemento, offset = 0) {
    const rect = elemento.getBoundingClientRect();
    return (
        rect.top <= (window.innerHeight || document.documentElement.clientHeight) - offset &&
        rect.bottom >= 0 + offset
    );
}

/**
 * Inicializa el Intersection Observer para animaciones al scroll
 */
function initScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                if (animacionesConfig[id] && !elementosAnimados.has(id)) {
                    const config = animacionesConfig[id];
                    moverElemento(
                        entry.target,
                        config.inicio,
                        0,
                        config.duracion,
                        config.eje,
                        config.opacidad,
                        1
                    );
                    elementosAnimados.add(id);
                }
            }
        });
    }, { threshold: 0.1 }); // 10% del elemento visible
    
    // Observar todos los elementos configurados
    Object.keys(animacionesConfig).forEach(id => {
        const el = document.getElementById(id);
        if (el) observer.observe(el);
    });
}

// ==================== SLIDER ====================

/**
 * Inicializa el slider con autoplay y controles
 */
function iniciarSlider() {
    const slides = document.querySelectorAll('.slider-item');
    const dotsContainer = document.querySelector('.slider-dots');
    let currentIndex = 0;

    // Mostrar el primer slide
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

    /**
     * Cambia al slide especificado
     * @param {number} index - Índice del slide a mostrar
     */
    function cambiarSlide(index) {
        slides[currentIndex].classList.remove('active');
        dotsContainer.children[currentIndex].classList.remove('active');
        
        currentIndex = index;
        
        slides[currentIndex].classList.add('active');
        dotsContainer.children[currentIndex].classList.add('active');
    }
 
    // Autoplay cada 5 segundos
    setInterval(() => {
        cambiarSlide((currentIndex + 1) % slides.length);
    }, 5000);
}

// ==================== MENÚ MÓVIL ====================

/**
 * Abre el menú móvil con animación
 */
menuAbrir.addEventListener('click', function() {
    menuContainer.style.display = 'flex';
    moverElemento(menuContainer, 120, 0, 0.0005, 'x', 0, 1); 
});

/**
 * Cierra el menú móvil con animación
 */
menuCerrar.addEventListener('click', function() {
    moverElemento(menuContainer, 0, 100, 0.0005, 'x', 1, 0).then(() => { 
        menuContainer.style.display = 'none';
    });
});

// ==================== INICIALIZACIÓN ====================

/**
 * Inicializa todas las animaciones y funcionalidades
 */
document.addEventListener('DOMContentLoaded', function() {
    animacionh2();          // Animación de texto del h2
    iniciarSlider();        // Configura el slider
    initScrollAnimations(); // Inicia animaciones al scroll
    
    // Animación inicial de elementos del header
    const h1 = document.getElementById('h1');
    moverElemento(h1, -40, 0, 0.01, 'x', 0, 1);

    // Animación de botones del header
    const botones = document.querySelectorAll('.header button');
    botones.forEach(boton => 
        moverElemento(boton, -50, 0, 0.6, 'y', 0, 1)
    );
});