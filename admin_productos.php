<?php
// 1. Seguridad de Sesión
session_start();

// Si no hay sesión, al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php'; // Usamos el header con el botón "Subir Reliquia"
require_once 'includes/conexion.php';

$id_user = $_SESSION['id_usuario'];

// 2. Consulta para el inventario filtrado por el vendedor logueado
// Así garantizamos que un usuario no pueda editar o ver productos de otros
$query_inventario = "SELECT p.*, c.nombre_categoria, c.tipo_libro 
                     FROM Producto p 
                     INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                     WHERE p.estado = 'activo' AND p.id_vendedor = ?
                     ORDER BY p.id_producto DESC";

$stmt = $conn->prepare($query_inventario);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$resultado_inventario = $stmt->get_result();
?>

<style>
    .admin-container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 20px;
    }

    .admin-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .admin-header h2 {
        font-family: 'Orbitron', sans-serif;
        color: var(--accent-blue);
        text-shadow: 0 0 10px var(--accent-blue);
        letter-spacing: 2px;
        margin-bottom: 5px;
    }

    .admin-header p {
        color: #666;
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    /* Estilo de la Tabla Estilo Cyberpunk */
    .tabla-reliquias {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px; /* Espacio entre filas */
        margin-top: 20px;
    }

    .tabla-reliquias th {
        color: var(--accent-blue);
        text-transform: uppercase;
        font-family: 'Orbitron', sans-serif;
        font-size: 0.8rem;
        padding: 15px;
        text-align: left;
        border-bottom: 2px solid #222;
    }

    .fila-reliquia {
        background: rgba(255, 255, 255, 0.03);
        transition: all 0.3s ease;
    }

    .fila-reliquia:hover {
        background: rgba(0, 212, 255, 0.08);
        transform: scale(1.01);
    }

    .fila-reliquia td {
        padding: 20px 15px;
        color: #eee;
        border-top: 1px solid rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .img-mini {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #333;
    }

    .badge-cat {
        background: #1a1a1a;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        border: 1px solid #333;
    }

    .precio-tag {
        color: #00ff88;
        font-weight: bold;
        font-family: 'Orbitron';
    }

    /* Botones de acción */
    .btn-accion {
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: bold;
        font-family: 'Orbitron';
        transition: 0.3s;
        display: inline-block;
        margin-right: 5px;
    }

    .btn-editar {
        border: 1px solid var(--accent-blue);
        color: var(--accent-blue);
    }

    .btn-editar:hover {
        background: var(--accent-blue);
        color: #000;
    }

    .btn-eliminar {
        border: 1px solid var(--accent-red);
        color: var(--accent-red);
    }

    .btn-eliminar:hover {
        background: var(--accent-red);
        color: #fff;
    }

    /* Mensajes flotantes */
    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
        font-family: 'Orbitron';
        font-size: 0.8rem;
    }
    .alert-success { background: rgba(0, 255, 136, 0.1); color: #00ff88; border: 1px solid #00ff88; }
    .alert-error { background: rgba(255, 0, 0, 0.1); color: #ff0000; border: 1px solid #ff0000; }
</style>

<main class="admin-container">
    <div class="admin-header">
        <h2>GESTIÓN DE INVENTARIO</h2>
        <p>Tus reliquias activas en el mercado</p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert <?= ($_GET['msg'] == 'ok' || $_GET['msg'] == 'updated') ? 'alert-success' : 'alert-error' ?>">
            <?php
                if ($_GET['msg'] == 'ok') echo "Sincronización completa: Reliquia forjada con éxito.";
                if ($_GET['msg'] == 'updated') echo "Datos actualizados en el núcleo.";
                if ($_GET['msg'] == 'deleted') echo "Reliquia retirada del mercado.";
            ?>
        </div>
    <?php endif; ?>

    <table class="tabla-reliquias">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre de la Reliquia</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado_inventario->num_rows > 0): ?>
                <?php while($row = $resultado_inventario->fetch_assoc()): ?>
                    <tr class="fila-reliquia">
                        <td>
                            <img src="img/productos/<?= $row['imagen'] ?>" class="img-mini" onerror="this.src='img/productos/default.jpg'">
                        </td>
                        <td style="font-weight: bold;"><?= htmlspecialchars($row['nombre']) ?></td>
                        <td>
                            <span class="badge-cat">
                                <?= $row['nombre_categoria'] ?> 
                                <?= (!empty($row['tipo_libro'])) ? "({$row['tipo_libro']})" : "" ?>
                            </span>
                        </td>
                        <td class="precio-tag"><?= number_format($row['precio'], 2) ?>€</td>
                        <td><?= $row['stock'] ?> uds.</td>
                        <td>
                            <a href="editar_producto.php?id=<?= $row['id_producto'] ?>" class="btn-accion btn-editar">
                                <i class="fas fa-edit"></i> EDITAR
                            </a>
                            <a href="eliminar_producto.php?id=<?= $row['id_producto'] ?>" 
                               class="btn-accion btn-eliminar" 
                               onclick="return confirm('¿Retirar esta reliquia del mercado permanentemente?');">
                                <i class="fas fa-trash"></i> RETIRAR
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 50px; color: #555;">
                        <i class="fas fa-box-open" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        Aún no has subido ninguna reliquia.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include 'includes/pie.php'; ?>