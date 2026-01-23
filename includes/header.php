<?php
// Contador de carrito (puedes conectarlo a tu base de datos después)
$items_en_carrito = 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Collector | Tienda de Reliquias</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #000000;
            --accent-blue: #00d4ff;
            --accent-red: #ff0000;
            --text-color: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Rajdhani', sans-serif;
            background-color: #000;
        }

        /* ===== HEADER ANIMADO ===== */
        .main-header {
            background-color: var(--primary-color);
            color: var(--text-color);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Borde animado del Header */
        .header-border {
            position: relative;
            overflow: hidden;
        }

        .header-border::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(transparent, var(--accent-blue), transparent, var(--accent-red), transparent);
            animation: rotateBorder 10s linear infinite;
            z-index: -1;
        }

        .header-border::after {
            content: "";
            position: absolute;
            inset: 2px;
            background-color: var(--primary-color);
            z-index: -1;
        }

        @keyframes rotateBorder {
            100% {
                transform: rotate(360deg);
            }
        }

        /* Secciones del Nav */
        .nav-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-home {
            color: var(--text-color);
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-weight: bold;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }

        .btn-home:hover {
            color: var(--accent-blue);
        }

        .user-info {
            font-size: 0.85rem;
            border-left: 1px solid #333;
            padding-left: 20px;
            color: #ccc;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        /* BOTÓN SUBIR PRODUCTO */
        .btn-subir-reliquia {
            background: transparent;
            border: 1px solid var(--accent-blue);
            color: var(--accent-blue);
            padding: 8px 16px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.75rem;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 1px;
        }

        .btn-subir-reliquia:hover {
            background: var(--accent-blue);
            color: #000;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.6);
            transform: translateY(-2px);
        }

        /* Iconos */
        .nav-icon {
            color: var(--text-color);
            font-size: 1.2rem;
            position: relative;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-icon:hover {
            color: var(--accent-blue);
            text-shadow: 0 0 10px var(--accent-blue);
        }

        .cart-count {
            background-color: var(--accent-red);
            color: #fff;
            font-size: 0.65rem;
            padding: 2px 5px;
            border-radius: 50%;
            position: absolute;
            top: -8px;
            right: -10px;
            box-shadow: 0 0 5px var(--accent-red);
        }

        .btn-logout {
            color: var(--accent-red);
            text-decoration: none;
            font-weight: bold;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.7rem;
            margin-left: 10px;
            transition: text-shadow 0.3s;
        }

        .btn-logout:hover {
            text-shadow: 0 0 8px var(--accent-red);
        }

        main {
            flex: 1;
        }

        /* Modifica esta parte en el <style> de tu header.php */
        .header-border::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(transparent,
                    var(--accent-blue),
                    transparent,
                    var(--accent-red),
                    transparent);
            /* Animación ralentizada a 20 segundos */
            animation: rotateBorder 20s linear infinite;
            z-index: -1;
        }
    </style>
</head>

<body>

    <header class="main-header header-border">
        <div class="nav-left">
            <a href="index.php" class="btn-home">
                <i class="fas fa-bullseye"></i> INICIO
            </a>

            <?php if (isset($_SESSION['nombre'])): ?>
                <div class="user-info">
                    <span>Mazo de: <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong></span>
                    <a href="logout.php" class="btn-logout">ABANDONAR SESIÓN</a>
                </div>
            <?php endif; ?>
        </div>

        <nav class="nav-right">
            <a href="subir_producto.php" class="btn-subir-reliquia">
                <i class="fas fa-plus-circle"></i> SUBIR RELIQUIA
            </a>

            <a href="perfil.php" class="nav-icon" title="Mi Perfil">
                <i class="fas fa-user-shield"></i>
            </a>

            <a href="carrito.php" class="nav-icon" title="Ver Carrito">
                <i class="fas fa-shopping-cart"></i>
                <?php if ($items_en_carrito > 0): ?>
                    <span class="cart-count"><?= $items_en_carrito ?></span>
                <?php endif; ?>
            </a>
        </nav>
    </header>

    <main>