<?php
include 'includes/cabecera.php';
require_once 'includes/conexion.php';

// Verificamos que recibimos un ID válido
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM Producto WHERE id_producto = $id";
    $resultado = $conn->query($query);
    $producto = $resultado->fetch_assoc();

    if (!$producto) {
        die("Producto no encontrado.");
    }
} else {
    header("Location: admin_productos.php");
    exit();
}
?>

<main class="contenedor">
    <h2>Editar Producto: <?php echo htmlspecialchars($producto['nombre']); ?></h2>
    
    <section class="seccion-admin" style="border: 1px solid #ccc; padding: 20px;">
        <form action="actualizar_producto.php" method="POST">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

            <div style="margin-bottom: 10px;">
                <label>Nombre (Solo lectura):</label><br>
                <input type="text" value="<?php echo htmlspecialchars($producto['nombre']); ?>" disabled style="width: 100%; background: #eee;">
            </div>

            <div style="margin-bottom: 10px;">
                <label>Precio Actual (€):</label><br>
                <input type="number" name="precio" step="0.01" value="<?php echo $producto['precio']; ?>" required style="width: 100%;">
            </div>

            <div style="margin-bottom: 10px;">
                <label>Stock Actual:</label><br>
                <input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required style="width: 100%;">
            </div>

            <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer;">
                Actualizar Datos
            </button>
            <a href="admin_productos.php" style="margin-left: 10px;">Cancelar</a>
        </form>
    </section>
</main>

<?php include 'includes/pie.php'; ?>