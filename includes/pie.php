</main> <style>
    /* CONTENEDOR DEL BORDE ANIMADO SUPERIOR DEL FOOTER */
    .footer-border-container {
        margin-top: auto; 
        padding-top: 3px; /* Grosor de la línea de luz */
        background: linear-gradient(90deg, 
            transparent, 
            var(--accent-red), 
            var(--accent-blue), 
            var(--accent-red), 
            transparent);
        background-size: 200% 100%;
        animation: moveGradientFooter 6s linear infinite;
        position: relative;
        z-index: 20;
    }

    /* CUERPO DEL FOOTER */
    .main-footer {
        background-color: #000;
        color: #fff;
        padding: 40px 5% 20px 5%;
        text-align: center;
        font-family: 'Rajdhani', sans-serif;
    }

    /* Animación fluida inversa a la del header para crear simetría */
    @keyframes moveGradientFooter {
        0% { background-position: 200% 50%; }
        100% { background-position: 0% 50%; }
    }

    .footer-content h2 {
        font-family: 'Orbitron', sans-serif;
        letter-spacing: 3px;
        font-size: 1.4rem;
        margin-bottom: 15px;
        color: var(--accent-blue);
        text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
    }

    /* SECCIÓN DE CONTACTO Y DIRECCIÓN */
    .footer-info {
        margin: 20px 0;
        font-size: 1rem;
        color: #ccc;
        line-height: 1.8;
    }
    
    .footer-info p {
        margin: 5px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .footer-info i {
        color: var(--accent-blue);
        font-size: 1.1rem;
        width: 25px;
    }

    /* ICONOS DE REDES SOCIALES */
    .social-icons {
        margin: 25px 0;
        display: flex;
        justify-content: center;
        gap: 25px;
    }

    .social-link {
        color: #fff;
        font-size: 1.6rem;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        color: var(--accent-red);
        transform: translateY(-3px);
        filter: drop-shadow(0 0 10px var(--accent-red));
    }

    /* LÍNEA DE COPYRIGHT */
    .copyright {
        font-size: 0.8rem;
        color: #444;
        margin-top: 30px;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-top: 1px solid #111;
        padding-top: 20px;
    }
</style>

<footer class="footer-border-container">
    <div class="main-footer">
        <div class="footer-content">
            <h2>CARD COLLECTOR</h2>
            
            <div class="footer-info">
                <p>
                    <i class="fas fa-map-marker-alt"></i> 
                    Calle de las Reliquias, 42 - Sector Neón, CP 28001
                </p>
                <p>
                    <i class="fas fa-phone-alt"></i> 
                    +34 600 000 000
                </p>
                <p>
                    <i class="fas fa-envelope"></i> 
                    soporte@cardcollector.com
                </p>
            </div>

            <div class="social-icons">
                <a href="#" class="social-link" title="Discord"><i class="fab fa-discord"></i></a>
                <a href="#" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link" title="Twitter / X"><i class="fab fa-twitter"></i></a>
            </div>

            <p class="copyright">
                &copy; <?php echo date("Y"); ?> Card Collector System - Acceso de Usuario Autorizado
            </p>
        </div>
    </div>
</footer>

<script src="js/script.js"></script>

</body>
</html>