<?php
require_once 'includes/conexion.php';
include 'includes/header.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// He añadido p.imagen_especial a la consulta para traer la imagen 3D
$sql = "SELECT p.*, c.nombre_categoria FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.id_producto = $id";
$res = $conn->query($sql);
$producto = $res->fetch_assoc();

if (!$producto) { header("Location: catalogo.php"); exit(); }

$img = !empty($producto['imagen']) ? $producto['imagen'] : 'default.jpg';
// Detectamos si existe la imagen especial (ej: charizardex3d.png)
$img_especial = !empty($producto['imagen_especial']) ? $producto['imagen_especial'] : null;
?>

<link rel="stylesheet" href="css/detalle.css">

<main class="reliquia-explorer">
    <div class="scan-line"></div>

    <div class="layout-premium">
        <div class="visual-vault">
            <div class="card-stasis" id="cardContainer">
                <div class="card-3d" id="card3d">
                    <div class="energy-glare"></div>
                    
                    <div class="card-media" style="background-image: url('img/productos/<?php echo $img; ?>');"></div>
                    
                    <?php if ($img_especial): ?>
                        <div class="mew-reveal" style="background-image: url('img/productos/<?php echo $img_especial; ?>');"></div>
                    <?php endif; ?>

                    <div class="corner tl"></div><div class="corner tr"></div>
                    <div class="corner bl"></div><div class="corner br"></div>
                </div>
            </div>
        </div>

        <div class="data-panel">
            <header class="panel-header">
                <div class="id-tag">REF: ARCH-<?php echo str_pad($id, 4, "0", STR_PAD_LEFT); ?></div>
                <h1 class="main-name"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
                <div class="category-badge"><?php echo htmlspecialchars($producto['nombre_categoria']); ?></div>
            </header>

            <div class="price-section">
                <span class="price-val"><?php echo number_format($producto['precio'], 2); ?> <small>€</small></span>
                <div class="stock-status">
                    <div class="pulse-dot"></div>
                    <span>Sincronizado: <?php echo $producto['stock']; ?> disponibles</span>
                </div>
            </div>
            
            <div class="intel-box">
                <h2 class="intel-title">NOTAS DE CAMPO</h2>
                <p class="intel-text"><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
            </div>

            <div class="action-footer">
                <a href="agregar_al_carrito.php?id=<?php echo $id; ?>" class="pill-button-neon">
                    ADQUIRIR RELIQUIA
                </a>
                <a href="catalogo.php" class="back-link">Volver Atrás</a>
            </div>
        </div>
    </div>
</main>

<script src="js/detalle.js"></script>
<?php include 'includes/pie.php'; ?>