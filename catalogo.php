<?php
require_once 'includes/conexion.php';
include 'includes/header.php'; 

$sql = "SELECT p.*, c.nombre_categoria FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.estado = 'activo' AND p.stock > 0 ORDER BY p.id_producto DESC";
$resultado = $conn->query($sql);
?>

<link rel="stylesheet" href="css/catalogo.css">

<main class="catalogo-container">
    <div class="grid-productos">
        <?php while ($row = $resultado->fetch_assoc()): 
            $img = !empty($row['imagen']) ? $row['imagen'] : 'default.jpg';
            $nombre = strtolower($row['nombre']);
            
            // Detección de rareza para efectos especiales
            $raras = ['vmax', 'ex', 'gx', 'shiny', 'secret', 'full art', 'gold'];
            $esEspecial = false;
            foreach($raras as $r) { if(strpos($nombre, $r) !== false) { $esEspecial = true; break; } }
            $tipoCard = $esEspecial ? 'card-rare' : 'card-standard';
        ?>
            <article class="card-item <?php echo $tipoCard; ?>">
                <a href="detalle_producto.php?id=<?php echo $row['id_producto']; ?>" class="card-3d-anchor">
                    <div class="card-3d">
                        <div class="holo-shine"></div>
                        <div class="card-front" style="background-image: url('img/productos/<?php echo $img; ?>');"></div>
                    </div>
                </a>

                <div class="card-meta">
                    <h3 class="product-title"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                    <div class="product-action-panel">
                        <div class="price-badge">
                            <span class="price-label">VALOR</span>
                            <span class="price-amount"><?php echo number_format($row['precio'], 2); ?>€</span>
                        </div>
                        <a href="agregar_al_carrito.php?id=<?php echo $row['id_producto']; ?>" class="buy-pill">
                            <span class="btn-text">ADQUIRIR</span>
                            <span class="btn-icon">+</span>
                        </a>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<script src="js/efectocartas.js"></script>
<?php include 'includes/pie.php'; ?>