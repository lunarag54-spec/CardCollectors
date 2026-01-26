document.addEventListener('DOMContentLoaded', () => {
    // Usamos el selector que coincide con tu estructura PHP
    const cards = document.querySelectorAll('.card-item');

    cards.forEach(item => {
        const card = item.querySelector('.card-3d');
        if (!card) return;

        item.addEventListener('mousemove', (e) => {
            const rect = item.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Aumentamos de 15 a 25 para más movimiento
            const rotateX = ((y - centerY) / centerY) * -25; 
            const rotateY = ((x - centerX) / centerX) * 25;

            // Variables para el brillo (Holo)
            const px = (x / rect.width) * 100;
            const py = (y / rect.height) * 100;
            const angle = Math.atan2(py - 50, px - 50) * (180 / Math.PI) + 90;

            item.style.setProperty('--px', `${px}%`);
            item.style.setProperty('--py', `${py}%`);
            item.style.setProperty('--angle', `${angle}deg`);

            // Aplicamos rotación y un ligero zoom
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
        });

        item.addEventListener('mouseleave', () => {
            // Retorno suave al estado original
            card.style.transition = "transform 0.5s ease";
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale(1)';
            
            setTimeout(() => {
                card.style.transition = "transform 0.1s ease-out";
            }, 500);
        });
    });
});