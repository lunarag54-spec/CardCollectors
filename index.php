<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit(); }
require_once 'includes/conexion.php';
include 'includes/header.php'; 
?>

<link rel="stylesheet" href="css/index.css">

<div class="app-container">
    <div id="glow-red"></div>
    <div id="glow-blue"></div>

    <main class="main-content">
        <div class="scroll-area">
            <h1 class="titulo-catalogo">INVENTARIO DE RELIQUIAS</h1>
            
            <div class="grid-productos">
                <?php 
                $sql = "SELECT p.*, c.nombre_categoria FROM Producto p 
                        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                        WHERE p.estado = 'activo' AND p.stock > 0 ORDER BY p.id_producto DESC";
                $resultado = $conn->query($sql);

                if ($resultado && $resultado->num_rows > 0): 
                    while ($row = $resultado->fetch_assoc()): 
                        $img = !empty($row['imagen']) ? $row['imagen'] : 'default.jpg';
                        $claseHolo = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $row['nombre']), '-'));
                ?>
                    <article class="producto-card-wrapper <?php echo $claseHolo; ?>">
                        <div class="producto-card-body">
                            <div class="img-container">
                                <img src="img/productos/<?php echo $img; ?>" alt="Reliquia">
                                <div class="holo-shine"></div>
                            </div>
                            <div class="info-container">
                                <h3 class="nombre-producto"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                                <p class="precio-txt"><?php echo number_format($row['precio'], 2); ?>€</p>
                                <div class="btn-container">
                                    <a href="agregar_al_carrito.php?id=<?php echo $row['id_producto']; ?>" class="btn-reclamar">
                                        ADQUIRIR
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </main>
</div>

<script src="js/index.js"></script>
<?php include 'includes/pie.php'; ?>