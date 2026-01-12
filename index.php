<?php

// 1. Conexión (Reutilizando tu archivo de conexión)
require_once 'includes/conexion.php';

// 2. La consulta SELECT con el JOIN (Donde unimos Producto y Categoria)
// Usamos p.* para traer todo de producto y c.nombre_categoria para el nombre de la categoría
$sql = "SELECT p.*, c.nombre_categoria 
        FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.estado = 'activo' AND p.stock > 0";

$resultado = $conn->query($sql);
?>

<?php include 'includes/cabecera.php'; ?>

<main class="contenedor-catalogo">
    <h2>Nuestros Coleccionables</h2>

    <div class="grid-productos">
        <?php
        // 4. Bucle para generar la cuadrícula
        if ($resultado && $resultado->num_rows > 0):
            while ($row = $resultado->fetch_assoc()): ?>
                <div class="producto-card">
                    <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                    <p class="categoria-badge"><?php echo $row['nombre_categoria']; ?></p>
                    <p class="precio"><?php echo number_format($row['precio'], 2); ?>€</p>
                    <p class="stock">Disponibles: <?php echo $row['stock']; ?></p>
                    <button class="btn-comprar">Ver detalle</button>
                </div>
            <?php endwhile;
        else: ?>
            <p>No hay productos disponibles por ahora.</p>
        <?php endif; ?>
    </div>
</main>

<main class="grid-catalogo">
    <?php
    // Suponiendo que ya hiciste la consulta SELECT uniendo Producto y Categoria
    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            // Llamamos al componente pasando los datos de la base de datos
            generarTarjetaProducto(
                $fila['nombre'],
                $fila['precio'],
                $fila['stock']
            );
        }
    } else {
        echo "<p>No se encontraron coleccionables disponibles.</p>";
    }
    ?>
</main>

<?php
require_once 'includes/conexion.php';

// Capturamos el filtro si existe
$filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Consulta base con el JOIN que ya definimos
$sql = "SELECT p.*, c.nombre_categoria, c.tipo_libro 
        FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.estado = 'activo'";

// Si hay un filtro seleccionado, añadimos la condición a la SQL
if ($filtro != '') {
    // Usamos real_escape_string por seguridad básica
    $f = $conn->real_escape_string($filtro);
    $sql .= " AND c.nombre_categoria = '$f'";
}

$resultado = $conn->query($sql);
?>

<?php include 'includes/pie.php'; ?>
<!-- Página principal del sitio web -->