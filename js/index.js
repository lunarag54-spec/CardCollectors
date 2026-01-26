document.addEventListener('mousemove', (e) => {
    // Mover luces de fondo suavemente
    const red = document.getElementById('glow-red');
    const blue = document.getElementById('glow-blue');
    
    if(red && blue) {
        red.style.transform = `translate(${e.clientX - 200}px, ${e.clientY - 200}px)`;
        blue.style.transform = `translate(${window.innerWidth - e.clientX - 200}px, ${window.innerHeight - e.clientY - 200}px)`;
    }

    // Efecto 3D en las cartas solo al estar encima
    const wrapper = e.target.closest('.producto-card-wrapper');
    if (wrapper) {
        const body = wrapper.querySelector('.producto-card-body');
        const rect = wrapper.getBoundingClientRect();
        
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const rX = (y - rect.height/2) / -10;
        const rY = (x - rect.width/2) / 10;
        
        body.style.transform = `rotateX(${rX}deg) rotateY(${rY}deg)`;
        
        // Brillo holográfico dinámico
        const shine = wrapper.querySelector('.holo-shine');
        if(shine) {
            shine.style.background = `radial-gradient(circle at ${(x/rect.width)*100}% ${(y/rect.height)*100}%, rgba(255,255,255,0.3) 0%, transparent 70%)`;
        }
    }
});