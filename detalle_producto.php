<?php
require_once 'includes/conexion.php';
include 'includes/header.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT p.*, c.nombre_categoria, c.id_padre FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.id_producto = $id";
$res = $conn->query($sql);
$producto = $res->fetch_assoc();

if (!$producto) { header("Location: catalogo.php"); exit(); }

$img = !empty($producto['imagen']) ? $producto['imagen'] : 'default.jpg';
$img_especial = !empty($producto['imagen_especial']) ? $producto['imagen_especial'] : null;

// --- LÓGICA DE CATEGORÍAS ---
$esFigura = ($producto['id_categoria'] == 8);
$esMagic = ($producto['id_categoria'] == 7);
// Categorías de lectura (Novelas, Mangas, Comics, Libros)
$esLibro = ($producto['id_padre'] == 9 || $producto['id_categoria'] == 3 || $producto['id_categoria'] == 4|| $producto['id_categoria'] == 5);

$usarDisenoLimpio = ($esFigura || $esMagic || $esLibro);
?>

<link rel="stylesheet" href="css/detalle.css">

<style>
    /* Estilo específico para la visualización del libro en detalle */
    .libro-visual-static {
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0 50px rgba(0, 0, 0, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .libro-visual-static img {
        width: 100%;
        display: block;
        transition: transform 0.5s ease;
    }

    /* Pequeño efecto de iluminación suave al pasar el ratón, sin 3D */
    .libro-visual-static:hover img {
        transform: scale(1.02);
    }

    .libro-visual-static::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(0,0,0,0.5) 0%, transparent 10%, transparent 90%, rgba(0,0,0,0.2) 100%);
        pointer-events: none;
    }
</style>

<main class="reliquia-explorer">
    <div class="scan-line"></div>

    <div class="layout-premium">
        <div class="visual-vault">
            <?php if ($esFigura || $esMagic): ?>
                <div class="figura-display-detail animate-float">
                    <div class="neon-pedestal-large <?php echo $esMagic ? 'neon-magic' : ''; ?>"></div>
                    <img src="img/productos/<?php echo $img; ?>" class="figura-standalone-img" alt="Figura">
                </div>

            <?php elseif ($esLibro): ?>
                <div class="libro-display-container animate-book">
                    <div class="libro-shelf-glow"></div>
                    <img src="img/productos/<?php echo $img; ?>" class="libro-img-refined" alt="Portada">
                </div>

            <?php else: ?>
                <div class="card-stasis" id="cardContainer">
                    <div class="card-3d" id="card3d">
                        <div class="energy-glare"></div>
                        <div class="card-media" style="background-image: url('img/productos/<?php echo $img; ?>');"></div>
                        <?php if ($img_especial): ?>
                            <div class="mew-reveal" style="background-image: url('img/productos/<?php echo $img_especial; ?>');"></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
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
    <a href="agregar_al_carrito.php?id=<?php echo $id; ?>&retorno_cat=<?php echo $producto['id_categoria']; ?>" class="pill-button-neon">
        ADQUIRIR RELIQUIA
    </a>
    <a href="catalogo.php" class="back-link">Volver Atrás</a>
</div>
        </div>
    </div>
</main>

<?php if (!$usarDisenoLimpio): ?>
    <script src="js/detalle.js"></script>
<?php endif; ?>

<?php include 'includes/pie.php'; ?>