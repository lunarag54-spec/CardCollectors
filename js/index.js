// 1. Solo mover luces de fondo suavemente
document.addEventListener('mousemove', (e) => {
    const red = document.getElementById('glow-red');
    const blue = document.getElementById('glow-blue');
    
    if(red && blue) {
        red.style.transform = `translate(${e.clientX - 300}px, ${e.clientY - 300}px)`;
        blue.style.transform = `translate(${window.innerWidth - e.clientX - 300}px, ${window.innerHeight - e.clientY - 300}px)`;
    }
});

// 2. Animación de Scroll (Reveal)
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));