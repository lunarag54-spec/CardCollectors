<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/conexion.php';

// Lógica Real del contador del carrito (Mantenida)
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

// Lógica de categorías para el menú
$cat_activa = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
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
            --neon-red: #ff4444;
            --text-color: #ffffff;
        }

        body { display: flex; flex-direction: column; min-height: 100vh; margin: 0; font-family: 'Rajdhani', sans-serif; background-color: #000; color: var(--text-color); }

        .main-header {
            background-color: var(--primary-color);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 68, 68, 0.2);
        }

        /* --- ESTILOS SUBMENÚ DROPDOWN --- */
        .header-mid-nav { display: flex; gap: 10px; align-items: center; }
        .dropdown { position: relative; display: inline-block; }
        
        .cat-pill {
            text-decoration: none; color: #888; font-family: 'Orbitron'; font-size: 0.65rem;
            padding: 6px 14px; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 4px;
            transition: 0.3s; text-transform: uppercase; cursor: pointer;
        }

        .cat-pill:hover, .cat-pill.active { color: #fff; border-color: var(--neon-red); box-shadow: 0 0 10px var(--neon-red); }
        .cat-pill.active { background: var(--neon-red); color: #000; font-weight: bold; }

        .dropdown-content {
            display: none; position: absolute; background: #000; min-width: 140px;
            border: 1px solid var(--neon-red); top: 100%; left: 0; z-index: 1100;
            box-shadow: 0 5px 15px rgba(0,0,0,0.8);
        }

        .dropdown-content a {
            color: #ccc; padding: 10px 15px; text-decoration: none; display: block;
            font-family: 'Orbitron'; font-size: 0.6rem; transition: 0.3s; border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .dropdown-content a:hover { background: rgba(255, 68, 68, 0.2); color: #fff; }
        .dropdown:hover .dropdown-content { display: block; }

        /* --- TUS ESTILOS ORIGINALES --- */
        header::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 1px;
            background: linear-gradient(90deg, transparent, var(--neon-red), transparent);
            animation: scanHeader 4s linear infinite;
        }
        @keyframes scanHeader { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

        .nav-left, .nav-right { display: flex; align-items: center; gap: 20px; }
        .btn-home { color: var(--text-color); text-decoration: none; font-family: 'Orbitron'; font-weight: bold; display: flex; align-items: center; gap: 8px; }
        .btn-subir-reliquia { border: 1px solid var(--accent-blue); color: var(--accent-blue); padding: 8px 16px; text-decoration: none; font-family: 'Orbitron'; font-size: 0.75rem; border-radius: 5px; }
        .nav-icon { color: #fff; font-size: 1.2rem; position: relative; text-decoration: none; }
        .cart-count { background: var(--accent-red); color: #fff; font-size: 0.65rem; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; position: absolute; top: -10px; right: -12px; font-family: 'Orbitron'; }
        .user-info { font-size: 0.85rem; border-left: 1px solid #333; padding-left: 20px; color: #ccc; }
        .btn-logout { color: var(--accent-red); text-decoration: none; font-family: 'Orbitron'; font-size: 0.7rem; margin-left: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="nav-left">
            <a href="index.php" class="btn-home"><i class="fas fa-bullseye"></i> INICIO</a>
            <?php if (isset($_SESSION['nombre'])): ?>
                <div class="user-info">
                    Mazo de: <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>
                    <a href="logout.php" class="btn-logout">SALIR</a>
                </div>
            <?php endif; ?>
        </div>

        <nav class="header-mid-nav">
    <a href="catalogo.php" class="cat-pill <?php echo ($cat_activa == 0) ? 'active' : ''; ?>">TODOS</a>
    
    <?php 
    // Selecciona categorías principales (como LIBROS o CARTAS)
    $res_menu = $conn->query("SELECT * FROM Categoria WHERE id_padre IS NULL");
    while($cat = $res_menu->fetch_assoc()): 
        $id_c = $cat['id_categoria'];
        // Busca si tiene subcategorías (Mangas, Comics, etc.)
        $res_sub = $conn->query("SELECT * FROM Categoria WHERE id_padre = $id_c");
        
        if($res_sub->num_rows > 0): ?>
            <div class="dropdown">
                <a href="catalogo.php?categoria=<?php echo $id_c; ?>" class="cat-pill <?php echo ($cat_activa == $id_c) ? 'active' : ''; ?>">
                    <?php echo strtoupper(htmlspecialchars($cat['nombre_categoria'])); ?> <i class="fas fa-caret-down"></i>
                </a>
                <div class="dropdown-content">
                    <?php while($sub = $res_sub->fetch_assoc()): ?>
                        <a href="catalogo.php?categoria=<?php echo $sub['id_categoria']; ?>">
                            <?php echo strtoupper(htmlspecialchars($sub['nombre_categoria'])); ?>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php else: ?>
            <a href="catalogo.php?categoria=<?php echo $id_c; ?>" class="cat-pill <?php echo ($cat_activa == $id_c) ? 'active' : ''; ?>">
                <?php echo strtoupper(htmlspecialchars($cat['nombre_categoria'])); ?>
            </a>
        <?php endif; ?>
    <?php endwhile; ?>
</nav>

        <nav class="nav-right">
            <a href="admin_productos.php" class="btn-subir-reliquia"><i class="fas fa-plus-circle"></i> GESTIONAR</a>
            <a href="perfil.php" class="nav-icon"><i class="fas fa-user-shield"></i></a>
            <a href="carrito.php" class="nav-icon">
                <i class="fas fa-shopping-cart"></i>
                <?php if ($items_en_carrito > 0): ?><span class="cart-count"><?php echo $items_en_carrito; ?></span><?php endif; ?>
            </a>
        </nav>
    </header>
    <main>