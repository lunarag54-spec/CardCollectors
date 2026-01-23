<?php
// Ejemplo de contador del carrito
$items_en_carrito = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #000000;
            --accent-color: #3498db;
            --text-color: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Layout general */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            padding: 40px 5%;
            background-color: #f5f5f5;
        }

        /* ===== HEADER ===== */
        .main-header {
            background-color: var(--primary-color);
            color: var(--text-color);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 5px rgba(0,0,0,0.1);
        }

        .nav-left .btn-home {
            color: var(--text-color);
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-right {
            display: flex;
            gap: 20px;
        }

        .nav-icon {
            color: var(--text-color);
            font-size: 1.2rem;
            position: relative;
            text-decoration: none;
        }

        .cart-count {
            background-color: #e74c3c;
            color: #fff;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        /* ===== FOOTER ===== */
        .main-footer {
            background-color: var(--primary-color);
            color: #ffffff;
            padding: 10px 5% 15px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .footer-section h4 {
            margin-bottom: 10px;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icons a {
            color: #ffffff;
            font-size: 1rem;
            text-decoration: none;
            display: flex;           
            align-items: center;
            gap: 5px;
        }

        .social-icons a:hover {
            color: var(--accent-color);
        }

        .footer-bottom {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8rem;
            border-top: 1px solid rgba(255,255,255,0.3);
            padding-top: 10px;
        }

        @media (max-width: 600px) {
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }
        /* ===== BORDE ANIMADO (HEADER & FOOTER) ===== */
        .animated-border {
            position: relative;
            overflow: hidden;
            z-index: 0;
        }

        /* Capa del borde animado */
        .animated-border::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                transparent,
                #00d4ff,
                transparent,
                #ff0000,
                transparent
            );
            animation: rotateBorder 20s linear infinite;
            z-index: -1;
        }

        /* Capa interior para tapar el centro */
        .animated-border::after {
            content: "";
            position: absolute;
            inset: 4px; /* grosor del borde */
            background-color: var(--primary-color);
            z-index: -1;
        }

        /* Animación */
        @keyframes rotateBorder {
            100% {
                transform: rotate(360deg);
            }
        }


    </style>
</head>

<body>

<!-- ===== HEADER ===== -->
 <header class="main-header animated-border">
    <div class="nav-left">
        <a href="index.php" class="btn-home">
            <i class="fas fa-store"></i> Inicio
        </a>
    </div>

    <nav class="nav-right">
        <a href="perfil.php" class="nav-icon">
            <i class="fas fa-user"></i>
        </a>

        <a href="../carrito.php" class="nav-icon">
            <i class="fas fa-shopping-cart"></i>
            <?php if ($items_en_carrito > 0): ?>
                <span class="cart-count"><?= $items_en_carrito ?></span>
            <?php endif; ?>
        </a>
    </nav>
</header>

<!-- ===== CONTENIDO ===== -->
<main>
    <h1>Bienvenido a la tienda</h1>
    <p>Contenido de la página aquí.</p>
</main>
<!-- ===== FOOTER ===== -->
<!-- ===== FOOTER ===== -->
 <footer class="main-footer animated-border">
     <div class="footer-content">

        <div class="footer-section">
            <p>C. Beatriz Galindo, 6<br>28914 Leganés, Madrid</p>
        </div>

        <div class="footer-section">
            <div class="social-icons">
                <a href="#">@Card_Collectors<i class="fab fa-instagram"></i></a>
                <br>
                <a href="#">@Card_Collectors<i class="fab fa-twitter"></i></a>
                <br>
                <a href="#">@Card_Collectors<i class="fab fa-facebook-f"></i></a>
            </div>
        </div>
                    <div></div>
    </div>

    <div class="footer-bottom">
        © <?= date("Y") ?> CardCollectors
    </div>
</footer>

</body>
</html>
