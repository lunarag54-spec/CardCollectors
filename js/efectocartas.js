document.addEventListener('DOMContentLoaded', () => {
    // Seleccionamos solo los envoltorios de cartas 3D
    const cards = document.querySelectorAll('.card-3d-wrapper');

    cards.forEach(wrapper => {
        const card = wrapper.querySelector('.card-3d');
        if (!card) return;

        wrapper.addEventListener('mousemove', (e) => {
            const rect = wrapper.getBoundingClientRect();
            // Posición del ratón dentro de la carta
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            // Centro de la carta
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            // Cálculo de rotación (Invertido para que siga al ratón)
            // Dividimos por 12 para suavizar el ángulo (ajusta este valor a tu gusto)
            const rotateX = ((y - centerY) / centerY) * -12; 
            const rotateY = ((x - centerX) / centerX) * 12;

            // Variables para el CSS (posicionamiento de brillos)
            const percentX = (x / rect.width) * 100;
            const percentY = (y / rect.height) * 100;

            // Aplicamos la transformación 3D
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
            
            // Movemos el brillo (glare)
            card.style.setProperty('--x', `${percentX}%`);
            card.style.setProperty('--y', `${percentY}%`);
            
            // Movemos el arcoíris (shine) en dirección opuesta para efecto foil realista
            card.style.setProperty('--bg-x', `${100 - percentX}%`);
            card.style.setProperty('--bg-y', `${100 - percentY}%`);
        });

        // Al salir el ratón, la carta vuelve a su posición original
        wrapper.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
            // Opcional: reiniciar brillo
            card.style.setProperty('--x', `50%`);
            card.style.setProperty('--y', `50%`);
        });
    });
});