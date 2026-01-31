<?php
require_once 'includes/conexion.php';
include 'includes/header.php'; 

$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;

// Construimos el filtro
// 1. Si NO hay categoría seleccionada, excluimos la 9 (Exclusivos) por defecto.
// 2. Si HAY una categoría seleccionada, mostramos solo esa (permitiendo ver la 9 si se llega por enlace).
if ($categoria_id > 0) {
    $where_clause = " AND (p.id_categoria = $categoria_id OR c.id_padre = $categoria_id)";
} else {
    // ESTA ES LA CLAVE: Excluimos la categoría 9 del "Ver Todo"
    $where_clause = " AND p.id_categoria != 9";
}

$sql = "SELECT p.*, c.nombre_categoria, c.id_padre FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.estado = 'activo' AND p.stock > 0 $where_clause 
        ORDER BY p.id_producto DESC";

$resultado = $conn->query($sql);
?>

<link rel="stylesheet" href="css/catalogo.css">

<style>
    /* --- ESTILO PARA LIBROS --- */
    .libro-item {
        background: rgba(20, 20, 20, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        overflow: hidden;
    }

    .libro-display {
        position: relative; width: 100%; aspect-ratio: 2 / 3;
        overflow: hidden; border-radius: 4px;
    }

    .libro-img {
        width: 100%; height: 100%; background-size: cover;
        background-position: center; transition: transform 0.5s ease;
    }

    .libro-item:hover {
        transform: translateY(-10px);
        border-color: var(--neon-blue, #00d4ff) !important;
        box-shadow: 0 10px 30px rgba(0, 212, 255, 0.3);
    }

    /* --- ESTILO PARA FIGURAS --- */
    .figura-item { background: transparent !important; border: none !important; box-shadow: none !important; }
    .figura-display-unique {
        position: relative; height: 350px; display: flex;
        align-items: flex-end; justify-content: center;
    }
    .figura-standalone {
        width: 100%; height: 100%; background-size: contain !important;
        background-repeat: no-repeat !important; background-position: bottom center !important;
        z-index: 2; transition: transform 0.3s ease, filter 0.3s ease;
    }
    .neon-pedestal {
        position: absolute; bottom: 0; width: 140px; height: 20px;
        background: rgba(0, 212, 255, 0.1); border-radius: 50%;
        filter: blur(10px); box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
        z-index: 1; transform: scaleX(1.5);
    }
    .figura-item:hover .figura-standalone { transform: translateY(-10px); filter: drop-shadow(0 0 25px rgba(0, 212, 255, 0.5)); }
</style>

<main class="catalogo-container">
    <div class="grid-productos">
        <?php while ($row = $resultado->fetch_assoc()): 
            $img = !empty($row['imagen']) ? $row['imagen'] : 'default.jpg';
            $nombre_minus = strtolower($row['nombre']);
            $categoria_nombre = strtolower($row['nombre_categoria']);
            
            // Detectamos tipos para aplicar estilos
            $esLibro = ($row['id_padre'] == 9 || $row['id_categoria'] == 9); // Nota: si tu cat 9 es exclusiva, esto marcará a Kaito como libro visualmente si entras directo.
            $esFigura = ($row['id_categoria'] == 2 || strpos($categoria_nombre, 'figura') !== false);
            
            // Lógica para Cartas
            $esMagic = (strpos($categoria_nombre, 'magic') !== false || strpos($nombre_minus, 'magic') !== false);
            $esCaja = (strpos($nombre_minus, 'box') !== false || strpos($nombre_minus, 'caja') !== false);
            $claseImagen = $esCaja ? 'img-contain' : '';

            // Renderizado por tipo
            if ($esLibro): ?>
                <article class="card-item figura-item">
                    <a href="detalle_producto.php?id=<?php echo $row['id_producto']; ?>">
                        <div class="figura-display-unique">
                            <div class="figura-standalone" style="background-image: url('img/productos/<?php echo $img; ?>');"></div>
                        </div>
                    </a>
                    <div class="card-meta">
                        <h3 class="product-title"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                        <div class="product-action-panel">
                            <div class="price-badge">
                                <span class="price-amount"><?php echo number_format($row['precio'], 2); ?>€</span>
                            </div>
                            <a href="agregar_al_carrito.php?id=<?php echo $row['id_producto']; ?>" class="buy-pill">ADQUIRIR +</a>
                        </div>
                    </div>
                </article>

            <?php elseif ($esFigura): ?>
                <article class="card-item figura-item">
                    <a href="detalle_producto.php?id=<?php echo $row['id_producto']; ?>">
                        <div class="figura-display-unique">
                            <div class="neon-pedestal"></div>
                            <div class="figura-standalone" style="background-image: url('img/productos/<?php echo $img; ?>');"></div>
                        </div>
                    </a>
                    <div class="card-meta">
                        <h3 class="product-title"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                        <div class="product-action-panel">
                            <div class="price-badge">
                                <span class="price-amount"><?php echo number_format($row['precio'], 2); ?>€</span>
                            </div>
                            <a href="agregar_al_carrito.php?id=<?php echo $row['id_producto']; ?>" class="buy-pill">ADQUIRIR +</a>
                        </div>
                    </div>
                </article>

            <?php else: 
                $raras = ['vmax', 'ex', 'gx', 'shiny', 'secret', 'full art', 'gold'];
                $esEspecial = false;
                foreach($raras as $r) { if(strpos($nombre_minus, $r) !== false) { $esEspecial = true; break; } }
                $tipoCard = $esEspecial ? 'card-rare' : 'card-standard';
                ?>
                <article class="card-item <?php echo $tipoCard; ?>">
                    <a href="detalle_producto.php?id=<?php echo $row['id_producto']; ?>" class="card-3d-anchor">
                        <div class="card-3d">
                            <?php if (!$esMagic): ?>
                                <div class="holo-shine"></div>
                            <?php endif; ?>
                            <div class="card-front <?php echo $claseImagen; ?>" 
                                 style="background-image: url('img/productos/<?php echo $img; ?>');">
                            </div>
                        </div>
                    </a>
                    <div class="card-meta">
                        <h3 class="product-title"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                        <div class="product-action-panel">
                            <div class="price-badge">
                                <span class="price-amount"><?php echo number_format($row['precio'], 2); ?>€</span>
                            </div>
                            <a href="agregar_al_carrito.php?id=<?php echo $row['id_producto']; ?>" class="buy-pill">ADQUIRIR +</a>
                        </div>
                    </div>
                </article>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</main>

<script>
    document.querySelectorAll('.figura-item a, .libro-item a').forEach(item => {
        item.addEventListener('mousemove', (e) => e.stopPropagation());
    });
</script>

<script src="js/efectocartas.js"></script>
<?php include 'includes/pie.php'; ?>