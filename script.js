let currentSlide = 1;
const sliderElement = document.getElementById("slider");
const arrowLeft = document.querySelector('.slider-arrow-left');
const arrowRight = document.querySelector('.slider-arrow-right');

// Array corregido con rutas relativas
const slideImages = [
    "imagenes/trabajoimg.jpg",
    "imagenes/trabajoimg2.jpg",
    "imagenes/trabajoimg3.png"
];

function actualizarSlider() {
    if (currentSlide >= 1 && currentSlide <= slideImages.length) {
        sliderElement.style.backgroundImage = `url('${slideImages[currentSlide - 1]}')`;
        sliderElement.style.backgroundSize = 'cover';
        sliderElement.style.backgroundPosition = 'center';
    }
}

// Inicializar el slider después de definir las imágenes
actualizarSlider();

arrowLeft.addEventListener('click', function() {
    currentSlide--;
    if (currentSlide < 1) {
        currentSlide = slideImages.length;
    }
    actualizarSlider();
});

arrowRight.addEventListener('click', function() {
    currentSlide++;
    if (currentSlide > slideImages.length) {
        currentSlide = 1;
    }
    actualizarSlider();
});