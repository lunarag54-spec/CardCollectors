<?php
// 1. Seguridad: Iniciar sesión y verificar si el usuario está logueado
session_start();
if (!isset($_SESSION['id_usuario'])) {
    // Si no está logueado, lo mandamos de vuelta al login
    header("Location: login.php");
    exit();
}

// 2. Conexión (Reutilizando tu archivo de conexión)
require_once 'includes/conexion.php';

// 3. Consulta SELECT (Unificada para evitar repeticiones)
$filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$sql = "SELECT p.*, c.nombre_categoria 
        FROM Producto p 
        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.estado = 'activo' AND p.stock > 0";

if ($filtro != '') {
    $f = $conn->real_escape_string($filtro);
    $sql .= " AND c.nombre_categoria = '$f'";
}

$resultado = $conn->query($sql);
?>

<?php include 'includes/cabecera.php'; ?>

<section style="background: rgba(0,0,0,0.8); color: white; padding: 15px; text-align: right; border-bottom: 2px solid #ff0000;">
    <div class="contenedor">
        <span>Bienvenido, <strong><?php echo $_SESSION['nombre']; ?></strong> (<?php echo $_SESSION['rol']; ?>)</span>
        <a href="logout.php" style="margin-left: 20px; color: #ff0000; text-decoration: none; font-weight: bold; font-family: 'Orbitron';">CERRAR SESIÓN</a>
    </div>
</section>

<main class="contenedor-catalogo">
    <h2 style="font-family: 'Orbitron'; color: #00d4ff; margin-top: 20px;">Nuestros Coleccionables</h2>

    <div class="grid-productos">
        <?php
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

<?php include 'includes/pie.php'; ?>