document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('cardContainer');
    const card = document.getElementById('card3d');

    if (!container || !card) return;

    container.addEventListener('mousemove', (e) => {
        const rect = container.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        // Aumentado a 25 para mayor dinamismo (igual que en catálogo)
        const rotateX = ((y - centerY) / centerY) * -25; 
        const rotateY = ((x - centerX) / centerX) * 25;

        // Variables de luz dinámicas
        const px = (x / rect.width) * 100;
        const py = (y / rect.height) * 100;
        const angle = Math.atan2(y - centerY, x - centerX) * (180 / Math.PI);
        
        card.style.setProperty('--px', `${px}%`);
        card.style.setProperty('--py', `${py}%`);
        card.style.setProperty('--angle', `${angle + 90}deg`);

        card.style.transform = `perspective(1200px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
    });

    container.addEventListener('mouseleave', () => {
        card.style.transition = "transform 0.8s cubic-bezier(0.23, 1, 0.32, 1)";
        card.style.transform = `perspective(1200px) rotateX(0deg) rotateY(0deg) scale(1)`;
        setTimeout(() => { card.style.transition = "transform 0.1s ease-out"; }, 800);
    });
});