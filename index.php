<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
require_once 'includes/conexion.php';
include 'includes/header.php';

// 1. Grial y Novedades
$sql_grail = "SELECT * FROM Producto ORDER BY precio DESC LIMIT 1";
$res_grail = $conn->query($sql_grail);
$grail = $res_grail->fetch_assoc();

$sql_novedades = "SELECT p.*, c.nombre_categoria FROM Producto p 
                  INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                  ORDER BY p.id_producto DESC LIMIT 4";
$res_novedades = $conn->query($sql_novedades);

// 2. Traer categorías con su nueva columna de imagen
$sql_cats = "SELECT id_categoria, nombre_categoria, imagen_categoria FROM Categoria WHERE id_categoria IN (2, 3, 6, 7)";
$res_cats = $conn->query($sql_cats);
$categorias = [];
while($cat = $res_cats->fetch_assoc()) {
    $categorias[$cat['id_categoria']] = $cat;
}
?>

<link rel="stylesheet" href="css/index.css">

<div id="glow-red"></div>
<div id="glow-blue"></div>

<main class="home-container">
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="glitch-title" data-text="THE VAULT">THE VAULT</h1>
            <p class="hero-subtitle">El santuario del coleccionista profesional.</p>
            <div class="hero-buttons">
                <a href="catalogo.php" class="btn-main">EXPLORAR BÓVEDA</a>
                <a href="#novedades" class="btn-secondary">VER NOVEDADES</a>
            </div>
        </div>
    </section>

    <section class="bento-section reveal">
        <h2 class="section-title">ACCESO POR UNIVERSOS</h2>
        <div class="bento-grid">
            <?php 
            $tipos = [8 => 'figuras', 6 => 'pokemon', 7 => 'magic', 3 => 'libros'];
            foreach($tipos as $id => $clase) {
                $nombre = $categorias[$id]['nombre_categoria'] ?? "FIGURAS";
                $img = $categorias[$id]['imagen_categoria'] ?? "categoria_figuras.png";
                echo "
                <a href='catalogo.php?categoria=$id' class='bento-item $clase' style='background-image: url(\"img/categorias/$img\");'>
                    <div class='bento-overlay'><span>".strtoupper($nombre)."</span></div>
                </a>";
            }
            ?>
            <a href="catalogo.php" class="bento-item ver-todo" style="background-image: url('img/categorias/categorias.jpg');">
                <div class="bento-overlay"><span>EXPEDIENTE COMPLETO</span></div>
            </a>
        </div>
    </section>

    <?php if ($grail): ?>
    <section class="grail-section reveal">
        <div class="grail-container">
            <div class="grail-text">
                <span class="label">PIEZA DE ALTO VALOR</span>
                <h2><?php echo htmlspecialchars($grail['nombre']); ?></h2>
                <div class="grail-price"><?php echo number_format($grail['precio'], 2); ?>€</div>
                <a href="detalle_producto.php?id=<?php echo $grail['id_producto']; ?>" class="btn-reclamar">EXAMINAR</a>
            </div>
            <div class="grail-visual">
                <div class="scanner-line"></div>
                <img src="img/productos/<?php echo $grail['imagen']; ?>" alt="Grail">
            </div>
        </div>
    </section>


    <section id="novedades" class="news-section reveal">
    <h2 class="section-title">ÚLTIMAS ADQUISICIONES</h2>
    <div class="news-grid">
        <?php while($row = $res_novedades->fetch_assoc()): ?>
         <a href="detalle_producto.php?id=<?php echo $row['id_producto']; ?>" class="producto-card-wrapper" style="text-decoration: none; color: inherit;">
                <article class="producto-card-body">
                    <div class="img-container">
                        <img src="img/productos/<?php echo $row['imagen']; ?>" alt="">
                    </div>
                    <div class="info-container">
                        <h3 class="nombre-producto"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                        <div class="precio-txt"><?php echo number_format($row['precio'], 2); ?>€</div>
                        
                        <div class="btn-reclamar">
                            VER DETALLES
                        </div>
                    </div>
                    <div class="holo-shine"></div>
                </article>
            </a>
        <?php endwhile; ?>
    </div>
</section>
    <?php endif; ?>
</main>

<script src="js/index.js"></script>

<?php include 'includes/pie.php'; ?>