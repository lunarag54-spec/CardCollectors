<?php
require_once 'includes/conexion.php';
include 'includes/header.php'; 

// Captura de Filtros
$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'reciente';

// 1. Lógica de Filtrado por Categoría
$where_clause = " WHERE p.estado = 'activo' AND p.stock > 0";
if ($categoria_id > 0) {
    $where_clause .= " AND (p.id_categoria = $categoria_id OR c.id_padre = $categoria_id)";
}

// 2. Lógica de Ordenamiento
switch ($orden) {
    case 'vendidos': $sort_sql = "p.ventas DESC"; break; // Requiere columna 'ventas'
    case 'az':       $sort_sql = "p.nombre ASC"; break;
    case 'za':       $sort_sql = "p.nombre DESC"; break;
    case 'precio_min': $sort_sql = "p.precio ASC"; break;
    case 'precio_max': $sort_sql = "p.precio DESC"; break;
    case 'antiguo':  $sort_sql = "p.id_producto ASC"; break;
    case 'reciente': 
    default:         $sort_sql = "p.id_producto DESC"; break;
}



$sql = "SELECT p.*, c.nombre_categoria FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        $where_clause 
        ORDER BY $sort_sql";

$resultado = $conn->query($sql);
?>

<link rel="stylesheet" href="css/catalogo.css">

<style>
    /* --- DISEÑO EXCLUSIVO PARA FIGURAS (SIN RECUADRO, SIN 3D) --- */
    .figura-item {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* Contenedor de la figura que flota */
    .figura-display-unique {
        position: relative;
        height: 350px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        perspective: none !important; /* Quitamos perspectiva 3D */
    }

    /* Imagen de la figura: Sin recuadro, fondo transparente */
    .figura-standalone {
        width: 100%;
        height: 100%;
        background-size: contain !important;
        background-repeat: no-repeat !important;
        background-position: bottom center !important;
        z-index: 2;
        filter: drop-shadow(0 0 10px rgba(0, 212, 255, 0.1));
        transition: transform 0.3s ease, filter 0.3s ease;
    }

    /* Pedestal de luz neón en el suelo */
    .neon-pedestal {
        position: absolute;
        bottom: 0;
        width: 140px;
        height: 20px;
        background: rgba(0, 212, 255, 0.1);
        border-radius: 50%;
        filter: blur(10px);
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
        z-index: 1;
        transform: scaleX(1.5);
    }

    /* Hover: La figura se ilumina y sube ligeramente, pero sin girar en 3D */
    .figura-item:hover .figura-standalone {
        transform: translateY(-10px);
        filter: drop-shadow(0 0 25px rgba(0, 212, 255, 0.5));
    }

    .figura-item:hover .neon-pedestal {
        background: rgba(0, 212, 255, 0.3);
        box-shadow: 0 0 30px rgba(0, 212, 255, 0.8);
    }

    /* Mantenemos el estilo de las metas de texto */
    .figura-item .card-meta {
        background: rgba(10, 10, 10, 0.8);
        border-top: 1px solid #333;
        margin-top: 15px;
    }
</style>

<main class="catalogo-container">
    <?php include 'filtro.php'; ?>
    <div class="grid-productos">
        <?php while ($row = $resultado->fetch_assoc()): 
            $img = !empty($row['imagen']) ? $row['imagen'] : 'default.jpg';
            $nombre_minus = strtolower($row['nombre']);
            $categoria_nombre = strtolower($row['nombre_categoria']);
            
            // Detectamos si es una figura
            $esFigura = ($row['id_categoria'] == 2 || strpos($categoria_nombre, 'figura') !== false);
            
            // Lógica original para Magic y Cajas
            $esMagic = (strpos($categoria_nombre, 'magic') !== false || strpos($nombre_minus, 'magic') !== false);
            $esCaja = (strpos($nombre_minus, 'box') !== false || strpos($nombre_minus, 'caja') !== false);
            $claseImagen = $esCaja ? 'img-contain' : '';

            if ($esFigura): ?>
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

            <?php else: ?>
                <?php 
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
    // Script específico para evitar que las figuras ejecuten el JS de movimiento 3D
    document.querySelectorAll('.figura-item a').forEach(item => {
        item.addEventListener('mousemove', (e) => e.stopPropagation());
    });
</script>

<script src="js/efectocartas.js"></script>
<?php include 'includes/pie.php'; ?>