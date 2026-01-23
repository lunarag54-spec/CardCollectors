<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/conexion.php';

// Lógica del contador del carrito
$items_en_carrito = 0;
if (isset($_SESSION['id_usuario'])) {
    $uid = $_SESSION['id_usuario'];
    $sql_count = "SELECT SUM(cp.cantidad) as total 
                  FROM Carrito_Producto cp 
                  INNER JOIN Carrito c ON cp.id_carrito = c.id_carrito 
                  WHERE c.id_usuario = $uid";
    $res_count = $conn->query($sql_count);
    if ($res_count) {
        $row_count = $res_count->fetch_assoc();
        $items_en_carrito = $row_count['total'] ?? 0;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Collector | Tienda de Reliquias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #000000;
            --accent-blue: #00d4ff;
            --accent-red: #ff0000;
            --text-color: #ffffff;
        }

        body {
            margin: 0;
            font-family: 'Rajdhani', sans-serif;
            background-color: #000;
            color: var(--text-color);
        }

        /* ===== HEADER CON BORDE ANIMADO FLUIDO ===== */
        .main-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--primary-color);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* El borde real se simula con el pseudo-elemento */
        }

        /* Contenedor del borde animado */
        .header-border-container {
            position: sticky;
            top: 0;
            z-index: 100;
            padding-bottom: 3px; /* Grosor del borde inferior */
            background: linear-gradient(90deg, 
                transparent, 
                var(--accent-blue), 
                var(--accent-red), 
                var(--accent-blue), 
                transparent);
            background-size: 200% 100%;
            animation: moveGradient 6s linear infinite;
        }

        @keyframes moveGradient {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        /* Estilos de navegación */
        .nav-left, .nav-right { display: flex; align-items: center; gap: 20px; }

        .btn-home {
            color: var(--text-color);
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }
        .btn-home:hover { color: var(--accent-blue); text-shadow: 0 0 10px var(--accent-blue); }

        .user-info {
            font-size: 0.85rem;
            border-left: 1px solid #333;
            padding-left: 20px;
            color: #ccc;
        }

        .btn-logout {
            color: var(--accent-red);
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.7rem;
            margin-left: 10px;
            font-weight: bold;
        }

        .btn-subir-reliquia {
            border: 1px solid var(--accent-blue);
            color: var(--accent-blue);
            padding: 8px 16px;
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.75rem;
            border-radius: 5px;
            transition: 0.3s;
            text-transform: uppercase;
        }
        .btn-subir-reliquia:hover {
            background: var(--accent-blue);
            color: #000;
            box-shadow: 0 0 15px var(--accent-blue);
        }

        .nav-icon {
            color: #fff;
            font-size: 1.2rem;
            position: relative;
            text-decoration: none;
        }

        /* BADGE DEL CARRITO ESTILO NEÓN */
        .cart-count {
            background: var(--accent-red);
            color: #fff;
            font-size: 0.7rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            position: absolute;
            top: -10px;
            right: -12px;
            font-family: 'Orbitron', sans-serif;
            box-shadow: 0 0 10px var(--accent-red);
            border: 1px solid #fff;
        }
    </style>
</head>
<body>

<div class="header-border-container">
    <header class="main-header">
        <div class="nav-left">
            <a href="index.php" class="btn-home">
                <i class="fas fa-bullseye"></i> INICIO
            </a>

            <?php if (isset($_SESSION['nombre'])): ?>
                <div class="user-info">
                    Mazo de: <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>
                    <a href="logout.php" class="btn-logout">ABANDONAR SESIÓN</a>
                </div>
            <?php endif; ?>
        </div>

        <nav class="nav-right">
            <a href="subir_producto.php" class="btn-subir-reliquia">
                <i class="fas fa-plus-circle"></i> SUBIR RELIQUIA
            </a>

            <a href="perfil.php" class="nav-icon"><i class="fas fa-user-shield"></i></a>

            <a href="carrito.php" class="nav-icon">
                <i class="fas fa-shopping-cart"></i>
                <?php if ($items_en_carrito > 0): ?>
                    <span class="cart-count"><?php echo $items_en_carrito; ?></span>
                <?php endif; ?>
            </a>
        </nav>
    </header>
</div>

<main>