<?php
//Iniciamos sesión para verificar el rol del usuario
session_start();

/**
 * CONTROL DE ACCESO
 * Verificamos si existe la sesión y si el rol es 'admin'.
 * Nota: Por ahora, mientras probamos, si te redirige al login, 
 * se pueden comentar estas líneas (del 12 al 15).
 */
/*
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit(); // Detiene la carga del resto de la página
}
    */

include 'includes/cabecera.php';
require_once 'includes/conexion.php';

// Consulta para el desplegable de categorías
$query_categorias = "SELECT id_categoria, nombre_categoria, tipo_libro FROM Categoria";
$resultado_categorias = $conn->query($query_categorias);

// Solo traemos los que están 'activos' para cumplir con el borrado lógico
$query_inventario = "SELECT p.*, c.nombre_categoria, c.tipo_libro 
                     FROM Producto p 
                     INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                     WHERE p.estado = 'activo' 
                     ORDER BY p.id_producto DESC";
$resultado_inventario = $conn->query($query_inventario);
?>

<main class="contenedor">
    <div class="admin-header">
        <h2>Panel de Administración</h2>
        <p>Gestión de Inventario - Acceso Restringido</p>
    </div>
    <!-- Seccion para añadir un producto al inventario -->
    <section id="alta-producto" class="seccion-admin" style="margin-top: 20px; border: 1px solid #ccc; padding: 20px;">
        <h3>Añadir Nuevo Producto</h3>
        <!-- Mensaje de éxito añadido/borrado/actualizado -->
        <?php if (isset($_GET['msg'])) : ?>
            <?php if ($_GET['msg'] == 'ok'): ?>
                <p style="color: green;">Producto añadido correctamente.</p>
            <?php elseif ($_GET['msg'] == 'deleted'): ?>
                <p style="color: orange;">Producto elimnado.</p>
            <?php elseif ($_GET['msg'] == 'updated'): ?>
                <p style="color: blue;">Producto actualizado correctamente.</p>
    <?php endif; ?>
        <?php endif; ?>

        <form action="procesar_producto.php" method="POST">
            <div style="margin-bottom: 10px;">
                <label>Nombre del Producto:</label><br>
                <input type="text" name="nombre" required style="width: 100%;">
            </div>

            <div style="margin-bottom: 10px;">
                <label>Descripción:</label><br>
                <textarea name="descripcion" rows="3" style="width: 100%;"></textarea>
            </div>

            <div style="margin-bottom: 10px;">
                <label>Categoría:</label><br>
                <select name="id_categoria" required style="width: 100%;">
                    <option value="">-- Selecciona --</option>
                    <?php
                    if ($resultado_categorias && $resultado_categorias->num_rows > 0):
                        while ($cat = $resultado_categorias->fetch_assoc()):

                            //Construir el nombre a mostrar
                            $nombre_option = $cat['nombre_categoria'];
                            if (!empty($cat['tipo_libro'])) {
                                $nombre_option .= " (" . $cat['tipo_libro'] . ")";
                            }
                    ?>
                            <option value="<?php echo $cat['id_categoria']; ?>">
                                <?php echo $nombre_option; ?>
                            </option>
                    <?php endwhile;
                    endif; ?>
                </select>
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 10px;">
                <div style="flex: 1;">
                    <label>Precio (€):</label><br>
                    <input type="number" name="precio" step="0.01" required style="width: 100%;">
                </div>
                <div style="flex: 1;">
                    <label>Stock:</label><br>
                    <input type="number" name="stock" value="1" required style="width: 100%;">
                </div>
            </div>

            <button type="submit" style="background: green; color: white; padding: 10px 20px; border: none; cursor: pointer;">
                Guardar Producto
            </button>
        </form>
    </section>
    <!-- Seccion que mostrara el inventario y que servira para actualizar el estado de un prodcuto -->
    <section id="inventario" style="margin-top: 30px;">
        <h3>Inventario Actual</h3>
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado_inventario && $resultado_inventario->num_rows > 0): ?>
                    <?php while($row = $resultado_inventario->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nombre']; ?></td>
                            <td>
                                <?php echo $row['nombre_categoria']; ?> 
                                <?php echo (!empty($row['tipo_libro'])) ? "({$row['tipo_libro']})" : ""; ?>
                            </td>
                            <td><?php echo $row['precio']; ?>€</td>
                            <td><?php echo $row['stock']; ?></td>
                                
                            <td>
                                <a href="editar_producto.php?id=<?php echo $row['id_producto']; ?>">
                                    Editar -
                                </a>
                                <a href="eliminar_producto.php?id=<?php echo $row['id_producto']; ?>" 
                                   onclick="return confirm('¿Seguro que quieres eliminar este producto?');">
                                   Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No hay productos activos.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'includes/pie.php'; ?>