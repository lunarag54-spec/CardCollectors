const card = document.getElementById('card');
const glowRed = document.getElementById('glow-red');
const glowBlue = document.getElementById('glow-blue');

document.addEventListener('mousemove', (e) => {
    const x = e.clientX;
    const y = e.clientY;
    const centerX = window.innerWidth / 2;
    const centerY = window.innerHeight / 2;

    // 1. Efecto de inclinación 3D de la tarjeta
    let xAxis = (centerX - x) / 25;
    let yAxis = (centerY - y) / 25;
    
    if (card) {
        card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
    }

    // 2. Movimiento de los destellos de fondo (centrados en el mouse)
    if (glowRed) {
        glowRed.style.transform = `translate(${x - 300}px, ${y - 300}px)`;
    }
    
    if (glowBlue) {
        // Ligero desfase para el azul para crear profundidad
        glowBlue.style.transform = `translate(${x - 200}px, ${y - 200}px)`;
    }
});

// Suavizado al entrar y salir del área
document.addEventListener('mouseleave', () => {
    if (card) {
        card.style.transition = "all 0.5s ease";
        card.style.transform = `rotateY(0deg) rotateX(0deg)`;
    }
});

document.addEventListener('mouseenter', () => {
    if (card) {
        card.style.transition = "none";
    }
});