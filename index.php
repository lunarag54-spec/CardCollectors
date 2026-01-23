<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/conexion.php';
$id_usuario = $_SESSION['id_usuario'];
$nombre_user = $_SESSION['nombre'] ?? 'Usuario';
$rol_user = $_SESSION['rol'] ?? 'usuario';

// CONSULTA CORREGIDA PARA TU SQL: Sumar cantidades de Carrito_Producto
$items_en_carrito = 0;
$query_count = "SELECT SUM(cp.cantidad) as total 
                FROM Carrito_Producto cp 
                INNER JOIN Carrito c ON cp.id_carrito = c.id_carrito 
                WHERE c.id_usuario = $id_usuario";
$res_count = $conn->query($query_count);
if ($res_count) {
    $row_count = $res_count->fetch_assoc();
    $items_en_carrito = $row_count['total'] ?? 0;
}

// Consulta de productos
$sql = "SELECT p.*, c.nombre_categoria 
        FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.estado = 'activo' AND p.stock > 0";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Card Collector | Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body class="dashboard-body">
    <div class="cursor-glow-red" id="glow-red"></div>
    <div class="cursor-glow-blue" id="glow-blue"></div>

    <header class="main-header animated-border">
        <div class="nav-left">
            <a href="index.php" class="btn-home"><i class="fas fa-store"></i> Inicio</a>
        </div>
        <div class="nav-right">
            <a href="carrito.php" class="nav-icon">
                <i class="fas fa-shopping-cart"></i>
                <?php if ($items_en_carrito > 0): ?>
                    <span class="cart-count"><?= $items_en_carrito ?></span>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <div class="app-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item active"><span>🎴</span><small>Cartas</small></a>
                <?php if($rol_user == 'admin'): ?>
                    <a href="admin_productos.php" class="nav-item special"><span>➕</span><small>Admin</small></a>
                <?php endif; ?>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-bar-scanner">
                <button id="toggle-explorer" class="btn-scanner">
                    <div class="scanner-dot"></div>
                    <span class="scanner-text">MODO ESCÁNER</span>
                    <span class="scanner-status">OFF</span>
                </button>
                <div class="user-info-display">
                    <span class="rank-badge"><?= strtoupper($rol_user) ?></span>
                    <span class="username"><?= $nombre_user ?></span>
                </div>
            </header>

            <div class="scroll-area">
                <div class="grid-productos">
                    <?php if ($resultado && $resultado->num_rows > 0): 
                        while ($row = $resultado->fetch_assoc()): ?>
                            <div class="producto-card">
                                <div class="card-inner">
                                    <div class="card-top">
                                        <span class="id-tag">#<?= $row['id_producto'] ?></span>
                                    </div>
                                    <div class="card-image-area"><div class="placeholder-icon">🎴</div></div>
                                    <div class="card-body">
                                        <h4><?= htmlspecialchars($row['nombre']) ?></h4>
                                        <p class="cat-name"><?= $row['nombre_categoria'] ?></p>
                                        <div class="card-meta">
                                            <span class="precio"><?= number_format($row['precio'], 2) ?>€</span>
                                            <span class="stock">STK: <?= $row['stock'] ?></span>
                                        </div>
                                        <a href="agregar_al_carrito.php?id=<?= $row['id_producto'] ?>" class="btn-action">AÑADIR AL MAZO</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; endif; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="js/index.js"></script>
</body>
</html>