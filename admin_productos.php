<?php
// 1. Seguridad de Sesión
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php'; 
require_once 'includes/conexion.php';

$id_user = $_SESSION['id_usuario'];

// 2. Consulta para categorías (necesaria para el formulario)
$query_categorias = "SELECT id_categoria, nombre_categoria, tipo_libro FROM Categoria";
$resultado_categorias = $conn->query($query_categorias);

// 3. Consulta para el inventario filtrado por vendedor
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
    :root {
        --accent-blue: #00d4ff;
        --accent-red: #ff0055;
        --accent-green: #00ff88;
    }
    .admin-container { max-width: 1100px; margin: 40px auto; padding: 20px; font-family: 'Segoe UI', sans-serif; }
    .admin-header { text-align: center; margin-bottom: 40px; }
    .admin-header h2 { font-family: 'Orbitron', sans-serif; color: var(--accent-blue); text-shadow: 0 0 10px var(--accent-blue); }
    
    /* Formulario Estilizado */
    .seccion-alta { 
        background: rgba(255, 255, 255, 0.02); 
        border: 1px solid #333; 
        padding: 25px; 
        border-radius: 8px; 
        margin-bottom: 50px; 
    }
    .form-group { margin-bottom: 15px; }
    .form-group label { color: var(--accent-blue); font-family: 'Orbitron'; font-size: 0.8rem; display: block; margin-bottom: 5px; }
    .form-control { 
        width: 100%; background: #111; border: 1px solid #444; color: #fff; padding: 10px; border-radius: 4px; 
    }
    .btn-guardar { 
        background: transparent; border: 1px solid var(--accent-green); color: var(--accent-green); 
        padding: 10px 25px; font-family: 'Orbitron'; cursor: pointer; transition: 0.3s;
    }
    .btn-guardar:hover { background: var(--accent-green); color: #000; }

    /* Tabla Cyberpunk */
    .tabla-reliquias { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
    .tabla-reliquias th { color: var(--accent-blue); font-family: 'Orbitron'; font-size: 0.8rem; text-align: left; padding: 15px; border-bottom: 2px solid #222; }
    .fila-reliquia { background: rgba(255, 255, 255, 0.03); transition: 0.3s; }
    .fila-reliquia:hover { background: rgba(0, 212, 255, 0.08); transform: scale(1.01); }
    .fila-reliquia td { padding: 15px; color: #eee; border-top: 1px solid rgba(255,255,255,0.05); }
    .img-mini { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #333; }
    .precio-tag { color: var(--accent-green); font-weight: bold; font-family: 'Orbitron'; }
    
    .btn-accion { text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 0.7rem; font-family: 'Orbitron'; display: inline-block; margin-right: 5px; }
    .btn-editar { border: 1px solid var(--accent-blue); color: var(--accent-blue); }
    .btn-eliminar { border: 1px solid var(--accent-red); color: var(--accent-red); }
</style>

<main class="admin-container">
    <div class="admin-header">
        <h2>CENTRO DE COMANDO</h2>
        <p>Gestión de Inventario y Forja de Reliquias</p>
    </div>

    <section class="seccion-alta">
        <h3 style="color: #fff; font-family: 'Orbitron'; margin-bottom: 20px;">FORJAR NUEVA RELIQUIA</h3>
        <form action="procesar_producto.php" method="POST">
            <div class="form-group">
                <label>Nombre de la Reliquia</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Descripción del Artefacto</label>
                <textarea name="descripcion" class="form-control" rows="2"></textarea>
            </div>
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 2;">
                    <label>Categoría</label>
                    <select name="id_categoria" class="form-control" required>
                        <option value="">-- Seleccionar --</option>
                        <?php while ($cat = $resultado_categorias->fetch_assoc()): ?>
                            <option value="<?= $cat['id_categoria'] ?>">
                                <?= $cat['nombre_categoria'] ?> <?= (!empty($cat['tipo_libro'])) ? "({$cat['tipo_libro']})" : "" ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Precio (€)</label>
                    <input type="number" name="precio" step="0.01" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Unidades</label>
                    <input type="number" name="stock" value="1" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn-guardar">INICIAR SINCRONIZACIÓN</button>
        </form>
    </section>

    <table class="tabla-reliquias">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
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
                        <td><img src="img/productos/<?= $row['imagen'] ?>" class="img-mini" onerror="this.src='img/productos/default.jpg'"></td>
                        <td style="font-weight: bold;"><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><span style="background:#1a1a1a; padding:4px 8px; border-radius:4px; font-size:0.75rem; border:1px solid #333;">
                            <?= $row['nombre_categoria'] ?>
                        </span></td>
                        <td class="precio-tag"><?= number_format($row['precio'], 2) ?>€</td>
                        <td><?= $row['stock'] ?> uds.</td>
                        <td>
                            <a href="editar_producto.php?id=<?= $row['id_producto'] ?>" class="btn-accion btn-editar">EDITAR</a>
                            <a href="eliminar_producto.php?id=<?= $row['id_producto'] ?>" class="btn-accion btn-eliminar" onclick="return confirm('¿Retirar del mercado?');">RETIRAR</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align: center; padding: 40px;">No hay reliquias activas en tu inventario.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include 'includes/pie.php'; ?>