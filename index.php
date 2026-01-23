<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/conexion.php';
include 'includes/header.php';

$sql = "SELECT p.*, c.nombre_categoria 
        FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.estado = 'activo' AND p.stock > 0 
        ORDER BY p.id_producto DESC";

$resultado = $conn->query($sql);
?>

<style>
    body {
        /* Fondo con degradado radial y una textura de malla sutil */
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

    /* Efecto extra: Una luz difusa en la parte superior */
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 50% -20%, rgba(0, 212, 255, 0.15), transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    main {
        /* Esto empuja el footer hacia abajo obligatoriamente */
        flex: 1 0 auto !important;
        display: block !important;
        width: 100%;
        max-width: 1300px;
        margin: 0 auto;
        padding: 60px 20px 100px 20px !important;
        /* El padding inferior evita el choque */
        position: relative;
        z-index: 10;
    }

    /* Aseguramos que el footer se quede al final y no flote */
    .main-footer {
        flex-shrink: 0 !important;
        position: relative !important;
        z-index: 20;
        background: #000 !important;
        border-top: 1px solid #333;
    }

    /* 2. TÍTULO VIBRANTE */
    .titulo-catalogo {
        font-family: 'Orbitron', sans-serif;
        color: #fff;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 5px;
        margin-bottom: 50px;
        text-shadow: 0 0 15px rgba(255, 0, 0, 0.6);
    }

    /* 3. GRID DE PRODUCTOS */
    .grid-productos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 40px;
    }

    /* 4. TARJETAS CON BORDE LUMINOSO MEJORADO */
    .producto-card-wrapper {
        position: relative;
        padding: 2px;
        /* Grosor del haz de luz */
        background: #1a1a1a;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .producto-card-wrapper:hover {
        transform: scale(1.05);
        z-index: 15;
    }

    /* Efecto de luz de borde (Cian y Rojo) */
    .producto-card-wrapper::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg,
                transparent 0%,
                #00d4ff 25%,
                transparent 50%,
                #ff0000 75%,
                transparent 100%);
        animation: rotateGlow 4s linear infinite;
        z-index: 0;
    }

    .producto-card-body {
        position: relative;
        background: #0a0a0a;
        /* Fondo interno de la carta */
        border-radius: 13px;
        height: 100%;
        z-index: 1;
        display: flex;
        flex-direction: column;
    }

    @keyframes rotateGlow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* 5. CONTRASTE DE TEXTOS */
    .img-container {
        width: 100%;
        aspect-ratio: 1/1;
        overflow: hidden;
        border-bottom: 1px solid #222;
    }

    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-container {
        padding: 20px;
        flex-grow: 1;
        text-align: center;
    }

    /* Nombre en Blanco/Cian para contraste máximo */
    .nombre-producto {
        font-family: 'Rajdhani', sans-serif;
        color: #00d4ff;
        font-size: 1.5rem;
        margin-bottom: 10px;
        text-transform: uppercase;
        font-weight: bold;
    }

    .precio-txt {
        color: #fff;
        font-family: 'Orbitron', sans-serif;
        font-size: 1.4rem;
        display: block;
        margin: 10px 0;
    }

    .btn-reclamar {
        width: 100%;
        background: transparent;
        border: 2px solid #ff0000;
        color: #ff0000;
        padding: 10px;
        font-family: 'Orbitron', sans-serif;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
    }

    .btn-reclamar:hover {
        background: #ff0000;
        color: #fff;
        box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
    }
</style>

<h1 class="titulo-catalogo">Inventario de Reliquias</h1>

<div class="grid-productos">
    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <?php while ($row = $resultado->fetch_assoc()):
            $rutaImagen = "img/productos/" . $row['imagen'];
            if (empty($row['imagen']) || !file_exists($rutaImagen)) {
                $rutaImagen = "img/productos/default.jpg";
            }
        ?>
            <article class="producto-card-wrapper">
                <div class="producto-card-body">
                    <div class="img-container">
                        <img src="<?php echo $rutaImagen; ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                    </div>

                    <div class="info-container">
                        <h3 class="nombre-producto"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                        <span style="color: #666; font-size: 0.8rem;"><?php echo $row['nombre_categoria']; ?></span>

                        <span class="precio-txt"><?php echo number_format($row['precio'], 2); ?>€</span>

                        <button class="btn-reclamar">RECLAMAR</button>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="color: #888; text-align: center; grid-column: 1/-1;">No hay artículos en el mazo.</p>
    <?php endif; ?>
</div>

<?php include 'includes/pie.php'; ?>