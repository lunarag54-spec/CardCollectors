<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit(); }
require_once 'includes/conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// Consulta uniendo las tablas para sacar los productos actuales del mazo
$sql = "SELECT p.id_producto, p.nombre, p.precio, cp.cantidad 
        FROM Producto p 
        INNER JOIN Carrito_Producto cp ON p.id_producto = cp.id_producto 
        INNER JOIN Carrito c ON cp.id_carrito = c.id_carrito 
        WHERE c.id_usuario = $id_usuario";

$res_carrito = $conn->query($sql);
$total_compra = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito | Card Collector</title>
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="app-container">
        <div class="carrito-container animated-border">
            <h1><i class="fas fa-id-card"></i> TU MAZO ACTUAL</h1>

            <?php if ($res_carrito && $res_carrito->num_rows > 0): ?>
                <div class="lista-items">
                    <?php while ($row = $res_carrito->fetch_assoc()): ?>
                        <?php 
                            $subtotal = $row['precio'] * $row['cantidad'];
                            $total_compra += $subtotal;
                        ?>
                        <div class="carrito-item">
                            <div class="item-info">
                                <strong><?php echo htmlspecialchars($row['nombre']); ?></strong><br>
                                <small style="opacity: 0.6;"><?php echo number_format($row['precio'], 2); ?>€ / ud.</small>
                            </div>

                            <div class="item-controls">
                                <div class="quantity-selector">
                                    <a href="actualizar_carrito.php?id=<?= $row['id_producto'] ?>&action=remove" class="btn-qty">-</a>
                                    <span class="qty-number"><?= $row['cantidad'] ?></span>
                                    <a href="actualizar_carrito.php?id=<?= $row['id_producto'] ?>&action=add" class="btn-qty">+</a>
                                </div>

                                <div class="item-precio">
                                    <?= number_format($subtotal, 2); ?>€
                                </div>

                                <a href="actualizar_carrito.php?id=<?= $row['id_producto'] ?>&action=delete" class="btn-delete-item">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="total-carrito">
                    TOTAL: <?php echo number_format($total_compra, 2); ?>€
                </div>

                <div class="acciones">
                    <a href="index.php" class="btn btn-volver">CONTINUAR EXPLORANDO</a>
                    <a href="checkout.php" class="btn btn-comprar">FINALIZAR ADQUISICIÓN</a>
                </div>

            <?php else: ?>
                <div style="text-align: center; padding: 40px;">
                    <p style="font-size: 1.2rem; margin-bottom: 20px;">No hay cartas en tu mazo todavía.</p>
                    <a href="index.php" class="btn btn-comprar">IR A LA TIENDA</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/index.js"></script>
</body>
</html>