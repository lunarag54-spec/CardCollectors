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

// MODIFICACIÓN: Ahora pedimos también el tipo_libro
$query_categorias = "SELECT id_categoria, nombre_categoria, tipo_libro FROM Categoria";
$resultado_categorias = $conn->query($query_categorias);
?>

<main class="contenedor">
    <div class="admin-header">
        <h2>Panel de Administración</h2>
        <p>Gestión de Inventario - Acceso Restringido</p>
    </div>

    <section id="alta-producto" class="seccion-admin" style="margin-top: 20px; border: 1px solid #ccc; padding: 20px;">
        <h3>Añadir Nuevo Producto</h3>
        <!-- Mensaje de éxito -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'ok'): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
                <strong>¡Éxito!</strong> El producto se ha añadido correctamente al inventario.
            </div>
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

                            // LÓGICA NUEVA: Construir el nombre a mostrar
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
</main>

<?php include 'includes/pie.php'; ?>