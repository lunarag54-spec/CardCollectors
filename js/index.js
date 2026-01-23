document.addEventListener('mousemove', (e) => {
    // Luces de fondo
    const red = document.getElementById('glow-red');
    const blue = document.getElementById('glow-blue');
    if(red && blue) {
        red.style.left = (e.clientX - 250) + 'px';
        red.style.top = (e.clientY - 250) + 'px';
        blue.style.right = (window.innerWidth - e.clientX - 250) + 'px';
        blue.style.bottom = (window.innerHeight - e.clientY - 250) + 'px';
    }

    // Coordenadas para la linterna
    const flashlight = document.querySelector('.flashlight-overlay');
    if (flashlight) {
        flashlight.style.setProperty('--x', e.clientX + 'px');
        flashlight.style.setProperty('--y', e.clientY + 'px');
    }
});

const btnScanner = document.getElementById('toggle-explorer');
if (btnScanner) {
    // Crear el div de la linterna si no existe
    if (!document.querySelector('.flashlight-overlay')) {
        const overlay = document.createElement('div');
        overlay.className = 'flashlight-overlay';
        document.body.appendChild(overlay);
    }

    btnScanner.addEventListener('click', () => {
        const isActive = document.body.classList.toggle('explorer-active');
        btnScanner.querySelector('.scanner-status').innerText = isActive ? "ON" : "OFF";
        btnScanner.classList.toggle('active');
    });
}