<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php'; 
require_once 'includes/conexion.php';

$id_user = $_SESSION['id_usuario'];

$query_inventario = "SELECT p.*, c.nombre_categoria 
                     FROM Producto p 
                     INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                     WHERE p.estado = 'activo' AND p.id_vendedor = ?
                     ORDER BY p.id_producto DESC";

$stmt = $conn->prepare($query_inventario);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$resultado_inventario = $stmt->get_result();
?>

<link rel="stylesheet" href="css/detalle.css">

<style>
    .admin-vault {
        background: #020202;
        min-height: 100vh;
        padding: 80px 20px;
        font-family: 'Rajdhani', sans-serif;
        color: #fff;
    }

    .admin-header {
        max-width: 1200px;
        margin: 0 auto 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px 25px 20px;
        border-bottom: 2px solid rgba(255, 68, 68, 0.2);
    }

    .admin-title-group h2 {
        font-family: 'Orbitron';
        letter-spacing: 4px;
        text-transform: uppercase;
        font-size: 1.8rem;
        margin: 0;
        background: linear-gradient(90deg, #fff, #ff4444);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .admin-title-group p {
        font-size: 0.8rem;
        color: #666;
        margin-top: 5px;
        letter-spacing: 1px;
    }

    .btn-forjar-header {
        background: #ff4444;
        color: #000;
        text-decoration: none;
        padding: 15px 35px;
        font-family: 'Orbitron';
        font-weight: 900;
        font-size: 0.85rem;
        border-radius: 4px; /* Estilo más industrial */
        box-shadow: 0 0 20px rgba(255, 68, 68, 0.3);
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-forjar-header:hover {
        background: #fff;
        box-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
        transform: translateY(-3px);
    }

    /* CONTENEDOR DE TABLA */
    .tabla-container {
        max-width: 1200px;
        margin: 0 auto;
        background: rgba(15, 15, 15, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 30px 60px rgba(0,0,0,0.7);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: rgba(20, 20, 20, 1);
        font-family: 'Orbitron';
        font-size: 0.75rem;
        color: #888;
        text-align: left;
        padding: 25px 20px;
        text-transform: uppercase;
        border-bottom: 1px solid #222;
    }

    td {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        transition: 0.2s;
    }

    tr:hover td {
        background: rgba(255, 68, 68, 0.02);
    }

    .img-mini {
        width: 70px;
        height: 90px;
        object-fit: cover;
        border: 1px solid #333;
        transition: 0.3s;
    }

    tr:hover .img-mini {
        border-color: #ff4444;
        transform: scale(1.1);
    }

    .product-info-cell b {
        display: block;
        font-size: 1.1rem;
        color: #fff;
        margin-bottom: 5px;
    }

    .cat-badge {
        font-size: 0.7rem;
        padding: 3px 10px;
        background: #1a1a1a;
        border: 1px solid #333;
        color: #aaa;
        border-radius: 3px;
        text-transform: uppercase;
    }

    .precio-tag {
        color: #ff4444;
        font-family: 'Orbitron';
        font-weight: bold;
        font-size: 1.1rem;
    }

    /* ESPACIADO Y ESTILO DE BOTONES DE ACCIÓN */
    .actions-cell {
        display: flex;
        gap: 12px; /* Separación garantizada entre botones */
        align-items: center;
    }

    .btn-accion {
        padding: 10px 18px;
        text-decoration: none;
        font-family: 'Orbitron';
        font-size: 0.7rem;
        font-weight: bold;
        text-align: center;
        transition: 0.3s;
        border-radius: 2px;
        min-width: 100px;
    }

    .btn-editar {
        background: transparent;
        border: 1px solid #00d4ff;
        color: #00d4ff;
    }

    .btn-editar:hover {
        background: #00d4ff;
        color: #000;
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
    }

    .btn-eliminar {
        background: transparent;
        border: 1px solid #444;
        color: #666;
    }

    .btn-eliminar:hover {
        border-color: #ff4444;
        color: #ff4444;
        background: rgba(255, 68, 68, 0.05);
    }
</style>

<div class="admin-vault">
    <div class="scan-line"></div>

    <div class="admin-header">
        <div class="admin-title-group">
            <h2>Gestión de Inventario</h2>
            <p>Sincronización directa con el mercado de reliquias</p>
        </div>
        <a href="subir_producto.php" class="btn-forjar-header">
            <span>+ FORJAR RELIQUIA</span>
        </a>
    </div>

    <div class="tabla-container">
        <table>
            <thead>
                <tr>
                    <th>Estatus Visual</th>
                    <th>Información del Artefacto</th>
                    <th>Valor de Mercado</th>
                    <th>Stock</th>
                    <th>Protocolos</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado_inventario->num_rows > 0): ?>
                    <?php while($row = $resultado_inventario->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="img/productos/<?= $row['imagen'] ?>" class="img-mini" onerror="this.src='img/productos/default.jpg'">
                            </td>
                            <td class="product-info-cell">
                                <b><?= htmlspecialchars($row['nombre']) ?></b>
                                <span class="cat-badge"><?= $row['nombre_categoria'] ?></span>
                            </td>
                            <td>
                                <span class="precio-tag"><?= number_format($row['precio'], 2) ?>€</span>
                            </td>
                            <td style="font-family: 'Orbitron'; font-size: 0.9rem;">
                                <?= $row['stock'] ?> <small style="color:#555">UDS</small>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="editar_producto.php?id=<?= $row['id_producto'] ?>" class="btn-accion btn-editar">MODIFICAR</a>
                                    <a href="eliminar_producto.php?id=<?= $row['id_producto'] ?>" class="btn-accion btn-eliminar" onclick="return confirm('¿Confirmar retiro de la reliquia?');">RETIRAR</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 100px; color: #444; font-family: 'Orbitron'; letter-spacing: 2px;">
                            BÓVEDA VACÍA - ESPERANDO NUEVAS RELIQUIAS
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/pie.php'; ?>