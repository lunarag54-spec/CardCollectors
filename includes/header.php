<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/conexion.php';

// Lógica Real del contador del carrito (Rama Main)
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

        * { box-sizing: border-box; }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Rajdhani', sans-serif;
            background-color: #000;
            color: var(--text-color);
        }

        /* ===== HEADER CON BORDE ANIMADO CYBERPUNK ===== */
        .main-header {
            background-color: var(--primary-color);
            color: var(--text-color);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            overflow: hidden; /* Importante para el efecto de luz */
        }

        /* El efecto de luz rotatoria */
        .main-header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(transparent, var(--accent-blue), transparent, var(--accent-red), transparent);
            animation: rotateBorder 12s linear infinite;
            z-index: -1;
        }

        .main-header::after {
            content: "";
            position: absolute;
            inset: 2px; /* Grosor del borde */
            background-color: var(--primary-color);
            z-index: -1;
        }

        @keyframes rotateBorder {
            100% { transform: rotate(360deg); }
        }

        /* Navegación */
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
            transition: 0.3s;
        }
        .btn-logout:hover { text-shadow: 0 0 8px var(--accent-red); }

        .btn-subir-reliquia {
            border: 1px solid var(--accent-blue);
            color: var(--accent-blue);
            padding: 8px 16px;
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.75rem;
            border-radius: 5px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
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
            transition: 0.3s;
        }
        .nav-icon:hover { color: var(--accent-blue); }

        /* Badge del Carrito Estilo Neón */
        .cart-count {
            background: var(--accent-red);
            color: #fff;
            font-size: 0.65rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            position: absolute;
            top: -10px;
            right: -12px;
            box-shadow: 0 0 10px var(--accent-red);
            font-family: 'Orbitron', sans-serif;
        }

        main { flex: 1; }
    </style>
</head>
<body>

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
            <a href="admin_productos.php" class="btn-subir-reliquia">
                <i class="fas fa-plus-circle"></i> GESTIONAR INVENTARIO
            </a>

            <a href="perfil.php" class="nav-icon" title="Mi Perfil">
                <i class="fas fa-user-shield"></i>
            </a>

            <a href="carrito.php" class="nav-icon" title="Ver Carrito">
                <i class="fas fa-shopping-cart"></i>
                <?php if ($items_en_carrito > 0): ?>
                    <span class="cart-count"><?php echo $items_en_carrito; ?></span>
                <?php endif; ?>
            </a>
        </nav>
    </header>

    <main>