</main> <footer class="main-footer footer-border">
        <div class="footer-content">
            <div class="footer-section">
                <p>C. Beatriz Galindo, 6<br>28914 Leganés, Madrid</p>
            </div>

            <div class="footer-section">
                <div class="social-icons">
                    <a href="#"><i class="fab fa-instagram"></i> @Card_Collectors</a>
                    <a href="#"><i class="fab fa-twitter"></i> @Card_Collectors</a>
                    <a href="#"><i class="fab fa-facebook-f"></i> @Card_Collectors</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            © <?= date("Y") ?> CardCollectors | Mazo de Coleccionista
        </div>
    </footer>

    <style>
        .main-footer {
            flex-shrink: 0;
            background-color: #000;
            color: #ffffff;
            padding: 30px 5% 15px;
            position: relative;
            z-index: 100;
            margin-top: auto; /* Esto es clave para que siempre esté al final */
        }

        .footer-border {
            position: relative;
            overflow: hidden;
            border-top: 2px solid #333;
        }

        .footer-border::before {
            content: "";
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: conic-gradient(transparent, #00d4ff, transparent, #ff0000, transparent);
            animation: rotateBorder 15s linear infinite;
            z-index: -1;
            opacity: 0.5;
        }

        .footer-border::after {
            content: "";
            position: absolute;
            inset: 2px;
            background-color: #000;
            z-index: -1;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .social-icons {
            display: flex;
            gap: 20px;
        }

        .social-icons a {
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .social-icons a:hover {
            color: #00d4ff;
        }

        .footer-bottom {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 15px;
            color: #666;
        }
    </style>
</body>
</html>