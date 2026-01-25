<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/conexion.php';
include 'includes/header.php';

// Consulta para obtener productos activos con stock
$sql = "SELECT p.*, c.nombre_categoria 
        FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.estado = 'activo' AND p.stock > 0 
        ORDER BY p.id_producto DESC";

$resultado = $conn->query($sql);
?>

<style>
    body {
        /* Fondo Cyberpunk: Degradado radial + Malla técnica */
        background:
            radial-gradient(circle at center, rgba(13, 17, 23, 0.8) 0%, #050505 100%),
            linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        background-size: 100% 100%, 40px 40px, 40px 40px;
        display: flex !important;
        flex-direction: column !important;
        min-height: 100vh !important;
        margin: 0;
    }

    /* Luz de neón difusa en el techo de la página */
    body::before {
        content: "";
        position: fixed;
        top: 0; left: 50%;
        transform: translateX(-50%);
        width: 100%; height: 100%;
        background: radial-gradient(circle at 50% -20%, rgba(0, 212, 255, 0.15), transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    main {
        flex: 1 0 auto !important;
        display: block !important;
        width: 100%;
        max-width: 1300px;
        margin: 0 auto;
        padding: 60px 20px 100px 20px !important;
        position: relative;
        z-index: 10;
    }

    .titulo-catalogo {
        font-family: 'Orbitron', sans-serif;
        color: #fff;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 5px;
        margin-bottom: 50px;
        text-shadow: 0 0 15px rgba(0, 212, 255, 0.6);
    }

    .grid-productos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 40px;
    }

    /* Tarjetas con borde de neón rotatorio */
    .producto-card-wrapper {
        position: relative;
        padding: 2px;
        background: #1a1a1a;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .producto-card-wrapper:hover {
        transform: scale(1.05);
        z-index: 15;
    }

    .producto-card-wrapper::before {
        content: "";
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: conic-gradient(from 0deg, transparent 0%, #00d4ff 25%, transparent 50%, #ff0000 75%, transparent 100%);
        animation: rotateGlow 4s linear infinite;
        z-index: 0;
    }

    .producto-card-body {
        position: relative;
        background: #0a0a0a;
        border-radius: 13px;
        height: 100%;
        z-index: 1;
        display: flex;
        flex-direction: column;
    }

    @keyframes rotateGlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .img-container {
        width: 100%;
        aspect-ratio: 1/1;
        overflow: hidden;
        border-bottom: 1px solid #222;
    }

    .img-container img {
        width: 100%; height: 100%; object-fit: cover;
    }

    .info-container {
        padding: 20px;
        flex-grow: 1;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .nombre-producto {
        font-family: 'Rajdhani', sans-serif;
        color: #00d4ff;
        font-size: 1.5rem;
        margin-bottom: 5px;
        text-transform: uppercase;
        font-weight: bold;
    }

    .cat-txt { color: #666; font-size: 0.8rem; margin-bottom: 10px; display: block; }

    .precio-txt {
        color: #fff;
        font-family: 'Orbitron', sans-serif;
        font-size: 1.4rem;
        margin-bottom: 15px;
    }

    .btn-reclamar {
        display: block;
        width: 100%;
        background: transparent;
        border: 2px solid #ff0000;
        color: #ff0000;
        padding: 10px;
        font-family: 'Orbitron', sans-serif;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        text-align: center;
    }

    .btn-reclamar:hover {
        background: #ff0000;
        color: #fff;
        box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
    }
</style>

<main>
    <h1 class="titulo-catalogo">Inventario de Reliquias</h1>
    
    <div class="grid-productos">
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while ($row = $resultado->fetch_assoc()): 
                $nombreImagen = !empty($row['imagen']) ? $row['imagen'] : 'default.jpg';
                $rutaImagen = "img/productos/" . $nombreImagen;
            ?>
                <article class="producto-card-wrapper">
                    <div class="producto-card-body">
                        <div class="img-container">
                            <img src="<?php echo $rutaImagen; ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                        </div>
                        <div class="info-container">
                            <div>
                                <h3 class="nombre-producto"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                                <span class="cat-txt"><?php echo htmlspecialchars($row['nombre_categoria']); ?></span>
                            </div>
                            
                            <div class="precio-txt"><?php echo number_format($row['precio'], 2); ?>€</div>
                            
                            <a href="agregar_al_carrito.php?id=<?php echo $row['id_producto']; ?>" class="btn-reclamar">
                                AÑADIR AL CARRO
                            </a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #888; text-align: center; grid-column: 1/-1; padding: 50px;">
                <i class="fas fa-ghost" style="font-size: 3rem; display: block; margin-bottom: 15px;"></i>
                No hay artículos disponibles en el mazo actualmente.
            </p>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/pie.php'; ?>