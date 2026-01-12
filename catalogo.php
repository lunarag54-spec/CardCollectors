<section class="filtros-container">
    <form action="index.php" method="GET" class="form-filtro">
        <label for="categoria">Filtrar por tipo:</label>
        <select name="categoria" id="categoria" onchange="this.form.submit()">
            <option value="">Todos los productos</option>
            <option value="Cartas" <?php echo $filtro == 'Cartas' ? 'selected' : ''; ?>>Cartas</option>
            <option value="Figuras" <?php echo $filtro == 'Figuras' ? 'selected' : ''; ?>>Figuras</option>
            <option value="Libros" <?php echo $filtro == 'Libros' ? 'selected' : ''; ?>>Libros (Manga, Comic, Novela)</option>
        </select>
        <noscript><button type="submit">Filtrar</button></noscript>
    </form>
</section>

<?php
// 1. Conexión a la base de datos
require_once 'includes/conexion.php';
include 'includes/cabecera.php';

// 2. Consulta para obtener productos activos y su categoría
$sql = "SELECT p.*, c.nombre_categoria 
        FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.estado = 'activo' AND p.stock > 0";
$resultado = $conn->query($sql);
?>

<main class="contenedor-principal">
    <h1 class="titulo-catalogo">Catálogo de Coleccionables</h1>

    <div class="grid-productos">
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($producto = $resultado->fetch_assoc()): ?>
                <article class="producto-card">
                    <div class="producto-imagen">
                        <img src="https://via.placeholder.com/200" alt="<?php echo $producto['nombre']; ?>">
                        <span class="categoria-tag"><?php echo $producto['nombre_categoria']; ?></span>
                    </div>

                    <div class="producto-info">
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p class="descripcion"><?php echo htmlspecialchars(substr($producto['descripcion'], 0, 80)) . '...'; ?></p>

                        <div class="producto-footer">
                            <span class="precio"><?php echo number_format($producto['precio'], 2); ?>€</span>
                            <span class="stock">Stock: <?php echo $producto['stock']; ?></span>
                        </div>

                        <a href="detalles.php?id=<?php echo $producto['id_producto']; ?>" class="btn-ver">Ver Detalles</a>
                        <button class="btn-carrito">Añadir al carrito</button>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay productos disponibles en este momento.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/pie.php'; ?>
<!-- Catálogo de tarjetas del sitio web -->