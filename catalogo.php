<?php
// 1. Conexión y Cabecera
require_once 'includes/conexion.php';
include 'includes/header.php'; // Asegúrate de que este archivo tenga tus estilos base

// 2. Lógica de filtrado
$filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$where = "WHERE p.estado = 'activo' AND p.stock > 0";
if (!empty($filtro)) {
    $cat = $conn->real_escape_string($filtro);
    $where .= " AND c.nombre_categoria LIKE '%$cat%'";
}

// Consulta actualizada para incluir la columna 'imagen'
$sql = "SELECT p.*, c.nombre_categoria 
        FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        $where
        ORDER BY p.id_producto DESC";
$resultado = $conn->query($sql);
?>

<style>
    /* ===== MENÚ DE CATEGORÍAS "NEXUS" ===== */
    .terminal-selector { background: #000; padding: 40px 0; text-align: center; border-bottom: 1px solid #111; }
    .module-grid { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }
    .module-btn { 
        padding: 12px 25px; background: #0a0a0a; border: 1px solid #333; color: #fff; text-decoration: none;
        font-family: 'Orbitron', sans-serif; font-size: 0.7rem; clip-path: polygon(10% 0, 100% 0, 90% 100%, 0% 100%);
        transition: 0.3s; letter-spacing: 2px;
    }
    .module-btn:hover, .module-btn.active { border-color: #00d4ff; color: #00d4ff; box-shadow: 0 0 15px rgba(0, 212, 255, 0.4); }

    /* ===== GRID Y EFECTO 3D ===== */
    .grid-productos { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
        gap: 50px; 
        padding: 60px 5%; 
        background: #050505;
    }

    .card-3d-wrapper { perspective: 1200px; width: 260px; margin: 0 auto; position: relative; }
    
    .card-3d { 
        width: 100%; aspect-ratio: 2.5 / 3.5; position: relative; border-radius: 12px; 
        transform-style: preserve-3d; transition: transform 0.1s ease-out; 
        background: #111; box-shadow: 0 15px 35px rgba(0,0,0,0.8);
    }

    .card-front { 
        position: absolute; inset: 0; border-radius: 12px; 
        background-size: cover; background-position: center; z-index: 1; 
        border: 1px solid rgba(255,255,255,0.1); overflow: hidden;
    }

    /* Capa Holográfica */
    .holo-shine { 
        position: absolute; inset: 0; z-index: 2; opacity: 0; transition: 0.3s;
        background: linear-gradient(110deg, transparent 25%, rgba(255,255,255,0.5) 45%, rgba(0,255,255,0.2) 55%, transparent 75%);
        mix-blend-mode: color-dodge; background-size: 200% 200%;
    }
    .card-3d-wrapper:hover .holo-shine { opacity: 1; }

    .info-tcg { text-align: center; margin-top: 20px; font-family: 'Orbitron', sans-serif; }
    .info-tcg h3 { color: #fff; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 5px; }
    .precio-tcg { color: #ff003c; font-size: 1.2rem; font-weight: bold; }
</style>

<div class="terminal-selector">
    <nav class="module-grid">
        <a href="catalogo.php" class="module-btn <?php echo !$filtro ? 'active' : ''; ?>">TODOS LOS ARCHIVOS</a>
        <a href="catalogo.php?categoria=Cartas" class="module-btn <?php echo $filtro == 'Cartas' ? 'active' : ''; ?>">SECTOR-TCG</a>
        <a href="catalogo.php?categoria=Figuras" class="module-btn <?php echo $filtro == 'Figuras' ? 'active' : ''; ?>">FIGURAS</a>
        <a href="catalogo.php?categoria=Libros" class="module-btn <?php echo $filtro == 'Libros' ? 'active' : ''; ?>">ARCHIVOS</a>
    </nav>
</div>

<main class="contenedor-principal">
    <div class="grid-productos">
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while ($producto = $resultado->fetch_assoc()): 
                // Verificamos si es categoría Cartas (ID 1)
                $esCarta = ($producto['id_categoria'] == 1);
                // Usamos la imagen de la BD o la por defecto
                $imgNombre = !empty($producto['imagen']) ? $producto['imagen'] : 'default.jpg';
                $rutaImg = "img/productos/" . $imgNombre;
            ?>
                <?php if ($esCarta): ?>
                    <article class="card-3d-wrapper">
                        <div class="card-3d">
                            <div class="card-front" style="background-image: url('<?php echo $rutaImg; ?>');">
                                <div class="holo-shine"></div>
                            </div>
                        </div>
                        <div class="info-tcg">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="precio-tcg"><?php echo number_format($producto['precio'], 2); ?>€</p>
                            <a href="agregar_al_carrito.php?id=<?php echo $producto['id_producto']; ?>" class="module-btn" style="margin-top:10px; display:inline-block;">VINCULAR</a>
                        </div>
                    </article>
                <?php else: ?>
                    <article class="producto-card">
                        <div class="producto-imagen">
                            <img src="<?php echo $rutaImg; ?>" alt="<?php echo $producto['nombre']; ?>">
                            <span class="categoria-tag"><?php echo $producto['nombre_categoria']; ?></span>
                        </div>
                        <div class="producto-info">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="precio"><?php echo number_format($producto['precio'], 2); ?>€</p>
                            <a href="detalles.php?id=<?php echo $producto['id_producto']; ?>" class="btn-ver">Ver Detalles</a>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #666; text-align: center; grid-column: 1/-1;">No se han encontrado registros en este sector.</p>
        <?php endif; ?>
    </div>
</main>

<script>
// Lógica de movimiento 3D y brillo
document.querySelectorAll('.card-3d-wrapper').forEach(wrapper => {
    const card = wrapper.querySelector('.card-3d');
    const shine = wrapper.querySelector('.holo-shine');

    wrapper.addEventListener('mousemove', (e) => {
        const rect = wrapper.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        // Rotación
        const rotateX = ((y - centerY) / centerY) * -15;
        const rotateY = ((x - centerX) / centerX) * 15;

        card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        
        // Movimiento del brillo
        if(shine) {
            shine.style.backgroundPosition = `${(x / rect.width) * 100}% ${(y / rect.height) * 100}%`;
        }
    });

    wrapper.addEventListener('mouseleave', () => {
        card.style.transform = `rotateX(0deg) rotateY(0deg)`;
    });
});
</script>

<?php include 'includes/pie.php'; ?>