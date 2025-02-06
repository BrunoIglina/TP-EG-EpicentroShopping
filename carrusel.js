let slideIndex = 0;
const slides = document.querySelectorAll('.slide');

function nextSlide() {
    slides[slideIndex].classList.remove('active');
    slideIndex = (slideIndex + 1) % slides.length;
    slides[slideIndex].classList.add('active');
}

function prevSlide() {
    slides[slideIndex].classList.remove('active');
    slideIndex = (slideIndex - 1 + slides.length) % slides.length;
    slides[slideIndex].classList.add('active');
}

setInterval(nextSlide, 5000);
